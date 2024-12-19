
<?php
include '../config.php';

session_start(); // Démarre la session

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // Redirige vers la page de connexion si non connecté
    header("Location: auth.php");
    exit;
}



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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="admin.css" rel="stylesheet">
</head>
<body>
<?php include '../header.php'; ?>
<main class="container my-5">
    <h1 class="text-center mb-4">Administration des Animaux</h1>

    <!-- Formulaire pour ajouter un animal -->
    <div class="form-container mb-4">
        <div>
            <h2 class="card-title">Ajouter un Animal</h2>
            <form method="post" action="admin.php" >
                <div>
                    <label for="nom" class="form-label"></label>
                    <input type="text" class="form-control" id="nom" name="nom" placeholder="Nom" required>
                </div>
                <div>
                    <label for="description" class="form-label"></label>
                    <textarea class="form-control" id="description" name="description" placeholder="Description" required></textarea>
                </div>
                <div>
                    <label for="espece" class="form-label"></label>
                    <select id="espece" class="form-select" name="espece" onchange="updateRaces()" required>
                        <option value="">Sélectionner une espèce</option>
                        <?php foreach (array_keys($especesRaces) as $espece): ?>
                            <option value="<?= htmlspecialchars($espece) ?>"><?= htmlspecialchars($espece) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="race" class="form-label"></label>
                    <select id="race" class="form-select" name="race" required>
                        <option value="">Sélectionner une race</option>
                    </select>
                </div>
                <div>
                    <label for="date_naissance" class="form-label"></label>
                    <input type="date" class="form-control" id="date_naissance" name="date_naissance" required>
                </div>
                <div>
                    <label for="sexe" class="form-label"></label>
                    <select id="sexe" class="form-select" name="sexe" required>
                        <option value="Mâle">Mâle</option>
                        <option value="Femelle">Femelle</option>
                    </select>
                </div>
                <div>
                    <label for="lieu_adoption" class="form-label"></label>
                    <input type="text" class="form-control" id="lieu_adoption" name="lieu_adoption" placeholder="Lieu d'adoption" required>
                </div>
                <div>
                    <label for="photo" class="form-label"></label>
                    <input type="url" class="form-control" id="photo" name="photo" placeholder="URL de la photo" required>
                </div>
                <div class="pt-5 d-flex justify-content-center">
                <button type="submit" class="btn btn-primary d-flex justify-content-center">Ajouter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des animaux -->
    <h2 class="mb-3">Liste des Animaux</h2>
    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php foreach ($animaux as $animal): ?>
            <div class="col">
                <div class="card h-100 custom-card">
                    <img src="<?= htmlspecialchars($animal['photo']) ?>" class="card-img-top" alt="Photo de <?= htmlspecialchars($animal['nom']) ?>">
                    <div class="card-body">
                        <h5 class="card-title">Nom : <?= htmlspecialchars($animal['nom']) ?></h5>
                        <p class="card-text">Date de naissance : <?= htmlspecialchars($animal['date_naissance']) ?></p>
                        <p class="card-text">Sexe : <?= htmlspecialchars($animal['sexe']) ?></p>
                        <p class="card-text">Espèce : <?= htmlspecialchars($animal['espece']) ?></p>
                        <p class="card-text">Race : <?= htmlspecialchars($animal['race']) ?></p>
                    </div>
                    <div class="card-footer d-flex justify-content-between card-custom-blue">
                        <a href="edit.php?id=<?= $animal['id'] ?>" class="btn btn-warning">Modifier</a>
                        <a href="delete.php?id=<?= $animal['id'] ?>" class="btn btn-danger" onclick="return confirm('Voulez-vous vraiment supprimer cet animal ?')">Supprimer</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Liste des demandes -->
    <h2 class="mt-5 mb-3">Demandes d'Adoption</h2>
    <div class="row row-cols-1 g-4 ">
        <?php foreach ($demandes as $demande): ?>
            <div class="col">
                <div class="mb-3 rounded-4 mbdf ">
                    <div class="m-3">
                        <h5 class="card-title">Nom du Chat : <?= htmlspecialchars($demande['nom_chat']) ?></h5>
                        <p class="card-text">Famille : <?= htmlspecialchars($demande['nom_famille']) ?></p>
                        <p class="card-text">Email : <?= htmlspecialchars($demande['email']) ?></p>
                        <p class="card-text">Téléphone : <?= htmlspecialchars($demande['telephone']) ?></p>
                        <p class="card-text">Message : <?= nl2br(htmlspecialchars($demande['message'])) ?></p>
                        <p class="card-text">Description du Chat : <?= htmlspecialchars($demande['description_chat']) ?></p>
                        <p class="card-text text-muted"><em>Demande envoyée le : <?= htmlspecialchars($demande['date_demande']) ?></em></p>
                    </div>
                    <div class="card-footer2 text-end">
                        <a href="?delete_demande_id=<?= $demande['id'] ?>" class="btn btn-danger m-3" onclick="return confirm('Voulez-vous vraiment supprimer cette demande ?')">Supprimer</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
