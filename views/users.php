<?php
session_start();

if ( !isset( $_SESSION["profile"] ) ) {
    header( 'Location: ./index.php' );
    exit();
} else {
?>
<?php
require_once '../vendor/autoload.php';

// Create connection
$conn = new MongoDB\Client( 'mongodb://localhost:27017' );

// Connecting in database
$academy = $conn->academy;

// Connecting in collections
$users = $academy->users;
$allocations = $academy->allocations;

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
    $subRole = $_POST[ 'subRole' ];
    $mainRole = $_POST[ 'mainRole' ];
    $gender = $_POST[ 'gender' ];
    $country = $_POST[ 'country' ];
    $certificate = $_POST[ 'certificate' ];
    $speciality = $_POST[ 'speciality' ];
    $birthdate = date( 'd-m-Y', strtotime( $_POST[ 'birthdate' ] ) );
    $recrutmentDate = date( 'd-m-Y', strtotime( $_POST[ 'recrutmentDate' ] ) );
    $level = $_POST[ 'level' ];
    if ($_POST[ 'manager' ]) {
        $managerId = $_POST[ 'manager' ];
    }

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
            'subRole' => ucfirst( $subRole ),
            'mainRole' => ucfirst( $mainRole ),
            "manager" => new MongoDB\BSON\ObjectId( $managerId ),
            'updated' => date("d-m-Y")
        ];
        
    $member = $users->findOne( [ '_id' => new MongoDB\BSON\ObjectId( $id ) ] );

    if ( $managerId  ) {
        $users->updateOne(
            [ '_id' => new MongoDB\BSON\ObjectId( $id ) ],
            [ '$set' => $person ]
        );

        $manager = $users->findOne( [ '_id' => new MongoDB\BSON\ObjectId( $managerId ) ] );
        $allocate = $allocations->findOne( [
            '$and' => [
                [ 'user' => $id ],
                [ 'type' => 'Technicien dans manager' ]
            ]
        ] );

        if ( $allocate ) {
            $allocations->updateOne(
                [ '_id' => $allocate->_id ],
                [ '$set' => [
                    '    manager' => new MongoDB\BSON\ObjectId( $managerId ),
                        'updated' => date("d-m-Y")
                    ]
                ]
            );
        } else {
            $allocation = [
                'user' => $id,
                'manager' => new MongoDB\BSON\ObjectId( $managerId ) ,
                'type' => 'Technicien dans manager'
            ];
            $allocations->insertOne( $allocation );
        }

        $users->updateOne(
            [ '_id' => new MongoDB\BSON\ObjectId( $managerId ) ],
            [ '$addToSet' => [ 'users' => new MongoDB\BSON\ObjectId( $id ) ] ]
        );

        if ( $member->profile == 'Technicien' ) {
            $success_msg = 'Technicien modifié avec succes.';
        } elseif ( $member->profile == 'Manager' ) {
            $success_msg = 'Manager modifié avec succes.';
        } elseif ( $member->profile == 'Admin' ) {
            $success_msg = 'Administrateur modifié avec succes.';
        }
    } else {
        $users->updateOne(
            [ '_id' => new MongoDB\BSON\ObjectId( $id ) ],
            [ '$set' =>
                    [
                    'username' => $username,
                    'matricule' => $matricule,
                    'firstName' => $firstName,
                    'lastName' => $lastName,
                    'email' => $email,
                    'phone' => $phone,
                    'gender' => $gender,
                    'level' => $level,
                    'country' => $country,
                    'birthdate' => $birthdate,
                    'recrutmentDate' => $recrutmentDate,
                    'certificate' => $certificate,
                    'subsidiary' => $subsidiary,
                    'speciality' => $speciality,
                    'department' => $department,
                    'subRole' => $subRole,
                    'mainRole' => $mainRole,
                    'updated' => date("d-m-Y")
                ]
            ]
        );

        if ( $member->profile == 'Technicien' ) {
            $success_msg = 'Technicien modifié avec succes.';
        } elseif ( $member->profile == 'Manager' ) {
            $success_msg = 'Manager modifié avec succes.';
        } elseif ( $member->profile == 'Admin' ) {
            $success_msg = 'Administrateur modifié avec succes.';
        }
    }
}

