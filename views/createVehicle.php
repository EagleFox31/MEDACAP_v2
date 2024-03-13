<?php
session_start();

if (!isset($_SESSION["id"])) {
    header("Location: ./index.php");
    exit();
} else {
    if (isset($_POST["submit"])) {
        require_once "../vendor/autoload.php";

        // Create connection
        $conn = new MongoDB\Client("mongodb://localhost:27017");

        // Connecting in database
        $academy = $conn->academy;

        // Connecting in collections
        $users = $academy->users;
        $vehicles = $academy->vehicles;
        $quizzes = $academy->quizzes;
        $allocations = $academy->allocations;

        $label = $_POST["label"];
        $brand = strtoupper($_POST["brand"]);
        $speciality = $_POST["speciality"];

        $facJu = [];
        $facSe = [];
        $facEx = [];
        $declaJu = [];
        $declaSe = [];
        $declaEx = [];

        $exist = $vehicles->findOne([
            '$and' => [["label" => $label], ["brand" => $brand]],
        ]);

        if ($exist) {
            $error_msg = "Ce véhicule existe déjà";
        } else {
            for ($i = 0; $i < count($speciality); $i++) {
                $quizJuFac = $quizzes->findOne([
                    '$and' => [
                        ["speciality" => $speciality[$i]],
                        ["type" => "Factuel"],
                        ["level" => "Junior"],
                        ["active" => true],
                    ],
                ]);
                if ($quizJuFac) {
                    array_push($facJu, $quizJuFac->_id);
                }
            }
            for ($i = 0; $i < count($speciality); $i++) {
                $quizJuDecla = $quizzes->findOne([
                    '$and' => [
                        ["speciality" => $speciality[$i]],
                        ["type" => "Declaratif"],
                        ["level" => "Junior"],
                        ["active" => true],
                    ],
                ]);
                if ($quizJuDecla) {
                    array_push($declaJu, $quizJuDecla->_id);
                }
            }
            for ($i = 0; $i < count($speciality); $i++) {
                $quizSeFac = $quizzes->findOne([
                    '$and' => [
                        ["speciality" => $speciality[$i]],
                        ["type" => "Factuel"],
                        ["level" => "Senior"],
                        ["active" => true],
                    ],
                ]);
                if ($quizSeFac) {
                    array_push($facSe, $quizSeFac->_id);
                }
            }
            for ($i = 0; $i < count($speciality); $i++) {
                $quizSeDecla = $quizzes->findOne([
                    '$and' => [
                        ["speciality" => $speciality[$i]],
                        ["type" => "Declaratif"],
                        ["level" => "Senior"],
                        ["active" => true],
                    ],
                ]);
                if ($quizSeDecla) {
                    array_push($declaSe, $quizSeDecla->_id);
                }
            }
            for ($i = 0; $i < count($speciality); $i++) {
                $quizExFac = $quizzes->findOne([
                    '$and' => [
                        ["speciality" => $speciality[$i]],
                        ["type" => "Factuel"],
                        ["level" => "Expert"],
                        ["active" => true],
                    ],
                ]);
                if ($quizExFac) {
                    array_push($facEx, $quizExFac->_id);
                }
            }
            for ($i = 0; $i < count($speciality); $i++) {
                $quizExDecla = $quizzes->findOne([
                    '$and' => [
                        ["speciality" => $speciality[$i]],
                        ["type" => "Declaratif"],
                        ["level" => "Expert"],
                        ["active" => true],
                    ],
                ]);
                if ($quizExDecla) {
                    array_push($declaEx, $quizExDecla->_id);
                }
            }

            $vehicleJuFac = [
                "users" => [],
                "quizzes" => $facJu,
                "label" => $label,
                "type" => "Factuel",
                "brand" => $brand,
                "level" => "Junior",
                "total" => count($facJu),
                "test" => false,
                "active" => true,
                "created" => date("d-m-Y"),
            ];
            $vehicles->insertOne($vehicleJuFac);

            $vehicleSeFac = [
                "users" => [],
                "quizzes" => $facSe,
                "label" => $label,
                "type" => "Factuel",
                "brand" => $brand,
                "level" => "Senior",
                "total" => count($facSe),
                "test" => false,
                "active" => true,
                "created" => date("d-m-Y"),
            ];
            $vehicles->insertOne($vehicleSeFac);

            $vehicleExFac = [
                "users" => [],
                "quizzes" => $facEx,
                "label" => $label,
                "type" => "Factuel",
                "brand" => $brand,
                "level" => "Expert",
                "total" => count($facEx),
                "test" => false,
                "active" => true,
                "created" => date("d-m-Y"),
            ];
            $vehicles->insertOne($vehicleExFac);

            $vehicleJuDecla = [
                "users" => [],
                "quizzes" => $declaJu,
                "label" => $label,
                "type" => "Factuel",
                "brand" => $brand,
                "level" => "Junior",
                "total" => count($declaJu),
                "test" => false,
                "active" => true,
                "created" => date("d-m-Y"),
            ];
            $vehicles->insertOne($vehicleJuDecla);

            $vehicleSeDecla = [
                "users" => [],
                "quizzes" => $declaSe,
                "label" => $label,
                "type" => "Factuel",
                "brand" => $brand,
                "level" => "Senior",
                "total" => count($declaSe),
                "test" => false,
                "active" => true,
                "created" => date("d-m-Y"),
            ];
            $vehicles->insertOne($vehicleSeDecla);

            $vehicleExDecla = [
                "users" => [],
                "quizzes" => $declaEx,
                "label" => $label,
                "type" => "Factuel",
                "brand" => $brand,
                "level" => "Expert",
                "total" => count($declaEx),
                "test" => false,
                "active" => true,
                "created" => date("d-m-Y"),
            ];
            $vehicles->insertOne($vehicleExDecla);

            $success_msg = "Véhicule ajouté avec succès";
        }
    } ?>
<?php include_once "partials/header.php"; ?>

<!--begin::Title-->
<title>Ajouter Véhicule | CFAO Mobility Academy</title>
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
                <h1 class='my-3 text-center'>Ajouter un véhicule</h1>

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
                    <div class="row fv-row mb-7">
                        <!--begin::Input group-->
                        <div class="d-flex flex-column mb-7 fv-row">
                            <!--begin::Label-->
                            <label class="form-label fw-bolder text-dark fs-6">
                                <span class="required">Type de véhicule</span>
                            </label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type='text' class='form-control form-control-solid' placeholder='' name='label'
                            <?php if (isset($_POST["submit"])) {
                                echo 'value="' . $label . '"';
                            } ?> />
                            <!--end::Input-->
                            <?php if (isset($error)) { ?>
                                    <span class='text-danger'>
                                        <?php echo $error; ?>
                                    </span>
                            <?php } ?>
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="d-flex flex-column mb-7 fv-row">
                            <!--begin::Label-->
                            <label class="form-label fw-bolder text-dark fs-6">
                                <span class="required">Marque du véhicule</span>
                            </label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type='text' class='form-control form-control-solid' placeholder='' name='brand'
                            <?php if (isset($_POST["submit"])) {
                                echo 'value="' . $brand . '"';
                            } ?> />
                            <!--end::Input-->
                            <?php if (isset($error)) { ?>
                                    <span class='text-danger'>
                                        <?php echo $error; ?>
                                    </span>
                            <?php } ?>
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
                                <select name="speciality[]" multiple aria-label="Select a Country" data-control="select2"
                                    data-placeholder="Sélectionnez la spécialité..."
                                    class="form-select form-select-solid fw-bold">
                                    <option>Sélectionnez la
                                        spécialité...</option>
                                    <option value="Arbre de Transmission">
                                        Arbre de Transmission
                                    </option>
                                    <option value="Assistance à la Conduite">
                                        Assistance à la Conduite
                                    </option>
                                    <option value="Boite de Transfert">
                                        Boite de Transfert
                                    </option>
                                    <option value="Boite de Vitesse">
                                        Boite de Vitesse
                                    </option>
                                    <option value="Boite de Vitesse Automatique">
                                        Boite de Vitesse Automatique
                                    </option>
                                    <option value="Boite de Vitesse Mécanique">
                                        Boite de Vitesse Mécanique
                                    </option>
                                    <option value="Boite de Vitesse à Variation Continue">
                                        Boite de Vitesse à Variation Continue
                                    </option>
                                    <option value="Climatisation">
                                        Climatisation
                                    </option>
                                    <option value="Demi Arbre de Roue">
                                        Demi Arbre de Roue
                                    </option>
                                    <option value="Direction">
                                        Direction
                                    </option>
                                    <option value="Electricité et Electronique">
                                        Electricité & Electronique
                                    </option>
                                    <option value="Freinage">
                                        Freinage
                                    </option>
                                    <option value="Freinage Hydraulique">
                                        Freinage Hydraulique
                                    </option>
                                    <option value="Freinage Pneumatique">
                                        Freinage Pneumatique
                                    </option>
                                    <option value="Hydraulique">
                                        Hydraulique
                                    </option>
                                    <option value="Moteur Diesel">
                                        Moteur Diesel
                                    </option>
                                    <option value="Moteur Electrique">
                                        Moteur Electrique
                                    </option>
                                    <option value="Moteur Essence">
                                        Moteur Essence
                                    </option>
                                    <option value="Moteur Thermique">
                                        Moteur Thermique
                                    </option>
                                    <option value="Multiplexage">
                                        Multiplexage
                                    </option>
                                    <option value="Pneumatique">
                                        Pneumatique
                                    </option>
                                    <option value="Pont">
                                        Pont
                                    </option>
                                    <option value="Réducteur">
                                        Réducteur
                                    </option>
                                    <option value="Suspension">
                                        Suspension
                                    </option>
                                    <option value="Suspension à Lame">
                                        Suspension à Lame
                                    </option>
                                    <option value="Suspension Ressort">
                                        Suspension Ressort
                                    </option>
                                    <option value="Suspension Pneumatique">
                                        Suspension Pneumatique
                                    </option>
                                    <option value="Transversale">
                                        Transversale
                                    </option>
                                </select>
                            <!--end::Input-->
                            <?php if (isset($error)) { ?>
                            <span class='text-danger'>
                                <?php echo $error; ?>
                            </span>
                            <?php } ?>
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
<?php include_once "partials/footer.php"; ?>
<?php
} ?>
