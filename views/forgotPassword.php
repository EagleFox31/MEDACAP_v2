<?php
require_once '../vendor/autoload.php';
    
// Create connection
$conn = new MongoDB\Client('mongodb://localhost:27017');
    
 // Connecting in database
 $academy = $conn->academy;
    
// Connecting in collections
$users = $academy->users;

if (isset( $_POST[ 'submit' ] )) {
    $username = $_POST[ 'username' ];
    $password = sha1($_POST[ 'password' ]);
    
    $member = $users->findOne([
        '$and' => [
            'username' => $username,
            'active' => true
        ]
    ]);
    if (empty($username) || empty($password)) {
        $error = "Champ Obligatoire";
    } elseif (empty($member)) {
        $error_msg = "Nom d'utilisateur incorrect. Essayez encore!!!";
    } elseif (preg_match( '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{6,}$/', $password ) ) {
        $error_msg = "Le mot de passe doit contenir au moins des lettres minuscule et majuscule, au moins  un chiffre et doit être au moins de 6 caractères.";
    } else {
        $users->updateOne(
            [ '_id' => new MongoDB\BSON\ObjectId( $member->_id ) ],
            [ '$set' => [ 'password' => $password ] ]
        );
        $success_msg = 'Mot de passe modifié avec succes.';
    }
}
?>
<!DOCTYPE html>
<!--
Author: Keenthemes
Product Name: Craft 
Product Version: 1.1.3
Purchase: https://themes.getbootstrap.com/product/craft-bootstrap-5-admin-dashboard-theme
Website: http://www.keenthemes.com
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
License: For each use you must have a valid license purchased only from above link in order to legally use the theme for your project.
-->
<html lang="en">
<!--begin::Head-->

<head>
    <title>Mot de passe oublié ? | CFAO Mobility Academy</title>
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
    <link href="../public/images/logo-cfao.png" rel="icon">

    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <!--end::Fonts-->



    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href="../public/assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="../public/assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->

    <!--Begin::Google Tag Manager -->
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
            'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
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
</head>
<!--end::Head-->

<!--begin::Body-->

