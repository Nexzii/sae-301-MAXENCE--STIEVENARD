<?php
require_once __DIR__ . '/Database.php';

class Adoption
{
    private $conn;
    private $table = 'demandes'; // Table pour stocker les demandes d'adoption

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Méthode pour créer une demande d'adoption
    public function creerDemande($chat_id, $prenom, $nom_famille, $date_naissance, $email, $telephone, $message)
    {
        try {
            $query = "INSERT INTO {$this->table} (chat_id, prenom, nom_famille, date_naissance, email, telephone, message, date_demande)
                      VALUES (:chat_id, :prenom, :nom_famille, :date_naissance, :email, :telephone, :message, NOW())";

            $stmt = $this->conn->prepare($query);

            // Lier les paramètres
            $stmt->bindParam(':chat_id', $chat_id);
            $stmt->bindParam(':prenom', $prenom);
            $stmt->bindParam(':nom_famille', $nom_famille);
            $stmt->bindParam(':date_naissance', $date_naissance);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':telephone', $telephone);
            $stmt->bindParam(':message', $message);

            // Vérifier si l'exécution de la requête a réussi
            if ($stmt->execute()) {
                return true;
            } else {
                // Affichage des erreurs d'exécution si la requête échoue
                $errorInfo = $stmt->errorInfo();
                die("Erreur lors de l'exécution de la requête : " . $errorInfo[2]);
            }
        } catch (PDOException $e) {
            die("Erreur lors de la création de la demande : " . $e->getMessage());
        }
    }
}
?>
