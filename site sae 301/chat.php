<?php
require_once 'classes/animal.php'; 
require_once 'config.php'; // Connexion à la base de données
require_once 'classes/formulaireadoption.php';
require_once 'header.php';

// Récupération de l'ID depuis l'URL
$idAnimal = isset($_GET['id']) ? (int)$_GET['id'] : 0;

try {
    // Chargement de l'animal
    $animal = new Animal($pdo);
    $animal->charger($idAnimal);
    $animalData = $animal->getData();

    // Gestion du formulaire
    $formulaire = new FormulaireAdoption($pdo);
    $successMessage = $formulaire->traiter($idAnimal);
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adoption</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="chat.css">
</head>
<body>
    <main class="container mt-4">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php elseif ($animalData): ?>
            <!-- Informations sur l'animal -->
            <div class="animal-info">
                <div class="image">
                    <img src="<?= htmlspecialchars($animalData['photo']) ?>" alt="Photo de l'animal">
                </div>
                <div class="details">
                    <h1><?= htmlspecialchars($animalData['nom']) ?></h1>
                    <p><strong>Date de naissance :</strong> <?= htmlspecialchars($animalData['date_naissance']) ?></p>
                    <p><strong>Sexe :</strong> <?= htmlspecialchars($animalData['sexe']) ?></p>
                    <p><strong>Espèce :</strong> <?= htmlspecialchars($animalData['espece']) ?></p>
                    <p><strong>Race :</strong> <?= htmlspecialchars($animalData['race']) ?></p>
                </div>
            </div>
            <!-- Formulaire d'adoption -->
            <h2 class="my-4">Formulaire d'adoption</h2>
            <form method="POST" action="">
                <div class="form-container">
                    <div class="mb-3">
                        <label for="nom_famille" class="form-label">Nom de famille</label>
                        <input type="text" class="form-control" id="nom_famille" name="nom_famille" required>
                    </div>
                    <div class="mb-3">
                        <label for="prenom" class="form-label">Prénom</label>
                        <input type="text" class="form-control" id="prenom" name="prenom" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Adopter</button>
                </div>
            </form>
            <!-- Message de succès -->
            <?php if (isset($successMessage)): ?>
                <div class="alert alert-success mt-4"><?= htmlspecialchars($successMessage) ?></div>
            <?php endif; ?>
        <?php endif; ?>
    </main>
    <?php require_once 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
