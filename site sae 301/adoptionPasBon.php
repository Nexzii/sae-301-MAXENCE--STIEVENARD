<?php
// Charger la configuration et les classes nécessaires
require_once 'config.php';
require_once 'classes/Database.php';
require_once 'classes/DemandeAdoption.php';
require_once 'classes/Animal.php';

// Initialiser les classes nécessaires
$db = new Database();
$pdo = $db->getConnection();
$animal = new Animal($pdo);
$demandeAdoption = new DemandeAdoption($pdo);

// Récupérer tous les chats
$chats = $animal->getAllChats();

// Si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $chatId = $_POST['chat_id'];
    $nomFamille = $_POST['nom_famille'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $message = $_POST['message'];

    // Enregistrer la demande
    if ($demandeAdoption->create($chatId, $nomFamille, $email, $telephone, $message)) {
        echo "<p style='color: green;'>Votre demande a été soumise avec succès ! Nous vous contacterons bientôt.</p>";
    } else {
        echo "<p style='color: red;'>Une erreur est survenue. Veuillez réessayer.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demande d'Adoption</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <main>
        <h2>Faire une demande d'adoption</h2>
        <form method="POST" style="max-width: 600px; margin: 0 auto; background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);">
            <label for="chat_id">Choisissez un chat :</label>
            <select name="chat_id" id="chat_id" required>
                <option value="">-- Sélectionnez un chat --</option>
                <?php foreach ($chats as $chat): ?>
                    <option value="<?= htmlspecialchars($chat['id']) ?>"><?= htmlspecialchars($chat['nom']) ?></option>
                <?php endforeach; ?>
            </select>

            <label for="nom_famille">Votre nom :</label>
            <input type="text" name="nom_famille" id="nom_famille" required>

            <label for="email">Votre email :</label>
            <input type="email" name="email" id="email" required>

            <label for="telephone">Votre téléphone :</label>
            <input type="tel" name="telephone" id="telephone" required>

            <label for="message">Message (optionnel) :</label>
            <textarea name="message" id="message" rows="5"></textarea>

            <button type="submit" style="width: 100%; padding: 10px; background-color: #333; color: #fff; border: none; border-radius: 5px; cursor: pointer;">Soumettre la demande</button>
        </form>
    </main>
</body>
</html>
