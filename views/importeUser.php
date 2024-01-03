<?php
session_start();

if ( !isset( $_SESSION[ 'id' ] ) ) {
    header( 'Location: ./index.php' );
    exit();
} else {
?><?php

require_once '../vendor/autoload.php';

if ( isset( $_POST[ 'submit' ] ) ) {
    // Create connection
    $conn = new MongoDB\Client( 'mongodb://localhost:27017' );

    // Connecting in database
    $academy = $conn->academy;

    // Connecting in collections
    $users = $academy->users;
    $vehicles = $academy->vehicles;
    $allocations = $academy->allocations;


    $filePath = $_FILES['excel']['tmp_name'];
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
    $data = $spreadsheet->getActiveSheet()->toArray();

    foreach ($data as $row) {
        $firstName = $row["0"];
        $lastName = $row["1"];
        $email = $row["2"];
        $birthdate = $row["3"];
        $phone = $row["4"];
        $gender = $row["5"];
        $country = $row["6"];
        $speciality = $row["7"];
        $certificate = $row["8"];
        $subsidiary = $row["9"];
        $level = $row["10"];
        $password = sha1( $row["11"] );
        $department = $row["12"];
        $mainRole = $row["13"];
        $subRole = $row["14"];
        $subVehicle = $_POST[ '15' ];
        $vehicle = $_POST[ '16' ];
        $brand = $_POST[ '17' ];
        $profile = $row["18"];
        $recrutmentDate = $row["19"];
        $matricule = $row["20"];
        $username = $row["21"];
        $usernameManager = $row["22"];

        $member =  $users->findOne([
            '$and' => [
                [ 'username' => $username ],
                [ 'active' => true ],
            ],
        ]);
        $manager = $users->findOne([
            '$and' => [
                'username' => $usernameManager,
                'active' => true
            ]
        ]);
        if ($member){
            $error_msg = "Cet utilisateur ".$firstName." ".$lastName." existe déjà";
        } elseif ($profile == 'Technicien') {
            $person = [
                'users' => [],
                'username' => $username,
                'matricule' => $matricule,
                'firstName' => ucfirst( $firstName ),
                'lastName' => ucfirst( $lastName ),
                'email' => $email,
                'phone' => $phone,
                'gender' => $gender,
                'level' => $level,
                'country' => $country,
                'profile' => $profile,
                'birthdate' => $birthdate,
                'recrutmentDate' => $recrutmentDate,
                'certificate' => ucfirst( $certificate ),
                'subsidiary' => ucfirst( $subsidiary ),
                'speciality' => ucfirst( $speciality ),
                'vehicle' => $vehicle,
                'subVehicle' => $subVehicle,
                'brand' => $brand,
                'department' => ucfirst( $department ),
                'subRole' => ucfirst( $subRole ),
                'mainRole' => ucfirst( $mainRole ),
                'password' => $password,
                'manager' => new MongoDB\BSON\ObjectId( $manager->_id ),
                'active' => true,
                'created' => date("d-m-Y")
            ];
        
            $user = $users->insertOne($person);
            
            $users->updateOne(
                [ '_id' => new MongoDB\BSON\ObjectId( $manager->_id ) ],
                [ '$push' => [ 'users' => new MongoDB\BSON\ObjectId( $user->getInsertedId() ) ] ]
            );
            
            $vehicleFacJu = $vehicles->findOne([
                '$and' => [
                    [ 'label' => $vehicle ],
                    [ 'brand' => $brand ],
                    [ 'type' => 'Factuel' ],
                    [ 'level' => 'Junior' ],
                    [ 'active' => true ],
                ],
            ]);
            $vehicleDeclaJu = $vehicles->findOne([
                '$and' => [
                    [ 'label' => $vehicle ],
                    [ 'brand' => $brand ],
                    [ 'type' => 'Declaratif' ],
                    [ 'level' => 'Junior' ],
                    [ 'active' => true ],
                ],
            ]);
            $vehicleFacSe = $vehicles->findOne([
                '$and' => [
                    [ 'label' => $vehicle ],
                    [ 'brand' => $brand ],
                    [ 'type' => 'Factuel' ],
                    [ 'level' => 'Senior' ],
                    [ 'active' => true ],
                ],
            ]);
            $vehicleDeclaSe = $vehicles->findOne([
                '$and' => [
                    [ 'label' => $vehicle ],
                    [ 'brand' => $brand ],
                    [ 'type' => 'Declaratif' ],
                    [ 'level' => 'Senior' ],
                    [ 'active' => true ],
                ],
            ]);
            $vehicleFacEx = $vehicles->findOne([
                '$and' => [
                    [ 'label' => $vehicle ],
                    [ 'brand' => $brand ],
                    [ 'type' => 'Factuel' ],
                    [ 'level' => 'Expert' ],
                    [ 'active' => true ],
                ],
            ]);
            $vehicleDeclaEx = $vehicles->findOne([
                '$and' => [
                    [ 'label' => $vehicle ],
                    [ 'brand' => $brand ],
                    [ 'type' => 'Declaratif' ],
                    [ 'level' => 'Expert' ],
                    [ 'active' => true ],
                ],
            ]);
            if ($level == 'Technicien de maintenance') {
                if ($vehicleFacJu) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $vehicleFacJu->_id ) ],
                        [ '$push' => [ 'users' => new MongoDB\BSON\ObjectId( $user->getInsertedId() ) ] ]
                    );
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $vehicleFacJu->_id ),
                        'user' => new MongoDB\BSON\ObjectId( $user->getInsertedId() ),
                        'type' => $vehicleFacJu->type,
                        'level' => $vehicleFacJu->level,
                        'active' => false,
                        'created' => date("d-m-Y"),
                        ];
                        $allocations->insertOne( $allocates );
                }
                if ($vehicleDeclaJu) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $vehicleDeclaJu->_id ) ],
                        [ '$push' => [ 'users' => new MongoDB\BSON\ObjectId( $user->getInsertedId() ) ] ]
                    );
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $vehicleDeclaJu->_id ),
                        'user' => new MongoDB\BSON\ObjectId( $user->getInsertedId() ),
                        'type' => $vehicleDeclaJu->type,
                        'level' => $vehicleDeclaJu->level,
                        'activeManager' => false,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                }
                $success_msg = 'Technicien ajouté avec succès';
            } elseif ($level == 'Technicien de réparation') {
                if ($vehicleFacJu) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $vehicleFacJu->_id ) ],
                        [ '$push' => [ 'users' => new MongoDB\BSON\ObjectId( $user->getInsertedId() ) ] ]
                    );
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $vehicleFacJu->_id ),
                        'user' => new MongoDB\BSON\ObjectId( $user->getInsertedId() ),
                        'type' => $vehicleFacJu->type,
                        'level' => $vehicleFacJu->level,
                        'active' => false,
                        'created' => date("d-m-Y"),
                        ];
                        $allocations->insertOne( $allocates );
                }
                if ($vehicleDeclaJu) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $vehicleDeclaJu->_id ) ],
                        [ '$push' => [ 'users' => new MongoDB\BSON\ObjectId( $user->getInsertedId() ) ] ]
                    );
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $vehicleDeclaJu->_id ),
                        'user' => new MongoDB\BSON\ObjectId( $user->getInsertedId() ),
                        'type' => $vehicleDeclaJu->type,
                        'level' => $vehicleDeclaJu->level,
                        'activeManager' => false,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                }
                if ($vehicleFacSe) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $vehicleFacSe->_id ) ],
                        [ '$push' => [ 'users' => new MongoDB\BSON\ObjectId( $user->getInsertedId() ) ] ]
                    );
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $vehicleFacSe->_id ),
                        'user' => new MongoDB\BSON\ObjectId( $user->getInsertedId() ),
                        'type' => $vehicleFacSe->type,
                        'level' => $vehicleFacSe->level,
                        'active' => false,
                        'created' => date("d-m-Y"),
                        ];
                        $allocations->insertOne( $allocates );
                }
                if ($vehicleDeclaSe) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $vehicleDeclaSe->_id ) ],
                        [ '$push' => [ 'users' => new MongoDB\BSON\ObjectId( $user->getInsertedId() ) ] ]
                    );
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $vehicleDeclaSe->_id ),
                        'user' => new MongoDB\BSON\ObjectId( $user->getInsertedId() ),
                        'type' => $vehicleDeclaSe->type,
                        'level' => $vehicleDeclaSe->level,
                        'activeManager' => false,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                }
                $success_msg = 'Technicien ajouté avec succès';
            } elseif ($level == 'Technicien de diagnostic') {
                if ($vehicleFacJu) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $vehicleFacJu->_id ) ],
                        [ '$push' => [ 'users' => new MongoDB\BSON\ObjectId( $user->getInsertedId() ) ] ]
                    );
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $vehicleFacJu->_id ),
                        'user' => new MongoDB\BSON\ObjectId( $user->getInsertedId() ),
                        'type' => $vehicleFacJu->type,
                        'level' => $vehicleFacJu->level,
                        'active' => false,
                        'created' => date("d-m-Y"),
                        ];
                        $allocations->insertOne( $allocates );
                }
                if ($vehicleDeclaJu) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $vehicleDeclaJu->_id ) ],
                        [ '$push' => [ 'users' => new MongoDB\BSON\ObjectId( $user->getInsertedId() ) ] ]
                    );
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $vehicleDeclaJu->_id ),
                        'user' => new MongoDB\BSON\ObjectId( $user->getInsertedId() ),
                        'type' => $vehicleDeclaJu->type,
                        'level' => $vehicleDeclaJu->level,
                        'activeManager' => false,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                }
                if ($vehicleFacSe) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $vehicleFacSe->_id ) ],
                        [ '$push' => [ 'users' => new MongoDB\BSON\ObjectId( $user->getInsertedId() ) ] ]
                    );
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $vehicleFacSe->_id ),
                        'user' => new MongoDB\BSON\ObjectId( $user->getInsertedId() ),
                        'type' => $vehicleFacSe->type,
                        'level' => $vehicleFacSe->level,
                        'active' => false,
                        'created' => date("d-m-Y"),
                        ];
                        $allocations->insertOne( $allocates );
                }
                if ($vehicleDeclaSe) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $vehicleDeclaSe->_id ) ],
                        [ '$push' => [ 'users' => new MongoDB\BSON\ObjectId( $user->getInsertedId() ) ] ]
                    );
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $vehicleDeclaSe->_id ),
                        'user' => new MongoDB\BSON\ObjectId( $user->getInsertedId() ),
                        'type' => $vehicleDeclaSe->type,
                        'level' => $vehicleDeclaSe->level,
                        'activeManager' => false,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                }
                if ($vehicleFacEx) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $vehicleFacEx->_id ) ],
                        [ '$push' => [ 'users' => new MongoDB\BSON\ObjectId( $user->getInsertedId() ) ] ]
                    );
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $vehicleFacEx->_id ),
                        'user' => new MongoDB\BSON\ObjectId( $user->getInsertedId() ),
                        'type' => $vehicleFacEx->type,
                        'level' => $vehicleFacEx->level,
                        'active' => false,
                        'created' => date("d-m-Y"),
                        ];
                        $allocations->insertOne( $allocates );
                }
                if ($vehicleDeclaEx) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $vehicleDeclaEx->_id ) ],
                        [ '$push' => [ 'users' => new MongoDB\BSON\ObjectId( $user->getInsertedId() ) ] ]
                    );
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $vehicleDeclaEx->_id ),
                        'user' => new MongoDB\BSON\ObjectId( $user->getInsertedId() ),
                        'type' => $vehicleDeclaEx->type,
                        'level' => $vehicleDeclaEx->level,
                        'activeManager' => false,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                }
                $success_msg = 'Techniciens ajoutés avec succès';
            }
        } elseif ( $profile == 'Manager' ) {
            $personM = [
                'users' => [],
                'username' => $username,
                'matricule' => $matricule,
                'firstName' => ucfirst( $firstName ),
                'lastName' => ucfirst( $lastName ),
                'email' => $email,
                'phone' => $phone,
                'gender' => $gender,
                'level' => 'Non applicable',
                'country' => $country,
                'profile' => $profile,
                'birthdate' => $birthdate,
                'recrutmentDate' => $recrutmentDate,
                'certificate' => ucfirst( $certificate ),
                'subsidiary' => ucfirst( $subsidiary ),
                'speciality' => ucfirst( $speciality ),
                'vehicle' => $vehicle,
                'subVehicle' => $subVehicle,
                'brand' => $brand,
                'department' => ucfirst( $department ),
                'subRole' => ucfirst( $subRole ),
                'mainRole' => ucfirst( $mainRole ),
                'password' => $password_hash,
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $users->insertOne( $personM );
            $success_msg = 'Managers ajoutés avec succès';
        } else {
            $personA = [
                'users' => [],
                'username' => $username,
                'matricule' => $matricule,
                'firstName' => ucfirst( $firstName ),
                'lastName' => ucfirst( $lastName ),
                'email' => $email,
                'phone' => $phone,
                'gender' => $gender,
                'level' => 'Non applicable',
                'country' => $country,
                'profile' => $profile,
                'birthdate' => $birthdate,
                'recrutmentDate' => $recrutmentDate,
                'certificate' => ucfirst( $certificate ),
                'subsidiary' => ucfirst( $subsidiary ),
                'speciality' => ucfirst( $speciality ),
                'vehicle' => $vehicle,
                'subVehicle' => $subVehicle,
                'brand' => $brand,
                'department' => ucfirst( $department ),
                'subRole' => ucfirst( $subRole ),
                'mainRole' => ucfirst( $mainRole ),
                'password' => $password_hash,
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $users->insertOne( $personA );
            $success_msg = 'Administrateurs ajoutés avec succès';
        }
    }
}
?>
<?php
include_once 'partials/header.php'
?>

