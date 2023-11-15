<?php
session_start();

if ( !isset( $_SESSION[ 'id' ] ) ) {
    header( 'Location: ./index.php' );
    exit();
} else {
?>
<?php
require_once '../vendor/autoload.php';

if ( isset( $_POST[ 'submit' ] ) ) {
    // Create connection
    $conn = new MongoDB\Client( 'mongodb://localhost:27017' );

    // Connecting in database
    $academy = $conn->academy;

    // Connecting in collections
    $questions = $academy->questions;
    $quizzes = $academy->quizzes;
    $allocations = $academy->allocations;


    $filePath = $_FILES['excel']['tmp_name'];
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
    $data = $spreadsheet->getActiveSheet()->toArray();

    foreach ($data as $row) {
        $label = $row["0"];
        $proposal1 = $row["1"];
        $proposal2 = $row["2"];
        $proposal3 = $row["3"];
        $proposal4 = $row["4"];
        $answer = $row["5"];
        $speciality = $row["6"];
        $type = $row["7"];
        $level = $row["8"];
        $quiz = $row["9"];
        $image = $row["10"];
        
        $exist = $questions->findOne( [ [ 'label' => $label ] ] );
        if ( $exist) {
            $error_msg = 'Cette question existe déjà.';
        } else {
            $question = [
                'image' => $image,
                'label' => ucfirst( $label ),
                'proposal1' => ucfirst( $proposal1 ),
                'proposal2' => ucfirst( $proposal2 ),
                'proposal3' => ucfirst( $proposal3 ),
                'proposal4' => ucfirst( $proposal4 ),
                'answer' => ucfirst( $answer ),
                'speciality' => ucfirst( $speciality ),
                'type' => $type,
                'level' => $level,
                'active' =>true,
                'created' => date("d-m-y")
            ];
            
            $result = $questions->insertOne($question);
            $quizz = $quizzes->findOne([
                '$and' => [
                    ['label' => $quiz], ['level' => $level]
                ]
            ]);
            $quizzes->updateOne(
                ['_id' => new MongoDB\BSON\ObjectId( $quizz->_id )],
                ['$push' => ['questions' => new MongoDB\BSON\ObjectId( $result->getInsertedId() )]]
            );
            $quizz->total++;
            $allocation = [
                "quiz" => new MongoDB\BSON\ObjectId( $quizz->_id ),
                "question" => new MongoDB\BSON\ObjectId( $result->getInsertedId() ),
                "type" => "Question dans questionnaire",
                'active' =>true,
                'created' => date("d-m-y")
            ];
            $allocations->insertOne($allocation);
            $success_msg = "Questions ajoutés avec succès";
        }
    }
}
?>
<?php
include_once 'partials/header.php'
?>
<!--begin::Title-->
<title>Importe Questions | CFAO Mobility Academy</title>
<!--end::Title-->

<!--begin::Modal body-->
<div class="container mt-5 w-50">
    <img src="../public/images/logo.png" alt="10" height="170"
        style="display: block; margin-left: auto; margin-right: auto; width: 50%;">
    <h1 class="my-3 text-center">Importer des questions</h1>

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

    <form enctype="multipart/form-data" method="POST"><br>
        <!--begin::Input group-->
        <div class="fv-row mb-7">
            <!--begin::Label-->
            <label class="required form-label fw-bolder text-dark fs-6">Importer des questions via Excel</label>
            <!--end::Label-->
            <!--begin::Input-->
            <input type="file" class="form-control form-control-solid" placeholder="" name="excel" />
            <!--end::Input-->
        </div>
        <!--end::Input group-->
        <div class="modal-footer flex-center">
            <!--begin::Button-->
            <button type="submit" name="submit" class="btn btn-primary">
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