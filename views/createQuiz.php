<?php
session_start();

if ( !isset( $_SESSION[ 'id' ] ) ) {
    header( 'Location: ./index.php' );
    exit();
} else {

if ( isset( $_POST[ 'submit' ] ) ) {

    require_once '../vendor/autoload.php';

    // Create connection
    $conn = new MongoDB\Client( 'mongodb://localhost:27017' );

    // Connecting in database
    $academy = $conn->academy;

    // Connecting in collections
    $quizzes = $academy->quizzes;

    $label = $_POST[ 'label' ];
    $description = $_POST[ 'description' ];
    $type = $_POST[ 'type' ];
    $speciality = $_POST[ 'speciality' ];
    $number = $_POST[ 'number' ];
    $level = $_POST[ 'level' ];

    $exist = $quizzes->findOne( [
        '$and' => [
            [ 'label' => $label ],
            [ 'level' => $level ],
        ],
    ] );

    if ( empty( $label ) ||
    empty( $description ) ||
    empty( $type ) ||
    empty( $speciality ) ||
    empty( $level ) ||
    empty( $number ) ) {
        $error = 'Champ obligatoire';
    } elseif ( $exist && $exist->active == true ) {
        $error_msg = 'Ce questionnaire existe déjà.';
    } else {
        $quiz = [
            'users' => [],
            'questions' => [],
            'label' => ucfirst( $label ),
            'description' => ucfirst( $description ),
            'type' => $type,
            'speciality' => ucfirst( $speciality ),
            'level' => ucfirst( $level ),
            'number' => +$number,
            'total' => 0,
            'active' => true,
            'created' => date("d-m-Y")
        ];
        $quizzes->insertOne( $quiz );
        $success_msg = 'Questionnaire créé avec succès';
    }
}

?>
<?php
include_once 'partials/header.php'
?>

<!--begin::Title-->
<title>Ajouter Questionnaire | CFAO Mobility Academy</title>
<!--end::Title-->

<!--begin::Modal body-->
<div class='container mt-5 w-50'>
    <img src='../public/images/logo.png' alt='10' height='170'
        style='display: block; margin-left: auto; margin-right: auto; width: 50%;'>
    <h1 class='my-3 text-center'>Création d'un questionnaire</h1>

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
            <!--begin::Input group-->
            <div class="fv-row mb-7">
                <!--begin::Label-->
                <label class="required form-label fw-bolder text-dark fs-6">Questionnaire</label>
                <!--end::Label-->
                <!--begin::Input-->
                <input type="text" class="form-control form-control-solid" placeholder="" name="label" />
                <!--end::Input-->
                <?php 
                     if(isset($error)) {
                    ?>
                <span class='text-danger'>
                    <?php echo $error ?>
                </span>
                <?php 
                    }
                    ?>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="fv-row mb-7">
                <!--begin::Label-->
                <label class="required form-label fw-bolder text-dark fs-6">Description</label>
                <!--end::Label-->
                <!--begin::Input-->
                <input type="text" class="form-control form-control-solid" placeholder="" name="description" />
                <!--end::Input-->
                <?php 
                     if(isset($error)) {
                    ?>
                <span class='text-danger'>
                    <?php echo $error ?>
                </span>
                <?php 
                    }
                    ?>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="d-flex flex-column mb-7 fv-row">
                <!--begin::Label-->
                <label class="form-label fw-bolder text-dark fs-6">
                    <span class="required">Type</span>
                    </span>
                </label>
                <!--end::Label-->
                <!--begin::Input-->
                <select name="type" aria-label="Select a Country"
                    data-placeholder="Sélectionnez le type du questionnaire..."
                    data-dropdown-parent="#kt_modal_add_customer" class="form-select form-select-solid fw-bold">
                    <option value="">Sélectionnez le
                        type du questionniare...</option>
                    <option value="Factuel">
                        Factuel
                    </option>
                    <option value="Declaratif">
                        Declaratif
                    </option>
                </select>
                <!--end::Input-->
                <?php 
                     if(isset($error)) {
                    ?>
                <span class='text-danger'>
                    <?php echo $error ?>
                </span>
                <?php 
                    }
                    ?>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="d-flex flex-column mb-7 fv-row">
                <!--begin::Label-->
                <label class="form-label fw-bolder text-dark fs-6">
                    <span class="required">Spécialité</span>
                    </span>
                </label>
                <!--end::Label-->
                <!--begin::Input-->
                <select name="speciality" aria-label="Select a Country" data-control="select2"
                    data-placeholder="Sélectionnez la spécialité..." class="form-select form-select-solid fw-bold">
                    <option>Sélectionnez la
                        spécialité...</option>
                    <option value="Assistance à la Conduite">
                        Assistance à la Conduite
                    </option>
                    <option value="Climatisation">
                        Climatisation
                    </option>
                    <option value="Direction">
                        Direction
                    </option>
                    <option value="Electricité">
                        Electricité
                    </option>
                    <option value="Freinage">
                        Freinage
                    </option>
                    <option value="Hydraulique">
                        Hydraulique
                    </option>
                    <option value="Moteur">
                        Moteur
                    </option>
                    <option value="Multiplexage & Electronique">
                        Multiplexage & Electronique
                    </option>
                    <option value="Pneumatique">
                        Pneumatique
                    </option>
                    <option value="Suspension">
                        Suspension
                    </option>
                    <option value="Transmission">
                        Transmission
                    </option>
                    <option value="Transversale">
                        Transversale
                    </option>
                </select>
                <!--end::Input-->
                <?php 
                     if(isset($error)) {
                    ?>
                <span class='text-danger'>
                    <?php echo $error ?>
                </span>
                <?php 
                    }
                    ?>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="fv-row mb-7">
                <!--begin::Label-->
                <label class="required form-label fw-bolder text-dark fs-6">Nombre
                    de question à répondre</label>
                <!--end::Label-->
                <!--begin::Input-->
                <input type="text" class="form-control form-control-solid" placeholder="" name="number" />
                <!--end::Input-->
                <?php 
                     if(isset($error)) {
                    ?>
                <span class='text-danger'>
                    <?php echo $error ?>
                </span>
                <?php 
                    }
                    ?>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="d-flex flex-column mb-7 fv-row">
                <!--begin::Label-->
                <label class="form-label fw-bolder text-dark fs-6">
                    <span class="required">Niveau</span>
                    </span>
                </label>
                <!--end::Label-->
                <!--begin::Input-->
                <select name="level" aria-label="Select a Country"
                    data-placeholder="Sélectionnez le niveau du questionnaire..."
                    data-dropdown-parent="#kt_modal_add_customer" class="form-select form-select-solid fw-bold">
                    <option value="">Sélectionnez le
                        niveau du questionnaire...</option>
                    <option value="Junior">
                        Junior
                    </option>
                    <option value="Senior">
                        Senior
                    </option>
                    <option value="Expert">
                        Expert
                    </option>
                </select>
                <!--end::Input-->
                <?php 
                     if(isset($error)) {
                    ?>
                <span class='text-danger'>
                    <?php echo $error ?>
                </span>
                <?php 
                    }
                    ?>
            </div>
            <!--end::Input group-->
        </div>
        <!--end::Scroll-->
        <!--end::Modal body-->
        <!--begin::Modal footer-->
        <div class="modal-footer flex-center">
            <!--begin::Button-->
            <button type="submit" name="submit" class=" btn btn-primary">
                <span class="indicator-label">
                    Valider
                </span>
                <span class="indicator-progress">
                    Patientez... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
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
<?php } ?>