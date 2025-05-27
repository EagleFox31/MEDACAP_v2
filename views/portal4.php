<?php
session_start();
include_once "language.php";

// Initialisation de la langue (par défaut en français)
$lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'fr';

// Texte à afficher selon la langue
$texts = [
    'en' => [
        'words' => ['Measure', 'Exploit', 'Define', 'Acquire', 'Certify', 'Apply', 'Perform']
    ],
    'fr' => [
        'words' => ['1. Mesurer', '2. Exploiter', '3. Définir', '4. Acquérir', '5. Certifier', '6. Appliquer', '7. Performer']
    ]
];

// Changer la langue si l'utilisateur clique sur un bouton
if (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
    $_SESSION['lang'] = $lang;
}
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">

<head>
    <meta charset="utf-8">
    <title>CFAO Mobility Academy</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="../public/images/logo-cfao.png" rel="icon">
    <!-- Importation des polices Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Importation de Bootstrap CSS (version 5) -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0/css/bootstrap.min.css">
    <!-- Importation de Font Awesome (version 6) pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Importation de Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Importation d'Animate.css pour les animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            font-family: 'Orbitron', sans-serif;
            color: #ffffff;
        }

        body {
            background-color: #000; /* Set background to black */
            position: relative;
        }

        /* Conteneur principal */
        .main-container {
            display: flex;
            flex-direction: column;
            min-height: 100%;
        }

        /* Overlay pour simuler la brume */
        .mist-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5); /* Darker overlay */
            z-index: -1;
        }

        /* Section du titre */
        .header-section {
            position: relative;
            text-align: center;
            padding: 40px 20px 20px;
            z-index: 2;
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            margin: 20px auto;
            display: inline-block;
        }

        .header-section h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 20px;
            overflow: hidden;
            white-space: nowrap;
            border-right: .15em solid #d70006;
            animation: typing 4s steps(40, end), blink-caret .75s step-end infinite;
            display: inline-block;
        }

        @keyframes typing {
            from { width: 0 }
            to { width: 100% }
        }

        @keyframes blink-caret {
            from, to { border-color: transparent }
            50% { border-color: #d70006 }
        }

        .header-section p {
            font-size: 1.2rem;
            color: white;
            animation: focusInContract 1s ease-in-out;
        }

        /* Animation "focus in contract back" pour le slogan */
        @keyframes focusInContract {
            0% {
                letter-spacing: 1em;
                filter: blur(12px);
                opacity: 0;
            }
            100% {
                filter: blur(0);
                opacity: 1;
            }
        }

        /* Icône avec animation */
        .three-d-icon {
            font-size: 2rem;
            color: #d70006;
            animation: bounce 2s infinite;
            margin-left: 10px;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-20px);
            }
            60% {
                transform: translateY(-10px);
            }
        }

        /* Mise en évidence de "MEDACAP" */
        .highlight {
            color: #d70006;
        }

        /* Section centrale */
        .content-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
            position: relative;
            z-index: 1;
            transition: transform 0.5s ease;
        }

        /* Conteneur de la pyramide */
        .pyramid-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
        }

        .pyramid-level {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .pyramid-level:last-child {
            margin-bottom: 0;
        }

        .card-step {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            border-radius: 15px;
            padding: 20px;
            color: #ffffff;
            text-align: center;
            width: 200px;
            margin: 10px;
            transition: transform 0.3s ease, box-shadow 0.5s ease;
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.37);
            text-decoration: none;
            border: 1px solid rgba(255, 255, 255, 0.18);
            cursor: pointer;
        }

        .card-step:hover {
            transform: translateY(-5px);
            box-shadow: 0 16px 32px rgba(31, 38, 135, 0.37);
            text-decoration: none;
        }

        .card-step h3 {
            font-size: 1.4rem;
            font-weight: 500;
            margin-bottom: 10px;
            color: inherit;
        }

        .card-step i {
            font-size: 2.5rem;
            color: #d70006;
            margin-bottom: 5px;
            transition: none;
        }

        /* Flèches verticales */
        .arrow-container {
            display: flex;
            justify-content: center;
            margin-bottom: 10px;
        }

        .arrow {
            width: 30px;
            height: 30px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(5px);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.3s;
            animation: bounce 2s infinite;
        }

        .arrow::before {
            content: '';
            display: block;
            width: 10px;
            height: 10px;
            border: solid #fff;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
            transition: border-color 0.3s;
        }

        /* Flèches horizontales */
        .arrow-horizontal {
            width: 20px;
            height: 2px;
            background: rgba(255, 255, 255, 0.7);
            margin: 0 3px;
            position: relative;
        }

        .arrow-horizontal::after {
            content: '';
            position: absolute;
            top: -3px;
            right: -3px;
            width: 8px;
            height: 8px;
            border: solid #fff;
            border-width: 0 2px 2px 0;
            transform: rotate(-45deg);
        }

        /* Brand Carousel */
        .brand-carousel {
            overflow: hidden;
            position: relative;
            width: 100%;
            height: 70px;
            margin-bottom: 20px;
            background-color: rgba(0, 0, 0, 0.5); /* Dark background */
            backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .brand-container {
            display: flex;
            width: auto;
            animation: marquee 70s linear infinite;
            white-space: nowrap;
        }

        .brand-slide {
            display: flex;
            flex-wrap: nowrap;
        }

        .brand-item {
            display: inline-flex;
            align-items: center;
            margin: 0 20px;
            font-size: 1.2rem;
            color: #ffffff;
            white-space: nowrap;
        }

        .brand-item h4 {
            margin: 0;
            display: flex;
            align-items: center;
        }

        .brand-name {
            margin-right: 10px;
        }

        .icon-zoom {
            animation: zoomInOut 2s infinite;
            color: #d70006;
        }

        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        @keyframes zoomInOut {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.2);
            }
        }

        /* Pied de page */
        footer {
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(10px);
            color: #ffffff;
            text-align: center;
            padding: 20px;
            font-size: 1rem;
            position: relative;
            z-index: 1;
            border-top: 1px solid rgba(255, 255, 255, 0.18);
        }

        /* Responsive styles for the pyramid */
        @media (max-width: 1200px) {
            .card-step {
                width: 180px;
                padding: 15px;
                margin: 10px;
            }

            .card-step h3 {
                font-size: 1.2rem;
            }

            .card-step i {
                font-size: 2rem;
            }

            .pyramid-level {
                margin-bottom: 10px;
            }

            .arrow-container {
                margin-bottom: 10px;
            }

            .arrow {
                width: 25px;
                height: 25px;
            }

            .arrow::before {
                width: 8px;
                height: 8px;
            }
        }

        @media (max-width: 768px) {
            .card-step {
                width: 150px;
                padding: 10px;
                margin: 5px;
            }

            .card-step h3 {
                font-size: 1rem;
            }

            .card-step i {
                font-size: 1.8rem;
            }

            .pyramid-level {
                flex-direction: column;
            }

            .arrow-horizontal {
                display: none;
            }

            .lightbox {
                width: 100%;
                left: 0;
                right: 0;
            }

            .pyramid-shift {
                transform: translateX(0);
            }
        }

        /* Styles for the lightbox */
        .lightbox {
            position: fixed;
            top: 0;
            right: -100%; /* Start off-screen */
            width: 50%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            z-index: 100;
            transition: right 0.5s ease;
            overflow-y: auto;
        }

        .lightbox.open {
            right: 0; /* Slide in */
        }

        .lightbox-content {
            padding: 20px;
            color: #fff;
        }

        .lightbox-close {
            position: absolute;
            top: 10px;
            left: -40px;
            background: #d70006;
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            font-size: 1.5rem;
            cursor: pointer;
        }

        /* Adjust pyramid when lightbox is open */
        .pyramid-shift {
            transform: translateX(-25%); /* Shift left when lightbox is open */
        }

        /* Loader styling */
        .loader {
            border: 6px solid rgba(255, 255, 255, 0.3);
            border-top: 6px solid #d70006;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin: 50px auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

    </style>
</head>

<body>
    <!-- Overlay pour simuler la brume -->
    <div class="mist-overlay"></div>

    <!-- Carrousel d'images en arrière-plan -->
    <div class="background-carousel"></div>

    <div class="main-container">
        <!-- Section du titre -->
        <div class="header-section">
            <h1>Bienvenue sur <span class="highlight">MEDACAP</span>
                <i class="fas fa-car three-d-icon"></i>
            </h1>
            <p>Développez votre expertise professionnelle</p>
        </div>

        <!-- Section centrale -->
        <div class="content-section" id="content-section">
            <!-- Conteneur de la pyramide -->
            <div class="pyramid-container">
                <!-- Niveau 1 -->
                <div class="pyramid-level">
                    <div class="card-step" data-url="dashboard.php">
                        <i class="fas fa-lightbulb fs-2"></i>
                        <h3><?= $texts[$lang]['words'][0] ?></h3>
                    </div>
                </div>
                <!-- Flèche vers le niveau suivant -->
                <div class="arrow-container">
                    <div class="arrow"></div>
                </div>
                <!-- Niveau 2 -->
                <div class="pyramid-level">
                    <div class="card-step" data-url="404.php">
                        <i class="fas fa-cogs"></i>
                        <h3><?= $texts[$lang]['words'][1] ?></h3>
                    </div>
                    <!-- Flèche horizontale -->
                    <div class="arrow-horizontal"></div>
                    <div class="card-step" data-url="404.php">
                        <i class="fas fa-chart-line"></i>
                        <h3><?= $texts[$lang]['words'][2] ?></h3>
                    </div>
                </div>
                <!-- Flèche vers le niveau suivant -->
                <div class="arrow-container">
                    <div class="arrow"></div>
                </div>
                <!-- Niveau 3 -->
                <div class="pyramid-level">
                    <div class="card-step" data-url="404.php">
                        <i class="fas fa-book"></i>
                        <h3><?= $texts[$lang]['words'][3] ?></h3>
                    </div>
                    <!-- Flèche horizontale -->
                    <div class="arrow-horizontal"></div>
                    <div class="card-step" data-url="404.php">
                        <i class="fas fa-certificate"></i>
                        <h3><?= $texts[$lang]['words'][4] ?></h3>
                    </div>
                    <!-- Flèche horizontale -->
                    <div class="arrow-horizontal"></div>
                    <div class="card-step" data-url="404.php">
                        <i class="fas fa-tools"></i>
                        <h3><?= $texts[$lang]['words'][5] ?></h3>
                    </div>
                    <!-- Flèche horizontale -->
                    <div class="arrow-horizontal"></div>
                    <div class="card-step" data-url="404.php">
                        <i class="fas fa-award"></i>
                        <h3><?= $texts[$lang]['words'][6] ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lightbox -->
        <div class="lightbox" id="lightbox">
            <button class="lightbox-close" onclick="closeLightbox()">&times;</button>
            <div class="lightbox-content" id="lightbox-content">
                <div class="loader"></div> <!-- Loader -->
            </div>
        </div>

        <!-- Brand Carousel -->
        <div class="brand-carousel">
            <div class="brand-container">
                <div class="brand-slide">
                    <?php
                    $brands = [
                        ['name' => 'BYD', 'icon' => 'car'],
                        ['name' => 'CITROEN', 'icon' => 'car'],
                        ['name' => 'FUSO', 'icon' => 'truck'],
                        ['name' => 'HINO', 'icon' => 'truck'],
                        ['name' => 'JCB', 'icon' => 'truck-monster'],
                        ['name' => 'KING LONG', 'icon' => 'bus'],
                        ['name' => 'LOVOL', 'icon' => 'truck-pickup'],
                        ['name' => 'MERCEDES', 'icon' => 'car'],
                        ['name' => 'MERCEDES TRUCK', 'icon' => 'truck'],
                        ['name' => 'MITSUBISHI', 'icon' => 'car'],
                        ['name' => 'PEUGEOT', 'icon' => 'car'],
                        ['name' => 'RENAULT TRUCK', 'icon' => 'truck'],
                        ['name' => 'SINOTRUK', 'icon' => 'truck'],
                        ['name' => 'SUZUKI', 'icon' => 'car'],
                        ['name' => 'TOYOTA', 'icon' => 'car'],
                        ['name' => 'TOYOTA BT', 'icon' => 'truck-pickup'],
                        ['name' => 'TOYOTA FORKLIFT', 'icon' => 'truck-pickup'],
                    ];

                    // Duplicate for seamless scrolling
                    $all_brands = array_merge($brands, $brands);

                    foreach ($all_brands as $brand) {
                        echo '<div class="brand-item">
                                <h4><span class="brand-name">' . $brand['name'] . '</span> <i class="fas fa-' . $brand['icon'] . ' icon-zoom"></i></h4>
                              </div>';
                    }
                    ?>
                </div>
            </div>
        </div>

        <!-- Pied de page -->
        <footer>
            &copy;2024 CFAO MOBILITY ACADEMY, tous droits réservés
        </footer>
    </div>

    <!-- Scripts nécessaires -->
    <!-- Font Awesome pour les icônes -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <!-- Script pour le mode sombre -->
    <script>
        // Carrousel d'images en arrière-plan avec effet de panoramique
        const carouselImages = [
            { url: '../public/images/prado3.png', animation: 'panLeft' },
            { url: '../public/images/moteru_truck.png', animation: 'panLeft' },
            { url: '../public/images/welc_tech.png', animation: 'panLeft' },
            { url: '../public/images/peugeot_portal2.png', animation: 'panLeft' }
        ];

        let currentImageIndex = 0;
        const carouselElement = document.querySelector('.background-carousel');

        function changeBackgroundImage() {
            const image = carouselImages[currentImageIndex];
            carouselElement.style.backgroundImage = `url('${image.url}')`;
            carouselElement.style.animation = `${image.animation} 15s linear forwards`;
            carouselElement.style.backgroundSize = 'cover'; // Ensure image covers the background
            carouselElement.style.backgroundPosition = 'center';

            currentImageIndex = (currentImageIndex + 1) % carouselImages.length;
        }

        // Changer l'image toutes les 15 secondes
        changeBackgroundImage();
        setInterval(changeBackgroundImage, 15000);

        // Lightbox functionality
        const cardSteps = document.querySelectorAll('.card-step');
        const lightbox = document.getElementById('lightbox');
        const lightboxContent = document.getElementById('lightbox-content');
        const contentSection = document.getElementById('content-section');

        cardSteps.forEach(card => {
            card.addEventListener('click', () => {
                const url = card.getAttribute('data-url');
                openLightbox(url);
            });
        });

        function openLightbox(url) {
            // Show loader
            lightboxContent.innerHTML = '<div class="loader"></div>';
            lightbox.classList.add('open');
            contentSection.classList.add('pyramid-shift');

            // Load content via AJAX
            fetch(url)
                .then(response => response.text())
                .then(data => {
                    lightboxContent.innerHTML = data;
                })
                .catch(error => {
                    lightboxContent.innerHTML = '<p>Erreur lors du chargement du contenu.</p>';
                });
        }

        function closeLightbox() {
            lightbox.classList.remove('open');
            contentSection.classList.remove('pyramid-shift');
        }
    </script>
</body>

</html>
