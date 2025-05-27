<?php
// Connexion à MongoDB
require_once "../../vendor/autoload.php";
// Inclure la classe ModuleHelper
require_once __DIR__ . "/../ModuleHelper.php";

$client = new MongoDB\Client("mongodb://localhost:27017");
$academy = $client->academy;
$profilesCollection = $academy->profiles;
$functionalitiesCollection = $academy->functionalities;

// Inclure le fichier de langue (si nécessaire)
include_once "../language.php";

// Démarrer la session si ce n'est pas déjà fait
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Récupérer le profil de l'utilisateur
$currentProfileName = $_SESSION['profile'] ?? '';
$profile = $profilesCollection->findOne(['name' => $currentProfileName]);
$assignedFunctionalities = $profile['functionalities'] ?? [];

if ($assignedFunctionalities instanceof MongoDB\Model\BSONArray) {
    // Convertir en tableau PHP standard
    $assignedFunctionalities = $assignedFunctionalities->getArrayCopy();
}

// Supprimer les doublons après la conversion
$assignedFunctionalities = array_unique($assignedFunctionalities);

// Déterminer le module actuel basé sur l'URL
$currentModules = ModuleHelper::getCurrentModule();

// Debugging : Afficher les modules actifs dans le HTML
echo '<!-- Debugging Information -->';
echo '<!-- Current Modules: ' . htmlspecialchars(json_encode($currentModules)) . ' -->';

$menuStructure = [];

// Si aucun module n'est actif, le menu sera vide ou vous pouvez définir un menu par défaut
if (!empty($currentModules)) {
    // Récupérer les fonctionnalités assignées, actives et appartenant aux modules actifs, triées par group_order et order
    $functionalitiesCursor = $functionalitiesCollection->find(
        [
            '_id' => ['$in' => $assignedFunctionalities],
            'active' => true,
            'modules' => ['$in' => $currentModules] // Filtrer par modules actifs
        ],
        [
            'sort' => ['group_order' => 1, 'order' => 1]
        ]
    );

    $allFunctionalities = iterator_to_array($functionalitiesCursor);

    foreach ($allFunctionalities as $func) {
        $funcKey = $func['key'] ?? '';

        if ($funcKey === 'connect_app') {
            continue; // Passer à la prochaine fonctionnalité
        }

        $groupName = $func['group'] ?? 'Autres';
        $order = $func['order'] ?? 999;

        // Éviter les doublons
        if (!isset($menuStructure[$groupName])) {
            $menuStructure[$groupName] = [];
        }

        // Ajouter la fonctionnalité au groupe
        $menuStructure[$groupName][] = [
            'name' => $func['name'],
            'icon' => $func['icon'],
            'icon_type' => $func['icon_type'],
            'url' => $func['url'],
            'order' => $order
        ];
    }
}

// Variables supplémentaires (si nécessaires)
$country = $_SESSION["country"] ?? '';

// Map countries to their respective agencies
$agencies = [
    "Burkina Faso" => ["Ouaga"],
    "Cameroun" => ["Bafoussam", "Bertoua", "Douala", "Garoua", "Ngaoundere", "Yaoundé"],
    "Cote d'Ivoire" => ["Vridi - Equip"],
    "Gabon" => ["Libreville"],
    "Mali" => ["Bamako"],
    "RCA" => ["Bangui"],
    "RDC" => ["Kinshasa", "Kolwezi", "Lubumbashi"],
    "Senegal" => ["Dakar"],
    "Congo" => ["Brazzaville", "Pointe-Noire"],
    // Add more countries and their agencies here
];

// Map countries
$countries = [
    "Burkina Faso",
    "Cameroun",
    "Cote d'Ivoire",
    "Gabon",
    "Mali",
    "RCA",
    "RDC",
    "Senegal",
    "Congo"
    // Add more countries here
];

