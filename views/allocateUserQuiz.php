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
$quizzes = $academy->quizzes;
$allocations = $academy->allocations;

if ( isset( $_POST[ 'submit' ] ) ) {
    $quiz = $_POST['quiz'];
    $technicians[] = $_POST['technicians'];
    $start = $_POST['start'];
    $end = $_POST['end'];
    
    $member = $quizzes->findOne([
        '$and' => [
            ['users' => ['$in' => $technicians]],
            ['_id' => new MongoDB\BSON\ObjectId($quiz)],
            ['active' => true]
        ]
    ]);
    
    $quizz = $quizzes->findOne(['_id' => new MongoDB\BSON\ObjectId($quiz)]);

    if (!$quiz || !$technicians || !$start || !$end) {
        $error_msg = 'Veuillez remplir tous les champs.';
    } elseif ($member) {
            $error_msg = 'Le technicien ' . $member->firstName . ' ' . $member->lastName . ' est déjà affecté à ce questionnaire.';
    } elseif ($end < $start) {
        $error_msg = 'La date de fin doit être strictement supérieure à la date de début.';
    } else {
        for ($i = 0; $i < count($technicians); $i++) {
            $allocateExist = $allocations->findOne([
                '$and' => [
                    ['user' => new MongoDB\BSON\ObjectId($technicians[$i])],
                    ['quiz' => new MongoDB\BSON\ObjectId($quiz)]
                ]
            ]);
        
            if ($allocateExist) {
                $allocateExist->active = true;
                $allocateExist->period->start = $start;
                $allocateExist->period->end = $end;
                $allocateExist->save();
                $quizz->users->addToSet($technicians[$i]);
                $success_msg = 'Technicien(s) affecté(s) avec succès';
            } elseif ($quizz->type == 'Declaratif') {
                $allocate = [
                    'quiz' => new MongoDB\BSON\ObjectId($quiz),
                    'user' => new MongoDB\BSON\ObjectId($technicians[$i]),
                    'type' => 'Technicien dans questionnaire',
                    'typeQuiz' => $quizz->type,
                    'levelQuiz' => $quizz->level,
                    'period' => [
                        'start' => date( 'd-m-Y', strtotime($start)),
                        'end' => date( 'd-m-Y', strtotime($end)),
                    ],
                    "managerQuiz" => false,
                    "active" => true,
                    'created' => date("d-m-y")

                ];
            
                $quizzes->updateOne(
                    ['_id' => new MongoDB\BSON\ObjectId($quiz)],
                    ['$addToSet' => ['users' => new MongoDB\BSON\ObjectId($technicians[$i])]]
                );
                $allocations->insertOne($allocate);
                $success_msg = 'Technicien(s) affecté(s) avec succès';
            } else {
                $allocate = [
                    'quiz' => new MongoDB\BSON\ObjectId($quiz),
                    'user' => new MongoDB\BSON\ObjectId($technicians[$i]),
                    'type' => 'Technicien dans questionnaire',
                    'typeQuiz' => $quizz->type,
                    'levelQuiz' => $quizz->level,
                    'period' => [
                        'start' => date( 'd-m-Y', strtotime($start)),
                        'end' => date( 'd-m-Y', strtotime($end)),
                    ],
                    "active" => true,
                    'created' => date("d-m-y")
                ];
            
                $quizzes->updateOne(
                    ['_id' => new MongoDB\BSON\ObjectId($quiz)],
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
<title>Affecter un Technicien | CFAO Mobility Academy</title>
<!--end::Title-->

<!--begin::Modal body-->
<div class="container mt-5 w-50">
    <img src="../public/images/logo.png" alt="10" height="170"
        style="display: block; margin-left: auto; margin-right: auto; width: 50%;">
    <h1 class="my-3 text-center">Affecter un technicien à un questionnaire</h1>

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
                        <span class="required">Questionnaire</span>

                        <span class="ms-1" data-bs-toggle="tooltip" title="Veuillez choisir le questionnaire">
                            <i class="ki-duotone ki-information fs-7"><span class="path1"></span><span
                                    class="path2"></span><span class="path3"></span></i> </span>
                    </label>
                    <!--end::Label-->
                    <!--begin::Input-->
                    <select name="quiz" aria-label="Select a Country" data-control="select2"
                        data-placeholder="Sélectionnez le questionnaire..."
                        class="form-select form-select-solid fw-bold">
                        <option value="">Sélectionnez le questionnaire...
                        </option>
                        <?php
                            $quiz = $quizzes->find(['active' => true]);
                            foreach ($quiz as $quiz) {
                        ?>
                        <option value='<?php echo $quiz->_id ?>'>
                            <?php echo $quiz->label ?>
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
                        data-placeholder="Sélectionnez le technicien..." class="form-select form-select-solid fw-bold">
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
        <div class="row fv-row mb-7">
            <div class="col-xl-6">
                <div class="fv-row mb-15">
                    <!--begin::Label-->
                    <label class="required form-label fw-bolder text-dark fs-6">Date
                        de début</label>
                    <!--end::Label-->
                    <!--begin::Input-->
                    <input type="date" class="form-control form-control-solid" placeholder="" name="start" />
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
                <div class="fv-row mb-15">
                    <!--begin::Label-->
                    <label class="required form-label fw-bolder text-dark fs-6">Date
                        de
                        fin</label>
                    <!--end::Label-->
                    <!--begin::Input-->
                    <input type="date" class="form-control form-control-solid" placeholder="" name="end" />
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
                <!--end::Col-->
            </div>
            <!--end::Input group-->
            <div class="text-center" style="margin-bottom: 50px;">
                <button type="submit" name="submit" class="btn btn-lg btn-primary">
                    Valider
                </button>
            </div>
    </form>
</div>
<!--end::Modal body-->

<?php
include_once 'partials/footer.php'
?>
<?php } ?>