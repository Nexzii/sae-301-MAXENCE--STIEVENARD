<?php
require_once __DIR__ . '/classes/Adoption.php';
require_once __DIR__ . '/classes/Database.php';

// Connexion à la base de données
$db = (new Database())->getConnection();
$adoption = new Adoption($db);

$error = "";
$success = "";

// Récupérer l'ID de l'animal depuis l'URL
$chat_id = $_GET['id'] ?? null;

if ($chat_id) {
    // Récupérer les détails de l'animal depuis la base de données
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
if ($adoption->creerDemande($chat_id, $prenom, $nom_famille, $date_naissance, $email, $telephone, $message)) {
    echo "success";  // Réponse pour indiquer le succès de l'insertion
} else {
    echo "failure";  // Réponse pour indiquer l'échec
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Demande d'Adoption</title>
    <script>
        // Affichage du pop-up de succès ou d'erreur
        function showPopup(message, isSuccess) {
            const popup = document.createElement('div');
            popup.style.position = 'fixed';
            popup.style.top = '50%';
            popup.style.left = '50%';
            popup.style.transform = 'translate(-50%, -50%)';
            popup.style.padding = '20px';
            popup.style.backgroundColor = isSuccess ? 'green' : 'red';
            popup.style.color = 'white';
            popup.style.borderRadius = '10px';
            popup.style.fontSize = '18px';
            popup.style.textAlign = 'center';
            popup.textContent = message;
            document.body.appendChild(popup);

            setTimeout(() => {
                popup.remove();
            }, 5000); // Ferme le pop-up après 5 secondes
        }

        // Soumission du formulaire via AJAX
        document.addEventListener("DOMContentLoaded", function () {
            const form = document.getElementById('adoptionForm');

            form.addEventListener('submit', function (e) {
                e.preventDefault();  // Empêche la soumission normale du formulaire

                const formData = new FormData(form);

                // Envoi de la requête AJAX
                fetch('formulaireadoption.php?id=<?= $animal['id']; ?>', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    if (data.includes("success")) {
                        showPopup("Votre demande d'adoption a été envoyée avec succès.", true);
                    } else {
                        showPopup("Erreur lors de l'envoi de votre demande.", false);
                    }
                })
                .catch(error => {
                    showPopup("Erreur de connexion. Veuillez réessayer.", false);
                });
            });
        });
    </script>
</head>
<body>
    <h1>Demande d'Adoption pour <?= htmlspecialchars($animal['nom']); ?></h1>
    <img src="<?= htmlspecialchars($animal['photo']); ?>" alt="Photo de <?= htmlspecialchars($animal['nom']); ?>" width="200">
    <p><strong>Espèce :</strong> <?= htmlspecialchars($animal['espece']); ?></p>
    <p><strong>Race :</strong> <?= htmlspecialchars($animal['race']); ?></p>
    <p><strong>Description :</strong> <?= nl2br(htmlspecialchars($animal['description'])); ?></p>
    <p><strong>Sexe :</strong> <?= htmlspecialchars($animal['sexe']); ?></p>

    <form id="adoptionForm" method="POST">
        <input type="hidden" name="chat_id" value="<?= $animal['id']; ?>">

        <label for="prenom">Prénom :</label>
        <input type="text" id="prenom" name="prenom" required><br>

        <label for="nom_famille">Nom de famille :</label>
        <input type="text" id="nom_famille" name="nom_famille" required><br>

        <label for="date_naissance">Date de naissance :</label>
        <input type="date" id="date_naissance" name="date_naissance" required><br>

        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required><br>

        <label for="telephone">Téléphone :</label>
        <input type="text" id="telephone" name="telephone" required><br>

        <label for="message">Message :</label>
        <textarea id="message" name="message" rows="4" required></textarea><br>

        <button type="submit">Envoyer la Demande</button>
    </form>
</body>
</html>
