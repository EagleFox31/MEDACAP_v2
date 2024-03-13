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
    $allocations = $academy->allocations;

    $id = $_SESSION["id"];

    $user = $users->findOne([
        '$and' => [
            ["_id" => new MongoDB\BSON\ObjectId($id)],
            ["active" => true],
        ],
    ]);
    ?>
<?php
if (isset($_POST["update"])) {
    $id = $_POST["userID"];
    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $matricule = $_POST["matricule"];
    $username = $_POST["username"];
    $subsidiary = $_POST["subsidiary"];
    $department = $_POST["department"];
    $role = $_POST["role"];
    $gender = $_POST["gender"];
    $country = $_POST["country"];
    $level = $_POST["level"];
    $certificate = $_POST["certificate"];
    $speciality = $_POST["speciality"];
    $birthdate = date("d-m-Y", strtotime($_POST["birthdate"]));
    $recrutmentDate = date("d-m-Y", strtotime($_POST["recrutmentDate"]));
    $person = [
        "username" => $username,
        "matricule" => $matricule,
        "firstName" => ucfirst($firstName),
        "lastName" => ucfirst($lastName),
        "email" => $email,
        "phone" => $phone,
        "gender" => $gender,
        "level" => $level,
        "country" => $country,
        "birthdate" => $birthdate,
        "recrutmentDate" => $recrutmentDate,
        "certificate" => ucfirst($certificate),
        "subsidiary" => ucfirst($subsidiary),
        "speciality" => ucfirst($speciality),
        "department" => ucfirst($department),
        "role" => ucfirst($role),
        "updated" => date("d-m-Y"),
    ];
    $users->updateOne(
        ["_id" => new MongoDB\BSON\ObjectId($id)],
        ['$set' => $person]
    );
    $success_msg = "Collaborateur modifié avec succes.";
}
if (isset($_POST["brand"])) {
    $id = $_POST["userID"];

    $brand = $_POST["brand"];
    $users->updateOne(
        ["_id" => new MongoDB\BSON\ObjectId($id)],
        [
            '$set' => [
                "brand" => $brand,
            ],
        ]
    );
    $success_msg = "Collaborateur modifié avec succes.";
}
if (isset($_POST["password"])) {
    // Password modification
    $id = $_POST["userID"];
    $password = $_POST["password"]; // Check if the password contains at least 8 characters, including at least one uppercase letter, one lowercase letter, and one special character.
    if (
        preg_match(
            '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{6,}$/',
            $password
        )
    ) {
        $error =
            "Le mot de passe doit être au moins de six caractères contenir au moins un chiffre, une lettre majiscule";
    } else {
        $password_hash = sha1($password);
        $users->updateOne(
            ["_id" => new MongoDB\BSON\ObjectId($id)],
            ['$set' => ["password" => $password_hash]]
        );
        $success_msg = "Collaborateur modifié avec succes.";
    }
}
if (isset($_POST["delete"])) {
    $id = $_POST["userID"];
    $member = $users->findOne(["_id" => new MongoDB\BSON\ObjectId($id)]);
    $member["active"] = false;
    $users->updateOne(
        ["_id" => new MongoDB\BSON\ObjectId($id)],
        ['$set' => $member]
    );
    if ($member["profile"] == "Technicien") {
        $success_msg = "Technicien supprimé avec succès";
    } elseif ($member["profile"] == "Manager") {
        $success_msg = "Manager supprimé avec succès";
    } elseif ($member["profile"] == "Admin") {
        $success_msg = "Administrateur supprimé avec succès";
    }
}
?>
<?php include_once "partials/header.php"; ?>
<!--begin::Title-->
<title>Mes Informations | CFAO Mobility Academy</title>
<!--end::Title-->


