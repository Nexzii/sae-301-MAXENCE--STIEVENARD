<?php
include 'config.php'; // Contient la connexion à la base de données
include 'header.php'; // Inclut le header

// Vérifiez si l'ID est passé en paramètre
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID d'animal invalide.");
}

$id = (int) $_GET['id'];

// Préparez et exécutez la requête pour récupérer les informations du chat
$query = $pdo->prepare("SELECT * FROM animaux WHERE id = ?");
$query->execute([$id]);
$animal = $query->fetch(PDO::FETCH_ASSOC);

if (!$animal) {
    die("Animal introuvable.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($animal['nom']); ?> - Ami 4 Pattes</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main>
        <div class="animal-info">
            <div class="image">
                <img src="<?= htmlspecialchars($animal['photo']); ?>" alt="Photo de <?= htmlspecialchars($animal['nom']); ?>">
            </div>
            <div class="details">
                <h1><?= htmlspecialchars($animal['nom']); ?></h1>
                <p><strong>Date de naissance :</strong> <?= htmlspecialchars($animal['date_naissance']); ?></p>
                <p><strong>Espèce :</strong> <?= htmlspecialchars($animal['espece']); ?></p>
                <p><strong>Race :</strong> <?= htmlspecialchars($animal['race']); ?></p>
            </div>
        </div>

        <div class="description">
            <h2>Description :</h2>
            <p><?= htmlspecialchars($animal['description']); ?></p>
        </div>

        <form method="post" action="demande_adoption.php">
            <h3>Demande d'adoption :</h3>
            <label for="name">Nom :</label>
            <input type="text" id="name" name="name" required>

            <label for="prenom">Prénom :</label>
            <input type="text" id="prenom" name="prenom" required>

            <label for="birthdate">Date de naissance :</label>
            <input type="date" id="birthdate" name="birthdate" required>

            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required>

            <label for="description">Message :</label>
            <textarea id="description" name="description" rows="5" required></textarea>

            <input type="hidden" name="animal_id" value="<?= $animal['id']; ?>">

            <button type="submit">Envoyer</button>
        </form>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>
