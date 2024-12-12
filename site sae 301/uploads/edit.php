<?php
include 'config.php';

// Récupérer l'ID du chat à modifier
$id = $_GET['id'] ?? null;

if (!$id) {
    die("Chat introuvable.");
}

// Requête pour récupérer les détails du chat
$query = $pdo->prepare("SELECT * FROM animaux WHERE id = ?");
$query->execute([$id]);
$chat = $query->fetch();

if (!$chat) {
    die("Chat introuvable.");
}

// Modifier le chat
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'] ?? '';
    $espece = $_POST['espece'] ?? '';
    $race = $_POST['race'] ?? '';
    $date_naissance = $_POST['date_naissance'] ?? '';
    $sexe = $_POST['sexe'] ?? '';
    $lieu_adoption = $_POST['lieu_adoption'] ?? '';
    $photo = $_POST['photo'] ?? '';

    try {
        $stmt = $pdo->prepare("UPDATE animaux SET nom = ?, espece = ?, race = ?, date_naissance = ?, sexe = ?, lieu_adoption = ?, photo = ? WHERE id = ?");
        $stmt->execute([$nom, $espece, $race, $date_naissance, $sexe, $lieu_adoption, $photo, $id]);
        header("Location: admin.php");
        exit;
    } catch (PDOException $e) {
        die("Erreur SQL : " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier <?= htmlspecialchars($chat['nom']) ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Modifier <?= htmlspecialchars($chat['nom']) ?></h1>
    <form method="post">
        <label>Nom :</label><br>
        <input type="text" name="nom" value="<?= htmlspecialchars($chat['nom']) ?>" required><br>

        <label>Espèce :</label><br>
        <input type="text" name="espece" value="<?= htmlspecialchars($chat['espece']) ?>" required><br>

        <label>Race :</label><br>
        <input type="text" name="race" value="<?= htmlspecialchars($chat['race']) ?>" required><br>

        <label>Date de Naissance :</label><br>
        <input type="date" name="date_naissance" value="<?= htmlspecialchars($chat['date_naissance']) ?>" required><br>

        <label>Sexe :</label><br>
        <select name="sexe" required>
            <option value="Mâle" <?= $chat['sexe'] === 'Mâle' ? 'selected' : '' ?>>Mâle</option>
            <option value="Femelle" <?= $chat['sexe'] === 'Femelle' ? 'selected' : '' ?>>Femelle</option>
        </select><br>

        <label>Lieu d'Adoption :</label><br>
        <input type="text" name="lieu_adoption" value="<?= htmlspecialchars($chat['lieu_adoption']) ?>" required><br>

        <label>URL de la Photo :</label><br>
        <input type="url" name="photo" value="<?= htmlspecialchars($chat['photo']) ?>" required><br>

        <button type="submit">Modifier</button>
    </form>
</body>
</html>
