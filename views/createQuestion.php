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
$questions = $academy->questions;
$quizzes = $academy->quizzes;
$allocations = $academy->allocations;

if ( isset( $_POST[ 'submit' ] ) ) {
    $label = $_POST[ 'label' ];
    $proposal1 = $_POST[ 'proposal1' ];
    $proposal2 = $_POST[ 'proposal2' ];
    $proposal3 = $_POST[ 'proposal3' ];
    $proposal4 = $_POST[ 'proposal4' ];
    $answer = $_POST[ 'answer' ];
    $speciality = $_POST[ 'speciality' ];
    $type = $_POST[ 'type' ];
    $level = $_POST[ 'level' ];
    $quiz = $_POST[ 'quiz' ];
    $image = $_FILES[ 'image' ][ 'name' ];
    $tmp_name = $_FILES[ 'image' ][ 'tmp_name' ];
    $folder = '../public/files/'.$image;
    move_uploaded_file( $tmp_name, $folder );

    $exist = $questions->findOne( [ 'label' => $label ] );
    
    if ( empty( $label ) ||
    empty( $proposal1 ) ||
    empty( $type ) ||
    empty( $proposal2 ) ||
    empty( $level ) ||
    empty( $speciality ) ) {
        $error = 'Champ obligatoire';
    } elseif ( $exist && $exist->active == true ) {
        $error_msg = 'Cette question existe déjà.';
    } elseif ( empty( $image ) ) {
        $quizz = $quizzes->findOne( [ '_id' => new MongoDB\BSON\ObjectId( $quiz ) ] );
        $question = [
            'label' => ucfirst( $label ),
            'proposal1' => ucfirst( $proposal1 ),
            'proposal2' => ucfirst( $proposal2 ),
            'proposal3' => ucfirst( $proposal3 ),
            'proposal4' => ucfirst( $proposal4 ),
            'answer' => ucfirst( $answer ),
            'level' => ucfirst( $level ),
            'speciality' => ucfirst( $speciality ),
            'type' => $type,
            'active' =>true
        ];
        $results = $questions->insertOne( $question );
        $quizzes->updateOne(
            [ '_id' => new MongoDB\BSON\ObjectId( $quiz ) ],
            [ '$push' => [ 'questions' => new MongoDB\BSON\ObjectId( $results->getInsertedId() ) ] ]
        );
        $quizz->total++;
        $quizzes->updateOne(
            [ '_id' => new MongoDB\BSON\ObjectId( $quiz ) ],
            [ '$set' => $quizz ]
        );

        $allocation = [
            'quiz' =>  new MongoDB\BSON\ObjectId( $quiz ),
            'question' =>  new MongoDB\BSON\ObjectId( $results->getInsertedId() ),
            'type' => 'Question dans questionnaire',
            'active' =>true
        ];
        $result = $allocations->insertOne( $allocation );
        $success_msg = 'Question ajoutée avec succès';
    } else {
        $question = [
            'image' => $image,
            'label' => ucfirst( $label ),
            'proposal1' => ucfirst( $proposal1 ),
            'proposal2' => ucfirst( $proposal2 ),
            'proposal3' => ucfirst( $proposal3 ),
            'proposal4' => ucfirst( $proposal4 ),
            'answer' => ucfirst( $answer ),
            'level' => ucfirst( $level ),
            'speciality' => ucfirst( $speciality ),
            'type' => $type,
            'active' =>true
        ];
        $results = $questions->insertOne( $question );
        $quizzes->updateOne(
            [ '_id' => new MongoDB\BSON\ObjectId( $quiz ) ],
            [ '$push' => [ 'questions' => new MongoDB\BSON\ObjectId( $results->getInsertedId() ) ] ]
        );
        $quizz->total++;
        $quizzes->updateOne(
            [ '_id' => new MongoDB\BSON\ObjectId( $quiz ) ],
            [ '$set' => $quizz ]
        );

        $allocation = [
            'quiz' => new MongoDB\BSON\ObjectId( $quiz ),
            'question' => new MongoDB\BSON\ObjectId( $results->getInsertedId() ),
            'type' => 'Question dans questionnaire',
            'active' =>true
        ];
        $allocations->insertOne( $allocation );
        $success_msg = 'Question ajoutée avec succès';
    }
}
?>
<?php
include_once 'partials/header.php'
?>
<!--begin::Title-->
<title>Ajouter Question | CFAO Mobility Academy</title>
<!--end::Title-->