<!--begin::Title-->
<title>Importer Utilisateurs | CFAO Mobility Academy</title>
<!--end::Title-->


<!--begin::Body-->
<div class="content fs-6 d-flex flex-column flex-column-fluid" id="kt_content"
    data-select2-id="select2-data-kt_content">
    <!--begin::Post-->
    <div class="post fs-6 d-flex flex-column-fluid" id="kt_post" data-select2-id="select2-data-kt_post">
        <!--begin::Container-->
        <div class=" container-xxl " data-select2-id="select2-data-194-27hh">
            <!--begin::Modal body-->
            <div class="container mt-5 w-50">
                <img src="../public/images/logo.png" alt="10" height="170"
                    style="display: block; margin-left: auto; margin-right: auto; width: 50%;">
                <h1 class="my-3 text-center">Importer des utilisateurs</h1>

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

                <form enctype='multipart/form-data' method='POST'><br>
                    <!--begin::Input group-->
                    <div class='fv-row mb-7'>
                        <!--begin::Label-->
                        <label class='required form-label fw-bolder text-dark fs-6'>Importer des utilisateurs via
                            Excel</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type='file' class='form-control form-control-solid' placeholder='' name='excel' />
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->
                    <div class='modal-footer flex-center'>
                        <!--begin::Button-->
                        <button type='submit' name='submit' class='btn btn-primary'>
                            <span class='indicator-label'>
                                Valider
                            </span>
                            <span class='indicator-progress'>
                                Patientez... <span class='spinner-border spinner-border-sm align-middle ms-2'></span>
                            </span>
                        </button>
                        <!--end::Button-->
                    </div>
                    <!--end::Modal footer-->
                </form>
            </div>
            <!--end::Modal body-->
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