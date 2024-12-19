<?php
require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/classes/Adoption.php';
include "header.php";

// Connexion à la base de données
$db = (new Database())->getConnection();
$adoption = new Adoption($db);

// Récupération de l'ID de l'animal depuis l'URL
$chat_id = $_GET['id'] ?? null;

if ($chat_id) {
    // Préparer la requête pour obtenir les détails de l'animal
    $query = "SELECT * FROM animaux WHERE id = :chat_id LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':chat_id', $chat_id);
    $stmt->execute();

    $animal = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$animal) {
        echo "Animal non trouvé.";
        exit();
    }
} else {
    echo "ID de l'animal manquant.";
    exit();
}

$error = "";
$success = "";

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prenom = $_POST['prenom'] ?? null;
    $nom_famille = $_POST['nom_famille'] ?? null;
    $date_naissance = $_POST['date_naissance'] ?? null;
    $email = $_POST['email'] ?? null;
    $telephone = $_POST['telephone'] ?? null;
    $message = $_POST['message'] ?? null;

    // Vérification des champs
    if ($prenom && $nom_famille && $date_naissance && $email && $telephone && $message) {
        // Enregistrer la demande
        if ($adoption->creerDemande($chat_id, $prenom, $nom_famille, $date_naissance, $email, $telephone, $message)) {
            echo json_encode(["status" => "success", "message" => "Votre demande d'adoption a été envoyée avec succès."]);
            exit();
        } else {
            echo json_encode(["status" => "error", "message" => "Erreur lors de l'envoi de votre demande."]);
            exit();
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Tous les champs sont requis."]);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($animal['nom']); ?>, <?= htmlspecialchars($animal['race']); ?> à Adopter à Aulnat (63) | Amis 4 Pattes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <meta name="description" content="Découvrez <?= htmlspecialchars($animal['nom']); ?>, un <?= htmlspecialchars($animal['race']); ?> à adopter à Aulnat près de Clermont-Ferrand (63). Donnez-lui la chance de trouver un foyer aimant.">
    <link rel="stylesheet" href="css/chat.css">
    <link href="../css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">

        <div class="row align-items-center">
            <!-- Image de l'animal -->
            <div class="col-md-4 text-center">
                <img src="<?= htmlspecialchars($animal['photo']); ?>" alt="Photo de <?= htmlspecialchars($animal['nom']); ?>" class="img-fluid rounded-3">
            </div>

            <!-- Détails de l'animal -->
            <div class="col-md-8">
                <h1 class="animal-name"><?= htmlspecialchars($animal['nom']); ?></h1>
                <p><strong>Espèce :</strong> <?= htmlspecialchars($animal['espece']); ?></p>
                <p><strong>Race :</strong> <?= htmlspecialchars($animal['race']); ?></p>
                <p><strong>Description :</strong> <?= nl2br(htmlspecialchars($animal['description'])); ?></p>
                <p><strong>Sexe :</strong> <?= htmlspecialchars($animal['sexe']); ?></p>
            </div>
        </div>
        <h3>Adopter</h3>
        <!-- Formulaire de demande d'adoption -->
        <form id="adoptionForm" method="POST" class="mt-5">
            <input type="hidden" name="chat_id" value="<?= $animal['id']; ?>">

            <div class="form-container">
                <div class="mb-3">
                    <label for="prenom" class="form-label">Prénom</label>
                    <input type="text" id="prenom" name="prenom" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="nom_famille" class="form-label">Nom de famille</label>
                    <input type="text" id="nom_famille" name="nom_famille" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="date_naissance" class="form-label">Date de naissance</label>
                    <input type="date" id="date_naissance" name="date_naissance" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="telephone" class="form-label">Téléphone</label>
                    <input type="text" id="telephone" name="telephone" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="message" class="form-label">Message</label>
                    <textarea id="message" name="message" class="form-control" rows="4" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Envoyer la Demande</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php include "footer.php";
?>
