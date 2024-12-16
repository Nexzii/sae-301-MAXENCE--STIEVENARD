<?php
class FormulaireAdoption {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function traiter($chatId) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = htmlspecialchars($_POST['nom_famille']);
            $prenom = htmlspecialchars($_POST['prenom']);
            $email = htmlspecialchars($_POST['email']);
            $message = htmlspecialchars($_POST['message']);

            $stmt = $this->pdo->prepare("
                INSERT INTO demandes (nom_famille, prenom, email, message, chat_id)
                VALUES (:nom_famille, :prenom, :email, :message, :chat_id)
            ");
            $stmt->execute([
                ':nom_famille' => $nom,
                ':prenom' => $prenom,
                ':date_naissance' => $date_naissance,
                ':email' => $email,
                ':message' => $message,
                ':chat_id' => $chatId
            ]);

            return "Demande envoyée avec succès pour le chat ID : $chatId.";
        }
        return null;
    }
}
?>
