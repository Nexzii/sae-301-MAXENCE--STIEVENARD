<?php
include 'config.php';
include 'header.php';

// Liste des espèces et races
$especesRaces = [
    'Chat' => ['Persan', 'Siamois', 'Maine Coon', 'Européen'],
    'Chien' => ['Labrador', 'Berger Allemand', 'Golden Retriever', 'Bulldog']
];

// Ajouter un animal dans la base de données
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nom'])) {
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $espece = $_POST['espece'];
    $race = $_POST['race'];
    $date_naissance = $_POST['date_naissance'];
    $sexe = $_POST['sexe'];
    $lieu_adoption = $_POST['lieu_adoption'];
    $photo = $_POST['photo'];

    $stmt = $pdo->prepare("INSERT INTO animaux (nom, description, espece, race, date_naissance, sexe, lieu_adoption, photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$nom, $description, $espece, $race, $date_naissance, $sexe, $lieu_adoption, $photo]);

    // Rediriger vers la même page après l'ajout
    header("Location: admin.php");
    exit;
}

// Supprimer une demande d'adoption
if (isset($_GET['delete_demande_id'])) {
    $demandeId = $_GET['delete_demande_id'];

    $stmt = $pdo->prepare("DELETE FROM demandes WHERE id = ?");
    $stmt->execute([$demandeId]);

    // Rediriger vers la même page après la suppression
    header("Location: admin.php");
    exit;
}

// Récupérer tous les animaux pour affichage
$query = $pdo->query("SELECT * FROM animaux ORDER BY created_at DESC");
$animaux = $query->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les demandes d'adoption
$query = $pdo->query("
    SELECT d.id, d.nom_famille, d.email, d.telephone, d.message, d.date_demande, a.nom AS nom_chat, a.description AS description_chat
    FROM demandes d
    JOIN animaux a ON d.chat_id = a.id
    ORDER BY d.date_demande DESC
");
$demandes = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration des Animaux</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <main>
        <h1>Administration des Animaux</h1>

        <!-- Formulaire pour ajouter un animal -->
        <div class="form-container">
            <h2>Ajouter un Animal</h2>
            <form method="post" action="admin.php">
                <input type="text" name="nom" placeholder="Nom" required>
                <textarea name="description" placeholder="Description" required></textarea>
                <select id="espece" name="espece" onchange="updateRaces()" required>
                    <option value="">Sélectionner une espèce</option>
                    <?php foreach (array_keys($especesRaces) as $espece): ?>
                        <option value="<?= htmlspecialchars($espece) ?>"><?= htmlspecialchars($espece) ?></option>
                    <?php endforeach; ?>
                </select>
                <select id="race" name="race" required>
                    <option value="">Sélectionner une race</option>
                </select>
                <input type="date" name="date_naissance" required>
                <select name="sexe" required>
                    <option value="Mâle">Mâle</option>
                    <option value="Femelle">Femelle</option>
                </select>
                <input type="text" name="lieu_adoption" placeholder="Lieu d'adoption" required>
                <input type="url" name="photo" placeholder="URL de la photo" required>
                <button type="submit">Ajouter</button>
            </form>
        </div>

        <!-- Liste des animaux -->
        <h2>Liste des Animaux</h2>
        <div class="grid-container">
            <?php foreach ($animaux as $animal): ?>
                <div class="card">
                    <img src="<?= htmlspecialchars($animal['photo']) ?>" alt="Photo de <?= htmlspecialchars($animal['nom']) ?>">
                    <div class="info">
                        <p><strong>Nom :</strong> <?= htmlspecialchars($animal['nom']) ?></p>
                        <p><strong>Date de naissance :</strong> <?= htmlspecialchars($animal['date_naissance']) ?></p>
                        <p><strong>Sexe :</strong> <?= htmlspecialchars($animal['sexe']) ?></p>
                        <p><strong>Espèce :</strong> <?= htmlspecialchars($animal['espece']) ?></p>
                        <p><strong>Race :</strong> <?= htmlspecialchars($animal['race']) ?></p>
                    </div>
                    <div class="actions">
                        <a href="edit.php?id=<?= $animal['id'] ?>" class="edit">Modifier</a>
                        <a href="delete.php?id=<?= $animal['id'] ?>" class="delete" onclick="return confirm('Voulez-vous vraiment supprimer cet animal ?')">Supprimer</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Liste des demandes -->
        <h2>Demandes d'Adoption</h2>
        <div class="grid-container">
            <?php foreach ($demandes as $demande): ?>
                <div class="demande-card">
                    <p><strong>Nom du Chat :</strong> <?= htmlspecialchars($demande['nom_chat']) ?></p>
                    <p><strong>Famille :</strong> <?= htmlspecialchars($demande['nom_famille']) ?></p>
                    <p><strong>Email :</strong> <?= htmlspecialchars($demande['email']) ?></p>
                    <p><strong>Téléphone :</strong> <?= htmlspecialchars($demande['telephone']) ?></p>
                    <p><strong>Message :</strong> <?= nl2br(htmlspecialchars($demande['message'])) ?></p>
                    <p><strong>Description du Chat :</strong> <?= htmlspecialchars($demande['description_chat']) ?></p>
                    <p class="date"><em>Demande envoyée le : <?= htmlspecialchars($demande['date_demande']) ?></em></p>
                    <a href="?delete_demande_id=<?= $demande['id'] ?>" class="delete-btn" onclick="return confirm('Voulez-vous vraiment supprimer cette demande ?')">Supprimer</a>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
    <script>
        const especesRaces = <?php echo json_encode($especesRaces); ?>;
        const especeSelect = document.getElementById('espece');
        const raceSelect = document.getElementById('race');

        function updateRaces() {
            const espece = especeSelect.value;
            raceSelect.innerHTML = '<option value="">Sélectionner une race</option>';
            if (espece && especesRaces[espece]) {
                especesRaces[espece].forEach(function(race) {
                    const option = document.createElement('option');
                    option.value = race;
                    option.textContent = race;
                    raceSelect.appendChild(option);
                });
            }
        }
    </script>
</body>
</html>