<body id="kt_body" class="auth-bg">
    <!-- Spinner Start -->
    <div id="spinner"
        class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
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
            themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
        } else {
            if (localStorage.getItem("data-bs-theme") !== null) {
                themeMode = localStorage.getItem("data-bs-theme");
            } else {
                themeMode = defaultThemeMode;
            }
        }

        if (themeMode === "system") {
            themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
        }

        document.documentElement.setAttribute("data-bs-theme", themeMode);
    }
    </script>
    <!--end::Theme mode setup on page load-->
    <!--Begin::Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5FS8GGP" height="0" width="0"
            style="display:none;visibility:hidden"></iframe></noscript>
    <!--End::Google Tag Manager (noscript) -->

    <!--begin::Main-->
    <div class="d-flex flex-column flex-root">
        <!--begin::Authentication - Signup Free Trial-->
        <div class="d-flex flex-column flex-xl-row flex-column-fluid">
            <!--begin::Aside-->
            <div class="d-flex flex-column flex-lg-row-fluid">
                <!--begin::Wrapper-->
                <div class="d-flex flex-row-fluid flex-center p-10">
                    <!--begin::Content-->
                    <div class="d-flex flex-column">
                        <!--begin::Logo-->
                        <a href="./index.php" class="mb-15">
                            <img alt="Logo" src="../public/images/logo.png" class="h-150px" />
                        </a>
                        <!--end::Logo-->

                        <!--begin::Title-->
                        <h1 class="text-dark fs-3x mb-5">Mot de passe oublié ?</h1>
                        <!--end::Title-->

                        <!--begin::Description-->
                        <div class="fw-semibold fs-3 text-gray-400 mb-10">
                            Veuillez entrer les informations demandées <br>
                            pour changer de mot de passe.
                        </div>
                        <!--begin::Description-->
                    </div>
                    <!--end::Content-->
                </div>
                <!--end::Wrapper-->

                <!--begin::Illustration-->
                <div class="d-flex flex-row-auto bgi-no-repeat bgi-position-x-center bgi-size-contain bgi-position-y-bottom min-h-200px min-h-xl-450px"
                    style="background-image: url(../public/images/20230310_173722.jpg)">
                </div>
                <!--end::Illustration-->
            </div>
            <!--begin::Aside-->
            <?php
                if ( isset( $success_msg ) ) {
            ?>
            <div class='alert alert-success alert-dismissible fade show' role='alert'>
                <center><strong><?php echo $success_msg ?></strong></center>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;
                    </span>
                </button>
            </div>
            <?php
                }
            ?>
            <?php
                if ( isset( $error_msg ) ) {
                    ?>
            <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                <center><strong><?php echo $error_msg ?></strong></center>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;
                    </span>
                </button>
            </div>
            <?php
                }
            ?>

            <!--begin::Content-->
            <div class="flex-row-fluid d-flex flex-center justfiy-content-xl-first p-10">
                <!--begin::Wrapper-->
                <div class="d-flex flex-center p-15 bg-body shadow-sm rounded w-100 w-md-550px mx-auto ms-xl-20">
                    <!--begin::Form-->
                    <form class="form">
                        <!--begin::Heading-->
                        <div class="text-center mb-10">
                            <!--begin::Title-->
                            <h1 class="text-dark mb-3">
                                Mot de passe oublié ?
                            </h1>
                            <!--end::Title-->

                            <!--begin::Link-->
                            <div class="text-gray-400 fw-semibold fs-4">
                                Veuillez entrer votre nom d'utilisateur et un nouveau mot de passe
                            </div>
                            <!--end::Link-->
                        </div>
                        <!--begin::Heading-->

                        <!--begin::Input group-->
                        <div class="fv-row mb-10">
                            <label class="form-label fw-bold text-dark fs-6">Nom d'utilisateur</label>
                            <input class="form-control form-control-solid" type="text" placeholder="" name="username"
                                autocomplete="off" />
                        </div>
                        <!--end::Input group-->
                        <?php
                            if ( isset( $error ) ) {
                        ?>
                        <span class='text-danger'>
                            <?php echo $error ?>
                        </span>
                        <?php
                            }
                        ?>

                        <!--begin::Input group-->
                        <div class="mb-7 fv-row" data-kt-password-meter="true">
                            <!--begin::Wrapper-->
                            <div class="mb-1">
                                <!--begin::Label-->
                                <label class="form-label fw-bold text-dark fs-6">
                                    Mot de passe
                                </label>
                                <!--end::Label-->

                                <!--begin::Input wrapper-->
                                <div class="position-relative mb-3">
                                    <input class="form-control form-control-solid" type="password" placeholder=""
                                        name="password" autocomplete="off" />

                                    <span
                                        class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                                        data-kt-password-meter-control="visibility">
                                        <i class="ki-duotone ki-eye-slash fs-1"><span class="path1"></span><span
                                                class="path2"></span><span class="path3"></span><span
                                                class="path4"></span></i> <i class="ki-duotone ki-eye d-none fs-1"><span
                                                class="path1"></span><span class="path2"></span><span
                                                class="path3"></span></i> </span>
                                </div>
                                <!--end::Input wrapper-->
                                <?php
                                    if ( isset( $error ) ) {
                                ?>
                                <span class='text-danger'>
                                    <?php echo $error ?>
                                </span>
                                <?php
                                    }
                                ?>

                                <!--begin::Meter-->
                                <div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
                                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2">
                                    </div>
                                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2">
                                    </div>
                                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2">
                                    </div>
                                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
                                </div>
                                <!--end::Meter-->
                            </div>
                            <!--end::Wrapper-->

                            <!--begin::Hint-->
                            <div class="text-muted">
                                Utilisez 6
                                caractères ou plus avec un mélange de
                                lettres en majuscule et minuscule
                                &amp; de chiffres.
                            </div>
                            <!--end::Hint-->
                        </div>
                        <!--end::Input group--->

                        <!--begin::Row-->
                        <div class="text-center pb-lg-0 pb-8">
                            <button type="button" id="kt_free_trial_submit" name='submit'
                                class="btn btn-lg btn-primary fw-bold">
                                <span class="indicator-label">
                                    Valider
                                </span>

                                <span class="indicator-progress">
                                    Please wait... <span
                                        class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                        </div>
                        <!--end::Row-->
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Wrapper-->
            </div>
            <!--end::Right Content-->
        </div>
        <!--end::Authentication - Signup Free Trial-->
    </div>
    <!--end::Main-->


    <!--begin::Javascript-->
    <script>
    var hostUrl = "../public/assets/";
    </script>

    <!--begin::Global Javascript Bundle(mandatory for all pages)-->
    <script src="../public/assets/plugins/global/plugins.bundle.js"></script>
    <script src="../public/assets/js/scripts.bundle.js"></script>
    <!--end::Global Javascript Bundle-->


    <!--begin::Custom Javascript(used for this page only)-->
    <script src="../public/assets/js/custom/authentication/sign-up/free-trial.js"></script>
    <script>
    // Spinner
    var spinner = function() {
        setTimeout(function() {
            if ($('#spinner').length > 0) {
                $('#spinner').removeClass('show');
            }
        }, 1);
    };
    spinner();
    </script>
    <!--end::Custom Javascript-->
    <!--end::Javascript-->

</body>
<!--end::Body-->

</html>