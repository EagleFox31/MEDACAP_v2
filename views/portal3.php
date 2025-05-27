<?php
session_start();
include_once "language.php";

// Initialisation de la langue (par défaut en français)
$lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'fr';

// Textes à afficher en fonction de la langue
$texts = [
    'en' => [
        'words' => ['Measure', 'Exploit', 'Define', 'Acquire', 'Certify', 'Apply', 'Perform'],
        'descriptions' => [
            'Measure the initial state.',
            'Exploit the current opportunities.',
            'Define the key goals.',
            'Acquire necessary resources.',
            'Certify your success.',
            'Apply what you learned.',
            'Perform with excellence.'
        ]
    ],
    'fr' => [
        'words' => ['Mesurer', 'Exploiter', 'Définir', 'Acquérir', 'Certifier', 'Appliquer', 'Performer'],
        'descriptions' => [
            'Mesurez l\'état initial.',
            'Exploitez les opportunités actuelles.',
            'Définissez les objectifs clés.',
            'Acquérez les ressources nécessaires.',
            'Certifiez votre réussite.',
            'Appliquez ce que vous avez appris.',
            'Performer avec excellence.'
        ]
    ]
];

// Changer la langue lorsque l'utilisateur clique sur un bouton
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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            background: url('../public/images/prado_double1.png') no-repeat center center;
            background-size: cover;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
        }

        /* Titre avec espacement des lettres */
        .infographic-title {
            font-size: 36px;
            font-weight: 600;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-top: 50px;
            color: #007bff;
            white-space: nowrap;
            overflow: hidden;
            border-right: .15em solid orange;
            animation: typing 4s steps(40, end), blink-caret .75s step-end infinite;
        }

        @keyframes typing {
            from {
                width: 0;
            }
            to {
                width: 100%;
            }
        }

        @keyframes blink-caret {
            from, to {
                border-color: transparent;
            }
            50% {
                border-color: orange;
            }
        }

        .infographic {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 30px;
            margin-top: 50px;
            flex-wrap: nowrap;
            overflow-x: auto;
        }

        /* Styles des tiles */
        .infographic .item {
            background-color: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(8px);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            padding: 30px;
            text-align: center;
            width: 150px;
            height: 400px;
            position: relative;
            color: #fff;
            transition: all 0.4s ease;
        }

        .infographic .item:hover {
            transform: scale(1.05);
            background-color: rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 40px rgba(0, 0, 0, 0.3);
        }

        /* Icônes et numéros des étapes */
        .infographic .item .step-number {
            position: absolute;
            top: 10px;
            left: 10px;
            font-size: 18px;
            font-weight: 700;
            color: #007bff;
        }

        .infographic .item img {
            width: 80px;
            margin-bottom: 20px;
        }

        .infographic .item span {
            font-size: 18px;
            font-weight: 600;
            color: #fff;
        }

        /* Flèches Glassmorphism */
        .arrow {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: rgba(255, 255, 255, 0.7);
            transition: all 0.3s ease;
        }

        .arrow:hover {
            background: rgba(255, 255, 255, 0.4);
            color: rgba(255, 255, 255, 1);
        }

        @media (max-width: 768px) {
            .infographic {
                flex-wrap: wrap;
            }
            .infographic .item {
                width: 140px;
                height: 300px;
            }
        }
    </style>
</head>

<body>

    <div class="infographic-title">
        Développer votre expertise professionnelle
    </div>

    <div class="infographic">
        <!-- Étape 1 -->
        <div class="item">
            <div class="step-number">1</div>
            <img src="../public/images/success.png" alt="Mesurer">
            <span>Mesurer</span>
        </div>

        <div class="arrow"><i class="fas fa-arrow-up"></i></div>

        <!-- Étape 2 -->
        <div class="item">
            <div class="step-number">2</div>
            <img src="../public/images/success.png" alt="Exploiter">
            <span>Exploiter</span>
        </div>

        <div class="arrow"><i class="fas fa-arrow-up"></i></div>

        <!-- Étape 3 -->
        <div class="item">
            <div class="step-number">3</div>
            <img src="../public/images/success.png" alt="Définir">
            <span>Définir</span>
        </div>

        <div class="arrow"><i class="fas fa-arrow-up"></i></div>

        <!-- Étape 4 -->
        <div class="item">
            <div class="step-number">4</div>
            <img src="../public/images/success.png" alt="Acquérir">
            <span>Acquérir</span>
        </div>

        <div class="arrow"><i class="fas fa-arrow-up"></i></div>

        <!-- Étape 5 -->
        <div class="item">
            <div class="step-number">5</div>
            <img src="../public/images/success.png" alt="Certifier">
            <span>Certifier</span>
        </div>

        <div class="arrow"><i class="fas fa-arrow-up"></i></div>

        <!-- Étape 6 -->
        <div class="item">
            <div class="step-number">6</div>
            <img src="../public/images/success.png" alt="Appliquer">
            <span>Appliquer</span>
        </div>

        <div class="arrow"><i class="fas fa-arrow-up"></i></div>

        <!-- Étape 7 -->
        <div class="item">
            <div class="step-number">7</div>
            <img src="../public/images/success.png" alt="Performer">
            <span>Performer</span>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