<!--begin::Modal body-->
<div class='container mt-5 w-50'>
    <img src='../public/images/logo.png' alt='10' height='170'
        style='display: block; margin-left: auto; margin-right: auto; width: 50%;'>
    <h1 class='my-3 text-center'>Ajouter une question</h1>

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

    <form enctype='multipart/form-data' method='POST'>
        <br>
        <!--begin::Input group-->
        <div class='fv-row mb-7'>
            <!--begin::Label-->
            <label class='required form-label fw-bolder text-dark fs-6'>Question</label>
            <!--end::Label-->
            <!--begin::Input-->
            <input type='text' class='form-control form-control-solid' placeholder='' name='label' />
            <!--end::Input-->
            <?php
if ( isset( $error ) ) {
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
        <div class='fv-row mb-7'>
            <!--begin::Label-->
            <label class='required form-label fw-bolder text-dark fs-6'>Image</label>
            <!--end::Label-->
            <!--begin::Input-->
            <input type='file' class='form-control form-control-solid' placeholder='' name='image' />
            <!--end::Input-->
        </div>
        <!--end::Input group-->
        <!--begin::Input group-->
        <div class='d-flex flex-column mb-7 fv-row'>
            <!--begin::Label-->
            <label class='form-label fw-bolder text-dark fs-6'>
                <span class='required'>Type</span>
                </span>
            </label>
            <!--end::Label-->
            <!--begin::Input-->
            <select name='type' onchange='selectif()' aria-label='Select a Country' data-control='select2'
                data-placeholder='Sélectionnez votre sexe...' class='form-select form-select-solid fw-bold'>
                <option>Sélectionnez le type de
                    question...</option>
                <option value='Declaratif'>
                    Declaratif
                </option>
                <option value='Factuel'>
                    Factuel
                </option>
            </select>
            <!--end::Input-->
            <?php
if ( isset( $error ) ) {
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
        <div class='row g-9 mb-7'>
            <!--begin::Col-->
            <div class='col-md-6 fv-row'>
                <!--begin::Label-->
                <label class='required form-label fw-bolder text-dark fs-6'>Proposition
                    1</label>
                <!--end::Label-->
                <!--begin::Input-->
                <input class='form-control form-control-solid' placeholder='' name='proposal1' />
                <!--end::Input-->
                <?php
if ( isset( $error ) ) {
    ?>
                <span class='text-danger'>
                    <?php echo $error ?>
                </span>
                <?php
}
?>
            </div>
            <!--end::Col-->
            <!--begin::Col-->
            <div class='col-md-6 fv-row'>
                <!--begin::Label-->
                <label class='required form-label fw-bolder text-dark fs-6'>Proposition
                    2</label>
                <!--end::Label-->
                <!--begin::Input-->
                <input class='form-control form-control-solid' placeholder='' name='proposal2' />
                <!--end::Input-->
                <?php
if ( isset( $error ) ) {
    ?>
                <span class='text-danger'>
                    <?php echo $error ?>
                </span>
                <?php
}
?>
            </div>
            <!--end::Col-->
        </div>
        <!--end::Input group-->
        <!--begin::Input group-->
        <div class='row g-9 mb-7' id='prop'>
            <!--begin::Col-->
            <div class='col-md-6 fv-row'>
                <!--begin::Label-->
                <label class='form-label fw-bolder text-dark fs-6'>Proposition
                    3</label>
                <!--end::Label-->
                <!--begin::Input-->
                <input class='form-control form-control-solid' placeholder='' name='proposal3' />
                <!--end::Input-->
            </div>
            <!--end::Col-->
            <!--begin::Col-->
            <div class='col-md-6 fv-row'>
                <!--begin::Label-->
                <label class='form-label fw-bolder text-dark fs-6'>Proposition
                    4</label>
                <!--end::Label-->
                <!--begin::Input-->
                <input class='form-control form-control-solid' placeholder='' name='proposal4' />
                <!--end::Input-->
            </div>
            <!--end::Col-->
        </div>
        <!--end::Input group-->
        <!--begin::Input group-->
        <div class='fv-row mb-7' id='answer'>
            <!--begin::Label-->
            <label class='form-label fw-bolder text-dark fs-6'>Réponses</label>
            <!--end::Label-->
            <!--begin::Input-->
            <input type='text' class='form-control form-control-solid' placeholder='' name='answer' />
            <!--end::Input-->
        </div>
        <!--end::Input group-->
        <!--begin::Input group-->
        <div class='d-flex flex-column mb-7 fv-row'>
            <!--begin::Label-->
            <label class='form-label fw-bolder text-dark fs-6'>
                <span class='required'>Spécialité</span>
                </span>
            </label>
            <!--end::Label-->
            <!--begin::Input-->
            <select name='speciality' aria-label='Select a Country' data-control='select2'
                data-placeholder='Sélectionnez la spécialité...' class='form-select form-select-solid fw-bold'>
                <option>Sélectionnez la
                    spécialité...</option>
                <option value='Assistance à la Conduite'>
                    Assistance à la Conduite
                </option>
                <option value='Climatisation'>
                    Climatisation
                </option>
                <option value='Direction'>
                    Direction
                </option>
                <option value='Electricité'>
                    Electricité
                </option>
                <option value='Freinage'>
                    Freinage
                </option>
                <option value='Hydraulique'>
                    Hydraulique
                </option>
                <option value='Moteur'>
                    Moteur
                </option>
                <option value='Multiplexage & Electronique'>
                    Multiplexage & Electronique
                </option>
                <option value='Pneumatique'>
                    Pneumatique
                </option>
                <option value='Suspension'>
                    Suspension
                </option>
                <option value='Transmission'>
                    Transmission
                </option>
                <option value='Transversale'>
                    Transversale
                </option>
            </select>
            <!--end::Input-->
            <?php
if ( isset( $error ) ) {
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
        <div class='d-flex flex-column mb-7 fv-row'>
            <!--begin::Label-->
            <label class='form-label fw-bolder text-dark fs-6'>
                <span class='required'>Niveau</span>
                </span>
            </label>
            <!--end::Label-->
            <!--begin::Input-->
            <select name='level' aria-label='Select a Country'
                data-placeholder='Sélectionnez le niveau du questionnaire...'
                data-dropdown-parent='#kt_modal_add_customer' class='form-select form-select-solid fw-bold'>
                <option value=''>Sélectionnez le
                    niveau de la question...</option>
                <option value='Junior'>
                    Junior
                </option>
                <option value='Senior'>
                    Senior
                </option>
                <option value='Expert'>
                    Expert
                </option>
            </select>
            <!--end::Input-->
            <?php
if ( isset( $error ) ) {
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
        <div class='d-flex flex-column mb-7 fv-row'>
            <!--begin::Label-->
            <label class='form-label fw-bolder text-dark fs-6'>
                <span>Questionnaire</span>

                <span class='ms-1' data-bs-toggle='tooltip' title='Choississez le questionnaire pour cette question'>
                    <i class='ki-duotone ki-information fs-7'><span class='path1'></span><span
                            class='path2'></span><span class='path3'></span></i>
                </span>
            </label>
            <!--end::Label-->
            <!--begin::Input-->
            <select name='quiz' aria-label='Select a Country' data-control='select2'
                data-placeholder='Sélectionnez le questionnaire...' class='form-select form-select-solid fw-bold'>
                <option value=''>Sélectionnez le
                    questionnaire...</option>
                <?php
$quiz = $quizzes->find( [ 'active' => true ] );
foreach ( $quiz as $quiz ) {
    ?>
                <option value='<?php echo $quiz->_id ?>'>
                    <?php echo $quiz->label ?>
                </option>
                <?php }
    ?>
            </select>
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

<script>
const prop = document.querySelector('#prop')
const answer = document.querySelector('#answer')

function selectif() {
    const type = document.querySelector("select[name='type']").value
    console.log(type)
    if (type == 'Declaratif') {
        prop.classList.add('hidden')
        answer.classList.add('hidden')
    } else {
        prop.classList.remove('hidden')
        answer.classList.remove('hidden')
    }
}
</script>

<?php
    include_once 'partials/footer.php'
    ?>
<?php } ?>