<!--begin::Content-->
<div class="content fs-6 d-flex flex-column flex-column-fluid" id="kt_content">
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
    <!--begin::Toolbar-->
    <div class="toolbar" id="kt_toolbar">
        <div class="container-fluid d-flex flex-stack flex-wrap flex-sm-nowrap">
            <!--begin::Info-->
            <div class="d-flex flex-column align-items-start justify-content-center flex-wrap me-2">
                <!--begin::Title-->
                <h1 class="text-dark fw-bolder my-1 fs-2">
                    Mes informations
                </h1>
                <!--end::Title-->
            </div>
            <!--end::Info-->
        </div>
    </div>
    <!--end::Toolbar-->
      <!--begin::Post-->
      <div class="post fs-6 d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div class=" container-xxl ">
          <!--begin::Layout Builder Notice-->
          <div class="card mb-10">
            <!--begin::Navbar-->
            <div class="card mb-5 mb-xl-10">
              <div class="card-body pt-9 pb-0">
                <!--begin::Details-->
                <div class="d-flex flex-wrap flex-sm-nowrap mb-3">
                  <!--begin: Pic-->
                  <div class="me-7 mb-4">
                    <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                      <img src="../public/assets/media/avatars/300-1.jpg" alt="image" />
                      <div class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-success rounded-circle border border-4 border-body h-20px w-20px"></div>
                    </div>
                  </div>
                  <!--end::Pic-->

                  <!--begin::Info-->
                  <div class="flex-grow-1">
                    <!--begin::Title-->
                    <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                      <!--begin::User-->
                      <div class="d-flex flex-column">
                        <!--begin::Name-->
                        <div class="d-flex align-items-center mb-2">
                          <a href="#" class="text-gray-900 text-hover-primary fs-2 fw-bold me-1"><?php echo $user->firstName; ?> <?php echo $user->lastName; ?></a>
                          <a href="#"><i class="ki-duotone ki-verify fs-1 text-primary"><span class="path1"></span><span class="path2"></span></i></a>

                          <a href="#" class="btn btn-sm btn-light-success fw-bold ms-2 fs-8 py-1 px-3" data-bs-toggle="modal" data-bs-target="#kt_modal_upgrade_plan" data-bs-toggle="modal" data-bs-target="#kt_modal_upgrade_plan"><?php echo $user->profile; ?></a>
                        </div>
                        <!--end::Name-->

                        <!--begin::Info-->
                        <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                          <a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary mb-2">
                            <i class="ki-duotone ki-sms fs-4 me-1"><span class="path1"></span><span class="path2"></span></i> <?php echo $user->email; ?>
                          </a>
                        </div>
                        <!--end::Info-->
                      </div>
                      <!--end::User-->
                    </div>
                    <!--end::Title-->

                    <!--begin::Stats-->
                    <div class="d-flex flex-wrap flex-stack">
                      <!--begin::Wrapper-->
                      <div class="d-flex flex-column flex-grow-1 pe-8">
                      </div>
                      <!--end::Wrapper-->
                    </div>
                    <!--end::Stats-->
                  </div>
                  <!--end::Info-->
                </div>
                <!--end::Details-->
              </div>
            </div>
            <!--end::Navbar-->
            <!--begin::details View-->
            <div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
              <!--begin::Card header-->
              <div class="card-header cursor-pointer">
                <!--begin::Card title-->
                <div class="card-title m-0">
                  <!-- <h3 class="fw-bold m-0">Mes informations</h3> -->
                </div>
                <!--end::Card title-->

                <?php if (
                    $_SESSION["profile"] == "Admin" ||
                    $_SESSION["profile"] == "Manager" ||
                    $_SESSION["profile"] == "Technicien"
                ) { ?>
                <!--begin::Action-->
                <a href="#" style="background: #225e41;" 
                data-bs-toggle="modal" data-bs-target="#kt_modal_update_details" 
                class="btn btn-sm btn-success align-self-center">Modifier</a>
                <!--end::Action-->
                <?php } ?>
              </div>
              <!--begin::Card header-->

              <!--begin::Card body-->
              <div class="card-body p-9">
                <!--begin::Row-->
                <div class="row mb-7">
                  <!--begin::Label-->
                  <label class="col-lg-4 fw-semibold text-muted">Nom d'utilisateur</label>
                  <!--end::Label-->

                  <!--begin::Col-->
                  <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800"><?php echo $user->username; ?></span>
                  </div>
                  <!--end::Col-->
                </div>
                <!--end::Row-->

                <!--begin::Input group-->
                <div class="row mb-7">
                  <!--begin::Label-->
                  <label class="col-lg-4 fw-semibold text-muted">Matricule</label>
                  <!--end::Label-->

                  <!--begin::Col-->
                  <div class="col-lg-8 fv-row">
                    <span class="fw-bold fs-6 text-gray-800"><?php echo $user->matricule; ?></span>
                  </div>
                  <!--end::Col-->
                </div>
                <!--end::Input group-->

                <!--begin::Input group-->
                <div class="row mb-7">
                  <!--begin::Label-->
                  <label class="col-lg-4 fw-semibold text-muted">
                    Numéro de téléphone

                    <span class="ms-1" data-bs-toggle="tooltip" title="Le numéro de téléphone doit être actif">
                      <i class="ki-duotone ki-information fs-7"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i> </span>
                  </label>
                  <!--end::Label-->

                  <!--begin::Col-->
                  <div class="col-lg-8 d-flex align-items-center">
                    <span class="fw-bold fs-6 text-gray-800 me-2"><?php echo $user->phone; ?></span>
                    <!-- <span class="badge badge-success">Verifié</span> -->
                  </div>
                  <!--end::Col-->
                </div>
                <!--end::Input group-->

                <!--begin::Input group-->
                <div class="row mb-7">
                  <!--begin::Label-->
                  <label class="col-lg-4 fw-semibold text-muted">Date de naissance</label>
                  <!--end::Label-->

                  <!--begin::Col-->
                  <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800"><?php echo $user->birthdate; ?></span>
                  </div>
                  <!--end::Col-->
                </div>
                <!--end::Input group-->

                <!--begin::Input group-->
                <div class="row mb-7">
                  <!--begin::Label-->
                  <label class="col-lg-4 fw-semibold text-muted">Sexe</label>
                  <!--end::Label-->

                  <!--begin::Col-->
                  <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800"><?php echo $user->gender; ?></span>
                  </div>
                  <!--end::Col-->
                </div>
                <!--end::Input group-->

                <!--begin::Input group-->
                <div class="row mb-7">
                  <!--begin::Label-->
                  <label class="col-lg-4 fw-semibold text-muted">
                    Pays

                    <span class="ms-1" data-bs-toggle="tooltip" title="Country of origination">
                      <i class="ki-duotone ki-information fs-7"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i> </span>
                  </label>
                  <!--end::Label-->

                  <!--begin::Col-->
                  <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800"><?php echo $user->country; ?></span>
                  </div>
                  <!--end::Col-->
                </div>
                <!--end::Input group-->

                <!--begin::Input group-->
                <div class="row mb-7">
                  <!--begin::Label-->
                  <label class="col-lg-4 fw-semibold text-muted">Certificat le plus élevé</label>
                  <!--end::Label-->

                  <!--begin::Col-->
                  <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800"><?php echo $user->certificate; ?></span>
                  </div>
                  <!--end::Col-->
                </div>
                <!--end::Input group-->

                <!--begin::Input group-->
                <div class="row mb-7">
                  <!--begin::Label-->
                  <label class="col-lg-4 fw-semibold text-muted">Filiale</label>
                  <!--end::Label-->

                  <!--begin::Col-->
                  <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800"><?php echo $user->subsidiary; ?></span>
                  </div>
                  <!--end::Col-->
                </div>
                <!--end::Input group-->

                <!--begin::Input group-->
                <div class="row mb-7">
                  <!--begin::Label-->
                  <label class="col-lg-4 fw-semibold text-muted">Département</label>
                  <!--end::Label-->

                  <!--begin::Col-->
                  <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800"><?php echo $user->department; ?></span>
                  </div>
                  <!--end::Col-->
                </div>
                <!--end::Input group-->

                <!--begin::Input group-->
                <div class="row mb-7">
                  <!--begin::Label-->
                  <label class="col-lg-4 fw-semibold text-muted">Fonction</label>
                  <!--end::Label-->

                  <!--begin::Col-->
                  <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800"><?php echo $user->role; ?></span>
                  </div>
                  <!--end::Col-->
                </div>
                <!--end::Input group-->
                <?php if (isset($user->speciality)) { ?>
                <!--begin::Input group-->
                <div class="row mb-7">
                  <!--begin::Label-->
                  <label class="col-lg-4 fw-semibold text-muted">Spécialité</label>
                  <!--end::Label-->

                  <!--begin::Col-->
                  <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800"><?php echo $user->speciality; ?></span>
                  </div>
                  <!--end::Col-->
                </div>
                <!--end::Input group-->
                <?php } ?>

                <!--begin::Input group-->
                <div class="row mb-7">
                  <!--begin::Label-->
                  <label class="col-lg-4 fw-semibold text-muted">Date de recrutement</label>
                  <!--end::Label-->

                  <!--begin::Col-->
                  <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800"><?php echo $user->recrutmentDate; ?></span>
                  </div>
                  <!--end::Col-->
                </div>
                <!--end::Input group-->

                <!--begin::Input group-->
                <div class="row mb-7">
                  <!--begin::Label-->
                  <label class="col-lg-4 fw-semibold text-muted">Marques de Véhicule</label>
                  <!--end::Label-->
                  <!--begin::Col-->
                  <div class="col-lg-8">
                  <?php foreach ($user->brand as $brand) { ?>
                    <span class="fw-bold fs-6 text-gray-800"><?php echo $brand; ?>,</span>
                  <?php } ?>
                  </div>
                  <!--end::Col-->
                </div>
                <!--end::Input group-->

              </div>
              <!--end::Card body-->
              <!--begin::Modal - Update user details-->
              <div class="modal" id="kt_modal_update_details" tabindex="-1" aria-hidden="true">
                <!--begin::Modal dialog-->
                <div class="modal-dialog modal-dialog-centered mw-650px">
                  <!--begin::Modal content-->
                  <div class="modal-content">
                    <!--begin::Form-->
                    <form class="form" action="/update/<%= user.id %>?_method=PUT" method="POST" id="kt_modal_update_user_form">
                      <input type="hidden" name="_method" value="PUT">
                      <!--begin::Modal header-->
                      <div class="modal-header" id="kt_modal_update_user_header">
                        <!--begin::Modal title-->
                        <h2 class="fs-2 fw-bolder">Modification des
                          informations</h2>
                        <!--end::Modal title-->
                        <!--begin::Close-->
                        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-users-modal-action="close" data-bs-dismiss="modal" data-kt-menu-dismiss="true">
                          <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                          <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                              <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black" />
                              <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black" />
                            </svg>
                          </span>
                          <!--end::Svg Icon-->
                        </div>
                        <!--end::Close-->
                      </div>
                      <!--end::Modal header-->
                      <!--begin::Modal body-->
                      <div class="modal-body py-10 px-lg-17">
                        <!--begin::Scroll-->
                        <div class="d-flex flex-column scroll-y me-n7 pe-7" id="kt_modal_update_user_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_update_user_header" data-kt-scroll-wrappers="#kt_modal_update_user_scroll" data-kt-scroll-offset="300px">
                          <!--begin::User toggle-->
                          <div class="fw-boldest fs-3 rotate collapsible mb-7">
                            Informations
                          </div>
                          <!--end::User toggle-->
                          <!--begin::User form-->
                          <div id="kt_modal_update_user_user_info" class="collapse show">
                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                              <!--begin::Label-->
                              <label class="fs-6 fw-bold mb-2">Username</label>
                              <!--end::Label-->
                              <!--begin::Input-->
                              <input type="text" class="form-control form-control-solid" placeholder="" name="username" value="<?php echo $user->username; ?>" />
                              <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                              <!--begin::Label-->
                              <label class="fs-6 fw-bold mb-2">Matricule</label>
                              <!--end::Label-->
                              <!--begin::Input-->
                              <input type="text" class="form-control form-control-solid" placeholder="" name="matricule" value="<?php echo $user->matricule; ?>" />
                              <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row g-9 mb-7">
                              <!--begin::Col-->
                              <div class="col-md-6 fv-row">
                                <!--begin::Label-->
                                <label class="form-label fw-bolder text-dark fs-6">Prénoms</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input class="form-control form-control-solid" placeholder="" name="firstName" value="<?php echo $user->firstName; ?>" />
                                <!--end::Input-->
                              </div>
                              <!--end::Col-->
                              <!--begin::Col-->
                              <div class="col-md-6 fv-row">
                                <!--begin::Label-->
                                <label class="form-label fw-bolder text-dark fs-6">Noms</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input class="form-control form-control-solid" placeholder="" name="lastName" value="<?php echo $user->lastName; ?>" />
                                <!--end::Input-->
                              </div>
                              <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                              <!--begin::Label-->
                              <label class="fs-6 fw-bold mb-2">
                                <span>Email</span>
                              </label>
                              <!--end::Label-->
                              <!--begin::Input-->
                              <input type="email" class="form-control form-control-solid" placeholder="" name="email" value="<?php echo $user->email; ?>" />
                              <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                              <!--begin::Label-->
                              <label class="fs-6 fw-bold mb-2">
                                <span>Sexe</span>
                              </label>
                              <!--end::Label-->
                              <!--begin::Input-->
                              <input type="text" class="form-control form-control-solid" placeholder="" name="gender" value="<?php echo $user->gender; ?>" />
                              <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                              <!--begin::Label-->
                              <label class="fs-6 fw-bold mb-2">Numéro
                                de
                                téléphone</label>
                              <!--end::Label-->
                              <!--begin::Input-->
                              <input type="text" class="form-control form-control-solid" placeholder="" name="phone" value="<?php echo $user->phone; ?>" />
                              <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                              <!--begin::Label-->
                              <label class="fs-6 fw-bold mb-2">Date
                                de
                                naissance</label>
                              <!--end::Label-->
                              <!--begin::Input-->
                              <input type="text" class="form-control form-control-solid" placeholder="" name="birthdate" value="<?php echo $user->birthdate; ?>" />
                              <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                              <!--begin::Label-->
                              <label class="fs-6 fw-bold mb-2">Métier</label>
                              <!--end::Label-->
                              <!--begin::Input-->
                              <input type="text" class="form-control form-control-solid" placeholder="" name="level" value="<?php echo $user->level; ?>" />
                              <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                              <!--begin::Label-->
                              <label class="fs-6 fw-bold mb-2">Spécialité</label>
                              <!--end::Label-->
                              <!--begin::Input-->
                              <input type="text" class="form-control form-control-solid" placeholder="" name="speciality" value="<?php echo $user->speciality ??
                                  ""; ?>" />
                              <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                              <!--begin::Label-->
                              <label class="fs-6 fw-bold mb-2">Pays</label>
                              <!--end::Label-->
                              <!--begin::Input-->
                              <input type="text" class="form-control form-control-solid" placeholder="" name="country" value="<?php echo $user->country; ?>" />
                              <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                              <!--begin::Label-->
                              <label class="fs-6 fw-bold mb-2">Certificat plus
                                élévé</label>
                              <!--end::Label-->
                              <!--begin::Input-->
                              <input type="text" class="form-control form-control-solid" placeholder="" name="certificate" value="<?php echo $user->certificate; ?>" />
                              <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                              <!--begin::Label-->
                              <label class="fs-6 fw-bold mb-2">Filiale</label>
                              <!--end::Label-->
                              <!--begin::Input-->
                              <input type="text" class="form-control form-control-solid" placeholder="" name="subsidiary" value="<?php echo $user->subsidiary; ?>" />
                              <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                              <!--begin::Label-->
                              <label class="fs-6 fw-bold mb-2">Département</label>
                              <!--end::Label-->
                              <!--begin::Input-->
                              <input type="text" class="form-control form-control-solid" placeholder="" name="department" value="<?php echo $user->department; ?>" />
                              <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                              <!--begin::Label-->
                              <label class="fs-6 fw-bold mb-2">Fonction
                              </label>
                              <!--end::Label-->
                              <!--begin::Input-->
                              <input type="text" class="form-control form-control-solid" placeholder="" name="role" value="<?php echo $user->role; ?>" />
                              <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <?php if ($user["department"] == "Motors") { ?>
                            <!--begin::Input group-->
                            <div class="d-flex flex-column mb-7 fv-row">
                              <!--begin::Label-->
                              <label class="form-label fw-bolder text-dark fs-6">
                                <span>Marques de véhicule</span>
                                <span class="ms-1" data-bs-toggle="tooltip" title="Choississez les questionnaires">
                                  <i class="ki-duotone ki-information fs-7"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                </span>
                              </label>
                              <!--end::Label-->
                              <!--begin::Input-->
                              <select name="brand[]" multiple aria-label="Select a Country" data-control="select2" data-placeholder="Sélectionnez la/les marque(s) de véhicule..." class="form-select form-select-solid fw-bold">
                                <option value="">Sélectionnez la/les marque(s) de véhicule...</option>
                                <option value="BYD">
                                  BYD
                                </option>
                                <option value="CITROEN">
                                  CITROEN
                                </option>
                                <option value="MERCEDES">
                                  MERCEDES
                                </option>
                                <option value="MUTSUBISHI">
                                  MUTSUBISHI
                                </option>
                                <option value="PEUGEOT">
                                  PEUGEOT
                                </option>
                                <option value="SUZUKI">
                                  SUZUKI
                                </option>
                                <option value="TOYOTA">
                                  TOYOTA
                                </option>
                                <option value="YAMAHA BATEAU">
                                  YAMAHA BATEAU
                                </option>
                                <option value="YAMAHA MOTO">
                                  YAMAHA MOTO
                                </option>
                              </select>
                              <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <?php } ?>
                            <?php if ($user["department"] == "Equipment") { ?>
                            <!--begin::Input group-->
                            <div class="d-flex flex-column mb-7 fv-row">
                              <!--begin::Label-->
                              <label class="form-label fw-bolder text-dark fs-6">
                                <span>Marques de véhicule</span>
                                <span class="ms-1" data-bs-toggle="tooltip" title="Choississez les questionnaires">
                                  <i class="ki-duotone ki-information fs-7"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                </span>
                              </label>
                              <!--end::Label-->
                              <!--begin::Input-->
                              <select name="brand[]" multiple aria-label="Select a Country" data-control="select2" data-placeholder="Sélectionnez la/les marque(s) de véhicule..." class="form-select form-select-solid fw-bold">
                                <option value="">Sélectionnez la/les marque(s) de véhicule...</option>
                                <option value="FUSO">
                                  FUSO
                                </option>
                                <option value="HINO">
                                  HINO
                                </option>
                                <option value="JCB">
                                  JCB
                                </option>
                                <option value="KING LONG">
                                  KING LONG
                                </option>
                                <option value="LOVOL">
                                  LOVOL
                                </option>
                                <option value="MERCEDES TRUCK">
                                  MERCEDES TRUCK
                                </option>
                                <option value="RENAULT TRUCK">
                                  RENAULT TRUCK
                                </option>
                                <option value="SINOTRUCK">
                                  SINOTRUCK
                                </option>
                                <option value="TOYOTA BT">
                                  TOYOTA BT
                                </option>
                                <option value="TOYOTA FORFLIT">
                                  TOYOTA FORFLIT
                                </option>
                              </select>
                              <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <?php } ?>
                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                              <!--begin::Label-->
                              <label class="fs-6 fw-bold mb-2">Date
                                de
                                recrutement</label>
                              <!--end::Label-->
                              <!--begin::Input-->
                              <input type="text" class="form-control form-control-solid" placeholder="" name="recrutmentDate" value="<?php echo $user->recrutmentDate; ?>" />
                              <!--end::Input-->
                            </div>
                            <?php if (
                                $user->profile == "Technicien" ||
                                $user->test == true
                            ) { ?>
                            <!--begin::Input group-->
                            <div class="d-flex flex-column mb-7 fv-row">
                              <!--begin::Label-->
                              <label class="form-label fw-bolder text-dark fs-6">
                                <span>Manager</span>
                                <span class="ms-1" data-bs-toggle="tooltip" title="Choississez le manager de ce technicien">
                                  <i class="ki-duotone ki-information fs-7"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                </span>
                              </label>
                              <!--end::Label-->
                              <!--begin::Input-->
                              <select name="manager" aria-label="Select a Country" data-control="select2" data-placeholder="Sélectionnez votre manager..." class="form-select form-select-solid fw-bold">
                                <?php if ($user->manager) {
                                    $lead = $users->findOne([
                                        "_id" => $user["manager"],
                                    ]); ?>
                                <option value="<?php echo $lead->_id; ?>">
                                  Manager
                                  actuel: <?php echo $lead->firstName; ?>
                                  <?php echo $lead->lastName; ?>
                                </option>
                                <?php
                                } ?>
                                <?php
                                $managers = $users->find([
                                    '$and' => [
                                        ["profile" => "Manager"],
                                        ["active" => true],
                                    ],
                                ]);
                                foreach ($managers as $manager) { ?>
                                <option value="<?php echo $manager->_id; ?>">
                                  <?php echo $manager->firstName; ?>
                                  <?php echo $manager->lastName; ?>
                                </option>
                                <?php }
                                ?>
                              </select>
                              <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <?php } ?>
                          </div>
                          <!--end::User form-->
                        </div>
                        <!--end::Scroll-->
                      </div>
                      <!--end::Modal body-->
                      <!--begin::Modal footer-->
                      <div class="modal-footer flex-center">
                        <!--begin::Button-->
                        <button type="reset" class="btn btn-light me-3" data-kt-menu-dismiss="true" data-bs-dismiss="modal" data-kt-users-modal-action="cancel">Annuler</button>
                        <!--end::Button-->
                        <!--begin::Button-->
                        <button type="submit" name="update" class="btn btn-primary">
                          Valider
                        </button>
                        <!--end::Button-->
                      </div>
                      <!--end::Modal footer-->
                    </form>
                    <!--end::Form-->
                  </div>
                </div>
              </div>
              <!--end::Modal - Update user details-->
          </div>
          </div>
          <!--end::Wrapper-->
          </div>
          <!--end::Layout Builder Notice-->
        </div>
        <!--end::Container-->
      </div>
      <!--end::Post-->
<?php include_once "partials/footer.php"; ?>
<?php
} ?>