if ( isset( $_POST[ 'password' ] ) ) {
    // Password modification
    $id = $_POST[ 'userID' ];
    $password = $_POST[ 'password' ];
    
    // Check if the password contains at least 8 characters, including at least one uppercase letter, one lowercase letter, and one special character.
    if ( ! preg_match( '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{6,}$/', $password ) ) {
        $error = ( 'Le mot de passe doit contenir au moins un chiffre, une lettre majiscule' );
        exit();
    } else {
        $password_hash = password_hash( $password, PASSWORD_DEFAULT );
    
        $users->updateOne(
            [ '_id' => new MongoDB\BSON\ObjectId( $id ) ],
            [ '$set' => [ 'password' => $password_hash, ] ]
        );
        $success_msg = 'Mot de passe modifié avec succes.';
        exit();
    }
}

if ( isset( $_POST[ 'delete' ] ) ) {
    $id = $_POST[ 'userID' ];
    $member = $users->findOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
    $member['active'] = false;
    $users->updateOne(['_id' => new MongoDB\BSON\ObjectId($id)], ['$set' => $member]);

    if ($member['profile'] == "Technicien") {
        $success_msg = "Technicien supprimé avec succès";
        exit();
    } else if ($member['profile'] == "Manager") {
        $success_msg = "Manager supprimé avec succès";
        exit();
    } else if ($member['profile'] == "Admin") {
        $success_msg = "Administrateur supprimé avec succès";
        exit();
    }
}

if ( isset( $_POST[ 'retire-technician-manager' ] ) ) {
    $id = $_POST[ 'userID' ];
    $manager = $users->findOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
    $allocate = $allocations->findOne([
        '$and' => [
            ['user' => $id],
            ['manager' => $manager->id]
        ]
    ]);
    $allocate['active'] = false;
    $collection->updateOne(['_id' => $allocation['_id']], ['$set' => $allocate]);

    $membre = $users->updateOne(
        ['_id' => new MongoDB\BSON\ObjectId($manager->_id)],
        ['$push' => ['users' => new MongoDB\BSON\ObjectId($id)]]
    );
    $user = $users->updateOne(
        ['_id' => new MongoDB\BSON\ObjectId($id)],
        ['$unset' => ['manager' => 1]]
    );
    $success_msg = "Membre retiré avec succes.";
    exit();
}
?>

<?php
include_once 'partials/header.php'
?>
<!--begin::Title-->
<title>Listes des Utilisateurs | CFAO Mobility Academy</title>
<!--end::Title-->