// Map countries to their respective subsidiary name
$subsidiaries = [
    "CFAO MOTORS BURKINA",
    "CAMEROON MOTORS INDUSTRIES",
    "CFAO MOTORS COTE D'IVOIRE",
    "CFAO MOTORS GABON",
    "CFAO MOTORS MALI",
    "CFAO MOTORS CENTRAFRIQUE",
    "CFAO MOTORS RDC",
    "CFAO MOTORS SENEGAL",
    "CFAO MOTORS CONGO",
    // Add more subsidiaries here
];
?>
<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
    <meta charset="utf-8" />
    <meta name="description"
        content="Craft admin dashboard live demo. Check out all the features of the admin panel. A large number of settings, additional services and widgets." />
    <meta name="keywords"
        content="Craft, bootstrap, bootstrap 5, admin themes, dark mode, free admin themes, bootstrap admin, bootstrap dashboard" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="Craft - Bootstrap 5 HTML Admin Dashboard Theme" />
    <meta property="og:url" content="https://themes.getbootstrap.com/product/craft-bootstrap-5-admin-dashboard-theme" />
    <meta property="og:site_name" content="Keenthemes | Craft" />
    <link rel="canonical" href="https://preview.keenthemes.com/craft" />
    <link rel="shortcut icon" href="/images/logo-cfao.png" />
    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Vendor Stylesheets(used for this page only)-->
    <link href="../../public/assets/plugins/custom/leaflet/leaflet.bundle.css" rel="stylesheet" type="text/css" />
    <link href="../../public/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <!--end::Vendor Stylesheets-->
    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href="../../public/assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="../../public/assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag/dist/css/multi-select-tag.css">
    <link href="../../public/assets/css/ki-duotone-icons.css" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->
    <!--Begin::Google Tag Manager -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar/index.global.min.js'></script>
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src = 'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-5FS8GGP');
    </script>
    <!--End::Google Tag Manager -->
    <script>
        // Frame-busting to prevent site from being loaded within a frame without permission (click-jacking)
        if (window.top != window.self) {
            window.top.location.replace(window.self.location.href);
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.6.3.js"
        integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js">
    </script>
</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_body"
    class="header-fixed header-tablet-and-mobile-fixed toolbar-enabled aside-fixed aside-default-enabled">
    <!-- Spinner Start -->
    <div id="spinner"
        class="show bg-transparent position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Chargement...</span>
        </div>
    </div>
    <!-- Spinner End -->
    <!--begin::Theme mode setup on page load-->
    <script>
        var defaultThemeMode = "light";
        var themeMode;
        if (document.documentElement) {
            if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                themeMode = document.documentElement.getAttribute(
                    "data-bs-theme-mode");
            } else {
                if (localStorage.getItem("data-bs-theme") !== null) {
                    themeMode = localStorage.getItem("data-bs-theme");
                } else {
                    themeMode = defaultThemeMode;
                }
            }
            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)")
                    .matches ? "dark" : "light";
            }
            document.documentElement.setAttribute("data-bs-theme", themeMode);
        }
    </script>
    <!--end::Theme mode setup on page load-->
    <!--Begin::Google Tag Manager (noscript) -->
    <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5FS8GGP" height="0" width="0"
            style="display:none;visibility:hidden"></iframe>
    </noscript>
    <!--End::Google Tag Manager (noscript) -->
    <!--begin::Main-->
    <!--begin::Root-->
    <div class="d-flex flex-column flex-root">
        <!--begin::Page-->
        <div class="page d-flex flex-row flex-column-fluid">
            <!--begin::Aside-->
            <?php if (
                $_SESSION["profile"] == "Super Admin" ||
                $_SESSION["profile"] == "Admin" ||
                $_SESSION["profile"] == "Ressource Humaine" ||
                $_SESSION["profile"] == "Directeur Filiale" ||
                $_SESSION["profile"] == "Directeur Groupe"
            ) { ?>
                <div id="kt_aside" class="aside aside-default aside-hoverable" data-kt-drawer="true"
                    data-kt-drawer-name="aside" data-kt-drawer-activate="{default: true, lg: false}"
                    data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '300px': '250px'}"
                    data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_aside_toggle">
                    <!--begin::Brand-->
                    <div style="background:#e6e6e6;" class="aside-logo flex-column-auto px-10 pt-9 pb-5" id="kt_aside_logo">
                        <!--begin::Logo-->
                        <a href="./dashboard.php">
                            <img alt="Logo" src="../../public/images/logo.png" class="h-50px logo-default" />
                            <img alt="Logo" src="../../public/images/logo.png" class="h-50px logo-minimize" />
                        </a>
                        <!--end::Logo-->
                    </div>
                    <!--end::Brand-->
                    <!--begin::Aside menu-->
                    <div style="background:#e6e6e6;" class="aside-menu flex-column-fluid ps-3 pe-1">
                        <!--begin::Aside Menu-->
                        <!--begin:Menu item-->
                        <div style="margin-top: -15px">
                            <!-- Afficher le nom de l'espace profil -->
                            <?php if ($_SESSION["profile"] == "Super Admin") { ?>
                                <div class="menu-content text-center">
                                    <span class="fw-bolder text-black text-uppercase fs-4"><?php echo htmlspecialchars($super_admin_space); ?></span>
                                </div>
                            <?php } elseif ($_SESSION["profile"] == "Admin") { ?>
                                <div class="menu-content text-center">
                                    <span class="fw-bolder text-black text-uppercase fs-4"><?php echo htmlspecialchars($admin_space); ?></span>
                                </div>
                                <div class="menu-content text-center">
                                    <span class="fw-bolder text-black text-uppercase fs-4"><?php echo htmlspecialchars($_SESSION["country"]); ?></span>
                                </div>
                            <?php } elseif ($_SESSION["profile"] == "Ressource Humaine") { ?>
                                <div class="menu-content text-center">
                                    <span class="fw-bolder text-black text-uppercase fs-4"><?php echo htmlspecialchars($rh_space); ?></span>
                                </div>
                                <div class="menu-content text-center">
                                    <span class="fw-bolder text-black text-uppercase fs-4"><?php echo htmlspecialchars($_SESSION["country"]); ?></span>
                                </div>
                            <?php } elseif ($_SESSION["profile"] == "Directeur Filiale") { ?>
                                <div class="menu-content text-center">
                                    <span class="fw-bolder text-black text-uppercase fs-4"><?php echo htmlspecialchars($dir_filiale_space); ?></span>
                                </div>
                                <div class="menu-content text-center">
                                    <span class="fw-bolder text-black text-uppercase fs-4"><?php echo htmlspecialchars($_SESSION["country"]); ?></span>
                                </div>
                            <?php } elseif ($_SESSION["profile"] == "Directeur Groupe") { ?>
                                <div class="menu-content text-center">
                                    <span class="fw-bolder text-black text-uppercase fs-4"><?php echo htmlspecialchars($dir_grp_space); ?></span>
                                </div>
                            <?php } ?>
                        </div>
                        <br>
                        <!--end:Menu item-->
                        <!--begin::Menu-->
                        <div class="menu menu-sub-indention menu-column menu-rounded menu-title-gray-600 menu-icon-gray-400 menu-active-bg menu-state-primary menu-arrow-gray-500 fw-semibold fs-6 my-5 mt-lg-2 mb-lg-0"
                            id="kt_aside_menu" data-kt-menu="true">
                            <div class="hover-scroll-y mx-4" id="kt_aside_menu_wrapper" data-kt-scroll="true"
                                data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto"
                                data-kt-scroll-wrappers="#kt_aside_menu" data-kt-scroll-offset="20px"
                                data-kt-scroll-dependencies="#kt_aside_logo, #kt_aside_footer">
                                <!--begin:Menu item-->
                                <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                                    <!--begin:Menu link-->
                                    <div class="menu-item">
                                        <a class="menu-link" href="./dashboard.php">
                                            <span class="menu-icon">
                                                <i class="ki-duotone ki-element-11 fs-2">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                    <span class="path3"></span>
                                                    <span class="path4"></span>
                                                </i>
                                            </span>
                                            <span class="menu-title"><?php echo htmlspecialchars($tableau); ?></span>
                                        </a>
                                    </div>
                                    <!--end:Menu link-->
                                </div>
                                <!--end:Menu item-->

                                <!-- Dynamic Menu Items -->
                                <?php
                                // Récupération de l'URL actuelle
                                $currentUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                                $isResultsPage = strpos($currentUrl, 'results.php') !== false;
                                $isResultPage = strpos($currentUrl, 'result.php') !== false;
                                $isDetailPage = strpos($currentUrl, 'detail.php') !== false;
                                $isBrandResultPage = strpos($currentUrl, 'brandResult.php') !== false;
                                $isSystemPage = strpos($currentUrl, 'system.php') !== false;

                                // Paramètres dynamiques
                                $list_result = "Liste des résultats";
                                $result_detaillé = "Résultats détaillés";
                                $result_brand = "Résultats par marque";

                                // Boucle principale pour générer le menu dynamique
                                foreach ($menuStructure as $groupName => $items) {
                                    // Afficher le nom du groupe
                                    echo '<div class="menu-content">';
                                    echo '<span class="fw-bold text-black text-uppercase fs-70">' . htmlspecialchars($groupName) . '</span>';
                                    echo '</div>';

                                    // Affichage des fonctionnalités du groupe
                                    foreach ($items as $item) {
                                        echo '<div class="menu-item">';
                                        echo '<a class="menu-link" href="' . htmlspecialchars($item['url']) . '">';
                                        echo '<span class="menu-icon">';
                                        if ($item['icon_type'] === 'ki_duotone') {
                                            echo '<i class="' . htmlspecialchars($item['icon']) . ' fs-2">';
                                            echo '<span class="path1"></span><span class="path2"></span>';
                                            echo '</i>';
                                        } else {
                                            echo '<i class="' . htmlspecialchars($item['icon']) . ' fs-2"></i>';
                                        }
                                        echo '</span>';
                                        echo '<span class="menu-title">' . htmlspecialchars($item['name']) . '</span>';
                                        echo '</a>';
                                        echo '</div>';

                                        // Test URL et affichage conditionnel si groupe "BILAN DES EVALUATIONS"
                                        if ($isResultsPage && $groupName == "BILAN DES EVALUATIONS") {
                                            echo '<div class="menu-item">';
                                            echo '<a class="menu-link ' . (($isResultPage || $isDetailPage || $isBrandResultPage) ? 'active' : '') . '" href="./results.php" id="listResultLink">';
                                            echo '<span class="menu-icon">';
                                            echo '<i class="fas fa-address-book fs-2"><span class="path1"></span><span class="path2"></span></i>';
                                            echo '</span>';
                                            echo '<span class="menu-title">' . $list_result . '</span>';

                                            // Afficher la flèche si nous ne sommes pas sur "results.php"
                                            if (!$isResultsPage) {
                                                echo '<span class="toggle-arrow" onclick="toggleSubmenu()">';
                                                echo '<i class="fas fa-chevron-down"></i>';
                                                echo '</span>';
                                            }
                                            echo '</a>';

                                            // Conteneur pour le menu dynamique
                                            echo '<div id="dynamicMenuContainer" style="display: ' . ($isSystemPage ? 'block' : 'none') . ';"></div>';

                                            // Sous-menu dynamique
                                            echo '<div class="submenu-container" id="submenuItems" style="' . (($isResultPage || $isDetailPage || $isBrandResultPage) ? 'display: block;' : 'display: none;') . '">';
                                            echo '<a href="#" class="submenu-item menu-link ' . ($isDetailPage ? 'active' : '') . '" id="detailLink" onclick="navigateToDetail()">';
                                            echo '<span class="menu-icon"><i class="fas fa-search-plus"></i></span>' . $result_detaillé;
                                            echo '</a>';
                                            echo '<a href="#" class="submenu-item menu-link ' . ($isBrandResultPage ? 'active' : '') . '" id="brandResultLink" onclick="navigateToBrandResult()">';
                                            echo '<span class="menu-icon"><i class="fas fa-search-plus"></i></span>' . $result_brand;
                                            echo '</a>';
                                            echo '</div>'; // Fin sous-menu
                                            echo '</div>'; // Fin menu-item
                                        }
                                    }
                                }
                                ?>





                                <!--end:Menu item-->

                                <!-- Séparateur entre les groupes -->
                                <div class="menu-item">
                                    <div class="menu-content">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::Menu-->
                    </div>
                    <!--end::Aside menu-->
                    <!--begin::Footer-->
                    <div class="aside-footer flex-column-auto pb-5 d-none" id="kt_aside_footer">
                        <a href="./dashboard.php" class="btn btn-light-primary w-100">
                            Button
                        </a>
                    </div>
                    <!--end::Footer-->
                </div>
            <?php } ?>
            <!--end::Aside-->
            <!--begin::Wrapper-->
            <div style="background:#e6e6e6;" class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
                <!--begin::Header-->
                <div style="background:#e6e6e6;" id="kt_header" class="header" data-kt-sticky="true" data-kt-sticky-name="header"
                    data-kt-sticky-offset="{default: '200px', lg: '300px'}">
                    <!--begin::Container-->
                    <div class="container-fluid d-flex align-items-stretch justify-content-between">
                        <!--begin::Logo bar-->
                        <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
                            <!--begin::Aside Toggle-->
                            <div class="d-flex align-items-center d-lg-none">
                                <div class="btn btn-icon btn-active-color-primary ms-n2 me-1" id="kt_aside_toggle">
                                    <i class="ki-duotone ki-abstract-14 fs-1"><span class="path1"></span><span class="path2"></span></i>
                                </div>
                            </div>
                            <!--end::Aside Toggle-->
                            <!--begin::Logo-->
                            <a href="./dashboard.php" class="d-lg-none">
                                <img alt="Logo" src="../../public/images/logo.png" class="mh-40px" />
                            </a>
                            <!--end::Logo-->
                            <!--begin::Aside toggler-->
                            <div class="btn btn-icon w-auto ps-0 btn-active-color-primary d-none d-lg-inline-flex me-2 me-lg-5"
                                data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
                                data-kt-toggle-name="aside-minimize">
                                <i class="ki-duotone ki-black-left-line fs-1 rotate-180"><span class="path1"></span><span class="path2"></span></i>
                            </div>
                            <!--end::Aside toggler-->
                        </div>
                        <!--end::Logo bar-->
                        <!--begin::Topbar-->
                        <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1">
                            <!--begin::Search-->
                            <div class="d-flex align-items-stretch me-1">
                                <!--begin::Search-->
                                <div id="kt_header_search"
                                    class="header-search d-flex align-items-center w-100 w-lg-300px"
                                    data-kt-search-keypress="true" data-kt-search-min-length="2"
                                    data-kt-search-enter="enter" data-kt-search-layout="menu"
                                    data-kt-search-responsive="lg" data-kt-menu-trigger="auto"
                                    data-kt-menu-permanent="true" data-kt-menu-placement="bottom-start">
                                    <!--begin::Tablet and mobile search toggle-->
                                    <div data-kt-search-element="toggle"
                                        class="search-toggle-mobile d-flex d-lg-none align-items-center">
                                        <div class="d-flex">
                                            <i class="ki-duotone ki-magnifier fs-1"><span class="path1"></span><span
                                                    class="path2"></span></i>
                                        </div>
                                    </div>
                                    <!--end::Tablet and mobile search toggle-->
                                    <!--begin::Form(use d-none d-lg-block classes for responsive search)-->
                                    <form data-kt-search-element="form"
                                        class="d-none d-lg-block w-100 position-relative mb-5 mb-lg-0"
                                        autocomplete="off">
                                        <!--begin::Hidden input(Added to disable form autocomplete)-->
                                        <input type="hidden" />
                                        <!--end::Hidden input-->
                                        <!--begin::Spinner-->
                                        <span
                                            class="search-spinner position-absolute top-50 end-0 translate-middle-y lh-0 d-none me-5"
                                            data-kt-search-element="spinner">
                                            <span
                                                class="spinner-border h-15px w-15px align-middle text-gray-400"></span>
                                        </span>
                                        <!--end::Spinner-->
                                        <!--begin::Reset-->
                                        <span
                                            class="search-reset btn btn-flush btn-active-color-primary position-absolute top-50 end-0 translate-middle-y lh-0 d-none me-4"
                                            data-kt-search-element="clear">
                                            <i class="ki-duotone ki-cross fs-2 fs-lg-1 me-0"><span class="path1"></span><span class="path2"></span></i>
                                        </span>
                                        <!--end::Reset-->
                                    </form>
                                    <!--end::Form-->
                                </div>
                                <!--end::Search-->
                            </div>
                            <!--end::Search-->
                            <!--begin::Toolbar wrapper-->
                            <div class="d-flex align-items-stretch flex-shrink-0">
                                <!--begin::User-->
                                <div class="d-flex align-items-center ms-2 ms-lg-3" id="kt_header_user_menu_toggle">
                                    <!--begin::Menu wrapper-->
                                    <div class="cursor-pointer symbol symbol-35px symbol-lg-35px"
                                        data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                                        data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                                        <img alt="Pic" src="../../public/assets/media/avatars/300-1.png" />
                                        <b>
                                            <?php echo htmlspecialchars($_SESSION["firstName"] . ' ' . $_SESSION["lastName"]); ?>
                                        </b>
                                    </div>
                                    <!--begin::User account menu-->
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px"
                                        data-kt-menu="true">
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="./profile.php?id=<?php echo htmlspecialchars($_SESSION["id"]); ?>">
                                                <div class="menu-content d-flex align-items-center px-3">
                                                    <!--begin::Avatar-->
                                                    <div class="symbol symbol-50px me-5">
                                                        <img alt="Logo" src="../../public/assets/media/avatars/300-1.png"
                                                            style="max-width:100%;height:auto;" />
                                                    </div>
                                                    <!--end::Avatar-->
                                                    <span>
                                                        <!--begin::Username-->
                                                        <div class="d-flex flex-column">
                                                            <div class="fw-bolder d-flex align-items-center text-black fs-5">
                                                                <?php echo htmlspecialchars($salut . ', ' . $_SESSION["firstName"]); ?>
                                                                <span class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">
                                                                    <?php echo htmlspecialchars($_SESSION["profile"]); ?>
                                                                </span>
                                                            </div>
                                                            <div class="fw-bold text-black text-hover-primary fs-7">
                                                                <?php echo htmlspecialchars($_SESSION["email"]); ?>
                                                            </div>
                                                        </div>
                                                        <!--end::Username-->
                                                    </span>
                                                </div>
                                            </a>
                                        </div>
                                        <!--end::Menu item-->
                                        <!--begin::Menu separator-->
                                        <div class="separator my-2"></div>
                                        <!--end::Menu separator-->
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-5">
                                            <a href="./profile.php?id=<?php echo htmlspecialchars($_SESSION["id"]); ?>"
                                                class="menu-link px-5">
                                                <?php echo htmlspecialchars($my_info); ?>
                                            </a>
                                        </div>
                                        <!--end::Menu item-->
                                        <!--begin::Menu separator-->
                                        <div class="separator my-2"></div>
                                        <!--end::Menu separator-->
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-5"
                                            data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                                            data-kt-menu-placement="left-start" data-kt-menu-offset="-15px, 0">
                                            <a href="#" class="menu-link px-5">
                                                <span class="menu-title position-relative">
                                                    <?php echo htmlspecialchars($langue); ?>
                                                    <span
                                                        class="fs-8 rounded bg-light px-3 py-2 position-absolute translate-middle-y top-50 end-0">
                                                        <?php echo htmlspecialchars($francais); ?> <img class="w-15px h-15px rounded-1 ms-2"
                                                            src="../../public/assets/media/flags/france.svg" alt="" />
                                                    </span>
                                                </span>
                                            </a>
                                            <!--begin::Menu sub-->
                                            <div class="menu-sub menu-sub-dropdown w-175px py-4">
                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3">
                                                    <a href="#" class="menu-link d-flex px-5">
                                                        <span class="symbol symbol-20px me-4">
                                                            <img class="rounded-1"
                                                                src="../../public/assets/media/flags/france.svg" alt="" />
                                                        </span>
                                                        <?php echo htmlspecialchars($francais); ?>
                                                    </a>
                                                </div>
                                                <!--end::Menu item-->
                                            </div>
                                            <!--end::Menu sub-->
                                        </div>
                                        <!--end::Menu item-->
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-5">
                                            <a href="../portal.php" class="menu-link px-5">
                                                <?php echo htmlspecialchars('Retour Portail MEDACAP'); ?>
                                            </a>
                                        </div>
                                        <!--end::Menu item-->
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-5">
                                            <a href="../logout.php" id="logout-button" class="menu-link px-5">
                                                <?php echo htmlspecialchars($deconnexion); ?>
                                            </a>
                                        </div>
                                        <!--end::Menu item-->
                                    </div>
                                    <!--end::User account menu-->
                                    <!--end::Menu wrapper-->
                                </div>
                                <!--end::User -->
                            </div>
                            <!--end::Toolbar wrapper-->
                        </div>
                        <!--end::Topbar-->
                    </div>
                    <!--end::Container-->
                </div>
                <!--end::Header-->

                <script>
                    // Function to clear specific cookies
                    function clearCookies() {
                        document.cookie = 'userFullName=; Max-Age=-99999999; path=/';
                        document.cookie = 'userObjectId=; Max-Age=-99999999; path=/';
                        document.cookie = 'chatExpanded=; Max-Age=-99999999; path=/';
                    }

                    // Add event listener to the logout button
                    document.getElementById('logout-button').addEventListener('click', function(event) {
                        event.preventDefault();
                        // Prevent the default action (navigation)

                        // Clear the cookies
                        clearCookies();

                        // Redirect to the logout page
                        window.location.href = './logout.php';
                    });
                </script>
