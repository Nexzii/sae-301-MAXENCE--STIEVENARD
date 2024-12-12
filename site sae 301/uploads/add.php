<?php
// Liste des espèces et races associées
$especesRaces = [
    'Chat' => ['Persan', 'Siamois', 'Maine Coon', 'Européen'],
    'Chien' => ['Labrador', 'Berger Allemand', 'Golden Retriever', 'Bulldog']
];

// Si modification, récupérer les données de l'animal
if (isset($_GET['id'])) {
    include 'config.php';
    $stmt = $pdo->prepare("SELECT * FROM animaux WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $animal = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$animal) {
        echo "Animal non trouvé.";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($animal) ? 'Modifier' : 'Ajouter' ?> un Animal</title>
    <link rel="stylesheet" href="Style.css">
    <script>
        // Fonction pour mettre à jour dynamiquement les options de race
        function updateRaces() {
            const espece = document.getElementById('espece').value;
            const raceSelect = document.getElementById('race');
            const racesByEspece = <?= json_encode($especesRaces); ?>;

            raceSelect.innerHTML = '<option value="">Sélectionner une race</option>';
            if (racesByEspece[espece]) {
                racesByEspece[espece].forEach(race => {
                    const option = document.createElement('option');
                    option.value = race;
                    option.textContent = race;
                    raceSelect.appendChild(option);
                });
            }
        }
    </script>
</head>
<body>
    <h1><?= isset($animal) ? 'Modifier' : 'Ajouter' ?> un Animal</h1>
    <form method="post" action="<?= isset($animal) ? 'edit.php?id=' . $animal['id'] : 'add.php'; ?>">
        <label>Nom :</label><br>
        <input type="text" name="nom" value="<?= htmlspecialchars($animal['nom'] ?? '') ?>" required><br>

        <label>Description :</label><br>
        <textarea name="description" required><?= htmlspecialchars($animal['description'] ?? '') ?></textarea><br>

        <label>Espèce :</label><br>
        <select id="espece" name="espece" onchange="updateRaces()" required>
            <option value="">Sélectionner une espèce</option>
            <?php foreach (array_keys($especesRaces) as $espece): ?>
                <option value="<?= $espece ?>" <?= isset($animal) && $animal['espece'] === $espece ? 'selected' : '' ?>><?= $espece ?></option>
            <?php endforeach; ?>
        </select><br>

        <label>Race :</label><br>
        <select id="race" name="race" required>
            <option value="">Sélectionner une race</option>
            <?php if (isset($animal) && isset($especesRaces[$animal['espece']])): ?>
                <?php foreach ($especesRaces[$animal['espece']] as $race): ?>
                    <option value="<?= $race ?>" <?= $animal['race'] === $race ? 'selected' : '' ?>><?= $race ?></option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select><br>

        <label>Date de Naissance :</label><br>
        <input type="date" name="date_naissance" value="<?= htmlspecialchars($animal['date_naissance'] ?? '') ?>" required><br>

        <label>Sexe :</label><br>
        <select name="sexe" required>
            <option value="Mâle" <?= isset($animal) && $animal['sexe'] === 'Mâle' ? 'selected' : '' ?>>Mâle</option>
            <option value="Femelle" <?= isset($animal) && $animal['sexe'] === 'Femelle' ? 'selected' : '' ?>>Femelle</option>
        </select><br>

        <label>Lieu d'Adoption :</label><br>
        <input type="text" name="lieu_adoption" value="<?= htmlspecialchars($animal['lieu_adoption'] ?? '') ?>" required><br>

        <label>Photo (URL) :</label><br>
        <input type="url" name="photo" value="<?= htmlspecialchars($animal['photo'] ?? '') ?>" required><br>

        <button type="submit"><?= isset($animal) ? 'Modifier' : 'Ajouter' ?></button>
    </form>
</body>
</html>