<?php
    // Assuming you have the country stored in a session variable
    $country = $_SESSION["country"]; // Set this session variable based on your logic
        
    // Map countries to their respective agencies
    $agencies = [
        "Burkina Faso" => ["Ouaga"],
        "Cameroun" => ["Bafoussam","Bertoua", "Douala", "Garoua", "Ngaoundere", "Yaoundé"],
        "Cote d'Ivoire" => ["Vridi - Equip"],
        "Gabon" => ["Libreville"],
        "Mali" => ["Bamako"],
        "RCA" => ["Bangui"],
        "RDC" => ["Kinshasa", "Kolwezi", "Lubumbashi"],
        "Senegal" => ["Dakar"],
        // Add more countries and their agencies here
    ];
    // Map countries
    $countries = [
        "Burkina Faso","Cameroun", "Cote d'Ivoire", "Gabon", "Mali", "RCA", "RDC", "Senegal"
        // Add more countries and their agencies here
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
        // Add more countries and their agencies here
    ];
                                    
?>
<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->
<!-- Mirrored from preview.keenthemes.com/craft/ by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 14 Jul 2023 10:40:27 GMT -->
<!-- Added by HTTrack -->
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<!-- /Added by HTTrack -->
<!-- Favicon -->
<link href="../../public/images/logo-cfao.png" rel="icon">

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
        j.src =
            '../../../../www.googletagmanager.com/gtm5445.html?id=' + i + dl;
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
            style="display:none;visibility:hidden"></iframe></noscript>
    <!--End::Google Tag Manager (noscript) -->
    <!--begin::Main-->
    <!--begin::Root-->
    <div class="d-flex flex-column flex-root">
        <!--begin::Page-->
        <div class="page d-flex flex-row flex-column-fluid">
            <!--begin::Aside-->
            <?php if (
                $_SESSION["profile"] == "Super Admin" ||
                $_SESSION["profile"] == "Admin" || $_SESSION["profile"] == "Ressource Humaine" || $_SESSION["profile"] == "Directeur Filiale" || $_SESSION["profile"] == "Directeur Groupe"
            ) { ?>
                <div id="kt_aside" class="aside aside-default  aside-hoverable " data-kt-drawer="true"
                    data-kt-drawer-name="aside" data-kt-drawer-activate="{default: true, lg: false}"
                    data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '300px': '250px'}"
                    data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_aside_toggle">
                    <!--begin::Brand-->
                    <div style="background:#e6e6e6;" class="aside-logo flex-column-auto px-10 pt-9 pb-5" id="kt_aside_logo">
                        <!--begin::Logo-->
                        <a href="./dashboard">
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
                            <?php if (
                                $_SESSION["profile"] == "Super Admin"
                            ) { ?>
                                <!--begin:Menu content-->
                                <div class="menu-content text-center"><span
                                        class="fw-bolder text-black text-uppercase fs-4"><?php echo $super_admin_space ?></span>
                                </div>
                                <!--end:Menu content-->
                            <?php } ?>
                            <?php if (
                                $_SESSION["profile"] == "Admin"
                            ) { ?>
                                <!--begin:Menu content-->
                                <div class="menu-content text-center"><span
                                        class="fw-bolder text-black text-uppercase fs-4"><?php echo $admin_space ?></span>
                                </div>
                                <div class="menu-content text-center"><span
                                        class="fw-bolder text-black text-uppercase fs-4"><?php echo $_SESSION["country"] ?></span>
                                </div>
                                <!--end:Menu content-->
                            <?php } ?>
                            <?php if (
                                $_SESSION["profile"] == "Ressource Humaine"
                            ) { ?>
                                <!--begin:Menu content-->
                                <div class="menu-content text-center"><span
                                        class="fw-bolder text-black text-uppercase fs-4"><?php echo $rh_space ?></span>
                                </div>
                                <div class="menu-content text-center"><span
                                        class="fw-bolder text-black text-uppercase fs-4"><?php echo $_SESSION["country"] ?></span>
                                </div>
                                <!--end:Menu content-->
                            <?php } ?>
                            <?php if (
                                $_SESSION["profile"] == "Directeur Filiale"
                            ) { ?>
                                <!--begin:Menu content-->
                                <div class="menu-content text-center"><span
                                        class="fw-bolder text-black text-uppercase fs-4"><?php echo $dir_filiale_space ?></span>
                                </div>
                                <div class="menu-content text-center"><span
                                        class="fw-bolder text-black text-uppercase fs-4"><?php echo $_SESSION["country"] ?></span>
                                </div>
                                <!--end:Menu content-->
                            <?php } ?>
                            <?php if (
                                $_SESSION["profile"] == "Directeur Groupe"
                            ) { ?>
                                <!--begin:Menu content-->
                                <div class="menu-content text-center"><span
                                        class="fw-bolder text-black text-uppercase fs-4"><?php echo $dir_grp_space ?></span>
                                </div>
                                <!--end:Menu content-->
                            <?php } ?>
                        </div> <br>
                        <!--end:Menu item-->
                        <!--begin::Menu-->
                        <div class="menu menu-sub-indention menu-column menu-rounded menu-title-gray-600 menu-icon-gray-400 menu-active-bg menu-state-primary menu-arrow-gray-500 fw-semibold fs-6 my-5 mt-lg-2 mb-lg-0"
                            id="kt_aside_menu" data-kt-menu="true">
                            <div class="hover-scroll-y mx-4" id="kt_aside_menu_wrapper" data-kt-scroll="true"
                                data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto"
                                data-kt-scroll-wrappers="#kt_aside_menu" data-kt-scroll-offset="20px"
                                data-kt-scroll-dependencies="#kt_aside_logo, #kt_aside_footer">
                                <!--begin:Menu item-->
                                <di data-kt-menu-trigger="click" class="menu-item here show menu-accordion">
                                    <?php if (
                                        $_SESSION["profile"] == "Super Admin" || $_SESSION["profile"] == "Admin" || $_SESSION["profile"] == "Ressource Humaine" || $_SESSION["profile"] == "Directeur Filiale" || $_SESSION["profile"] == "Directeur Groupe"
                                    ) { ?>
                                        <!--begin:Menu link-->
                                        <div class="menu-item">
                                            <!--begin:Menu link-->
                                            <a class="menu-link" href="./dashboard"><span
                                                    class="menu-icon">
                                                    <i class="ki-duotone ki-element-11 fs-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                        <span class="path3"></span>
                                                        <span class="path4"></span>
                                                    </i></span><span class="menu-title"><?php echo $tableau ?></span>
                                                </a>
                                            <!--end:Menu link-->
                                        </div>
                                        <!--end:Menu link-->
                                    <?php } ?>
                                    <?php if ($_SESSION["profile"] == "Admin" || $_SESSION["profile"] == "Ressource Humaine") { ?>
                                        <!--begin:Menu item-->
                                        <div class="menu-content"><span
                                                class="fw-bold text-black text-uppercase fs-70"><?php echo $etat_avanacement_agences ?></span>
                                        </div>
                                        <?php
                                        if (isset($agencies[$country])) {
                                            foreach ($agencies[$country] as $agency) { ?>
                                                <div class="menu-item">
                                                    <a class="menu-link" href="./stateAgency?agency=<?php echo $agency; ?>"><span
                                                            class="menu-icon">
                                                            <i class="fa fa-line-chart fs-2" ></i>
                                                        </span><span class="menu-title"><?php echo $etat_avanacement_agence.' '.$agency ?></span></a>
                                                </div>
                                            <?php }
                                        } ?>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item pt-5">
                                            <!--begin:Menu content-->
                                            <div class="menu-content"><span
                                                    class="fw-bold text-black text-uppercase fs-70"><?php echo $calendar ?></span>
                                            </div>
                                            <!--end:Menu content-->
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item">
                                            <!--begin:Menu link--><a class="menu-link" href="./calendar"><span
                                                    class="menu-icon">
                                                    <i class="ki-duotone ki-calendar fs-2"><span class="path1"></span><span
                                                            class="path2"></span></i></span><span class="menu-title">
                                                    <?php echo $calendar ?>
                                                </span></a>
                                            <!--end:Menu link-->
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item pt-5">
                                            <!--begin:Menu content-->
                                            <div class="menu-content"><span
                                                    class="fw-bold text-black text-uppercase fs-70"><?php echo $ussers ?></span>
                                            </div>
                                            <!--end:Menu content-->
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item">
                                            <!--begin:Menu link--><a class="menu-link" href="./users"><span
                                                    class="menu-icon">
                                                    <i class="fas fa-users fs-3"><span class="path1"></span><span
                                                            class="path2"></span></i></span><span class="menu-title">
                                                    <?php echo $list_user ?>
                                                </span></a>
                                            <!--end:Menu link-->
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <!-- <div class="menu-item"> -->
                                            <!--begin:Menu link-->
                                            <!-- <a class="menu-link" href="./createUser"><span
                                                    class="menu-icon">
                                                    <i class="fas fa-users fs-3"><span class="path1"></span><span
                                                            class="path2"></span></i></span><span class="menu-title">
                                                    <?php echo $title_addUser ?>
                                                </span></a> -->
                                            <!--end:Menu link-->
                                        <!-- </div> -->
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <!-- <div class="menu-item"> -->
                                            <!--begin:Menu link-->
                                            <!-- <a class="menu-link" href="./editDeletUser"><span
                                                    class="menu-icon">
                                                    <i class="fas fa-users fs-3"><span class="path1"></span><span
                                                            class="path2"></span></i></span><span class="menu-title">
                                                    <?php echo $title_edit_user ?>
                                                </span></a> -->
                                            <!--end:Menu link-->
                                        <!-- </div> -->
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item pt-5">
                                            <!--begin:Menu content-->
                                            <div class="menu-content"><span
                                                    class="fw-bold text-black text-uppercase fs-70"><?php echo $bilan_evaluation ?></span>
                                            </div>
                                            <!--end:Menu content-->
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item">
                                            <?php
                                            // Détection de la page active
                                            $currentUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                                            $isResultsPage = strpos($currentUrl, 'results.php') !== false;
                                            $isResultPage = strpos($currentUrl, 'result.php') !== false; // Vérifie si nous sommes sur la page des résultats
                                            $isSystemPage = strpos($currentUrl, 'system.php') !== false;

                                            // Paramètres de l'URL
                                            $numberTest = $_GET['numberTest'] ?? '';
                                            $level = $_GET['level'] ?? 'Junior';
                                            $technicianId = $_GET['user'] ?? '';
                                            $speciality = $_GET['speciality'] ?? '';
                                            ?>

                                            <!-- Menu principal avec condition pour afficher la flèche -->
                                            <?php if (!$isSystemPage): ?>
                                                
                                                <a class="menu-link <?php echo ($isResultsPage || $isResultPage) ? 'active' : ''; ?>" href="./results.php" id="listResultLink">
                                                    <span class="menu-icon">
                                                        <i class="fas fa-address-book fs-2"><span class="path1"></span><span class="path2"></span></i>
                                                    </span>
                                                    <?php if (!$isResultPage): ?>
                                                        <span class="menu-title"><?php echo $list_result?></span>
                                                    <?php else : ?>
                                                        <span class="menu-title"><?php echo $list_result." : ".$Level ; ?></span>
                                                    <?php endif ?>

                                                </a>
                                            <?php endif; ?>
                                            <!-- Conteneur pour le menu dynamique -->
                                            <div id="dynamicMenuContainer" style="display: <?php echo $isSystemPage ? 'block' : 'none'; ?>;"></div>

                                            <!--end:Menu link-->
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item">
                                            <!--begin:Menu link--><a class="menu-link" href="./userSubsidiaryResult?level=Junior&subsidiary=<?php echo $_SESSION["subsidiary"] ?>"><span
                                                    class="menu-icon">
                                                    <i class="fas fa-address-book fs-2"><span class="path1"></span><span
                                                            class="path2"></span></i>
                                                        </span><span class="menu-title"><?php echo $result_techs ?> <?php echo $Level ?> <?php echo $junior ?> <?php echo $by_brand ?></span></a>
                                            <!--end:Menu link-->
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item">
                                            <!--begin:Menu link--><a class="menu-link" href="./userSubsidiaryResult?level=Senior&subsidiary=<?php echo $_SESSION["subsidiary"] ?>"><span
                                                    class="menu-icon">
                                                    <i class="fas fa-address-book fs-2"><span class="path1"></span><span
                                                            class="path2"></span></i>
                                                        </span><span class="menu-title"><?php echo $result_techs ?> <?php echo $Level ?> <?php echo $senior ?> <?php echo $by_brand ?></span></a>
                                            <!--end:Menu link-->
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item">
                                            <!--begin:Menu link--><a class="menu-link" href="./userSubsidiaryResult?level=Expert&subsidiary=<?php echo $_SESSION["subsidiary"] ?>"><span
                                                    class="menu-icon">
                                                    <i class="fas fa-address-book fs-2"><span class="path1"></span><span
                                                            class="path2"></span></i>
                                                        </span><span class="menu-title"><?php echo $result_techs ?> <?php echo $Level ?> <?php echo $expert ?> <?php echo $by_brand ?></span></a>
                                            <!--end:Menu link-->
                                        </div>
                                        <!--end:Menu item-->
                                    <?php } ?>
                                    <?php if ($_SESSION["profile"] == "Super Admin") { ?>
                                        <!--begin:Menu item-->
                                        <div class="menu-content"><span
                                            class="fw-bold text-black text-uppercase fs-70"><?php echo $etat_avanacement ?></span>
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item">
                                            <a class="menu-link" href="./stateCountry"><span
                                                    class="menu-icon">
                                                    <i class="fa fa-line-chart fs-2" ></i>
                                                </span><span class="menu-title"><?php echo $etat_avanacement_qcm_country ?></span></a>
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item">
                                            <a class="menu-link" href="./stateCountryTest"><span
                                                    class="menu-icon">
                                                    <i class="fa fa-line-chart fs-2" ></i>
                                                </span><span class="menu-title"><?php echo $etat_avanacement_test_country ?></span></a>
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item">
                                            <a class="menu-link" href="./stateAgency"><span
                                                    class="menu-icon">
                                                    <i class="fa fa-line-chart fs-2" ></i>
                                                </span><span class="menu-title"><?php echo $etat_avanacement_qcm_agences ?></span></a>
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item pt-5">
                                            <!--begin:Menu content-->
                                            <div class="menu-content"><span
                                                    class="fw-bold text-black text-uppercase fs-70"><?php echo $calendarFiliale ?></span>
                                            </div>
                                            <!--end:Menu content-->
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item">
                                            <!--begin:Menu link--><a class="menu-link" href="./calendar"><span
                                                    class="menu-icon">
                                                    <i class="ki-duotone ki-calendar fs-2"><span class="path1"></span><span
                                                            class="path2"></span></i></span><span class="menu-title">
                                                    <?php echo $calendar ?>
                                                </span></a>
                                            <!--end:Menu link-->
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item pt-5">
                                            <!--begin:Menu content-->
                                            <div class="menu-content"><span
                                                    class="fw-bold text-black text-uppercase fs-70"><?php echo $ussers ?></span>
                                            </div>
                                            <!--end:Menu content-->
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item">
                                            <!--begin:Menu link--><a class="menu-link" href="./users"><span
                                                    class="menu-icon">
                                                    <i class="fas fa-users fs-3"><span class="path1"></span><span
                                                            class="path2"></span></i></span><span class="menu-title">
                                                    <?php echo $list_user ?>
                                                </span></a>
                                            <!--end:Menu link-->
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item">
                                            <!--begin:Menu link--><a class="menu-link" href="./createUser"><span
                                                    class="menu-icon">
                                                    <i class="fas fa-users fs-3"><span class="path1"></span><span
                                                            class="path2"></span></i></span><span class="menu-title">
                                                    <?php echo $title_addUser ?>
                                                </span></a>
                                            <!--end:Menu link-->
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item">
                                            <!--begin:Menu link--><a class="menu-link" href="./editDeletUser"><span
                                                    class="menu-icon">
                                                    <i class="fas fa-users fs-3"><span class="path1"></span><span
                                                            class="path2"></span></i></span><span class="menu-title">
                                                    <?php echo $title_edit_sup_user ?>
                                                </span></a>
                                            <!--end:Menu link-->
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item">
                                            <!--begin:Menu link--><a class="menu-link" href="./importeUser"><span
                                                    class="menu-icon">
                                                    <i class="fas fa-users fs-3"><span class="path1"></span><span
                                                            class="path2"></span></i></span><span class="menu-title">
                                                    <?php echo $import_user ?>
                                                </span></a>
                                            <!--end:Menu link-->
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item pt-5">
                                            <!--begin:Menu content-->
                                            <div class="menu-content"><span
                                                    class="fw-bold text-black text-uppercase fs-70"><?php echo $quizs ?></span>
                                            </div>
                                            <!--end:Menu content-->
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item">
                                            <!--begin:Menu link--><a class="menu-link" href="./quizzes"><span
                                                    class="menu-icon">
                                                    <i
                                                        class="fas fa-laptop-code fs-2"><span class="path1"></span><span
                                                            class="path2"></span></i></span><span class="menu-title">
                                                    <?php echo $list_quiz ?>
                                                </span></a>
                                            <!--end:Menu link-->
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item">
                                            <!--begin:Menu link--><a class="menu-link" href="./editDeletQuiz"><span
                                                    class="menu-icon">
                                                    <i
                                                        class="fas fa-laptop-code fs-2"><span class="path1"></span><span
                                                            class="path2"></span></i></span><span class="menu-title">
                                                    <?php echo $title_edit_sup_quiz ?>
                                                </span></a>
                                            <!--end:Menu link-->
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item pt-5">
                                            <!--begin:Menu content-->
                                            <div class="menu-content"><span
                                                    class="fw-bold text-black text-uppercase fs-70"><?php echo $questionType ?></span>
                                            </div>
                                            <!--end:Menu content-->
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item">
                                            <!--begin:Menu link--><a class="menu-link" href="./questions"><span
                                                    class="menu-icon">
                                                    <i
                                                        class="fas fa-question fs-2"><span class="path1"></span><span
                                                            class="path2"></span></i></span><span class="menu-title">
                                                    <?php echo $list_question ?>
                                                </span></a>
                                            <!--end:Menu link-->
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item">
                                            <!--begin:Menu link--><a class="menu-link" href="./createQuestion"><span
                                                    class="menu-icon">
                                                    <i
                                                        class="fas fa-question fs-2"><span class="path1"></span><span
                                                            class="path2"></span></i></span><span class="menu-title">
                                                    <?php echo $title_question ?>
                                                </span></a>
                                            <!--end:Menu link-->
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item">
                                            <!--begin:Menu link--><a class="menu-link" href="./editDeletQuestion"><span
                                                    class="menu-icon">
                                                    <i
                                                        class="fas fa-question fs-2"><span class="path1"></span><span
                                                            class="path2"></span></i></span><span class="menu-title">
                                                    <?php echo $title_edit_sup_question ?>
                                                </span></a>
                                            <!--end:Menu link-->
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item">
                                            <!--begin:Menu link--><a class="menu-link" href="./importeQuestion"><span
                                                    class="menu-icon">
                                                    <i
                                                        class="fas fa-question fs-2"><span class="path1"></span><span
                                                            class="path2"></span></i></span><span class="menu-title">
                                                    <?php echo $import_question ?>
                                                </span></a>
                                            <!--end:Menu link-->
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item pt-5">
                                            <!--begin:Menu content-->
                                            <div class="menu-content"><span
                                                    class="fw-bold text-black text-uppercase fs-70"><?php echo $type_vehicle ?></span>
                                            </div>
                                            <!--end:Menu content-->
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item">
                                            <!--begin:Menu link--><a class="menu-link" href="./vehicles"><span
                                                    class="menu-icon">
                                                    <i class="fas fa-car-side fs-2"></i></span><span class="menu-title">
                                                    <?php echo $list_type_vehicule ?>
                                                </span></a>
                                            <!--end:Menu link-->
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item">
                                            <!--begin:Menu link--><a class="menu-link" href="./createVehicle"><span
                                                    class="menu-icon">
                                                    <i class="fas fa-car-side fs-2"></i></span><span class="menu-title">
                                                    <?php echo $title_vehicle ?>
                                                </span></a>
                                            <!--end:Menu link-->
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item">
                                            <!--begin:Menu link--><a class="menu-link" href="./editDeletVehicle"><span
                                                    class="menu-icon">
                                                    <i class="fas fa-car-side fs-2"></i></span><span class="menu-title">
                                                    <?php echo $title_edit_sup_vehicle ?>
                                                </span></a>
                                            <!--end:Menu link-->
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item pt-5">
                                            <!--begin:Menu content-->
                                            <div class="menu-content"><span
                                                    class="fw-bold text-black text-uppercase fs-70"><?php echo $Specialities ?></span>
                                            </div>
                                            <!--end:Menu content-->
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item">
                                            <!--begin:Menu link--><a class="menu-link" href="./specialities"><span
                                                    class="menu-icon">
                                                    <i class="ki-duotone ki-abstract-41 fs-1"><span class="path1"></span><span class="path2"></span></i></span><span class="menu-title">
                                                    <?php echo $list_specialities ?>
                                                </span></a>
                                            <!--end:Menu link-->
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item">
                                            <!--begin:Menu link--><a class="menu-link" href="./editDeletSpeciality"><span
                                                    class="menu-icon">
                                                    <i class="ki-duotone ki-abstract-41 fs-1"><span class="path1"></span><span class="path2"></span></i></span><span class="menu-title">
                                                    <?php echo $title_edit_sup_speciality ?>
                                                </span></a>
                                            <!--end:Menu link-->
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item pt-5">
                                            <!--begin:Menu content-->
                                            <div class="menu-content"><span
                                                    class="fw-bold text-black text-uppercase fs-70"><?php echo $Trainings ?></span>
                                            </div>
                                            <!--end:Menu content-->
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item">
                                            <!--begin:Menu link--><a class="menu-link" href="./trainings"><span
                                                    class="menu-icon">
                                                    <i class="fas fa-graduation-cap fs-2"><span class="path1"></span><span class="path2"></span></i></span><span class="menu-title">
                                                    <?php echo $list_training ?>
                                                </span></a>
                                            <!--end:Menu link-->
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item">
                                            <!--begin:Menu link--><a class="menu-link" href="./createTraining"><span
                                                    class="menu-icon">
                                                    <i class="fas fa-graduation-cap fs-2"></i></span><span class="menu-title">
                                                    <?php echo $title_training ?>
                                                </span></a>
                                            <!--end:Menu link-->
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item">
                                            <!--begin:Menu link--><a class="menu-link" href="./editDeletTraining"><span
                                                    class="menu-icon">
                                                    <i class="fas fa-graduation-cap fs-2"></i></span><span class="menu-title">
                                                    <?php echo $title_edit_sup_training ?>
                                                </span></a>
                                            <!--end:Menu link-->
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item">
                                            <!--begin:Menu link--><a class="menu-link" href="./importeTraining"><span
                                                    class="menu-icon">
                                                    <i class="fas fa-graduation-cap fs-2"></i></span><span class="menu-title">
                                                    <?php echo $import_training ?>
                                                </span></a>
                                            <!--end:Menu link-->
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item pt-5">
                                            <!--begin:Menu content-->
                                            <div class="menu-content"><span
                                                    class="fw-bold text-black text-uppercase fs-70"><?php echo $comment_users ?></span>
                                            </div>
                                            <!--end:Menu content-->
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item">
                                            <!--begin:Menu link--><a class="menu-link" href="./messages"><span
                                                    class="menu-icon">
                                                    <i
                                                        class="fas fa-message fs-2"><span class="path1"></span><span
                                                            class="path2"></span></i></span><span class="menu-title">
                                                    <?php echo $list_comment_users ?>
                                                </span></a>
                                            <!--end:Menu link-->
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item pt-5">
                                            <!--begin:Menu content-->
                                            <div class="menu-content"><span
                                                    class="fw-bold text-black text-uppercase fs-70"><?php echo $tab_Group_marques ?></span>
                                            </div>
                                            <!--end:Menu content-->
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item">
                                            <!--begin:Menu link--><a class="menu-link" href="./tests"><span
                                                    class="menu-icon">
                                                    <i
                                                        class="fas fa-book fs-2"><span class="path1"></span><span
                                                            class="path2"></span></i></span><span class="menu-title">
                                                    <?php echo $tab_Group_marques ?>
                                                </span></a>
                                            <!--end:Menu link-->
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item pt-5">
                                            <!--begin:Menu content-->
                                            <div class="menu-content"><span
                                                    class="fw-bold text-black text-uppercase fs-70"><?php echo $seuil_validation ?></span>
                                            </div>
                                            <!--end:Menu content-->
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item">
                                            <!--begin:Menu link-->
                                            <a class="menu-link" href="./validation">
                                                <span
                                                    class="menu-icon">
                                                    <i class="fa fa-edit fs-2" ></i>
                                                </span>
                                                <span class="menu-title"><?php echo $edit_validation ?></span>
                                            </a>
                                            <!--end:Menu link-->
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item pt-5">
                                            <!--begin:Menu content-->
                                            <div class="menu-content"><span
                                                    class="fw-bold text-black text-uppercase fs-70"><?php echo $title_activation ?></span>
                                            </div>
                                            <!--end:Menu content-->
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item">
                                            <!--begin:Menu link-->
                                            <a class="menu-link" href="./activation">
                                                <span
                                                    class="menu-icon">
                                                    <i class="fa fa-refresh fs-2" ></i>
                                                </span>
                                                <span class="menu-title"><?php echo $title_activation ?></span>
                                            </a>
                                            <!--end:Menu link-->
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item pt-5">
                                            <!--begin:Menu content-->
                                            <div class="menu-content"><span
                                                    class="fw-bold text-black text-uppercase fs-70"><?php echo $assignation_test ?></span>
                                            </div>
                                            <!--end:Menu content-->
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <!-- <div class="menu-item"> -->
                                            <!--begin:Menu link-->
                                            <!-- <a class="menu-link" href="./allocateUserTest">
                                                <span class="menu-icon">
                                                    <i class="fas fa-exchange fs-2"><span class="path1"></span><span
                                                            class="path2"></span></i>
                                                </span><span class="menu-title"><?php echo $add_test_tech ?></span></a> -->
                                            <!--end:Menu link-->
                                        <!-- </div> -->
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item">
                                            <!--begin:Menu link--><a class="menu-link" href="./reallocateUserTest">
                                                <span class="menu-icon">
                                                    <i class="fas fa-exchange fs-2"><span class="path1"></span><span
                                                            class="path2"></span></i>
                                                </span><span class="menu-title"><?php echo $reassigne_test ?></span></a>
                                            <!--end:Menu link-->
                                        </div>
                                        <!--end:Menu item-->
                                    <?php } ?>
                                    <?php if ($_SESSION["profile"] == "Directeur Filiale" || $_SESSION["profile"] == "Directeur Groupe") { ?>
                                        <?php if ($_SESSION["profile"] == "Directeur Filiale") { ?>
                                            <div class="menu-content"><span
                                                    class="fw-bold text-black text-uppercase fs-70"><?php echo $etat_avanacement_agences ?></span>
                                            </div>
                                            <?php
                                            if (isset($agencies[$country])) {
                                                foreach ($agencies[$country] as $agency) { ?>
                                                    <div class="menu-item">
                                                        <a class="menu-link" href="./stateAgency?agency=<?php echo $agency; ?>"><span
                                                                class="menu-icon">
                                                                <i class="fa fa-line-chart fs-2" ></i>
                                                            </span><span class="menu-title"><?php echo $etat_avanacement_agence ?> <?php echo $agency; ?></span></a>
                                                    </div>
                                                <?php }
                                            } ?>
                                        <?php } ?>
                                        <?php if ($_SESSION["profile"] == "Directeur Groupe") { ?>
                                            <!--begin:Menu item-->
                                            <div class="menu-content"><span
                                                    class="fw-bold text-black text-uppercase fs-70"><?php echo $etat_avanacement_subsidiary ?></span>
                                            </div>
                                            <div class="menu-item">
                                                <a class="menu-link" href="./stateFiliale?country=<?php echo $_GET['country'] ?? 'Cameroun' ?>"><span
                                                        class="menu-icon">
                                                        <i class="fa fa-line-chart fs-2" ></i>
                                                    </span><span class="menu-title"><?php echo $etat_avanacement_filiale ?> <?php echo $_GET['country'] ?? 'Cameroun' ?></span></a>
                                            </div>
                                            <!--end:Menu item-->
                                            <!--begin:Menu item-->
                                            <div class="menu-item pt-5">
                                                <!--begin:Menu content-->
                                                <div class="menu-content"><span
                                                        class="fw-bold text-black text-uppercase fs-70"><?php echo $taux_de_couvertur ?></span>
                                                </div>
                                                <!--end:Menu content-->
                                            </div>
                                            <!--end:Menu item-->
                                            <!--begin:Menu item-->
                                            <div class="menu-item">
                                                <!--begin:Menu link--><a class="menu-link" href="./userSpeciality?level=<?php echo $_GET['level'] ?? 'Junior' ?>&country=<?php echo $_GET['country'] ?? 'tous' ?>"><span
                                                        class="menu-icon">
                                                        <i class="fas fa-address-book fs-2"><span class="path1"></span><span
                                                                class="path2"></span></i></span><span class="menu-title"><?php echo $taux_cover_tache_pro ?></span></a>
                                                <!--end:Menu link-->
                                            </div>
                                            <!--end:Menu item-->
                                            <!--begin:Menu item-->
                                            <div class="menu-item pt-5">
                                                <!--begin:Menu content-->
                                                <div class="menu-content"><span
                                                        class="fw-bold text-black text-uppercase fs-70"><?php echo $result ?></span>
                                                </div>
                                                <!--end:Menu content-->
                                            </div>
                                            <!--end:Menu item-->
                                        <?php } ?>
                                        <?php if ($_SESSION["profile"] == "Directeur Filiale") { ?>
                                            <!--begin:Menu item-->
                                            <div class="menu-item pt-5">
                                                <!--begin:Menu content-->
                                                <div class="menu-content"><span
                                                        class="fw-bold text-black text-uppercase fs-70"><?php echo $taux_de_couvertur ?></span>
                                                </div>
                                                <!--end:Menu content-->
                                            </div>
                                            <!--end:Menu item-->
                                           <?php if (isset($_GET['country'])) { ?>
                                                <!--begin:Menu item-->
                                                <div class="menu-item">
                                                    <!--begin:Menu link--><a class="menu-link" href="./userSpeciality?level=<?php echo $_GET['level'] ?? 'Junior' ?>&country=<?php echo $_SESSION['country'] ?>"><span
                                                            class="menu-icon">
                                                            <i class="fas fa-address-book fs-2"><span class="path1"></span><span
                                                                    class="path2"></span></i></span><span class="menu-title"><?php echo $taux_cover_tache_pro ?></span></a>
                                                    <!--end:Menu link-->
                                                </div>
                                                <!--end:Menu item-->
                                            <?php } elseif (isset($_GET['agency'])) { ?>
                                                <!--begin:Menu item-->
                                                <div class="menu-item">
                                                    <!--begin:Menu link--><a class="menu-link" href="./userSpeciality?level=<?php echo $_GET['level'] ?? 'Junior' ?>&agency=<?php echo $_GET['agency'] ?>"><span
                                                            class="menu-icon">
                                                            <i class="fas fa-address-book fs-2"><span class="path1"></span><span
                                                                    class="path2"></span></i></span><span class="menu-title"><?php echo $taux_cover_tache_pro ?></span></a>
                                                    <!--end:Menu link-->
                                                </div>
                                                <!--end:Menu item-->                     
                                            <?php } else { ?>
                                                <!--begin:Menu item-->
                                                <div class="menu-item">
                                                    <!--begin:Menu link--><a class="menu-link" href="./userSpeciality"><span
                                                            class="menu-icon">
                                                            <i class="fas fa-address-book fs-2"><span class="path1"></span><span
                                                                    class="path2"></span></i></span><span class="menu-title"><?php echo $taux_cover_tache_pro ?></span></a>
                                                    <!--end:Menu link-->
                                                </div>
                                                <!--end:Menu item-->                    
                                            <?php } ?>
                                            <!--begin:Menu item-->
                                            <div class="menu-item pt-5">
                                                <!--begin:Menu content-->
                                                <div class="menu-content"><span
                                                        class="fw-bold text-black text-uppercase fs-70"><?php echo $result ?></span>
                                                </div>
                                                <!--end:Menu content-->
                                            </div>
                                            <!--end:Menu item-->
                                            <!--begin:Menu item-->
                                            <div class="menu-item">
                                                <?php
                                                // Détection de la page active
                                                $currentUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                                                $isResultsPage = strpos($currentUrl, 'results.php') !== false;
                                                $isResultPage = strpos($currentUrl, 'result.php') !== false; // Vérifie si nous sommes sur la page des résultats
                                                $isSystemPage = strpos($currentUrl, 'system.php') !== false;

                                                // Paramètres de l'URL
                                                $numberTest = $_GET['numberTest'] ?? '';
                                                $level = $_GET['level'] ?? 'Junior';
                                                $technicianId = $_GET['user'] ?? '';
                                                $speciality = $_GET['speciality'] ?? '';
                                                ?>

                                                <!-- Menu principal avec condition pour afficher la flèche -->
                                                <?php if (!$isSystemPage): ?>
                                                    <a class="menu-link <?php echo ($isResultsPage || $isResultPage) ? 'active' : ''; ?>" href="./results.php" id="listResultLink">
                                                        <span class="menu-icon">
                                                            <i class="fas fa-address-book fs-2"><span class="path1"></span><span class="path2"></span></i>
                                                        </span>
                                                        <span class="menu-title"><?php echo $list_result; ?></span>
                                                    </a>
                                                <?php endif; ?>
                                                <!-- Conteneur pour le menu dynamique -->
                                                <div id="dynamicMenuContainer" style="display: <?php echo $isSystemPage ? 'block' : 'none'; ?>;"></div>

                                                <!--end:Menu link-->
                                            </div>
                                            <!--end:Menu item-->
                                            <!--begin:Menu item-->
                                            <div class="menu-item">
                                                <!--begin:Menu link--><a class="menu-link" href="./userSubsidiaryResult?level=Junior&subsidiary=<?php echo $_SESSION["subsidiary"] ?>"><span
                                                        class="menu-icon">
                                                        <i class="fas fa-address-book fs-2"><span class="path1"></span><span
                                                                class="path2"></span></i>
                                                            </span><span class="menu-title"><?php echo $result_techs ?> <?php echo $Level ?> <?php echo $junior ?> <?php echo $by_brand ?></span></a>
                                                <!--end:Menu link-->
                                            </div>
                                            <!--end:Menu item-->
                                            <!--begin:Menu item-->
                                            <div class="menu-item">
                                                <!--begin:Menu link--><a class="menu-link" href="./userSubsidiaryResult?level=Senior&subsidiary=<?php echo $_SESSION["subsidiary"] ?>"><span
                                                        class="menu-icon">
                                                        <i class="fas fa-address-book fs-2"><span class="path1"></span><span
                                                                class="path2"></span></i>
                                                            </span><span class="menu-title"><?php echo $result_techs ?> <?php echo $Level ?> <?php echo $senior ?> <?php echo $by_brand ?></span></a>
                                                <!--end:Menu link-->
                                            </div>
                                            <!--end:Menu item-->
                                            <!--begin:Menu item-->
                                            <div class="menu-item">
                                                <!--begin:Menu link--><a class="menu-link" href="./userSubsidiaryResult?level=Expert&subsidiary=<?php echo $_SESSION["subsidiary"] ?>"><span
                                                        class="menu-icon">
                                                        <i class="fas fa-address-book fs-2"><span class="path1"></span><span
                                                                class="path2"></span></i>
                                                            </span><span class="menu-title"><?php echo $result_techs ?> <?php echo $Level ?> <?php echo $expert ?> <?php echo $by_brand ?></span></a>
                                                <!--end:Menu link-->
                                            </div>
                                            <!--end:Menu item-->
                                            <!--begin:Menu item-->
                                            <!-- <div class="menu-item"> -->
                                                <!--begin:Menu link-->
                                                <!-- <a class="menu-link" href="./userSubsidiaryResultTotal?subsidiary=<?php echo $_SESSION["subsidiary"] ?>"><span
                                                        class="menu-icon">
                                                        <i class="ki-duotone ki-gift fs-2">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                            <span class="path3"></span>
                                                            <span class="path4"></span>
                                                        </i></span><span class="menu-title"><?php echo $result_techs ?> <?php echo $global ?> <?php echo $by_brand ?></span></a> -->
                                                <!--end:Menu link-->
                                            <!-- </div> -->
                                            <!--end:Menu item-->
                                        <?php } ?>
                                        <?php if ($_SESSION["profile"] == "Directeur Groupe") { ?>
                                            <!--begin:Menu item-->
                                            <div class="menu-item">
                                                <!--begin:Menu link--><a class="menu-link" href="./subsidiaryResult?level=Junior"><span
                                                        class="menu-icon">
                                                        <i class="fas fa-address-book fs-2"><span class="path1"></span><span
                                                                class="path2"></span></i>
                                                            </span><span class="menu-title"><?php echo $result_mesure_competence ?> <?php echo $Level ?> <?php echo $junior ?> <?php echo $par_filiale ?> <?php echo $by_brand ?></span></a>
                                                <!--end:Menu link-->
                                            </div>
                                            <!--end:Menu item-->
                                            <!--begin:Menu item-->
                                            <div class="menu-item">
                                                <!--begin:Menu link--><a class="menu-link" href="./subsidiaryResult?level=Senior"><span
                                                        class="menu-icon">
                                                        <i class="fas fa-address-book fs-2"><span class="path1"></span><span
                                                                class="path2"></span></i>
                                                            </span><span class="menu-title"><?php echo $result_mesure_competence ?> <?php echo $Level ?> <?php echo $senior ?> <?php echo $par_filiale ?> <?php echo $by_brand ?></span></a>
                                                <!--end:Menu link-->
                                            </div>
                                            <!--end:Menu item-->
                                            <!--begin:Menu item-->
                                            <div class="menu-item">
                                                <!--begin:Menu link--><a class="menu-link" href="./subsidiaryResult?level=Expert"><span
                                                        class="menu-icon">
                                                        <i class="fas fa-address-book fs-2"><span class="path1"></span><span
                                                                class="path2"></span></i>
                                                            </span><span class="menu-title"><?php echo $result_mesure_competence ?> <?php echo $Level ?> <?php echo $expert ?> <?php echo $par_filiale ?> <?php echo $by_brand ?></span></a>
                                                <!--end:Menu link-->
                                            </div>
                                            <!--end:Menu item-->
                                            <!--begin:Menu item-->
                                            <!-- <div class="menu-item"> -->
                                                <!--begin:Menu link-->
                                                <!-- <a class="menu-link" href="./subsidiaryResultTotal"><span
                                                        class="menu-icon">
                                                        <i class="ki-duotone ki-gift fs-2">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                            <span class="path3"></span>
                                                            <span class="path4"></span>
                                                        </i></span><span class="menu-title"><?php echo $result_mesure_competence ?> <?php echo $global ?> <?php echo $par_filiale ?> <?php echo $by_brand ?></span></a> -->
                                                <!--end:Menu link-->
                                            <!-- </div> -->
                                            <!--end:Menu item-->
                                        <?php } ?>
                                    <?php } ?>
                                    <?php if ($_SESSION['profile'] == 'Super Admin') { ?>
                                        <!--begin:Menu item-->
                                        <div class="menu-item pt-5">
                                            <!--begin:Menu content-->
                                            <div class="menu-content"><span
                                                    class="fw-bold text-black text-uppercase fs-70"><?php echo $taux_de_couvertur ?></span>
                                            </div>
                                            <!--end:Menu content-->
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item">
                                            <!--begin:Menu link--><a class="menu-link" href="./userSpeciality?level=<?php echo $_GET['level'] ?? 'Junior' ?>&country=<?php echo $_GET['country'] ?? 'tous' ?>"><span
                                                    class="menu-icon">
                                                    <i class="fas fa-address-book fs-2"><span class="path1"></span><span
                                                            class="path2"></span></i></span><span class="menu-title"><?php echo $taux_cover_tache_pro ?></span></a>
                                            <!--end:Menu link-->
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item pt-5">
                                            <!--begin:Menu content-->
                                            <div class="menu-content"><span
                                                    class="fw-bold text-black text-uppercase fs-70"><?php echo $bilan_evaluation_filiale ?></span>
                                            </div>
                                            <!--end:Menu content-->
                                        </div>
                                        <!--end:Menu item-->
                                        <?php foreach ($countries as $country) { ?>
                                                <div class="menu-item">
                                                    <a class="menu-link" href="./results?country=<?php echo $country; ?>"><span
                                                            class="menu-icon">
                                                    <i class="fas fa-address-book fs-2"><span class="path1"></span><span
                                                            class="path2"></span></i>
                                                        </span><span class="menu-title"><?php echo $list_result_filiale.' '.$country ?></span></a>
                                                </div>
                                        <?php } ?>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item pt-5">
                                            <!--begin:Menu content-->
                                            <div class="menu-content"><span
                                                    class="fw-bold text-black text-uppercase fs-70"><?php echo $bilan_evaluation ?></span>
                                            </div>
                                            <!--end:Menu content-->
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item">
                                            <?php
                                            // Détection de la page active
                                            $currentUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                                            $isResultsPage = strpos($currentUrl, 'results.php') !== false;
                                            $isResultPage = strpos($currentUrl, 'result.php') !== false; // Vérifie si nous sommes sur la page des résultats
                                            $isDetailPage = strpos($currentUrl, 'detail.php') !== false; // Vérifie si nous sommes sur la page des résultats détaillés
                                            $isBrandResultPage = strpos($currentUrl, 'brandResult.php') !== false; // Vérifie si nous sommes sur la page des résultats par marques
                                            $isSystemPage = strpos($currentUrl, 'system.php') !== false;

                                            // Paramètres de l'URL
                                            $numberTest = $_GET['numberTest'] ?? '';
                                            $level = $_GET['level'] ?? 'Junior';
                                            $technicianId = $_GET['user'] ?? '';
                                            $speciality = $_GET['speciality'] ?? '';
                                            ?>

                                            <!-- Menu principal avec condition pour afficher la flèche -->
                                            <?php if (!$isSystemPage): ?>
                                                <a class="menu-link <?php echo ($isResultsPage || $isResultPage || $isDetailPage || $isBrandResultPage) ? 'active' : ''; ?>" href="./results.php" id="listResultLink">
                                                    <span class="menu-icon">
                                                        <i class="fas fa-address-book fs-2"><span class="path1"></span><span class="path2"></span></i>
                                                    </span>
                                                    <span class="menu-title"><?php echo $list_result; ?></span>
                                                    <?php if (!$isResultsPage): ?>
                                                        <span class="toggle-arrow" onclick="toggleSubmenu()">
                                                            <i class="fas fa-chevron-down"></i>
                                                        </span>
                                                    <?php endif; ?>
                                                </a>
                                            <?php endif; ?>
                                            <!-- Conteneur pour le menu dynamique -->
                                            <div id="dynamicMenuContainer" style="display: <?php echo $isSystemPage ? 'block' : 'none'; ?>;"></div>

                                            <!-- Sous-menu, caché par défaut -->
                                            <div class="submenu-container" id="submenuItems" style="<?php echo ($isResultPage || $isDetailPage || $isBrandResultPage) ? 'display: block;' : 'display: none;'; ?>">
                                                <a href="#"
                                                    class="submenu-item menu-link <?php echo $isDetailPage ? 'active' : ''; ?>" id="detailLink"
                                                    onclick="navigateToDetail()">
                                                    <span class="menu-icon">
                                                        <i class="fas fa-search-plus"></i>
                                                    </span>
                                                    <?php echo $result_detaillé; ?>
                                                </a>
                                                <a href="#"
                                                    class="submenu-item menu-link <?php echo $isBrandResultPage ? 'active' : ''; ?>" id="brandResultLink"
                                                    onclick="navigateToBrandResult()">
                                                    <span class="menu-icon">
                                                        <i class="fas fa-search-plus"></i>
                                                    </span>
                                                    <?php echo $result_brand; ?>
                                                </a>
                                            </div>
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item">
                                            <!--begin:Menu link--><a class="menu-link" href="./subsidiaryResult?level=Junior"><span
                                                    class="menu-icon">
                                                    <i class="fas fa-address-book fs-2"><span class="path1"></span><span
                                                            class="path2"></span></i>
                                                        </span><span class="menu-title"><?php echo $result_mesure_competence ?> <?php echo $Level ?> <?php echo $junior ?> <?php echo $par_filiale ?> <?php echo $by_brand ?></span></a>
                                            <!--end:Menu link-->
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item">
                                            <!--begin:Menu link--><a class="menu-link" href="./subsidiaryResult?level=Senior"><span
                                                    class="menu-icon">
                                                    <i class="fas fa-address-book fs-2"><span class="path1"></span><span
                                                            class="path2"></span></i>
                                                        </span><span class="menu-title"><?php echo $result_mesure_competence ?> <?php echo $Level ?> <?php echo $senior ?> <?php echo $par_filiale ?> <?php echo $by_brand ?></span></a>
                                            <!--end:Menu link-->
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item">
                                            <!--begin:Menu link--><a class="menu-link" href="./subsidiaryResult?level=Expert"><span
                                                    class="menu-icon">
                                                    <i class="fas fa-address-book fs-2"><span class="path1"></span><span
                                                            class="path2"></span></i>
                                                        </span><span class="menu-title"><?php echo $result_mesure_competence ?> <?php echo $Level ?> <?php echo $expert ?> <?php echo $par_filiale ?> <?php echo $by_brand ?></span></a>
                                            <!--end:Menu link-->
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <!-- <div class="menu-item"> -->
                                            <!--begin:Menu link-->
                                            <!-- <a class="menu-link" href="./subsidiaryResultTotal"><span
                                                    class="menu-icon">
                                                    <i class="ki-duotone ki-gift fs-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                        <span class="path3"></span>
                                                        <span class="path4"></span>
                                                    </i></span><span class="menu-title"><?php echo $result_mesure_competence ?> <?php echo $global ?> <?php echo $par_filiale ?> <?php echo $by_brand ?></span></a> -->
                                            <!--end:Menu link-->
                                        <!-- </div> -->
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item pt-5">
                                            <!--begin:Menu content-->
                                            <div class="menu-content"><span
                                                    class="fw-bold text-black text-uppercase fs-70"><?php echo $historiques ?></span>
                                            </div>
                                            <!--end:Menu content-->
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item">
                                            <!--begin:Menu link--><a class="menu-link" href="./historiqueUsers"><span
                                                    class="menu-icon">
                                                    <i
                                                        class="fas fa-trash-alt fs-2"><span class="path1"></span><span
                                                            class="path2"></span></i></span><span class="menu-title">
                                                    <?php echo $list_user_sup ?>
                                                </span></a>
                                            <!--end:Menu link-->
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item">
                                            <!--begin:Menu link--><a class="menu-link" href="./historiqueQuizzes"><span
                                                    class="menu-icon">
                                                    <i
                                                        class="fas fa-trash-alt fs-2"><span class="path1"></span><span
                                                            class="path2"></span></i></span><span class="menu-title">
                                                    <?php echo $list_quiz_sup ?>
                                                </span></a>
                                            <!--end:Menu link-->
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item">
                                            <!--begin:Menu link--><a class="menu-link" href="./historiqueQuestions"><span
                                                    class="menu-icon">
                                                    <i class="fas fa-trash-alt fs-2"><span class="path1"></span><span
                                                            class="path2"></span></i></span><span class="menu-title">
                                                    <?php echo $list_question_sup ?>
                                                </span></a>
                                            <!--end:Menu link-->
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item">
                                            <!--begin:Menu link--><a class="menu-link" href="./historiqueVehicles"><span
                                                    class="menu-icon">
                                                    <i
                                                        class="fas fa-trash-alt fs-2"><span class="path1"></span><span
                                                            class="path2"></span></i></span><span class="menu-title">
                                                    <?php echo $list_vehicle_sup ?>
                                                </span></a>
                                            <!--end:Menu link-->
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <div class="menu-item">
                                            <!--begin:Menu link-->
                                            <a class="menu-link" href="./historiqueResults"><span
                                                    class="menu-icon">
                                                    <i
                                                        class="fas fa-trash-alt fs-2"><span class="path1"></span><span
                                                            class="path2"></span></i></span><span class="menu-title">
                                                    <?php echo $histo_result ?>
                                                </span></a>
                                            <!--end:Menu link-->
                                        </div>
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <!-- <div class="menu-item pt-5"> -->
                                            <!--begin:Menu content-->
                                            <!-- <div class="menu-content"><span
                                                    class="fw-bold text-black text-uppercase fs-70"><?php echo $parameters ?></span>
                                            </div> -->
                                            <!--end:Menu content-->
                                        <!-- </div> -->
                                        <!--end:Menu item-->
                                        <!--begin:Menu item-->
                                        <!-- <div data-kt-menu-trigger="click" class="menu-item menu-accordion"> -->
                                        <!--begin:Menu link-->
                                        <!-- <span class="menu-link">
                                                <span class="menu-icon">
                                                    <i class="ki-duotone ki-setting-2 fs-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                </span>
                                                <span class="menu-title"><?php echo $parameters ?></span>
                                                <span class="menu-arrow"></span>
                                            </span> -->
                                            <!--end:Menu link-->
                                            <!--begin:Menu sub-->
                                        <!-- <div class="menu-sub menu-sub-accordion" kt-hidden-height="209" style="display: none; overflow: hidden;"> -->
                                            <!--begin:Menu item-->
                                            <!-- <div class="menu-item"> -->
                                                <!--begin:Menu link-->
                                                <!-- <a class="menu-link" href="./editBrandCountry">
                                                    <span class="menu-bullet">
                                                        <span class="bullet bullet-dot"></span>
                                                    </span>
                                                    <span class="menu-title"><?php echo $edit_brand_country ?></span>
                                                </a> -->
                                                <!--end:Menu link-->
                                            <!-- </div> -->
                                            <!--end:Menu item-->
                                            <!-- </div> -->
                                            <!--end:Menu sub-->
                                        <!-- </div> -->
                                        <!--end:Menu item-->
                                    <?php } ?>
                                    <!--begin:Menu item-->
                                    <div class="menu-item">
                                        <!--begin:Menu content-->
                                        <div class="menu-content">
                                            <div class="separator mx-1 my-4"></div>
                                        </div>
                                        <!--end:Menu content-->
                                    </div>
                                    <!--end:Menu item-->
                                    <!--begin:Menu item-->
                                    <!-- <div class="menu-item"> -->
                                        <!--begin:Menu link-->
                                        <!-- <a class="menu-link 2" href="#"><span class="menu-icon"><i
                                                    class="ki-duotone ki-code fs-2"><span class="path1"></span><span
                                                        class="path2"></span><span class="path3"></span><span
                                                        class="path4"></span></i></span><span class="menu-title">Changelog
                                                <span
                                                    class="badge badge-changelog badge-light-success bg-hover-danger text-hover-white fw-bold fs-9 px-2 ms-2">v0.0.1</span></span></a> -->
                                        <!--end:Menu link-->
                                    <!-- </div> -->
                                    <!--end:Menu item-->
                            </div>
                        </div>
                        <!--end::Menu-->
                    </div>
                    <!--end::Aside menu-->
                    <!--begin::Footer-->
                    <div class="aside-footer flex-column-auto pb-5 d-none" id="kt_aside_footer">
                        <a href="./dashboard" class="btn btn-light-primary w-100">
                            Button
                        </a>
                    </div>
                    <!--end::Footer-->
                </div>
                <!--end::Aside-->
            <?php } ?>
            <!--begin::Wrapper-->
            <div style="background:#e6e6e6;" class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
                <!--begin::Header-->
                <div style="background:#e6e6e6;" id="kt_header" class="header " data-kt-sticky="true" data-kt-sticky-name="header"
                    data-kt-sticky-offset="{default: '200px', lg: '300px'}">
                    <!--begin::Container-->
                    <div class=" container-fluid  d-flex align-items-stretch justify-content-between">
                        <!--begin::Logo bar-->
                        <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
                            <!--begin::Aside Toggle-->
                            <div class="d-flex align-items-center d-lg-none">
                                <div class="btn btn-icon btn-active-color-primary ms-n2 me-1 " id="kt_aside_toggle">
                                    <i class="ki-duotone ki-abstract-14 fs-1"><span class="path1"></span><span
                                            class="path2"></span></i>
                                </div>
                            </div>
                            <!--end::Aside Toggle-->
                            <!--begin::Logo-->
                            <a href="./dashboard" class="d-lg-none">
                                <img alt="Logo" src="../../public/images/logo.png" class="mh-40px" />
                            </a>
                            <!--end::Logo-->
                            <!--begin::Aside toggler-->
                            <div class="btn btn-icon w-auto ps-0 btn-active-color-primary d-none d-lg-inline-flex me-2 me-lg-5 "
                                data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
                                data-kt-toggle-name="aside-minimize">
                                <i class="ki-duotone ki-black-left-line fs-1 rotate-180"><span
                                        class="path1"></span><span class="path2"></span></i>
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
                                        <div class="d-flex ">
                                            <i class="ki-duotone ki-magnifier fs-1 "><span class="path1"></span><span
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
                                            class="search-spinner  position-absolute top-50 end-0 translate-middle-y lh-0 d-none me-5"
                                            data-kt-search-element="spinner">
                                            <span
                                                class="spinner-border h-15px w-15px align-middle text-gray-400"></span>
                                        </span>
                                        <!--end::Spinner-->
                                        <!--begin::Reset-->
                                        <span
                                            class="search-reset  btn btn-flush btn-active-color-primary position-absolute top-50 end-0 translate-middle-y lh-0 d-none me-4"
                                            data-kt-search-element="clear">
                                            <i class="ki-duotone ki-cross fs-2 fs-lg-1 me-0"><span
                                                    class="path1"></span><span class="path2"></span></i>
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
                                            <?php echo $_SESSION[
                                                "firstName"
                                            ]; ?>
                                            <?php echo $_SESSION["lastName"]; ?>
                                        </b>
                                    </div>
                                    <!--begin::User account menu-->
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px"
                                        data-kt-menu="true">
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="./profile?id=<?php echo $_SESSION[
                                                "id"
                                            ]; ?>">
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
                                                            <div
                                                                class="fw-bolder d-flex align-items-center text-black fs-5">
                                                                <?php echo $salut ?>, <?php echo $_SESSION[
                                                                    "firstName"
                                                                ]; ?>
                                                                <span
                                                                    class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">
                                                                    <?php echo $_SESSION[
                                                                        "profile"
                                                                    ]; ?>
                                                                </span>
                                                            </div>
                                                            <div class="fw-bold text-black text-hover-primary fs-7">
                                                                <?php echo $_SESSION[
                                                                    "email"
                                                                ]; ?>
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
                                            <a href="./profile?id=<?php echo $_SESSION[
                                                "id"
                                            ]; ?>"
                                                class="menu-link px-5">
                                                <?php echo $my_info ?>
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
                                                    <?php echo $langue ?>
                                                    <span
                                                        class="fs-8 rounded bg-light px-3 py-2 position-absolute translate-middle-y top-50 end-0">
                                                        <?php echo $francais ?> <img class="w-15px h-15px rounded-1 ms-2"
                                                            src="../../public/assets/media/flags/france.svg" alt="" />
                                                    </span>
                                                </span>
                                            </a>
                                            <!--begin::Menu sub-->
                                            <div class="menu-sub menu-sub-dropdown w-175px py-4">
                                                <!--begin::Menu item-->
                                                <!-- <div class="menu-item px-3">
                                                    <a href="account/settings.html"
                                                        class="menu-link d-flex px-5 active">
                                                        <span
                                                            class="symbol symbol-20px me-4">
                                                            <img class="rounded-1"
                                                                src="../../public/assets/media/flags/united-states.svg"
                                                                alt="" />
                                                        </span>
                                                        English
                                                    </a>
                                                </div> -->
                                                <!--end::Menu item-->
                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3">
                                                    <a href="#" class="menu-link d-flex px-5">
                                                        <span class="symbol symbol-20px me-4">
                                                            <img class="rounded-1"
                                                                src="../../public/assets/media/flags/france.svg" alt="" />
                                                        </span>
                                                        <?php echo $francais ?>
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
                                                <?php echo 'Retour Portail MEDACAP' ?>
                                            </a>
                                        </div>
                                        <!--end::Menu item-->
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-5">
                                            <a href="../logout" id="logout-button" class="menu-link px-5">
                                                <?php echo $deconnexion ?>
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
                    document.getElementById('logout-button').addEventListener('click',
                    function(event) {

                        event.preventDefault();
                    // Prevent the default action (navigation)

                        // Clear the cookies

                        clearCookies();

                        // Redirect to the logout page

                        window.location.href = './logout';

                    });
                </script>
                <script>
                    const urlParams = new URLSearchParams(window.location.search);
                    const numberTests = urlParams.get('numberTest') || '';
                    const technicianIds = urlParams.get('user') || urlParams.get('id') || '';
                    const levels = urlParams.get('level') || 'Junior';
                    const specialitys = urlParams.get('speciality') || '';

                    const specialities = [
                        "Arbre de Transmission",
                        "Assistance à la Conduite",
                        "Boite de Transfert",
                        "Boite de Vitesse",
                        "Boite de Vitesse Automatique",
                        "Boite de Vitesse Mécanique",
                        "Climatisation",
                        "Demi Arbre de Roue",
                        "Direction",
                        "Électricité & Électronique",
                        "Freinage",
                        "Freinage Électromagnétique",
                        "Hydraulique",
                        "Moteur Essence",
                        "Moteur Diesel",
                        "Moteur Électrique",
                        "Moteur Thermique",
                        "Réseaux de Communication",
                        "Pneumatique",
                        "Pont",
                        "Réducteur",
                        "Suspension",
                        "Suspension à Lame",
                        "Suspension à Ressort",
                        "Suspension Pneumatique",
                    ];

                    function generateDynamicMenu() {
                        const dynamicMenuContainer = document.getElementById('dynamicMenuContainer');
                        dynamicMenuContainer.innerHTML = ''; // Réinitialiser le contenu

                        // Normaliser la spécialité pour la comparaison
                        const normalizedSpeciality = decodeURIComponent(specialitys).toLowerCase().trim();

                        // Vérifier si la spécialité normalisée existe dans le tableau
                        const foundSpeciality = specialities.find(speciality => speciality.toLowerCase() === normalizedSpeciality);

                        // Récupérer l'URL actuelle
                        const currentUrl = window.location.href;

                        if (foundSpeciality) {
                            // Créer le lien pour le menu
                            const link = `./system.php?numberTest=${numberTests}&speciality=${encodeURIComponent(foundSpeciality)}&level=${levels}&user=${technicianIds}`;

                            // Créer l'élément de menu
                            const menuItem = document.createElement('a');
                            menuItem.href = link;
                            menuItem.className = 'menu-link';
                            menuItem.id = `menuLink-${encodeURIComponent(foundSpeciality)}`;

                            // Ajouter une classe active si l'URL actuelle correspond au lien
                            if (currentUrl.includes(`speciality=${encodeURIComponent(foundSpeciality)}`)) {
                                menuItem.classList.add('active');
                            }

                            // Ajouter l'icône et le titre
                            menuItem.innerHTML = `
                                                    <span class="menu-icon">
                                                        <i class="fas fa-address-book fs-2"><span class="path1"></span><span class="path2"></span></i>
                                                    </span>
                                                    <span class="menu-title">Résultats du Technicien par Groupe Fonctionnel :  ${foundSpeciality}</span>
                                                `;

                            // Ajouter l'élément de menu au conteneur
                            dynamicMenuContainer.appendChild(menuItem);
                        } else {
                            // Si la spécialité n'est pas reconnue, afficher un message par défaut
                            const messageElement = document.createElement('p');
                            messageElement.textContent = "Aucune spécialité sélectionnée.";
                            dynamicMenuContainer.appendChild(messageElement);
                        }

                        // Afficher le conteneur du menu dynamique
                        dynamicMenuContainer.style.display = 'block';
                    }

                    // Appeler la fonction pour générer le menu dynamique uniquement si nous sommes sur system.php
                    if (window.location.pathname.includes('system.php')) {
                        generateDynamicMenu();
                    }

                    function toggleSubmenu() {
                        const submenuItems = document.getElementById('submenuItems');
                        submenuItems.style.display = submenuItems.style.display === 'none' ? 'block' : 'none';
                    }

                    function navigateToDetail() {
                        const url = `./detail.php?numberTest=${numberTests}&id=${technicianIds}&level=${levels}`;
                        window.location.href = url;
                    }

                    function navigateToBrandResult() {
                        const url = `./brandResult.php?numberTest=${numberTests}&id=${technicianIds}&level=${levels}`;
                        window.location.href = url;
                    }

                    // Fonction pour analyser les paramètres de requête à partir d'une URL
                    function getQueryParams(url) {
                        const params = {};
                        const parser = new URL(url, window.location.origin);
                        for (const [key, value] of parser.searchParams.entries()) {
                            params[key] = value;
                        }
                        return params;
                    }

                    window.onload = function() {
                        const currentUrl = window.location.href;
                        const currentPathname = window.location.pathname;
                        const currentParams = getQueryParams(currentUrl);
                        const currentCountry = currentParams.country || '';
                        const currentLevel = currentParams.level || '';
                        const currentAgency = currentParams.agency || '';

                        // Extraire le nom de la page actuelle à partir de l'URL
                        const currentPage = currentPathname.substring(currentPathname.lastIndexOf('/') + 1).replace('.php', '');

                        const menuLinks = document.querySelectorAll('.menu-link');

                        menuLinks.forEach(link => {
                            const linkPage = link.getAttribute('data-page');

                            if (linkPage) {
                                // Comparer le data-page du lien avec la page actuelle
                                if (linkPage === currentPage) {
                                    // Ajouter la classe 'active' au lien
                                    link.classList.add('active');

                                     // Si le lien est dans un sous-menu, afficher le sous-menu
                                    const submenuContainer = link.closest('.submenu-container');
                                    if (submenuContainer) {
                                        submenuContainer.style.display = 'block';
                                    }

                                    // Ajouter la classe 'active' aux éléments de menu parent
                                    let parentMenuItem = link.closest('.menu-item');
                                    while (parentMenuItem) {
                                        parentMenuItem.classList.add('active');
                                        parentMenuItem = parentMenuItem.parentElement.closest('.menu-item');
                                    }
                                }
                            } else {
                                // Logique pour les liens avec des attributs href réels
                                const linkHref = link.getAttribute('href');
                                if (!linkHref || linkHref === '#' || linkHref === 'javascript:void(0)') {
                                    return;
                                }

                                const tempLink = document.createElement('a');
                                tempLink.href = linkHref;
                                const linkPathname = tempLink.pathname;

                                // Obtenir les paramètres de requête du lien
                                const linkParams = getQueryParams(tempLink.href);
                                const linkCountry = linkParams.country || '';
                                const linkLevel= linkParams.level|| '';
                                const linkAgency= linkParams.agency|| '';

                                // Comparer à la fois le pathname et le paramètre country
                                if (linkPathname === currentPathname && linkCountry === currentCountry && linkLevel === currentLevel && linkAgency === currentAgency) {
                                    // Ajouter la classe 'active' au lien
                                    link.classList.add('active');

                                    // Si le lien est dans un sous-menu, afficher le sous-menu
                                    const submenuContainer = link.closest('.submenu-container');
                                    if (submenuContainer) {
                                        submenuContainer.style.display = 'block';
                                    }

                                    // Ajouter la classe 'active' aux éléments de menu parent
                                    let parentMenuItem = link.closest('.menu-item');
                                    while (parentMenuItem) {
                                        parentMenuItem.classList.add('active');
                                        parentMenuItem = parentMenuItem.parentElement.closest('.menu-item');
                                    }
                                }
                            }
                        });

                        // Sélectionner le lien de menu actif après avoir traité tous les liens
                        const activeMenuLink = document.querySelector('.menu-link.active');

                        if (activeMenuLink) {
                            // S'assurer que tous les sous-menus parents sont visibles
                            let parent = activeMenuLink.parentElement;
                            while (parent) {
                                if (parent.classList.contains('submenu-container')) {
                                    parent.style.display = 'block';
                                }
                                parent = parent.parentElement;
                            }

                            // Faire défiler jusqu'au lien de menu actif
                            setTimeout(() => {
                                activeMenuLink.scrollIntoView({
                                    behavior: 'smooth',
                                    block: 'center'
                                });
                            }, 100);
                        }
                    }
                </script>