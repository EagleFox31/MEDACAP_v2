<!-- Carrousel des marques du technicien -->
<div class="text-center mb-4">
    <div class="brand-scroll-wrapper">
        <!-- Bouton flèche gauche -->
        <button class="arrow-button" id="scrollLeft">&lsaquo;</button>

        <!-- Conteneur scrollable pour les cartes de marques -->
        <div id="brandContainer" class="horizontal-scroll-container">
            <?php
            // Boucle sur le tableau des marques à afficher
            foreach ($brandsToShow as $brand) {
                // Récupération du logo de la marque, avec une valeur par défaut
                $logoSrc = isset($brandLogos[$brand]) ? "../../public/images/" . $brandLogos[$brand] : "../../public/images/default.png";
            ?>
            <div class="card custom-card brand-card">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <img src="<?php echo $logoSrc; ?>"
                         alt="<?php echo htmlspecialchars($brand); ?> Logo"
                         class="img-fluid brand-logo">
                    <div class="brand-name"><?php echo htmlspecialchars($brand); ?></div>
                </div>
            </div>
            <?php
            }
            ?>
        </div>

        <!-- Bouton flèche droite -->
        <button class="arrow-button" id="scrollRight">&rsaquo;</button>
    </div>

    <script>
        document.getElementById('scrollLeft').addEventListener('click', function() {
            document.getElementById('brandContainer').scrollBy({
                left: -300,
                behavior: 'smooth'
            });
        });

        document.getElementById('scrollRight').addEventListener('click', function() {
            document.getElementById('brandContainer').scrollBy({
                left: 300,
                behavior: 'smooth'
            });
        });
    </script>
</div>

<style>
    /* Style pour le carrousel des marques */
    .brand-scroll-wrapper {
        position: relative;
        padding: 0 3rem;
        overflow: hidden;
    }

    .horizontal-scroll-container {
        display: flex;
        flex-wrap: nowrap;
        overflow-x: auto;
        scroll-behavior: smooth;
        gap: 1rem;
        justify-content: center;
    }

    /* Masquer la scrollbar */
    .horizontal-scroll-container::-webkit-scrollbar {
        display: none;
    }

    .horizontal-scroll-container {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    /* Cartes des marques */
    .brand-card {
        flex: 0 0 auto;
        width: 18rem;
    }

    .custom-card {
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s, box-shadow 0.2s;
        cursor: pointer;
        border: none;
        background-color: #fff;
    }

    .custom-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2);
    }

    /* Boutons de navigation */
    .arrow-button {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(255, 255, 255, 0.8);
        border: none;
        font-size: 2rem;
        cursor: pointer;
        z-index: 2;
        width: 2.5rem;
        height: 2.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #198754;
    }

    #scrollLeft {
        left: 0;
    }

    #scrollRight {
        right: 0;
    }

    /* Logo de la marque */
    .brand-logo {
        width: 60px;
        height: 45px;
        margin-bottom: 0.5rem;
    }
    
    .brand-name {
        font-size: 0.9rem;
        font-weight: bold;
    }
</style>