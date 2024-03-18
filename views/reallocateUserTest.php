<?php
session_start();

if (!isset($_SESSION["id"])) {
    header("Location: ./index.php");
    exit();
} else {

    require_once "../vendor/autoload.php";

    // Create connection
    $conn = new MongoDB\Client("mongodb://localhost:27017");

    // Connecting in database
    $academy = $conn->academy;

    // Connecting in collections
    $users = $academy->users;
    $vehicles = $academy->vehicles;
    $allocations = $academy->allocations;
    $tests = $academy->tests;

    if (isset($_POST["submit"])) {
        $technician = $_POST["technician"];

        if (!$technician) {
            $error_msg = "Veuillez le technicien à reassigner ses tests.";
        } else {
            $user = $users->findOne([
                '$and' => [
                    ["_id" => new MongoDB\BSON\ObjectId($technician)],
                    ["active" => true],
                ],
            ]);

            $allocate = $allocations->find([
                '$and' => [
                    ["user" => new MongoDB\BSON\ObjectId($technician)],
                    ["active" => true],
                ],
            ]);
            foreach ($allocate as $allocate) {
                $allocations->updateOne(
                    ["_id" => new MongoDB\BSON\ObjectId($technician)],
                    [
                        '$set' => [
                            "active" => false
                        ],
                    ]
                );
            }
            $result = $results->find([
                '$and' => [
                    ["user" => new MongoDB\BSON\ObjectId($technician)],
                    ["active" => true],
                ],
            ]);
            foreach ($result as $result) {
                $result['active'] = false;
                $results->updateOne(
                    ["_id" => new MongoDB\BSON\ObjectId($result->_id)],
                    [
                        '$set' => $result
                    ]
                );
            }
            $success_msg = "Technicien(s) reassigné(s) avec succès";
        }
    }
    ?>

<?php include_once "partials/header.php"; ?>
<!--begin::Title-->
<title>Reassigner Test Technicien | CFAO Mobility Academy</title>
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
                <h1 class="my-3 text-center">Reassigner le précédent test d'un technicien</h1>

                <?php if (isset($success_msg)) { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <center><strong><?php echo $success_msg; ?></strong></center>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php } ?>
                <?php if (isset($error_msg)) { ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <center><strong><?php echo $error_msg; ?></strong></center>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php } ?>

                <form method="POST"><br>
                <!--begin::Input group-->
                <div class="d-flex flex-column mb-7 fv-row">
                    <!--begin::Label-->
                    <label class="form-label fw-bolder text-dark fs-6">
                        <span class="required">Spécialité</span>
                        </span>
                    </label>
                    <!--end::Label-->
                    <!--begin::Input-->
                    <select name="technician" aria-label="Select a Country"
                        data-control="select2" data-placeholder="Sélectionnez le technicien..."
                        class="form-select form-select-solid fw-bold">
                        <option value="">Sélectionnez le technicien...</option>
                        <?php
                        $user = $users->find([
                            '$and' => [
                                ["profile" => "Technicien"],
                                ["active" => true],
                            ],
                        ]);
                        foreach ($user as $user) { ?>
                        <option value='<?php echo $user->_id; ?>'>
                            <?php echo $user->firstName; ?> <?php echo $user->lastName; ?>
                        </option>
                        <?php }
                        ?>
                    </select>
                    <?php if (isset($error)) { ?>
                    <span class='text-danger'>
                        <?php echo $error; ?>
                    </span>
                    <?php } ?>
                    <!--end::Input-->
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
    </div>
    <!--end::Container-->
</div>
<!--end::Post-->
</div>
<!--end::Body-->

<?php include_once "partials/footer.php"; ?>
<?php
} ?>
