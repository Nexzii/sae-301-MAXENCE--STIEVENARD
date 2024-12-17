<?php
include '../config.php';

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
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="form-container form-control mb-4">
        <div>
            <h2 class="card-title">Modifier <?= htmlspecialchars($chat['nom']) ?></h2>
            <form method="post">
                <div class="">
                    <label for="nom" class="form-label">Nom :</label>
                    <input type="text" class="form-control" id="nom" name="nom" value="<?= htmlspecialchars($chat['nom']) ?>" required>
                </div>

                <div class="">
                    <label for="espece" class="form-label">Espèce :</label>
                    <input type="text" class="form-control" id="espece" name="espece" value="<?= htmlspecialchars($chat['espece']) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="race" class="form-label">Race :</label>
                    <input type="text" class="form-control" id="race" name="race" value="<?= htmlspecialchars($chat['race']) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="date_naissance" class="form-label">Date de Naissance :</label>
                    <input type="date" class="form-control" id="date_naissance" name="date_naissance" value="<?= htmlspecialchars($chat['date_naissance']) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="sexe" class="form-label">Sexe :</label>
                    <select id="sexe" class="form-select" name="sexe" required>
                        <option value="Mâle" <?= $chat['sexe'] === 'Mâle' ? 'selected' : '' ?>>Mâle</option>
                        <option value="Femelle" <?= $chat['sexe'] === 'Femelle' ? 'selected' : '' ?>>Femelle</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="lieu_adoption" class="form-label">Lieu d'Adoption :</label>
                    <input type="text" class="form-control" id="lieu_adoption" name="lieu_adoption" value="<?= htmlspecialchars($chat['lieu_adoption']) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="photo" class="form-label">URL de la Photo :</label>
                    <input type="url" class="form-control" id="photo" name="photo" value="<?= htmlspecialchars($chat['photo']) ?>" required>
                </div>
                    <button type="submit" class="btn btn-primary">Modifier</button>
            </form>
        </div>
    </div>
</body>
</html>
