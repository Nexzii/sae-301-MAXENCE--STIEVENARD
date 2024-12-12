<?php
include 'config.php';

// Récupérer tous les chats dans la base de données
$query = $pdo->query("SELECT * FROM animaux ORDER BY created_at DESC");
$animaux = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adoptions - Ami 4 Pattes</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="logo">Logo</div>
        <h1>Ami 4 Pattes</h1>
        <nav>
            <a href="index.php">Accueil</a>
            <a href="#about">À propos</a>
            <a href="adoption.php">Adoptions</a>
            <a href="./uploads/index.php">Admin</a>
        </nav>
    </header>
    <main>
        <h2>Nos Chats à l'Adoption</h2>
        <div class="grid-container">
            <?php foreach ($animaux as $animal): ?>
                <!-- Rendre la carte cliquable vers la page de détails -->
                <a href="chat.php?id=<?= $animal['id'] ?>" class="card">
                    <div class="image">
                        <img src="<?= htmlspecialchars($animal['photo']) ?>" alt="Photo de <?= htmlspecialchars($animal['nom']) ?>">
                    </div>
                    <div class="info">
                        <h3><?= htmlspecialchars($animal['nom']) ?></h3>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </main>
    <?php include 'footer.php'; ?>

</body>
</html>
