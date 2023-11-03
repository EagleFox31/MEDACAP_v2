<?php

require_once '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader;

if ( isset( $_POST[ 'submit' ] ) ) {
    // Create connection
    $conn = new MongoDB\Client( 'mongodb://localhost:27017' );

    // Connecting in database
    $academy = $conn->academy;

    // Connecting in collections
    $users = $academy->users;


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
        $password = password_hash( $row["11"], PASSWORD_DEFAULT );
        $department = $row["12"];
        $mainRole = $row["13"];
        $subRole = $row["14"];
        $profile = $row["15"];
        $recrutmentDate = $row["16"];
        $matricule = $row["17"];
        $username = $row["18"];

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
            'profile' => $profile,
            'birthdate' => $birthdate,
            'recrutmentDate' => $recrutmentDate,
            'certificate' => ucfirst( $certificate ),
            'subsidiary' => ucfirst( $subsidiary ),
            'speciality' => ucfirst( $speciality ),
            'department' => ucfirst( $department ),
            'subRole' => ucfirst( $subRole ),
            'mainRole' => ucfirst( $mainRole ),
            'password' => $password,
            'active' => true
        ];
        
        $users->insertOne($person);
        $success_msg = "Utilisateurs ajoutés avec succès";
    }
}
?>
<?php
include_once 'partials/header.php'
?>

<!--begin::Title-->
<title>Importe Utilisateurs | CFAO Mobility Academy</title>
<!--end::Title-->

<!--begin::Modal body-->
<div class='container mt-5 w-50'>
    <img src='../public/images/logo.png' alt='10' height='170'
        style='display: block; margin-left: auto; margin-right: auto; width: 50%;'>
    <h1 class='my-3 text-center'>Importer des utilisateurs</h1>

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
            <label class='required form-label fw-bolder text-dark fs-6'>Importer des utilisateurs via Excel</label>
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

<?php
include_once 'partials/footer.php'
?>