<?php
session_start();

if ( !isset( $_SESSION[ 'id' ] ) ) {
    header( 'Location: ./index.php' );
    exit();
} else {
require_once '../vendor/autoload.php';

// Create connection
$conn = new MongoDB\Client( 'mongodb://localhost:27017' );

// Connecting in database
$academy = $conn->academy;

// Connecting in collections
$users = $academy->users;
$vehicles = $academy->vehicles;
$allocations = $academy->allocations;

if ( isset( $_POST[ 'submit' ] ) ) {
    $vehicle = $_POST['vehicle'];
    $technicians[] = $_POST['technicians'];
    
    $member = $vehicles->findOne([
        '$and' => [
            ['users' => ['$in' => $technicians]],
            ['_id' => new MongoDB\BSON\ObjectId($vehicle)],
            ['active' => true]
        ]
    ]);
    
    $vehice = $vehicles->findOne(['_id' => new MongoDB\BSON\ObjectId($vehicle)]);

    if (!$vehicle || !$technicians) {
        $error_msg = 'Veuillez remplir tous les champs.';
    } elseif ($member) {
            $error_msg = 'Le technicien ' . $member->firstName . ' ' . $member->lastName . ' est déjà affecté à ce questionnaire.';
    }  else {
        for ($i = 0; $i < count($technicians); $i++) {
            $allocateExist = $allocations->findOne([
                '$and' => [
                    ['user' => new MongoDB\BSON\ObjectId($technicians[$i])],
                    ['vehicle' => new MongoDB\BSON\ObjectId($vehicle)]
                ]
            ]);
        
            if ($allocateExist) {
                $allocateExist->active = false;
                $allocateExist->save();
                $vehicles->updateOne(
                    ['_id' => new MongoDB\BSON\ObjectId($vehicle)],
                    ['$addToSet' => ['users' => new MongoDB\BSON\ObjectId($technicians[$i])]]
                );
                $success_msg = 'Technicien(s) affecté(s) avec succès';
            } elseif ($vehice->type == 'Declaratif') {
                $allocate = [
                    'vehicle' => new MongoDB\BSON\ObjectId($vehicle),
                    'user' => new MongoDB\BSON\ObjectId($technicians[$i]),
                    'type' => $vehice->type,
                    'level' => $vehice->level,
                    "activeManager" => false,
                    "active" => false,
                    'created' => date("d-m-y")

                ];
            
                $vehicles->updateOne(
                    ['_id' => new MongoDB\BSON\ObjectId($vehicle)],
                    ['$addToSet' => ['users' => new MongoDB\BSON\ObjectId($technicians[$i])]]
                );
                $allocations->insertOne($allocate);
                $success_msg = 'Technicien(s) affecté(s) avec succès';
            } else {
                $allocate = [
                    'vehicle' => new MongoDB\BSON\ObjectId($vehicle),
                    'user' => new MongoDB\BSON\ObjectId($technicians[$i]),
                    'type' => $vehice->type,
                    'level' => $vehice->level,
                    "active" => false,
                    'created' => date("d-m-y")
                ];
            
                $vehicles->updateOne(
                    ['_id' => new MongoDB\BSON\ObjectId($vehicle)],
                    ['$addToSet' => ['users' => new MongoDB\BSON\ObjectId($technicians[$i])]]
                );
                $allocations->insertOne($allocate);
                $success_msg = 'Technicien(s) affecté(s) avec succès';
            }
        }
    }
    
}

?>

<?php
include_once 'partials/header.php'
?>
<!--begin::Title-->
<title>Affecter Technicien au Test | CFAO Mobility Academy</title>
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
                <h1 class="my-3 text-center">Affecter un technicien à un test</h1>

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

                <form method="POST"><br>
                    <!--begin::Input group-->
                    <div class="row fv-row mb-7">
                        <!--begin::Col-->
                        <div class="col-xl-6">
                            <div class="d-flex flex-column mb-7 fv-row">
                                <!--begin::Label-->
                                <label class="form-label fw-bolder text-dark fs-6">
                                    <span class="required">Véhicule</span>

                                    <span class="ms-1" data-bs-toggle="tooltip" title="Veuillez choisir le véhicule">
                                        <i class="ki-duotone ki-information fs-7"><span class="path1"></span><span
                                                class="path2"></span><span class="path3"></span></i> </span>
                                </label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <select name="vehicle" aria-label="Select a Country" data-control="select2"
                                    data-placeholder="Sélectionnez le questionnaire..."
                                    class="form-select form-select-solid fw-bold">
                                    <option value="">Sélectionnez le véhicule...
                                    </option>
                                    <?php
                            $vehicle = $vehicles->find(['active' => true]);
                            foreach ($vehicle as $vehicle) {
                        ?>
                                    <option value='<?php echo $vehicle->_id ?>'>
                                        <?php echo $vehicle->label ?>
                                    </option>
                                    <?php } ?>
                                </select>
                                <?php
                     if(isset($error)) {
                    ?>
                                <span class='text-danger'>
                                    <?php echo $error ?>
                                </span>
                                <?php
                    }
                    ?>
                                <!--end::Input-->
                            </div>
                        </div>
                        <!--end::Col-->
                        <!--begin::Col-->
                        <div class="col-xl-6">
                            <div class="d-flex flex-column mb-7 fv-row">
                                <!--begin::Label-->
                                <label class="form-label fw-bolder text-dark fs-6">
                                    <span class="required">Technicien</span>

                                    <span class="ms-1" data-bs-toggle="tooltip" title="Veuillez choisir le technicien">
                                        <i class="ki-duotone ki-information fs-7"><span class="path1"></span><span
                                                class="path2"></span><span class="path3"></span></i> </span>
                                </label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <select name="technicians" aria-label="Select a Country" data-control="select2"
                                    data-placeholder="Sélectionnez le technicien..."
                                    class="form-select form-select-solid fw-bold">
                                    <option value="">Sélectionnez le technicien...</option>
                                    <?php
                            $user = $users->find([
                                '$and' => [
                                    ['profile' => "Technicien"],
                                    ['active' => true]
                                ]
                            ]);
                            foreach ($user as $user) {
                        ?>
                                    <option value='<?php echo $user->_id ?>'>
                                        <?php echo $user->firstName ?> <?php echo $user->lastName ?>
                                    </option>
                                    <?php } ?>
                                </select>
                                <?php
                     if(isset($error)) {
                    ?>
                                <span class='text-danger'>
                                    <?php echo $error ?>
                                </span>
                                <?php
                    }
                    ?>
                                <!--end::Input-->
                            </div>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="text-center" style="margin-bottom: 50px;">
                        <button type="submit" name="submit" class="btn btn-lg btn-primary">
                            Valider
                        </button>
                    </div>
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