<!--begin::Body-->
<div class="content fs-6 d-flex flex-column flex-column-fluid" id="kt_content"
    data-select2-id="select2-data-kt_content">
    <!--begin::Toolbar-->
    <div class="toolbar" id="kt_toolbar">
        <div class=" container-fluid  d-flex flex-stack flex-wrap flex-sm-nowrap">
            <!--begin::Info-->
            <div class="d-flex flex-column align-items-start justify-content-center flex-wrap me-2">
                <!--begin::Title-->
                <h1 class="text-dark fw-bold my-1 fs-2">
                    Listes des utilisateurs </h1>
                <!--end::Title-->
                <div class="card-title">
                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5"><span class="path1"></span><span
                                class="path2"></span></i>
                        <input type="text" id="search" class="form-control form-control-solid w-250px ps-12"
                            placeholder="Recherche...">
                    </div>
                    <!--end::Search-->
                </div>
            </div>
            <!--end::Info-->
            <!--begin::Actions-->
            <div class="d-flex align-items-center flex-nowrap text-nowrap py-1">
                <div class="d-flex justify-content-end align-items-center" style="margin-left: 10px;">
                    <button type="button" id="users" data-bs-toggle="modal" class="btn btn-primary">
                        Liste subordonnés
                    </button>
                </div>
                <div class="d-flex justify-content-end align-items-center" style="margin-left: 10px;">
                    <button type="button" id="edit" title="Cliquez ici pour modifier le technicien"
                        data-bs-toggle="modal" class="btn btn-primary">
                        Modifier
                    </button>
                </div>
                <div class="d-flex justify-content-end align-items-center" style="margin-left: 10px;">
                    <button type="button" id="password" data-bs-toggle="modal"
                        title="Cliquez ici pour modifier le mot de passe du technicien" class="btn btn-primary">
                        Modifier mot de passe
                    </button>
                </div>
                <div class="d-flex justify-content-end align-items-center" style="margin-left: 10px;">
                    <button type="button" id="delete" title="Cliquez ici pour supprimer le technicien"
                        data-bs-toggle="modal" class="btn btn-danger">
                        Supprimer
                    </button>
                </div>
            </div>
            <!--end::Actions-->
        </div>
    </div>
    <!--end::Toolbar-->

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
    <!--begin::Post-->
    <div class="post fs-6 d-flex flex-column-fluid" id="kt_post" data-select2-id="select2-data-kt_post">
        <!--begin::Container-->
        <div class=" container-xxl " data-select2-id="select2-data-194-27hh">
            <!--begin::Card-->
            <div class="card">
                <!--begin::Card header-->
                <!-- <div class="card-header border-0 pt-6"> -->
                <!--begin::Card title-->
                <!-- <div class="card-title"> -->
                <!--begin::Search-->
                <!-- <div
                            class="d-flex align-items-center position-relative my-1">
                            <i
                                class="ki-duotone ki-magnifier fs-3 position-absolute ms-5"><span
                                    class="path1"></span><span
                                    class="path2"></span></i>
                            <input type="text" id="search"
                                class="form-control form-control-solid w-250px ps-12"
                                placeholder="Recherche">
                        </div> -->
                <!--end::Search-->
                <!-- </div> -->
                <!--begin::Card title-->
                <!--begin::Card toolbar-->
                <!-- <div class="card-toolbar"> -->
                <!--begin::Toolbar-->
                <!-- <div class="d-flex justify-content-end"
                            data-kt-customer-table-toolbar="base"> -->
                <!--begin::Filter-->
                <!-- <div class="w-150px me-3" id="etat"> -->
                <!--begin::Select2-->
                <!-- <select id="select"
                                    class="form-select form-select-solid"
                                    data-control="select2"
                                    data-hide-search="true"
                                    data-placeholder="Etat"
                                    data-kt-ecommerce-order-filter="etat">
                                    <option></option>
                                    <option value="tous">Tous
                                    </option>
                                    <option value="true">
                                        Active</option>
                                    <option value="false">
                                        Supprimé</option>
                                </select> -->
                <!--end::Select2-->
                <!-- </div> -->
                <!--end::Filter-->
                <!--begin::Export dropdown-->
                <!-- <button type="button" id="excel"
                                class="btn btn-light-primary">
                                <i class="ki-duotone ki-exit-up fs-2"><span
                                        class="path1"></span><span
                                        class="path2"></span></i>
                                Excel
                            </button> -->
                <!--end::Export dropdown-->
                <!--begin::Group actions-->
                <!-- <div class="d-flex justify-content-end align-items-center"
                                style="margin-left: 10px;">
                                <button type="button" id="edit"
                                    data-bs-toggle="modal"
                                    class="btn btn-primary">
                                    Modifier
                                </button>
                            </div> -->
                <!--end::Group actions-->
                <!--begin::Group actions-->
                <!-- <div class="d-flex justify-content-end align-items-center"
                                style="margin-left: 10px;">
                                <button type="button" id="password"
                                    data-bs-toggle="modal"
                                    class="btn btn-primary">
                                    Modifier mot de passe
                                </button>
                            </div> -->
                <!--end::Group actions-->
                <!--begin::Group actions-->
                <!-- <div class="d-flex justify-content-end align-items-center"
                                style="margin-left: 10px;">
                                <button type="button" id="delete"
                                    data-bs-toggle="modal"
                                    class="btn btn-danger">
                                    Supprimer
                                </button>
                            </div> -->
                <!--end::Group actions-->
                <!-- </div> -->
                <!--end::Toolbar-->
                <!-- </div> -->
                <!--end::Card toolbar-->
                <!-- </div> -->
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Table-->
                    <div id="kt_customers_table_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <div class="table-responsive">
                            <table aria-describedby=""
                                class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer"
                                id="kt_customers_table">
                                <thead>
                                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                        <th class="w-10px pe-2 sorting_disabled" rowspan="1" colspan="1" aria-label=""
                                            style="width: 29.8906px;">
                                            <div
                                                class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                                <input class="form-check-input" type="checkbox" value="1">
                                            </div>
                                        </th>
                                        <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Customer Name: activate to sort column ascending"
                                            style="width: 125px;">Username
                                        </th>
                                        <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Customer Name: activate to sort column ascending"
                                            style="width: 125px;">Matricule
                                        </th>
                                        <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Customer Name: activate to sort column ascending"
                                            style="width: 125px;">Prénoms et
                                            noms
                                        </th>
                                        <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Email: activate to sort column ascending"
                                            style="width: 155.266px;">Email</th>
                                        <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Company: activate to sort column ascending"
                                            style="width: 134.188px;">Numéro de
                                            téléphone
                                        </th>
                                        <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Created Date: activate to sort column ascending"
                                            style="width: 152.719px;">Sexe</th>
                                        <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Created Date: activate to sort column ascending"
                                            style="width: 152.719px;">Date de
                                            naissance</th>
                                        <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Created Date: activate to sort column ascending"
                                            style="width: 152.719px;">Profile
                                        </th>
                                        <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Created Date: activate to sort column ascending"
                                            style="width: 152.719px;">Métier
                                        </th>
                                        <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Created Date: activate to sort column ascending"
                                            style="width: 152.719px;">Pays</th>
                                        <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Created Date: activate to sort column ascending"
                                            style="width: 152.719px;">Diplôme
                                        </th>
                                        <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Payment Method: activate to sort column ascending"
                                            style="width: 126.516px;">Filiale
                                        </th>
                                        <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Created Date: activate to sort column ascending"
                                            style="width: 152.719px;">
                                            Departement</th>
                                        <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Created Date: activate to sort column ascending"
                                            style="width: 152.719px;">Fonction Principale
                                        </th>
                                        <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Created Date: activate to sort column ascending"
                                            style="width: 152.719px;">Fonction Secondaire
                                        </th>
                                        <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Created Date: activate to sort column ascending"
                                            style="width: 152.719px;">Date de
                                            recrutement</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-600" id="table">
                                    <?php
                                        $persons = $users->find(['active' => true]);
                                        foreach ($persons as $user) {
                                        ?>
                                    <?php if ($_SESSION["profile"] == "Admin") { ?>
                                    <?php if ($user["profile" ] != "Admin" && $user["profile" ]  != "Super Admin") { ?>
                                    <tr class="odd" etat="<?php echo $user->active ?>">
                                        <td>
                                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                <input class="form-check-input" id="checkbox" type="checkbox"
                                                    onclick="enable()" value="<?php echo $user->_id ?>">
                                            </div>
                                        </td>
                                        <td data-filter="email">
                                            <?php echo $user->username ?>
                                        </td>
                                        <td data-filter="email">
                                            <?php echo $user->matricule ?>
                                        </td>
                                        <td data-filter="search">
                                            <?php echo $user->firstName ?> <?php echo $user->lastName ?>
                                        </td>
                                        <td data-filter="email">
                                            <?php echo $user->email ?>
                                        </td>
                                        <td data-filter="phone">
                                            <?php echo $user->phone ?>
                                        </td>
                                        <td data-order="subsidiary">
                                            <?php echo $user->gender ?>
                                        </td>
                                        <td data-order="subsidiary">
                                            <?php echo $user->birthdate ?>
                                            <!-- $user->birthdate->toDateTime()->format("d M Y"); -->
                                        </td>
                                        <td data-order="subsidiary">
                                            <?php echo $user->profile ?>
                                        </td>
                                        <td data-order="subsidiary">
                                            <?php echo $user->level ?>
                                        </td>
                                        <td data-order="subsidiary">
                                            <?php echo $user->country ?>
                                        </td>
                                        <td data-order="subsidiary">
                                            <?php echo $user->certificate ?>
                                        </td>
                                        <td data-order="subsidiary">
                                            <?php echo $user->subsidiary ?>
                                        </td>
                                        <td data-order="department">
                                            <?php echo $user->department ?>
                                        </td>
                                        <td data-order="department">
                                            <?php echo $user->mainRole ?>
                                        </td>
                                        <td data-order="department">
                                            <?php echo $user->subRole ?? "" ?>
                                        </td>
                                        <td data-order="subsidiary">
                                            <?php echo $user->recrutmentDate ?>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                    <?php } ?>
                                    <?php if ($_SESSION["profile"] == "Super Admin") { ?>
                                    <?php if ($user["profile" ] != "Super Admin") { ?>
                                    <tr class="odd" etat="<?php echo $user->active ?>">
                                        <td>
                                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                <input class="form-check-input" id="checkbox" type="checkbox"
                                                    onclick="enable()" value="<?php echo $user->_id ?>">
                                            </div>
                                        </td>
                                        <td data-filter="email">
                                            <?php echo $user->username ?>
                                        </td>
                                        <td data-filter="email">
                                            <?php echo $user->matricule ?>
                                        </td>
                                        <td data-filter="search">
                                            <?php echo $user->firstName ?> <?php echo $user->lastName ?>
                                        </td>
                                        <td data-filter="email">
                                            <?php echo $user->email ?>
                                        </td>
                                        <td data-filter="phone">
                                            <?php echo $user->phone ?>
                                        </td>
                                        <td data-order="subsidiary">
                                            <?php echo $user->gender ?>
                                        </td>
                                        <td data-order="subsidiary">
                                            <?php echo $user->birthdate ?>
                                        </td>
                                        <td data-order="subsidiary">
                                            <?php echo $user->profile ?>
                                        </td>
                                        <td data-order="subsidiary">
                                            <?php echo $user->level ?>
                                        </td>
                                        <td data-order="subsidiary">
                                            <?php echo $user->country ?>
                                        </td>
                                        <td data-order="subsidiary">
                                            <?php echo $user->certificate ?>
                                        </td>
                                        <td data-order="subsidiary">
                                            <?php echo $user->subsidiary ?>
                                        </td>
                                        <td data-order="department">
                                            <?php echo $user->department ?>
                                        </td>
                                        <td data-order="department">
                                            <?php echo $user->mainRole ?>
                                        </td>
                                        <td data-order="department">
                                            <?php echo $user->subRole ?? "" ?>
                                        </td>
                                        <td data-order="subsidiary">
                                            <?php echo $user->recrutmentDate ?>
                                        </td>
                                    </tr>
                                    </tr>
                                    <?php } ?>
                                    <?php } ?>
                                    <!-- begin:: Modal - Confirm suspend -->
                                    <div class="modal" id="kt_modal_desactivate<?php echo $user->_id ?>" tabindex="-1"
                                        aria-hidden="true">
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
                                                            Suppréssion
                                                        </h2>
                                                        <!--end::Modal title-->
                                                        <!--begin::Close-->
                                                        <div class="btn btn-icon btn-sm btn-active-icon-primary"
                                                            data-kt-users-modal-action="close" data-bs-dismiss="modal">
                                                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                                                            <span class="svg-icon svg-icon-1">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none">
                                                                    <rect opacity="0.5" x="6" y="17.3137" width="16"
                                                                        height="2" rx="1"
                                                                        transform="rotate(-45 6 17.3137)"
                                                                        fill="black" />
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
                                                            Voulez-vous vraiment
                                                            supprimer cet
                                                            utilisateur?
                                                        </h4>
                                                    </div>
                                                    <!--end::Modal body-->
                                                    <!--begin::Modal footer-->
                                                    <div class="modal-footer flex-center">
                                                        <!--begin::Button-->
                                                        <button type="reset" class="btn btn-light me-3"
                                                            id="closeDesactivate" data-bs-dismiss="modal"
                                                            data-kt-users-modal-action="cancel">
                                                            Non
                                                        </button>
                                                        <!--end::Button-->
                                                        <!--begin::Button-->
                                                        <button type="submit" name="delete" class="btn btn-danger">
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
                                    <div class="modal" id="kt_modal_activate<?php echo $user->_id ?>" tabindex="-1"
                                        aria-hidden="true">
                                        <!--begin::Modal dialog-->
                                        <div class="modal-dialog modal-dialog-centered mw-450px">
                                            <!--begin::Modal content-->
                                            <div class="modal-content">
                                                <!--begin::Form-->
                                                <form class="form"
                                                    action="/active/<?php echo $user->_id ?>/?_method=PUT" method="POST"
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
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none">
                                                                    <rect opacity="0.5" x="6" y="17.3137" width="16"
                                                                        height="2" rx="1"
                                                                        transform="rotate(-45 6 17.3137)"
                                                                        fill="black" />
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
                                                            Voulez-vous vraiment
                                                            activer cet
                                                            utilisateur?
                                                        </h4>
                                                    </div>
                                                    <!--end::Modal body-->
                                                    <!--begin::Modal footer-->
                                                    <div class="modal-footer flex-center">
                                                        <!--begin::Button-->
                                                        <button type="reset" class="btn btn-light me-3"
                                                            id="closeDesactivate" data-bs-dismiss="modal"
                                                            data-kt-users-modal-action="cancel">
                                                            Non
                                                        </button>
                                                        <!--end::Button-->
                                                        <!--begin::Button-->
                                                        <button type="submit" class="btn btn-primary">
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
                                    <!--begin::Modal - Update user password-->
                                    <div class="modal" id="kt_modal_update_password<?php echo $user->_id ?>"
                                        tabindex="-1" aria-hidden="true">
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
                                                            Modification du mot
                                                            de passe
                                                        </h2>
                                                        <!--end::Modal title-->
                                                        <!--begin::Close-->
                                                        <div class="btn btn-icon btn-sm btn-active-icon-primary"
                                                            data-kt-users-modal-action="close" data-bs-dismiss="modal">
                                                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                                                            <span class="svg-icon svg-icon-1">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none">
                                                                    <rect opacity="0.5" x="6" y="17.3137" width="16"
                                                                        height="2" rx="1"
                                                                        transform="rotate(-45 6 17.3137)"
                                                                        fill="black" />
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
                                                        <div class="fv-row mb-7">
                                                            <!--begin::Label-->
                                                            <label class="fs-6 fw-bold mb-2">Mot
                                                                de
                                                                passe</label>
                                                            <!--end::Label-->
                                                            <!--begin::Input-->
                                                            <input type="password"
                                                                class="form-control form-control-solid"
                                                                placeholder="Entrer le nouveau mot de passe"
                                                                name="password" />
                                                            <!--end::Input-->
                                                        </div>
                                                        <!--end::Input group-->
                                                    </div>
                                                    <!--end::Modal body-->
                                                    <!--begin::Modal footer-->
                                                    <div class="modal-footer flex-center">
                                                        <!--begin::Button-->
                                                        <button type="reset" class="btn btn-light me-3"
                                                            data-bs-dismiss="modal" id="closeDesactivate"
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
                                    <!--end::Modal - Update user password-->
                                    <!--begin::Modal - Update user details-->
                                    <div class="modal" id="kt_modal_update_details<?php echo $user->_id ?>"
                                        tabindex="-1" aria-hidden="true">
                                        <!--begin::Modal dialog-->
                                        <div class="modal-dialog modal-dialog-centered mw-650px">
                                            <!--begin::Modal content-->
                                            <div class="modal-content">
                                                <!--begin::Form-->
                                                <form class="form" method="POST" id="kt_modal_update_user_form">
                                                    <input type="hidden" name="userID" value="<?php echo $user->_id ?>">
                                                    <!--begin::Modal header-->
                                                    <div class="modal-header" id="kt_modal_update_user_header">
                                                        <!--begin::Modal title-->
                                                        <h2 class="fs-2 fw-bolder">
                                                            Modification des
                                                            informations</h2>
                                                        <!--end::Modal title-->
                                                        <!--begin::Close-->
                                                        <div class="btn btn-icon btn-sm btn-active-icon-primary"
                                                            data-kt-users-modal-action="close" data-bs-dismiss="modal"
                                                            data-kt-menu-dismiss="true">
                                                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                                                            <span class="svg-icon svg-icon-1">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none">
                                                                    <rect opacity="0.5" x="6" y="17.3137" width="16"
                                                                        height="2" rx="1"
                                                                        transform="rotate(-45 6 17.3137)"
                                                                        fill="black" />
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
                                                                        value="<?php echo $user->speciality ?>" />
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
                                                                        Principale</label>
                                                                    <!--end::Label-->
                                                                    <!--begin::Input-->
                                                                    <input type="text"
                                                                        class="form-control form-control-solid"
                                                                        placeholder="" name="mainRole"
                                                                        value="<?php echo $user->mainRole ?>" />
                                                                    <!--end::Input-->
                                                                </div>
                                                                <!--end::Input group-->
                                                                <!--begin::Input group-->
                                                                <div class="fv-row mb-7">
                                                                    <!--begin::Label-->
                                                                    <label class="fs-6 fw-bold mb-2">Fonction
                                                                        Secondaire</label>
                                                                    <!--end::Label-->
                                                                    <!--begin::Input-->
                                                                    <input type="text"
                                                                        class="form-control form-control-solid"
                                                                        placeholder="" name="subRole"
                                                                        value="<?php echo $user->subRole ?>" />
                                                                    <!--end::Input-->
                                                                </div>
                                                                <!--end::Input group-->
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
                                                                <!--end::Input group-->
                                                                <?php if($user->profile == "Technicien" ) { ?>
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
                                                        <button type="reset" class="btn btn-light me-3"
                                                            data-kt-menu-dismiss="true" data-bs-dismiss="modal"
                                                            data-kt-users-modal-action="cancel">Annuler</button>
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
                                    <!--begin::Modal - Invite Friends-->
                                    <div class="modal fade" id="kt_modal_invite_users<?php echo $user->_id ?>"
                                        tabindex="-1" aria-hidden="true">
                                        <!--begin::Modal dialog-->
                                        <div class="modal-dialog mw-650px">
                                            <!--begin::Modal content-->
                                            <div class="modal-content">
                                                <!--begin::Modal header-->
                                                <div class="modal-header pb-0 border-0 justify-content-end">
                                                    <!--begin::Close-->
                                                    <div class="btn btn-sm btn-icon btn-active-color-primary"
                                                        data-bs-dismiss="modal">
                                                        <i class="ki-duotone ki-cross fs-1"><span
                                                                class="path1"></span><span class="path2"></span></i>
                                                    </div>
                                                    <!--end::Close-->
                                                </div>
                                                <!--begin::Modal header-->
                                                <!--begin::Modal body-->
                                                <div class="modal-body scroll-y mx-5 mx-xl-18 pt-0 pb-15">
                                                    <!--begin::Heading-->
                                                    <div class="text-center mb-13">
                                                        <!--begin::Title-->
                                                        <h1 class="mb-3">
                                                            Liste des
                                                            techniciens
                                                        </h1>
                                                        <!--end::Title-->
                                                    </div>
                                                    <!--end::Heading-->
                                                    <!--begin::Users-->
                                                    <div class="mb-10">
                                                        <!--begin::List-->
                                                        <div class="mh-300px scroll-y me-n7 pe-7">
                                                            <!--begin::User-->
                                                            <?php
                                                                $technicians = $users->find(['_id' => ['$in' => $user["users"]]]);
                                                                foreach ($technicians as $technician) {
                                                            ?>
                                                            <div
                                                                class="d-flex flex-stack py-4 border-bottom border-gray-300 border-bottom-dashed">
                                                                <!--begin::Details-->
                                                                <div class="d-flex align-items-center">
                                                                    <!--begin::Avatar-->
                                                                    <div class="symbol symbol-35px symbol-circle">
                                                                        <img alt="Pic"
                                                                            src="../public/assets/media/avatars/300-1.jpg" />
                                                                    </div>
                                                                    <!--end::Avatar -->
                                                                    <!--begin::Details-->
                                                                    <div class="ms-5">
                                                                        <a href="#"
                                                                            class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">
                                                                            <?php echo $technician->firstName ?>
                                                                            <?php echo $technician->lastName ?>
                                                                        </a>
                                                                        <div class="fw-semibold text-muted">
                                                                            <?php echo $technician->email ?>
                                                                        </div>
                                                                    </div>
                                                                    <!--end::Details-->
                                                                </div>
                                                                <!--end::Details-->
                                                                <!--begin::Access menu-->
                                                                <div data-kt-menu-trigger="click">
                                                                    <form method="POST">
                                                                        <input type="hidden" name="userID"
                                                                            value="<?php echo $technician->_id ?>">
                                                                        <button
                                                                            class="btn btn-light btn-active-light-primary btn-sm"
                                                                            type="submit"
                                                                            name="retire-technician-manager">Supprimer</button>
                                                                    </form>
                                                                </div>
                                                                <!--end::Access menu-->
                                                            </div>
                                                            <!--end::User-->
                                                            <?php } ?>
                                                        </div>
                                                        <!--end::List-->
                                                    </div>
                                                    <!--end::Users-->
                                                </div>
                                                <!--end::Modal body-->
                                            </div>
                                            <!--end::Modal content-->
                                        </div>
                                        <!--end::Modal dialog-->
                                    </div>
                                    <!--end::Modal - Invite Friend-->
                                    <?php  
                                      }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div
                                class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start">
                                <div class="dataTables_length">
                                    <label><select id="kt_customers_table_length" name="kt_customers_table_length"
                                            class="form-select form-select-sm form-select-solid">
                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select></label>
                                </div>
                            </div>
                            <div
                                class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end">
                                <div class="dataTables_paginate paging_simple_numbers">
                                    <ul class="pagination" id="kt_customers_table_paginate">
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Table-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->
            <!--begin::Export dropdown-->
            <div class="d-flex justify-content-end align-items-center" style="margin-top: 20px;">
                <button type="button" id="excel" title="Cliquez ici pour importer la table" class="btn btn-primary">
                    <i class="ki-duotone ki-exit-up fs-2"><span class="path1"></span><span class="path2"></span></i>
                    Excel
                </button>
            </div>
            <!--end::Export dropdown-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
</div>
<!--end::Body-->

<?php
include_once 'partials/footer.php'
?>
<?php } ?>