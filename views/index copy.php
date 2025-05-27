<?php
session_start();
include_once "language.php";

require_once "vendor/autoload.php";

// Create connection
$conn = new MongoDB\Client("mongodb://localhost:27017");

// Connecting in database
$academy = $conn->academy;

// Connecting in collections
$users = $academy->users;
$connections = $academy->connections;

if (isset($_POST["login"])) {
    $userName = $_POST["username"];
    $password = sha1($_POST["password"]);

    $data = [
        "username" => $userName,
        "password" => $password,
    ];

    $login = $users->findOne($data);
    if (empty($login)) {
        $error_msg = $error_username_pass;
    } elseif ($login->active == false) {
        $error_msg = $error_authentication;
    } else {
        $_SESSION["username"] = $login["username"];
        $_SESSION["profile"] = $login["profile"];
        $_SESSION["lastName"] = $login["lastName"];
        $_SESSION["firstName"] = $login["firstName"];
        $_SESSION["email"] = $login["email"];
        $_SESSION["test"] = $login["test"];
        $_SESSION["id"] = $login["_id"];
        $_SESSION["subsidiary"] = $login["subsidiary"];
        $_SESSION["department"] = $login["department"];
        $_SESSION["country"] = $login["country"];
        
        $userConnected = $connections->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($_SESSION["id"]),
                    "active" => true,
                ],
            ],
        ]);
        if ($userConnected) {
            $userConnected->status = "Online";
            $userConnected->start = date("d-m-Y H:i:s");
            $connections->updateOne(
                ["_id" => new MongoDB\BSON\ObjectId($userConnected->_id)],
                ['$set' => $userConnected]
            );
        } else {
            $connection = [
                "user" => new MongoDB\BSON\ObjectId($_SESSION["id"]),
                "status" => "Online",
                "start" => date("d-m-Y H:i:s"),
                "end" => "",
                "active" => true
            ];

            $connections->insertOne($connection);
        }

        header("Location: views/dashboard.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>CFAO Mobility Academy</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">
    <!-- Favicon -->
    <link href="public/images/logo-cfao.png" rel="icon">
    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&display=swap"
        rel="stylesheet">
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Libraries Stylesheet -->
    <link href="public/lib/animate/animate.min.css" rel="stylesheet">
    <link href="public/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="public/lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />
    <!-- Customized Bootstrap Stylesheet -->
    <link href="public/css/bootstrap.min.css" rel="stylesheet">
    <!-- Template Stylesheet -->
    <link href="public/css/style.css" rel="stylesheet">
    <style>
        .highlight {
            background: linear-gradient(90deg, #1e3c72, #2a5298, #6dd5fa, #2980b9, #1e3c72);
            background-size: 400%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: gradientMove 5s ease infinite;
        }
    
        @keyframes gradientMove {
            0% {
                background-position: 0%;
            }
    
            100% {
                background-position: 100%;
            }
        }
    </style>
</head>

<body>
    <!-- Spinner Start -->
    <div id="spinner"
        class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Chargement...</span>
        </div>
    </div>
    <!-- Spinner End -->
    <!-- Navbar & Hero Start -->
    <div class="container-fluid position-relative p-0">
        <nav class="navbar navbar-expand-lg navbar-light px-4 px-lg-5 py-3 py-lg-0">
            <a href="" class="navbar-brand p-0">
                <img class="fa fa-map-marker-alt me-3" src="public/images/logo.png" alt="Logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="fa fa-bars"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto py-0">
                </div>
                <a href="#connexion" class="btn btn-primary text-white rounded-pill py-2 px-4"><?php echo $connexion ?></a>
            </div>
        </nav>
        <div class="container-fluid bg-primary py-5 mb-5 hero-header">
            <div class="container py-5">
                <div class="row justify-content-center py-5">
                    <div class="col-lg-10 pt-lg-5 mt-lg-5 text-center">
                        <h4 class="display-3 text-white mb-3 animated slideInDown">
                            <?php echo str_replace('MEDACAP', '<span class="highlight">MEDACAP</span>', $index); ?>
                        </h4>
                    </div>
                </div>
            </div>
        </div>
        <!-- Navbar & Hero End -->

        <?php if (isset($success_msg)) { ?>
        <div class='alert alert-success alert-dismissible fade show' role='alert'>
            <center><strong><?php echo $success_msg; ?></strong></center>
            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                <span aria-hidden='true'>&times;
                </span>
            </button>
        </div>
        <?php } ?>
        <?php if (isset($error_msg)) { ?>
        <div class='alert alert-danger alert-dismissible fade show' role='alert'>
            <center><strong><?php echo $error_msg; ?></strong></center>
            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                <span aria-hidden='true'>&times;
                </span>
            </button>
        </div>
        <?php } ?>

    <!-- Contact Start -->
    <div class="container-xxl py-5" id="connexion">
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h6 class="section-title bg-white text-center text-primary px-3">
                    <?php echo $connexion ?></h6>
                <h1 class="mb-5"><?php echo $connectez_vous ?></h1>
            </div>
            <div class="row g-4" style="margin-top: -70px">
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s" style="min-height: 450px;">
                    <div class="position-relative h-100">
                        <img class="img-fluid position-absolute w-100 h-100"
                            src="public/images/vue_face.png" alt="" style="object-fit: contain; clip-path: inset(0 0 0 150px);">
                    </div>
                </div>
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.3s" style="margin-top: 120px;">
                    <form method="POST">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="username" placeholder="Username">
                                    <label for="subject"><?php echo $username ?></label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="Password">
                                    <label for="subject"><?php echo $Password ?></label>
                                    <span class="password-viewer" onclick="togglePasswordVisibility()">
                                        <i class="fas fa-eye"></i> Afficher le mot de passe.
                                    </span>
                                </div>
                            </div>
                            <div></div><br><br>
                            <!-- <a href="forgotPassword.php" class="link-primary fs-6 fw-bolder">Mot de passe Oublié ?</a> -->
                            <div class="col-12">
                                <button class="btn btn-primary w-100 py-3" type="submit" name="login"><?php echo $acceder ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Contact End -->
    <!-- Destination Start -->
    <div class="container-xxl py-5 destination">
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h6 class="section-title bg-white text-center text-primary px-3">
                    <?php echo $image ?></h6>
                <h1 class="mb-5"><?php echo $some_image ?></h1>
            </div>
            <div class="row g-3">
                <div class="col-lg-7 col-md-6">
                    <div class="row g-3">
                        <div class="col-lg-12 col-md-12 wow zoomIn" data-wow-delay="0.1s">
                            <a class="position-relative d-block overflow-hidden" href="">
                                <img class="img-fluid" src="public/images/Cami montage.png" alt="">
                                <div class="bg-white text-danger fw-bold position-absolute top-0 start-0 m-3 py-1 px-2">
                                </div>
                                <div
                                    class="bg-white text-primary fw-bold position-absolute bottom-0 end-0 m-3 py-1 px-2">
                                    <?php echo $vehicle_com ?></div>
                            </a>
                        </div>
                        <div class="col-lg-6 col-md-12 wow zoomIn" data-wow-delay="0.3s">
                            <a class="position-relative d-block overflow-hidden" href="">
                                <img class="img-fluid" src="public/images/IMG_20220916_175932_369.jpg" alt="">
                                <div class="bg-white text-danger fw-bold position-absolute top-0 start-0 m-3 py-1 px-2">
                                </div>
                                <div
                                    class="bg-white text-primary fw-bold position-absolute bottom-0 end-0 m-3 py-1 px-2">
                                    <?php echo $formation_dpok ?></div>
                            </a>
                        </div>
                        <div class="col-lg-6 col-md-12 wow zoomIn" data-wow-delay="0.5s">
                            <a class="position-relative d-block overflow-hidden" href="">
                                <img class="img-fluid" src="public/images/IMG_20221123_153828_683.jpg" alt="">
                                <div class="bg-white text-danger fw-bold position-absolute top-0 start-0 m-3 py-1 px-2">
                                </div>
                                <div
                                    class="bg-white text-primary fw-bold position-absolute bottom-0 end-0 m-3 py-1 px-2">
                                    <?php echo $visit_entreprise ?></div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-md-6 wow zoomIn" data-wow-delay="0.7s" style="min-height: 350px;">
                    <a class="position-relative d-block h-100 overflow-hidden" href="">
                        <img class="img-fluid position-absolute w-100 h-100"
                            src="public/images/IMG_20230309_100343_936.jpg" alt="" style="object-fit: cover;">
                        <div class="bg-white text-danger fw-bold position-absolute top-0 start-0 m-3 py-1 px-2">
                        </div>
                        <div class="bg-white text-primary fw-bold position-absolute bottom-0 end-0 m-3 py-1 px-2">
                            <?php echo $formation_toyota ?></div>
                    </a>
                </div>
                <div class="row g-3">
                    <div class="col-lg-6 col-md-12 wow zoomIn" data-wow-delay="0.3s">
                        <a class="position-relative d-block overflow-hidden" href="">
                            <img class="img-fluid" src="public/images/IMG_9092.JPG" alt="">
                            <div class="bg-white text-danger fw-bold position-absolute top-0 start-0 m-3 py-1 px-2">
                            </div>
                            <div class="bg-white text-primary fw-bold position-absolute bottom-0 end-0 m-3 py-1 px-2">
                                <?php echo $formation_nigeria ?></div>
                        </a>
                    </div>
                    <div class="col-lg-6 col-md-12 wow zoomIn" data-wow-delay="0.5s">
                        <a class="position-relative d-block overflow-hidden" href="">
                            <img class="img-fluid" src="public/images/IMG_20240328_161007_342.jpg" alt="">
                            <div class="bg-white text-danger fw-bold position-absolute top-0 start-0 m-3 py-1 px-2">
                            </div>
                            <div class="bg-white text-primary fw-bold position-absolute bottom-0 end-0 m-3 py-1 px-2">
                                <?php echo $formation_nigeria ?></div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Destination Start -->
    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-light footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container">
            <div class="copyright">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        &copy; <a class="border-bottom" href="/">CFAO
                            Mobility
                            Academy</a>,
                        All Right Reserved.
                        <!--/*** This template is free as long as you keep the footer author’s credit link/attribution link/backlink. If you'd like to use the template without the footer author’s credit link/attribution link/backlink, you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". Thank you for your support. ***/-->
                        Designed By <a class="border-bottom" href="https://www.cfaogroup.com/fr/accueil/">CFAO Group</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->
    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js">
    </script>
    <script src="public/lib/wow/wow.min.js"></script>
    <script src="public/lib/easing/easing.min.js"></script>
    <script src="public/lib/waypoints/waypoints.min.js"></script>
    <script src="public/lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="public/lib/tempusdominus/js/moment.min.js"></script>
    <script src="public/lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="public/lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js">
    </script>
    <!--begin::Custom Javascript(used for this page only)-->
    <script src="public/assets/js/custom/authentication/sign-up/free-trial.js"></script>
    <!-- Template Javascript -->
    <script src="public/js/main.js"></script>
    <script>
    // Function to handle closing of the alert message
    document.addEventListener('DOMContentLoaded', function() {
        const closeButtons = document.querySelectorAll('.alert .close');
        closeButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                const alert = this.closest('.alert');
                alert.remove();
            });
        });
    });

    function togglePasswordVisibility() {
        const passwordInput = document.getElementById("password");
        const eyeIcon = document.querySelector(".password-viewer i");
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            eyeIcon.classList.remove("fa-eye");
            eyeIcon.classList.add("fa-eye-slash");
        } else {
            passwordInput.type = "password";
            eyeIcon.classList.remove("fa-eye-slash");
            eyeIcon.classList.add("fa-eye");
        }
    }
    </script>
</body>

</html>