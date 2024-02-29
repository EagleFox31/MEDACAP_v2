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
    $quizzes = $academy->quizzes;
    $allocations = $academy->allocations;

if ( isset( $_POST[ 'submit' ] ) ) {
    $level = $_POST[ 'level' ];

    $allocates = $allocations->find([ 'level' => $level ])->toArray();

    foreach ($allocates as $allocate) {
        if ($allocate->activeTest == false) {
            $allocate->activeTest = true;

            $allocations->updateOne(
                [ '_id' => new MongoDB\BSON\ObjectId( $allocate['_id'] ) ],
                [ '$set' => $allocate ]
            );
        }
    }
    
    $success_msg = 'Tests du niveau '.$level.' disponible';
}

?>
<?php
include_once 'partials/header.php'
?>

<!--begin::Title-->
<title>Activation des Tests | CFAO Mobility Academy</title>
<!--end::Title-->


<!--begin::Body-->
<div class="content fs-6 d-flex flex-column flex-column-fluid" id="kt_content"
    data-select2-id="select2-data-kt_content">
    <!--begin::Post-->
    <div class="post fs-6 d-flex flex-column-fluid" id="kt_post" data-select2-id="select2-data-kt_post">
        <!--begin::Container-->
        <div class=" container-xxl " data-select2-id="select2-data-194-27hh">
            <!--begin::Modal body-->
            <div class='container mt-5 w-50'>
                <img src='../public/images/logo.png' alt='10' height='170'
                    style='display: block; margin-left: auto; margin-right: auto; width: 50%;'>
                <h1 class='my-3 text-center'>Activation des tests</h1>

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
                        <div class="d-flex flex-column mb-7 fv-row">
                            <!--begin::Label-->
                            <label class="form-label fw-bolder text-dark fs-6">
                                <span class="required">Niveau</span>
                                </span>
                            </label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <select name="level" aria-label="Select a Country"
                                data-placeholder="Sélectionnez le niveau..."
                                data-dropdown-parent="#kt_modal_add_customer"
                                class="form-select form-select-solid fw-bold">
                                <option value="">Sélectionnez le
                                    niveau...</option>
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