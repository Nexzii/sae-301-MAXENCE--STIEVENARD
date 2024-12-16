<?php
session_start();
require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/User.php';

$error = "";

// Traitement du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;

    if ($email && $password) {
        // Connexion à la base de données
        $db = (new Database())->getConnection();
        $user = new User($db);

        // Essayer de se connecter avec l'email et le mot de passe fournis
        $login_user = $user->login($email, $password);

        if ($login_user) {
            // Si l'utilisateur est trouvé, stocker ses informations dans la session
            $_SESSION['user_id'] = $login_user['id'];
            $_SESSION['username'] = $login_user['username'];

            // Rediriger vers la page d'administration après une connexion réussie
            header('Location: admin.php');  // Redirige vers la page d'administration
            exit();
        } else {
            $error = "Email ou mot de passe incorrect."; // Affiche un message d'erreur si la connexion échoue
        }
    } else {
        $error = "Veuillez entrer un email et un mot de passe."; // Affiche un message si les champs sont vides
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="auth.css">
    <title>Connexion</title>
</head>
<body>
    <?php include '../header.php'; ?> <!-- Assure-toi que le chemin vers header.php est correct -->

    <h1>Connexion</h1>
  
    <?php if ($error): ?>
        <p style="color: red;"><?= htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="POST" action="auth.php">
        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required><br>

        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required><br>

        <button type="submit">Se connecter</button>
    </form>
</body>
</html>
