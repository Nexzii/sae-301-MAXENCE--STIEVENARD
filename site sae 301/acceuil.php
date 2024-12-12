<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Amis 4 Pattes</title>
    <link rel="stylesheet" href="acceuil.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'header.php'; ?>

    <!-- Section Hero -->
    <section class="hero-section">
        <div class="hero-content">
            <h1>Refuge pour animaux</h1>
            <p>Offrez un foyer chaleureux à nos animaux en attente d'adoption.</p>
        </div>
        <a href="apropos.php" class="hero-button">
            <span>A propos →</span>
            <p>En apprendre plus<br>sur nous</p>
            <div class="button-deco">
                <div class="circle"></div>
                <div class="circle"></div>
                <div class="circle"></div>
            </div>
        </a>
    </section>

    <!-- Section Carousel -->
    <?php
    // Connexion à la base de données
    include 'config.php';

    // Récupération des chats depuis la table "animaux"
    $query = $pdo->query("SELECT nom, description, photo FROM animaux WHERE espece = 'Chat'");
    $chats = $query->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <div class="carousel-section">
        <h2 class="text-center my-4">Nos amis à l'adoption</h2>
        <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php foreach ($chats as $index => $chat): ?>
                    <div class="carousel-item <?php if ($index === 0) echo 'active'; ?>">
                        <div class="carousel-card">
                            <img src="<?= htmlspecialchars($chat['photo']) ?>" class="d-block mx-auto img-fluid" alt="<?= htmlspecialchars($chat['nom']) ?>">
                            <h3 class="text-center mt-3"><?= htmlspecialchars($chat['nom']) ?></h3>
                            <p class="text-center"><?= htmlspecialchars($chat['description']) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<!-- Section de contact -->

<section class="contact-section">
    <div class="contact-container">
        <div class="contact-text">
            <h2>Vous souhaitez signaler un animal perdu, nous faire un don ou simplement adopter un de nos résidents ?</h2>
            <p>N'attendez plus !</p>
            <a href="contact.php" class="contact-button">Contactez-nous →</a>
        </div>
        <div class="contact-image">
        <img src="recources/hamster-mignon.webp" alt="Hamster mignon">
        </div>
    </div>
</section>

<?php    include 'footer.php'; ?>