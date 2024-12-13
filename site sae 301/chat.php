<?php
// Inclure les fichiers existants
include 'header.php';
include 'config.php'; // Fichier contenant la connexion à la base de données

// Classe pour représenter un animal
class Animal {
    private $pdo;
    private $data;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function charger($idAnimal) {
        $stmt = $this->pdo->prepare("SELECT * FROM animaux WHERE id = :id");
        $stmt->bindParam(':id', $idAnimal, PDO::PARAM_INT);
        $stmt->execute();
        $this->data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$this->data) {
            throw new Exception("Animal introuvable.");
        }
    }

    public function afficher() {
        if ($this->data) {
            echo '
            <div class="animal-info">
                <div class="image">
                    <img src="' . htmlspecialchars($this->data['photo']) . '" alt="Photo de l\'animal">
                </div>
                <div class="details">
                    <h1>' . htmlspecialchars($this->data['nom']) . '</h1>
                    <p><strong>Date de naissance :</strong> ' . htmlspecialchars($this->data['date_naissance']) . '</p>
                    <p><strong>Sexe :</strong> ' . htmlspecialchars($this->data['sexe']) . '</p>
                    <p><strong>Espèce :</strong> ' . htmlspecialchars($this->data['espece']) . '</p>
                    <p><strong>Race :</strong> ' . htmlspecialchars($this->data['race']) . '</p>
                </div>
            </div>
            <div class="description">
                <p>' . htmlspecialchars($this->data['description']) . '</p>
            </div>';
        } else {
            echo '<p>Aucun animal trouvé.</p>';
        }
    }

    public function getId() {
        return $this->data['id'];
    }
}

// Classe pour gérer le formulaire d'adoption
class FormulaireAdoption {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function afficher() {
        echo '
        <form method="POST" action="">
            <input type="text" name="nom_famille" placeholder="Nom" required>
            <input type="text" name="prenom" placeholder="Prénom" required>
            <input type="date" name="date_naissance" placeholder="Date de naissance" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="tel" name="telephone" placeholder="Téléphone" required>
            <textarea name="message" placeholder="Message" rows="5" required></textarea>
            <button type="submit">Adopter</button>
        </form>';
    }

    public function traiter($chatId) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = htmlspecialchars($_POST['nom_famille']);
            $prenom = htmlspecialchars($_POST['prenom']);
            $dateNaissance = htmlspecialchars($_POST['date_naissance']);
            $email = htmlspecialchars($_POST['email']);
            $telephone = htmlspecialchars($_POST['telephone']);
            $message = htmlspecialchars($_POST['message']);

            // Préparation de la requête SQL
            $stmt = $this->pdo->prepare("INSERT INTO demandes (nom_famille, prenom, date_naissance, email, telephone, message, chat_id)
                                         VALUES (:nom_famille, :prenom, :date_naissance, :email, :telephone, :message, :chat_id)");
            // Exécution avec les données
            $stmt->execute([
                ':nom_famille' => $nom,
                ':prenom' => $prenom,
                ':date_naissance' => $dateNaissance,
                ':email' => $email,
                ':telephone' => $telephone,
                ':message' => $message,
                ':chat_id' => $chatId
            ]);

            echo "<p>Merci $prenom $nom, votre demande d'adoption a été envoyée avec succès pour le chat ID : $chatId.</p>";
        }
    }
}

// Récupérer l'ID de l'animal depuis l'URL
$idAnimal = isset($_GET['id']) ? (int)$_GET['id'] : 0;

try {
    // Charger l'animal
    $animal = new Animal($pdo);
    $animal->charger($idAnimal);

    // Gérer le formulaire
    $formulaire = new FormulaireAdoption($pdo);
} catch (Exception $e) {
    $animal = null;
    echo "<p>" . $e->getMessage() . "</p>";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adoption</title>
    <link rel="stylesheet" href="chat.css">
</head>
<body>
    <main>
        <!-- Afficher les informations de l'animal -->
        <?php if ($animal) $animal->afficher(); ?>
        <div class="form-container">
        <!-- Afficher le formulaire -->
        <?php if ($animal) $formulaire->afficher(); ?>

        <!-- Traiter le formulaire -->
        <?php if ($animal) $formulaire->traiter($animal->getId()); ?>
        </div>
    </main>
</body>
</html>