<script>
    const urlParams = new URLSearchParams(window.location.search);
    const numberTests = urlParams.get('numberTest') || '';
    const technicianIds = urlParams.get('user') || urlParams.get('id') || '';
    const levels = urlParams.get('level') || 'Junior';
    const specialitys = urlParams.get('speciality') || '';

    const specialities = [
        "Arbre de Transmission", "Assistance à la Conduite", "Boite de Transfert", "Boite de Vitesse",
        "Boite de Vitesse Automatique", "Boite de Vitesse Mécanique", "Climatisation", "Demi Arbre de Roue",
        "Direction", "Électricité & Électronique", "Freinage", "Freinage Électromagnétique", "Hydraulique",
        "Moteur Essence", "Moteur Diesel", "Moteur Électrique", "Moteur Thermique", "Réseaux de Communication",
        "Pneumatique", "Pont", "Réducteur", "Suspension", "Suspension à Lame", "Suspension à Ressort", "Suspension Pneumatique"
    ];
    

    /**
     * Génération dynamique du menu spécifique à la spécialité sélectionnée
     */
    function generateDynamicMenu() {
        const dynamicMenuContainer = document.getElementById('dynamicMenuContainer');
        if (!dynamicMenuContainer) return; // S'assurer que l'élément existe

        dynamicMenuContainer.innerHTML = ''; // Réinitialiser le contenu
        const normalizedSpeciality = decodeURIComponent(specialitys).toLowerCase().trim();

        const foundSpeciality = specialities.find(speciality => speciality.toLowerCase() === normalizedSpeciality);
        const currentUrl = window.location.href;

        if (foundSpeciality) {
            const link = `./system.php?numberTest=${numberTests}&speciality=${encodeURIComponent(foundSpeciality)}&level=${levels}&user=${technicianIds}`;
            const menuItem = document.createElement('a');
            menuItem.href = link;
            menuItem.className = 'menu-link';
            menuItem.id = `menuLink-${encodeURIComponent(foundSpeciality)}`;

            if (currentUrl.includes(`speciality=${encodeURIComponent(foundSpeciality)}`)) {
                menuItem.classList.add('active');
            }

            menuItem.innerHTML = `
                <span class="menu-icon">
                    <i class="fas fa-address-book fs-2"><span class="path1"></span><span class="path2"></span></i>
                </span>
                <span class="menu-title">Résultats du Technicien : ${foundSpeciality}</span>
            `;
            dynamicMenuContainer.appendChild(menuItem);
        } else {
            dynamicMenuContainer.innerHTML = "<p>Aucune spécialité sélectionnée.</p>";
        }
        dynamicMenuContainer.style.display = 'block';
    }

    /**
     * Basculer l'affichage des sous-menus
     */
    function toggleSubmenu() {
        const submenuItems = document.getElementById('submenuItems');
        if (submenuItems) {
            submenuItems.style.display = submenuItems.style.display === 'none' ? 'block' : 'none';
        }
    }

    /**
     * Navigation vers la page détail
     */
    function navigateToDetail() {
        const url = `./detail.php?numberTest=${numberTests}&id=${technicianIds}&level=${levels}`;
        window.location.href = url;
    }

    /**
     * Navigation vers la page des résultats par marque
     */
    function navigateToBrandResult() {
        const url = `./brandResult.php?numberTest=${numberTests}&id=${technicianIds}&level=${levels}`;
        window.location.href = url;
    }

    /**
     * Gestion de l'activation des liens et des sous-menus
     */
    function activateMenuLinks() {
        const currentUrl = window.location.href;
        const menuLinks = document.querySelectorAll('.menu-link');

        menuLinks.forEach(link => {
            const linkHref = link.getAttribute('href');
            if (linkHref && currentUrl.includes(linkHref)) {
                link.classList.add('active');

                const submenuContainer = link.closest('.submenu-container');
                if (submenuContainer) {
                    submenuContainer.style.display = 'block';
                }

                let parentMenuItem = link.closest('.menu-item');
                while (parentMenuItem) {
                    parentMenuItem.classList.add('active');
                    parentMenuItem = parentMenuItem.parentElement.closest('.menu-item');
                }
            }
        });
    }

    /**
     * Initialisation au chargement de la page
     */
    window.onload = function () {
        // Générer dynamiquement le menu pour system.php
        if (window.location.pathname.includes('system.php')) {
            generateDynamicMenu();
        }

        // Activer les liens correspondants
        activateMenuLinks();

        // Faire défiler jusqu'au lien actif
        const activeMenuLink = document.querySelector('.menu-link.active');
        if (activeMenuLink) {
            setTimeout(() => {
                activeMenuLink.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }, 100);
        }
    };
</script>

</body>

</html>