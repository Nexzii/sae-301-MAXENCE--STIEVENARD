<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adoption d'animaux à Aulnat (63) | Amis 4 Pattes</title>
    <link rel="stylesheet" href="acceuil.css">
    <meta name="description" content="Bienvenue sur Amis 4 Pattes ! Adoptez un animal à Aulnat près de Clermont-Ferrand (63). Chiens, chats, lapins et autres animaux cherchent un foyer aimant.">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'header.php'; ?>

    <!-- Section Hero -->
    <div class="hero-section">
    <div class="hero-content">
        <h1>Refuge pour animaux</h1>
        <p>Donnez une seconde chance à nos amis à quatre pattes.</p>
        <a href="#" class="hero-button">
            <div class="photo-group">
                <div class="photo-circle">
                    <img src="ressources/jb.webp" alt="Photo JB">
                </div>
                <div class="photo-circle">
                    <img src="ressources/romain.webp" alt="Photo Romain">
                </div>
                <div class="photo-circle">
                    <img src="ressources/etienne.webp" alt="Photo Étienne">
                </div>
            </div>
            <span>À propos →</span>
            <small>En apprendre plus sur nous</small>
        </a>
    </div>
</div>

    </section>

    <!-- Section Carousel -->
    <?php
    // Connexion à la base de données
    include 'config.php';

    // Récupération des chats depuis la table "animaux"
    $query = $pdo->query("SELECT nom, description, photo FROM animaux WHERE espece = 'Chat'");
    $chats = $query->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <h2 class="carousel-title text-center mt-5 mb-4">Nos amis à l'adoption</h2>

    <div id="carouselExample" class="carousel slide mb-5" data-bs-ride="carousel">
        <div class="carousel-inner">
            <?php foreach ($chats as $index => $chat): ?>
                <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                    <div class="carousel-card">
                        <img src="<?php echo $chat['photo']; ?>" class="d-block w-100" alt="<?php echo htmlspecialchars($chat['nom']); ?>">
                        <h3><?php echo htmlspecialchars($chat['nom']); ?></h3>
                        <p><?php echo htmlspecialchars($chat['description']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Précédent</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Suivant</span>
        </button>
    </div>

    <!-- Section de contact -->
    <section class="contact-section mb-5">
        <div class="contact-container">
            <div class="contact-text">
                <h2>Vous souhaitez signaler un animal perdu, nous faire un don ou simplement adopter un de nos résidents ?</h2>
                <p>N'attendez plus !</p>
                <a href="apropos.php" class="contact-button">Contactez-nous →</a>
            </div>
            <div class="contact-image">
                <img src="recources/hamster-mignon.webp" alt="Hamster mignon">
            </div>
        </div>
    </section>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const carouselElement = document.querySelector("#carouselExample");

        if (carouselElement) {
            const carousel = new bootstrap.Carousel(carouselElement);

            carouselElement.addEventListener("touchstart", handleTouchStart, false);
            carouselElement.addEventListener("touchmove", handleTouchMove, false);

            let xStart = null;

            function handleTouchStart(event) {
                xStart = event.touches[0].clientX;
            }

            function handleTouchMove(event) {
                if (!xStart) return;

                const xEnd = event.touches[0].clientX;
                const xDiff = xStart - xEnd;

                if (xDiff > 50) {
                    // Swipe gauche : suivant
                    carousel.next();
                } else if (xDiff < -50) {
                    // Swipe droite : précédent
                    carousel.prev();
                }

                xStart = null;
            }
        }
    });
</script>

    <div id="news-container"></div>

    <?php include 'footer.php'; ?>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
