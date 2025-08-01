<?php
session_start();
include_once "language.php";

// Initialisation de la langue (par défaut en français)
$lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'fr';

// Texte à afficher selon la langue
$texts = [
    'en' => [
        'numbers' => ['1.', '2.', '3.', '4.', '5.', '6.', '7.'],
        'labels' => ['Measure', 'Exploit', 'Define', 'Acquire', 'Certify', 'Apply', 'Perform']
    ],
    'fr' => [
        'numbers' => ['1.', '2.', '3.', '4.', '5.', '6.', '7.'],
        'labels' => ['Mesurer', 'Exploiter', 'Définir', 'Acquérir', 'Certifier', 'Appliquer', 'Performer']
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
            background: #000;
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
            background: rgba(255, 255, 255, 0.2);
            z-index: -1;
        }

        /* Section du titre */
        .header-section {
            position: relative;
            text-align: center;
            padding: 40px 20px 20px;
            z-index: 2;
            background-color: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            margin: 20px;
            display: inline-block;
        }

        .header-section h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 20px;
            overflow: hidden;
            white-space: nowrap;
            border-right: .15em solid #b23a48;
            animation: typing 4s steps(40, end), blink-caret 1.5s step-end infinite;
            display: inline-block;
        }

        @keyframes typing {
            from { width: 0 }
            to { width: 100% }
        }

        @keyframes blink-caret {
            from, to { border-color: transparent }
            50% { border-color: #b23a48 }
        }

        .header-section p {
            font-size: 1.2rem;
            color: white;
            animation: focusInContract 2s ease-in-out;
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
            animation: bounce 4s infinite;
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
        }

        /* Conteneur de la pyramide */
        .pyramid-container {
            display: flex;
            flex-direction: column;
            align-items: center;
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
            background-color: rgba(255, 255, 255, 255);
            backdrop-filter: blur(15px);
            border-radius: 15px;
            padding: 20px;
            color: #102a43;
            text-align: center;
            width: 200px;
            margin: 10px;
            transition: transform 0.3s ease, box-shadow 0.5s ease;
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.37);
            text-decoration: none;
            border: 1px solid rgba(255, 255, 255, 0.18);
            position: relative;
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

        /* Style for step numbers */
        .step-number {
            font-size: 2rem; /* Larger font size */
            color: #d70006; /* Red color */
            font-weight: bold;
            margin-right: 5px;
        }

        /* Icons in red */
        .card-step i {
            font-size: 2.5rem;
            color: #d70006 !important;
            margin-bottom: 5px;
            transition: none;
        }

        /* Active state */
        .card-step.active {
            background-color: #d70006;
            color: #fff;
        }

        .card-step.active h3 {
            color: #fff;
        }

        /* Keep icons red even when active */
        .card-step.active i {
            color: #d70006 !important;
        }

        /* Zoom animation on first letter */
        .card-step h3::first-letter {
            display: inline-block;
            animation: zoomLetter 2s infinite;
        }

        @keyframes zoomLetter {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.5);
            }
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
            background: rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(5px);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.3s;
            animation: bounce 4s infinite;
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
            width: 23px;
            height: 3px;
            background: #fff;
            margin: 0 3px;
            position: relative;
            animation: moveArrow 4s infinite;
        }

        .arrow-horizontal::after {
            content: '';
            position: absolute;
            top: -3px;
            right: -3px;
            width: 12px;
            height: 10px;
            border: solid #fff;
            border-width: 0 2px 2px 0;
            transform: rotate(-45deg);
        }

        /* Arrow animation */
        @keyframes moveArrow {
            0%, 100% {
                transform: translateX(0);
            }
            50% {
                transform: translateX(5px);
            }
        }

        /* Brand Carousel */
        .brand-carousel {
            overflow: hidden;
            position: relative;
            width: 100%;
            height: 70px;
            margin-bottom: 20px;
            background-color: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .brand-container {
            display: flex;
            width: auto;
            animation: marquee 100s linear infinite;
            white-space: nowrap;
            position: relative;
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
            color: white;
            white-space: nowrap;
            transition: transform 0.3s ease;
        }

        /* Zoom effect when brand is centered */
        .brand-item.animate {
            animation: brandZoom 10s infinite;
        }

        @keyframes brandZoom {
            0%, 10%, 100% {
                transform: scale(1);
            }
            5% {
                transform: scale(1.5);
            }
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
            background-color: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            color: #ffffff;
            text-align: center;
            padding: 20px;
            font-size: 1rem;
            position: relative;
            z-index: 1;
            border-top: 1px solid rgba(255, 255, 255, 0.18);
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .pyramid-level {
                margin-bottom: 10px; /* Reduced space between levels */
            }

            .arrow-container {
                margin-bottom: 5px; /* Reduced space */
            }

            .card-step {
                flex: 1 1 calc(33.33% - 20px);
                margin: 5px;
                max-width: calc(33.33% - 20px);
            }

            .pyramid-level:nth-child(2) .card-step {
                flex: 1 1 calc(50% - 20px);
                max-width: calc(50% - 20px);
            }

            .pyramid-level:nth-child(3) .card-step {
                flex: 1 1 calc(25% - 20px);
                max-width: calc(25% - 20px);
            }
        }

        @media (max-width: 768px) {
            .card-step {
                width: 100%;
                margin: 10px 0;
            }

            .pyramid-level {
                flex-direction: column;
            }

            .arrow-horizontal {
                display: none;
            }

            .header-section h1 {
                font-size: 1.5rem;
            }

            .card-step i {
                font-size: 2.5rem;
            }

            .brand-carousel {
                height: auto;
            }

            .brand-container {
                animation: marquee 40s linear infinite;
            }
        }

        /* Animations pour le panoramique de l'image de fond */
        .background-carousel {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -2;
            background-size: cover;
            background-position: center;
            transition: background-image 1s ease-in-out;
        }
        .svg-inline--fa{
            height : 1.5em;
        }

        /* Animations personnalisées pour le panoramique */
        @keyframes panLeft {
            from { background-position: right center; }
            to { background-position: left center; }
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
                <i class="fas fa-car three-d-icon fs-2x"></i>
            </h1>
            <p>Développez votre expertise professionnelle</p>
        </div>

        <!-- Section centrale -->
        <div class="content-section">
            <!-- Conteneur de la pyramide -->
            <div class="pyramid-container">
                <!-- Niveau 1 -->
                <div class="pyramid-level">
                    <a href="dashboard.php" class="card-step">
                        <i class="fas fa-lightbulb fs-2x"></i>
                        <h3><span class="step-number"><?= $texts[$lang]['numbers'][0] ?></span><?= $texts[$lang]['labels'][0] ?></h3>
                    </a>
                </div>
                <!-- Flèche vers le niveau suivant -->
                <div class="arrow-container">
                    <div class="arrow"></div>
                </div>
                <!-- Niveau 2 -->
                <div class="pyramid-level">
                    <a href="404.php" class="card-step">
                        <i class="fas fa-cogs"></i>
                        <h3><span class="step-number"><?= $texts[$lang]['numbers'][1] ?></span><?= $texts[$lang]['labels'][1] ?></h3>
                    </a>
                    <!-- Flèche horizontale -->
                    <div class="arrow-horizontal"></div>
                    <a href="404.php" class="card-step">
                        <i class="fas fa-chart-line"></i>
                        <h3><span class="step-number"><?= $texts[$lang]['numbers'][2] ?></span><?= $texts[$lang]['labels'][2] ?></h3>
                    </a>
                </div>
                <!-- Flèche vers le niveau suivant -->
                <div class="arrow-container">
                    <div class="arrow"></div>
                </div>
                <!-- Niveau 3 -->
                <div class="pyramid-level">
                    <a href="404.php" class="card-step">
                        <i class="fas fa-book"></i>
                        <h3><span class="step-number"><?= $texts[$lang]['numbers'][3] ?></span><?= $texts[$lang]['labels'][3] ?></h3>
                    </a>
                    <!-- Flèche horizontale -->
                    <div class="arrow-horizontal"></div>
                    <a href="404.php" class="card-step">
                        <i class="fas fa-certificate"></i>
                        <h3><span class="step-number"><?= $texts[$lang]['numbers'][4] ?></span><?= $texts[$lang]['labels'][4] ?></h3>
                    </a>
                    <!-- Flèche horizontale -->
                    <div class="arrow-horizontal"></div>
                    <a href="404.php" class="card-step">
                        <i class="fas fa-tools"></i>
                        <h3><span class="step-number"><?= $texts[$lang]['numbers'][5] ?></span><?= $texts[$lang]['labels'][5] ?></h3>
                    </a>
                    <!-- Flèche horizontale -->
                    <div class="arrow-horizontal"></div>
                    <a href="404.php" class="card-step">
                        <i class="fas fa-award"></i>
                        <h3><span class="step-number"><?= $texts[$lang]['numbers'][6] ?></span><?= $texts[$lang]['labels'][6] ?></h3>
                    </a>
                </div>
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
    <!-- Script pour les animations -->
    <script>
        // Carrousel d'images en arrière-plan avec effet de panoramique
        const carouselImages = [
            { url: '../public/images/prado3.png', animation: 'panLeft' },
            { url: '../public/images/truck.png', animation: 'panLeft' },
            { url: '../public/images/welc_tech.png', animation: 'panLeft' },
            { url: '../public/images/peugeot_portal2.png', animation: 'panLeft' }
        ];

        let currentImageIndex = 0;
        const carouselElement = document.querySelector('.background-carousel');

        function changeBackgroundImage() {
            const image = carouselImages[currentImageIndex];
            carouselElement.style.backgroundImage = `url('${image.url}')`;
            carouselElement.style.animation = `${image.animation} 30s linear forwards`;
            currentImageIndex = (currentImageIndex + 1) % carouselImages.length;
        }

        // Changer l'image toutes les 30 secondes
        changeBackgroundImage();
        setInterval(changeBackgroundImage, 30000);

        // Brand carousel effect
        const brandItems = document.querySelectorAll('.brand-item');

        // Add 'animate' class to brand items for zoom effect
        brandItems.forEach((item, index) => {
            const delay = index * 10; // Adjust timing as needed
            item.style.animationDelay = `${delay}s`;
            item.classList.add('animate');
        });

        // Handle click on card-step
        const cardSteps = document.querySelectorAll('.card-step');

        cardSteps.forEach(card => {
            card.addEventListener('click', (e) => {
                // Remove 'active' class from all card-steps
                cardSteps.forEach(c => c.classList.remove('active'));
                // Add 'active' class to the clicked card
                card.classList.add('active');
            });
        });
    </script>
</body>

</html>
