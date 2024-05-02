<?php
session_start();

if (!isset($_SESSION["id"])) {
    header("Location: ../");
    exit();
} else {
    require_once '../vendor/autoload.php';
    
    // Create connection
    $conn = new MongoDB\Client('mongodb://localhost:27017');
    
    // Connecting in database
    $academy = $conn->academy;
    
    // Connecting in collections
    $users = $academy->users;
    $allocations = $academy->allocations;

    $id = $_SESSION["id"];
    
    $user = $users->findOne([
        '$and' => [
                [ '_id' => new MongoDB\BSON\ObjectId( $id ) ],
                [ 'active' => true ]
        ]
    ]);
    
?>
<?php

if ( isset( $_POST[ 'update' ] ) ) {
    $id = $_POST[ 'userID' ];
    $firstName = $_POST[ 'firstName' ];
    $lastName = $_POST[ 'lastName' ];
    $email = $_POST[ 'email' ];
    $phone = $_POST[ 'phone' ];
    $matricule = $_POST[ 'matricule' ];
    $username = $_POST[ 'username' ];
    $subsidiary = $_POST[ 'subsidiary' ];
    $department = $_POST[ 'department' ];
    $role = $_POST[ 'role' ];
    $gender = $_POST[ 'gender' ];
    $country = $_POST[ 'country' ];
    $level = $_POST[ 'level' ];
    $certificate = $_POST[ 'certificate' ];
    $speciality = $_POST[ 'speciality' ];
    $birthdate = date( 'd-m-Y', strtotime( $_POST[ 'birthdate' ] ) );
    $recrutmentDate = date( 'd-m-Y', strtotime( $_POST[ 'recrutmentDate' ] ) );

    $person = [
        'username' => $username,
        'matricule' => $matricule,
        'firstName' => ucfirst( $firstName ),
        'lastName' => ucfirst( $lastName ),
        'email' => $email,
        'phone' => $phone,
        'gender' => $gender,
        'level' => $level,
        'country' => $country,
        'birthdate' => $birthdate,
        'recrutmentDate' => $recrutmentDate,
        'certificate' => ucfirst( $certificate ),
        'subsidiary' => ucfirst( $subsidiary ),
        'speciality' => ucfirst( $speciality ),
        'department' => ucfirst( $department ),
        'role' => ucfirst( $role ),
        'updated' => date("d-m-Y")
    ];
        
    $users->updateOne(
        [ '_id' => new MongoDB\BSON\ObjectId( $id ) ],
        [ '$set' => $person ]
    );

    $success_msg = 'Collaborateur modifié avec succes.';
}

if ( isset( $_POST[ 'brand' ] ) ) {
    $id = $_POST[ 'userID' ];
    $brand = $_POST[ 'brand' ];

    $users->updateOne(
        [ '_id' => new MongoDB\BSON\ObjectId( $id ) ],
        [ '$set' => [ 'brand' => $brand, ] ]
    );
    $success_msg = 'Collaborateur modifié avec succes.';
}

if ( isset( $_POST[ 'password' ] ) ) {
    // Password modification
    $id = $_POST[ 'userID' ];
    $password = $_POST[ 'password' ];

    // Check if the password contains at least 8 characters, including at least one uppercase letter, one lowercase letter, and one special character.
    if ( preg_match( '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{6,}$/', $password ) ) {
        $error = ( 'Le mot de passe doit être au moins de six caractères contenir au moins un chiffre, une lettre majiscule' );
    } else {
        $password_hash = sha1( $password );
    
        $users->updateOne(
            [ '_id' => new MongoDB\BSON\ObjectId( $id ) ],
            [ '$set' => [ 'password' => $password_hash, ] ]
        );
        $success_msg = 'Collaborateur modifié avec succes.';
    }
}

if ( isset( $_POST[ 'delete' ] ) ) {
    $id = $_POST[ 'userID' ];
    $member = $users->findOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
    $member['active'] = false;
    $users->updateOne(['_id' => new MongoDB\BSON\ObjectId($id)], ['$set' => $member]);

    if ($member['profile'] == "Technicien") {
        $success_msg = "Technicien supprimé avec succès";
    } elseif ($member['profile'] == "Manager") {
        $success_msg = "Manager supprimé avec succès";
    } elseif ($member['profile'] == "Admin") {
        $success_msg = "Administrateur supprimé avec succès";
    }
}

?>
<?php
include_once 'partials/header.php'
?>
<!--begin::Title-->
<title>Mes Informations | CFAO Mobility Academy</title>
<!--end::Title-->


<!--begin::Content-->
<div class="content fs-6 d-flex flex-column flex-column-fluid" id="kt_content">
    <?php
         if(isset($success_msg)) {
        ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <center><strong><?php echo $success_msg ?></strong></center>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php
        }
        ?>
    <?php
         if(isset($error_msg)) {
        ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <center><strong><?php echo $error_msg ?></strong></center>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php
        }
        ?>
    <!--begin::Toolbar-->
    <div class="toolbar" id="kt_toolbar">
        <div class="container-fluid d-flex flex-stack flex-wrap flex-sm-nowrap">
            <!--begin::Info-->
            <div class="d-flex flex-column align-items-start justify-content-center flex-wrap me-2">
                <!--begin::Title-->
                <h1 class="text-dark fw-bolder my-1 fs-2">
                    Mes informations
                </h1>
                <!--end::Title-->
            </div>
            <!--end::Info-->
        </div>
    </div>
    <!--end::Toolbar-->
    <!--begin::Post-->
    <div class="post fs-6 d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div class="container-xxl">
            <!--begin::Layout-->
            <div class="d-flex flex-column flex-xl-row">
                <!--begin::Sidebar-->
                <div class="flex-column flex-lg-row-auto w-50 w-xl-350px mb-10">
                    <!--begin::Card-->
                    <div class="card mb-5 mb-xl-8">
                        <!--begin::Card body-->
                        <div class="card-body">
                            <?php if($_SESSION["profile"]=="Admin" || $_SESSION["profile"]=="Manager" ||
                                $_SESSION["profile"]=="Technicien" ) { ?>
                            <div class="mt-5" style="margin-right: -10px">
                                <span data-bs-toggle="tooltip" data-bs-trigger="hover" style="margin-right: 40px;">
                                    <a href="#" class="btn btn-sm text-white fs-6 float-end"
                                        style="background: #225e41;" data-bs-toggle="modal"
                                        data-bs-target="#kt_modal_update_details">Modifier</a>
                                </span>
                            </div>
                            <?php } ?>
                            <!--begin::Summary-->
                            <!--begin::User Info-->
                            <div class="d-flex flex-center flex-column py-5">
                                <!--begin::Avatar-->
                                <div class="symbol symbol-100px symbol-circle mb-7">
                                    <img src="../public/assets/media/avatars/300-1.jpg" alt="image" />
                                </div>
                                <!--end::Avatar-->
                                <!--begin::Name-->
                                <a href="#" class="fs-1 text-gray-800 text-hover-success fw-bolder mb-3">
                                    <?php echo $user->firstName ?> <?php echo $user->lastName ?>
                                </a>
                                <!--end::Name-->
                                <!--begin::Position-->
                                <div class="mb-9">
                                    <!--begin::Badge-->
                                    <div class="fs-4 badge badge-lg badge-light-success d-inline">
                                        <?php echo $user->profile ?>
                                    </div>
                                    <!--begin::Badge-->
                                </div>
                                <!--end::Position-->
                            </div>
                            <!--end::User Info-->
                            <!--end::Summary-->
                            <div class="separator"></div>
                            <!--end::Summary-->
                            <!--begin::Details content-->
                            <div id="kt_user_view_details" class="collapse show">
                                <div class="pb-5 fs-6">
                                    <!--begin::Details item-->
                                    <div class="fw-bolder mt-5">
                                        Nom d'utilisateur
                                    </div>
                                    <div class="text-gray-600">
                                        <?php echo $user->username ?>
                                    </div>
                                    <!--begin::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bolder mt-5">
                                        Matricule
                                    </div>
                                    <div class="text-gray-600">
                                        <?php echo $user->matricule ?>
                                    </div>
                                    <!--begin::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bolder mt-5">
                                        Email
                                    </div>
                                    <div class="text-gray-600">
                                        <?php echo $user->email ?>
                                    </div>
                                    <!--begin::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bolder mt-5">
                                        Numéro de téléphone
                                    </div>
                                    <div class="text-gray-600">
                                        <?php echo $user->phone ?>
                                    </div>
                                    <!--begin::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bolder mt-5">
                                        Sexe
                                    </div>
                                    <div class="text-gray-600">
                                        <?php echo $user->gender ?>
                                    </div>
                                    <!--begin::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bolder mt-5">
                                        Pays
                                    </div>
                                    <div class="text-gray-600">
                                        <?php echo $user->country ?>
                                    </div>
                                    <!--begin::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bolder mt-5">
                                        Date de naissance
                                    </div>
                                    <div class="text-gray-600">
                                        <?php echo $user->birthdate ?>
                                    </div>
                                    <!--begin::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bolder mt-5">
                                        Certificat le plus élevé
                                    </div>
                                    <div class="text-gray-600">
                                        <?php echo $user->certificate ?>
                                    </div>
                                    <!--begin::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bolder mt-5">
                                        Filiale
                                    </div>
                                    <div class="text-gray-600">
                                        <?php echo $user->subsidiary ?>
                                    </div>
                                    <!--begin::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bolder mt-5">
                                        Departement
                                    </div>
                                    <div class="text-gray-600">
                                        <?php echo $user->department ?>
                                    </div>
                                    <!--begin::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bolder mt-5">
                                        Fonction Principale
                                    </div>
                                    <div class="text-gray-600">
                                        <?php echo $user->role ?>
                                    </div>
                                    <!--begin::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bolder mt-5">
                                        Marques de Véhicule
                                    </div>
                                    <?php foreach ($user->brand as $brand) { ?>
                                    <div class="text-gray-600">
                                        <?php echo $brand?>
                                    </div>
                                    <?php } ?>
                                    <!--begin::Details item-->
                                    <!--begin::Details item-->
                                    <?php if (isset($user->speciality)) { ?>
                                    <div class="fw-bolder mt-5">
                                        Spécialité
                                    </div>
                                    <div class="text-gray-600">
                                        <?php echo $user->speciality ?>
                                    </div>
                                    <?php } ?>
                                    <!--begin::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bolder mt-5">
                                        Date de recrutement
                                    </div>
                                    <div class="text-gray-600">
                                        <?php echo $user->recrutmentDate ?>
                                    </div>
                                    <!--begin::Details item-->
                                </div>
                            </div>
                            <!--end::Details content-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Card-->
                </div>
                <!--end::Sidebar-->
            </div>
            <!--end::Layout-->
            <!-- begin:: Modal - Confirm suspend -->
            <div class="modal" id="kt_modal_desactivate" tabindex="-1" aria-hidden="true">
                <!--begin::Modal dialog-->
                <div class="modal-dialog modal-dialog-centered mw-450px">
                    <!--begin::Modal content-->
                    <div class="modal-content">
                        <!--begin::Form-->
                        <form class="form" method="POST" id="kt_modal_update_user_form">
                            <input type="hidden" name="userID" value="<?php echo $user->_id ?>">
                            <!--begin::Modal header-->
                            <div class="modal-header" id="kt_modal_update_user_header">
                                <!--begin::Modal title-->
                                <h2 class="fs-2 fw-bolder">
                                    Supréssion
                                </h2>
                                <!--end::Modal title-->
                                <!--begin::Close-->
                                <div class="btn btn-icon btn-sm btn-active-icon-primary"
                                    data-kt-users-modal-action="close" data-bs-dismiss="modal">
                                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                                    <span class="svg-icon svg-icon-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                                transform="rotate(-45 6 17.3137)" fill="black" />
                                            <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                                transform="rotate(45 7.41422 6)" fill="black" />
                                        </svg>
                                    </span>
                                    <!--end::Svg Icon-->
                                </div>
                                <!--end::Close-->
                            </div>
                            <!--end::Modal header-->
                            <!--begin::Modal body-->
                            <div class="modal-body py-10 px-lg-17">
                                <h4>
                                    Voulez-vous vraiment supprimé cette
                                    personne?
                                </h4>
                            </div>
                            <!--end::Modal body-->
                            <!--begin::Modal footer-->
                            <div class="modal-footer flex-center">
                                <!--begin::Button-->
                                <button type="reset" class="btn btn-light me-3" id="closeDesactivate"
                                    data-bs-dismiss="modal" data-kt-users-modal-action="cancel">
                                    Non
                                </button>
                                <!--end::Button-->
                                <!--begin::Button-->
                                <button type="submit" class="btn btn-danger">
                                    Oui
                                </button>
                                <!--end::Button-->
                            </div>
                            <!--end::Modal footer-->
                        </form>
                        <!--end::Form-->
                    </div>
                </div>
                <!-- end Modal dialog -->

            </div>
            <!-- end:: Modal - Confirm suspend -->
            <!-- begin:: Modal - Confirm suspend -->
            <div class="modal" id="kt_modal_activate" tabindex="-1" aria-hidden="true">
                <!--begin::Modal dialog-->
                <div class="modal-dialog modal-dialog-centered mw-450px">
                    <!--begin::Modal content-->
                    <div class="modal-content">
                        <!--begin::Form-->
                        <form class="form" action="/active/<%= user.id %>/?_method=PUT" method="POST"
                            id="kt_modal_update_user_form">
                            <input type="hidden" name="_method" value="PUT">
                            <!--begin::Modal header-->
                            <div class="modal-header" id="kt_modal_update_user_header">
                                <!--begin::Modal title-->
                                <h2 class="fs-2 fw-bolder">
                                    Activation
                                </h2>
                                <!--end::Modal title-->
                                <!--begin::Close-->
                                <div class="btn btn-icon btn-sm btn-active-icon-primary"
                                    data-kt-users-modal-action="close" data-bs-dismiss="modal">
                                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                                    <span class="svg-icon svg-icon-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                                transform="rotate(-45 6 17.3137)" fill="black" />
                                            <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                                transform="rotate(45 7.41422 6)" fill="black" />
                                        </svg>
                                    </span>
                                    <!--end::Svg Icon-->
                                </div>
                                <!--end::Close-->
                            </div>
                            <!--end::Modal header-->
                            <!--begin::Modal body-->
                            <div class="modal-body py-10 px-lg-17">
                                <h4>
                                    Voulez-vous vraiment activer cette personne?
                                </h4>
                            </div>
                            <!--end::Modal body-->
                            <!--begin::Modal footer-->
                            <div class="modal-footer flex-center">
                                <!--begin::Button-->
                                <button type="reset" class="btn btn-light me-3" id="closeDesactivate"
                                    data-bs-dismiss="modal" data-kt-users-modal-action="cancel">
                                    Non
                                </button>
                                <!--end::Button-->
                                <!--begin::Button-->
                                <button type="submit" name="delete" class="btn btn-primary">
                                    Oui
                                </button>
                                <!--end::Button-->
                            </div>
                            <!--end::Modal footer-->
                        </form>
                        <!--end::Form-->
                    </div>
                </div>
                <!-- end Modal dialog -->

            </div>
            <!-- end:: Modal - Confirm suspend -->
            <!--begin::Modal - Update user details-->
            <div class="modal" id="kt_modal_update_details" tabindex="-1" aria-hidden="true">
                <!--begin::Modal dialog-->
                <div class="modal-dialog modal-dialog-centered mw-650px">
                    <!--begin::Modal content-->
                    <div class="modal-content">
                        <!--begin::Form-->
                        <form class="form" action="/update/<%= user.id %>?_method=PUT" method="POST"
                            id="kt_modal_update_user_form">
                            <input type="hidden" name="_method" value="PUT">
                            <!--begin::Modal header-->
                            <div class="modal-header" id="kt_modal_update_user_header">
                                <!--begin::Modal title-->
                                <h2 class="fs-2 fw-bolder">Modification des
                                    informations</h2>
                                <!--end::Modal title-->
                                <!--begin::Close-->
                                <div class="btn btn-icon btn-sm btn-active-icon-primary"
                                    data-kt-users-modal-action="close" data-bs-dismiss="modal"
                                    data-kt-menu-dismiss="true">
                                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                                    <span class="svg-icon svg-icon-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                                transform="rotate(-45 6 17.3137)" fill="black" />
                                            <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                                transform="rotate(45 7.41422 6)" fill="black" />
                                        </svg>
                                    </span>
                                    <!--end::Svg Icon-->
                                </div>
                                <!--end::Close-->
                            </div>
                            <!--end::Modal header-->
                            <!--begin::Modal body-->
                            <div class="modal-body py-10 px-lg-17">
                                <!--begin::Scroll-->
                                <div class="d-flex flex-column scroll-y me-n7 pe-7"
                                    id="kt_modal_update_user_scroll" data-kt-scroll="true"
                                    data-kt-scroll-activate="{default: false, lg: true}"
                                    data-kt-scroll-max-height="auto"
                                    data-kt-scroll-dependencies="#kt_modal_update_user_header"
                                    data-kt-scroll-wrappers="#kt_modal_update_user_scroll"
                                    data-kt-scroll-offset="300px">
                                    <!--begin::User toggle-->
                                    <div class="fw-boldest fs-3 rotate collapsible mb-7">
                                        Informations
                                    </div>
                                    <!--end::User toggle-->
                                    <!--begin::User form-->
                                    <div id="kt_modal_update_user_user_info"
                                        class="collapse show">
                                        <!--begin::Input group-->
                                        <div class="fv-row mb-7">
                                            <!--begin::Label-->
                                            <label class="fs-6 fw-bold mb-2">Username</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text"
                                                class="form-control form-control-solid"
                                                placeholder="" name="username"
                                                value="<?php echo $user->username ?>" />
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="fv-row mb-7">
                                            <!--begin::Label-->
                                            <label class="fs-6 fw-bold mb-2">Matricule</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text"
                                                class="form-control form-control-solid"
                                                placeholder="" name="matricule"
                                                value="<?php echo $user->matricule ?>" />
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="row g-9 mb-7">
                                            <!--begin::Col-->
                                            <div class="col-md-6 fv-row">
                                                <!--begin::Label-->
                                                <label
                                                    class="form-label fw-bolder text-dark fs-6">Prénoms</label>
                                                <!--end::Label-->
                                                <!--begin::Input-->
                                                <input class="form-control form-control-solid"
                                                    placeholder="" name="firstName"
                                                    value="<?php echo $user->firstName ?>" />
                                                <!--end::Input-->
                                            </div>
                                            <!--end::Col-->
                                            <!--begin::Col-->
                                            <div class="col-md-6 fv-row">
                                                <!--begin::Label-->
                                                <label
                                                    class="form-label fw-bolder text-dark fs-6">Noms</label>
                                                <!--end::Label-->
                                                <!--begin::Input-->
                                                <input class="form-control form-control-solid"
                                                    placeholder="" name="lastName"
                                                    value="<?php echo $user->lastName ?>" />
                                                <!--end::Input-->
                                            </div>
                                            <!--end::Col-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="fv-row mb-7">
                                            <!--begin::Label-->
                                            <label class="fs-6 fw-bold mb-2">
                                                <span>Email</span>
                                            </label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="email"
                                                class="form-control form-control-solid"
                                                placeholder="" name="email"
                                                value="<?php echo $user->email ?>" />
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="fv-row mb-7">
                                            <!--begin::Label-->
                                            <label class="fs-6 fw-bold mb-2">
                                                <span>Sexe</span>
                                            </label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text"
                                                class="form-control form-control-solid"
                                                placeholder="" name="gender"
                                                value="<?php echo $user->gender ?>" />
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="fv-row mb-7">
                                            <!--begin::Label-->
                                            <label class="fs-6 fw-bold mb-2">Numéro
                                                de
                                                téléphone</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text"
                                                class="form-control form-control-solid"
                                                placeholder="" name="phone"
                                                value="<?php echo $user->phone ?>" />
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="fv-row mb-7">
                                            <!--begin::Label-->
                                            <label class="fs-6 fw-bold mb-2">Date
                                                de
                                                naissance</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text"
                                                class="form-control form-control-solid"
                                                placeholder="" name="birthdate"
                                                value="<?php echo $user->birthdate ?>" />
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="fv-row mb-7">
                                            <!--begin::Label-->
                                            <label class="fs-6 fw-bold mb-2">Métier</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text"
                                                class="form-control form-control-solid"
                                                placeholder="" name="level"
                                                value="<?php echo $user->level ?>" />
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="fv-row mb-7">
                                            <!--begin::Label-->
                                            <label class="fs-6 fw-bold mb-2">Spécialité</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text"
                                                class="form-control form-control-solid"
                                                placeholder="" name="speciality"
                                                value="<?php echo $user->speciality ?? '' ?>" />
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="fv-row mb-7">
                                            <!--begin::Label-->
                                            <label class="fs-6 fw-bold mb-2">Pays</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text"
                                                class="form-control form-control-solid"
                                                placeholder="" name="country"
                                                value="<?php echo $user->country ?>" />
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="fv-row mb-7">
                                            <!--begin::Label-->
                                            <label class="fs-6 fw-bold mb-2">Certificat plus
                                                élévé</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text"
                                                class="form-control form-control-solid"
                                                placeholder="" name="certificate"
                                                value="<?php echo $user->certificate ?>" />
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="fv-row mb-7">
                                            <!--begin::Label-->
                                            <label class="fs-6 fw-bold mb-2">Filiale</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text"
                                                class="form-control form-control-solid"
                                                placeholder="" name="subsidiary"
                                                value="<?php echo $user->subsidiary ?>" />
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="fv-row mb-7">
                                            <!--begin::Label-->
                                            <label class="fs-6 fw-bold mb-2">Département</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text"
                                                class="form-control form-control-solid"
                                                placeholder="" name="department"
                                                value="<?php echo $user->department ?>" />
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="fv-row mb-7">
                                            <!--begin::Label-->
                                            <label class="fs-6 fw-bold mb-2">Fonction
                                                </label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text"
                                                class="form-control form-control-solid"
                                                placeholder="" name="role"
                                                value="<?php echo $user->role ?>" />
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->
                                        <?php
                                        if ($user['department'] == 'Motors') {
                                            ?>
                                        <!--begin::Input group-->
                                        <div class="d-flex flex-column mb-7 fv-row">
                                            <!--begin::Label-->
                                            <label class="form-label fw-bolder text-dark fs-6">
                                                <span>Marques de véhicule</span>
                                                <span class="ms-1" data-bs-toggle="tooltip"
                                                    title="Choississez les questionnaires">
                                                    <i class="ki-duotone ki-information fs-7"><span
                                                            class="path1"></span><span
                                                            class="path2"></span><span
                                                            class="path3"></span></i>
                                                </span>
                                            </label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <select name="brand[]" multiple 
                                            aria-label="Select a Country" 
                                            data-control="select2" data-placeholder="Sélectionnez la/les marque(s) de véhicule..." 
                                            class="form-select form-select-solid fw-bold">
                                              <option value="">Sélectionnez la/les marque(s) de véhicule...</option>
                                              <option value="BYD">
                                                  BYD
                                                </option>
                                                <option value="CITROEN">
                                                  CITROEN
                                                </option>
                                                <option value="MERCEDES">
                                                  MERCEDES
                                                </option>
                                                <option value="MUTSUBISHI">
                                                  MUTSUBISHI
                                                </option>
                                                <option value="PEUGEOT">
                                                  PEUGEOT
                                                </option>
                                                <option value="SUZUKI">
                                                  SUZUKI
                                                </option>
                                                <option value="TOYOTA">
                                                  TOYOTA
                                                </option>
                                                <option value="YAMAHA BATEAU">
                                                  YAMAHA BATEAU
                                                </option>
                                                <option value="YAMAHA MOTO">
                                                  YAMAHA MOTO
                                                </option>
                                            </select>
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->
                                        <?php
                                           }
                                        ?>
                                        <?php
                                        if ($user['department'] == 'Equipment') {
                                            ?>
                                        <!--begin::Input group-->
                                        <div class="d-flex flex-column mb-7 fv-row">
                                            <!--begin::Label-->
                                            <label class="form-label fw-bolder text-dark fs-6">
                                                <span>Marques de véhicule</span>
                                                <span class="ms-1" data-bs-toggle="tooltip"
                                                    title="Choississez les questionnaires">
                                                    <i class="ki-duotone ki-information fs-7"><span
                                                            class="path1"></span><span
                                                            class="path2"></span><span
                                                            class="path3"></span></i>
                                                </span>
                                            </label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <select name="brand[]" multiple 
                                            aria-label="Select a Country" 
                                            data-control="select2" data-placeholder="Sélectionnez la/les marque(s) de véhicule..." 
                                            class="form-select form-select-solid fw-bold">
                                              <option value="">Sélectionnez la/les marque(s) de véhicule...</option>
                                              <option value="FUSO">
                                                FUSO
                                              </option>
                                              <option value="HINO">
                                                HINO
                                              </option>
                                              <option value="JCB">
                                                JCB
                                              </option>
                                              <option value="KING LONG">
                                                KING LONG
                                              </option>
                                              <option value="LOVOL">
                                                LOVOL
                                              </option>
                                              <option value="MERCEDES TRUCK">
                                                MERCEDES TRUCK
                                              </option>
                                              <option value="RENAULT TRUCK">
                                                RENAULT TRUCK
                                              </option>
                                              <option value="SINOTRUCK">
                                                SINOTRUCK
                                              </option>
                                              <option value="TOYOTA BT">
                                                TOYOTA BT
                                              </option>
                                              <option value="TOYOTA FORFLIT">
                                                TOYOTA FORFLIT
                                              </option>
                                            </select>
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->
                                        <?php
                                           }
                                        ?>
                                        <!--begin::Input group-->
                                        <div class="fv-row mb-7">
                                            <!--begin::Label-->
                                            <label class="fs-6 fw-bold mb-2">Date
                                                de
                                                recrutement</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text"
                                                class="form-control form-control-solid"
                                                placeholder="" name="recrutmentDate"
                                                value="<?php echo $user->recrutmentDate ?>" />
                                            <!--end::Input-->
                                        </div>
                                        <!--begin::Input group-->
                                        <div class="fv-row mb-7">
                                            <!--begin::Label-->
                                            <label class="fs-6 fw-bold mb-2">Mot de passe</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="password"
                                                class="form-control form-control-solid"
                                                placeholder="" name="password"
                                                value="********" />
                                            <!--end::Input-->
                                        </div>
                                        <?php if($user->profile == "Technicien" || $user->test == true) { ?>
                                        <!--begin::Input group-->
                                        <div class="d-flex flex-column mb-7 fv-row">
                                            <!--begin::Label-->
                                            <label class="form-label fw-bolder text-dark fs-6">
                                                <span>Manager</span>
                                                <span class="ms-1" data-bs-toggle="tooltip"
                                                    title="Choississez le manager de ce technicien">
                                                    <i class="ki-duotone ki-information fs-7"><span
                                                            class="path1"></span><span
                                                            class="path2"></span><span
                                                            class="path3"></span></i>
                                                </span>
                                            </label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <select name="manager" aria-label="Select a Country"
                                                data-control="select2"
                                                data-placeholder="Sélectionnez votre manager..."
                                                class="form-select form-select-solid fw-bold">
                                                <?php
                                                    if ($user->manager) {
                                                    $lead = $users->findOne(['_id' => $user["manager"]]);
                                                ?>
                                                <option value="<?php echo $lead->_id ?>">
                                                    Manager
                                                    actuel: <?php echo $lead->firstName ?>
                                                    <?php echo $lead->lastName ?>
                                                </option>
                                                <?php } ?>
                                                <?php 
                                                $managers = $users->find([
                                                    '$and' => [['profile' => 'Manager'], ['active' => true]]
                                                ]);
                                                foreach ($managers as $manager) {
                                                ?>
                                                <option value="<?php echo $manager->_id ?>">
                                                    <?php echo $manager->firstName ?>
                                                    <?php echo $manager->lastName ?>
                                                </option>
                                                <?php } ?>
                                            </select>
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->
                                        <?php } ?>
                                    </div>
                                    <!--end::User form-->
                                </div>
                                <!--end::Scroll-->
                            </div>
                            <!--end::Modal body-->
                            <!--begin::Modal footer-->
                            <div class="modal-footer flex-center">
                                <!--begin::Button-->
                                <button type="reset" class="btn btn-light me-3" data-kt-menu-dismiss="true"
                                    data-bs-dismiss="modal" data-kt-users-modal-action="cancel">Annuler</button>
                                <!--end::Button-->
                                <!--begin::Button-->
                                <button type="submit" name="update" class="btn btn-primary">
                                    Valider
                                </button>
                                <!--end::Button-->
                            </div>
                            <!--end::Modal footer-->
                        </form>
                        <!--end::Form-->
                    </div>
                </div>
            </div>
            <!--end::Modal - Update user details-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
</div>
<!--end::Content-->

<div class="modal" id="kt_modal_update_password" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-450px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Form-->
            <form class="form" method="POST">
                <input type="hidden" name="userID" value="<?php echo $user->_id ?>">
                <!--begin::Modal header-->
                <div class="modal-header" id="kt_modal_update_user_header">
                    <!--begin::Modal title-->
                    <h2 class="fs-2 fw-bolder">
                        Changement du mot de passe
                    </h2>
                    <!--end::Modal title-->
                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal"
                        data-kt-users-modal-action="close">
                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                    transform="rotate(-45 6 17.3137)" fill="black" />
                                <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)"
                                    fill="black" />
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                    </div>
                    <!--end::Close-->
                </div>
                <!--end::Modal header-->
                <!--begin::Modal body-->
                <div class="modal-body py-10 px-lg-17">
                    <div class="fv-row mb-7">
                        <!--begin::Label-->
                        <label class="fs-6 fw-bold mb-2">Ancien mot de
                            passe</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type="password" class="form-control form-control-solid" placeholder="" name="oldPassword"
                            value="" />
                        <!--end::Input-->
                    </div>
                    <div class="fv-row mb-7">
                        <!--begin::Label-->
                        <label class="fs-6 fw-bold mb-2">Nouveau mot de
                            passe</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type="password" class="form-control form-control-solid" placeholder="" name="newPassword"
                            value="" />
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->
                </div>
                <!--end::Modal body-->
                <!--begin::Modal footer-->
                <div class="modal-footer flex-center">
                    <!--begin::Button-->
                    <button type="reset" class="btn btn-light me-3" id="closeDesactivate" data-bs-dismiss="modal"
                        data-kt-users-modal-action="cancel">
                        Annuler
                    </button>
                    <!--end::Button-->
                    <!--begin::Button-->
                    <button type="submit" name="password" class="btn btn-primary">
                        Valider
                    </button>
                    <!--end::Button-->
                </div>
                <!--end::Modal footer-->
            </form>
            <!--end::Form-->
        </div>
    </div>
    <!-- end Modal dialog -->
</div>
<!--end::Modal - Update user details-->
<?php
include_once 'partials/footer.php'
?>
<?php
}
?>