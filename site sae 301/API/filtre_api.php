<?php
include_once './classes/animal.php';

// Database connection
$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

class AnimalAPI {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAnimals($filters = []) {
        $query = "SELECT * FROM animaux";
        $conditions = [];
        $params = [];

        if (!empty($filters)) {
            foreach ($filters as $key => $value) {
                if ($key === 'age') {
                    $conditions[] = "TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) = :age";
                    $params[':age'] = $value;
                } else {
                    $conditions[] = "$key = :$key";
                    $params[":$key"] = $value;
                }
            }
        }

        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function handleRequest() {
        try {
            $filters = [];

            if (!empty($_GET)) {
                $filters = $_GET;
            }

            $animals = $this->getAnimals($filters);

            echo json_encode([
                'success' => true,
                'data' => $animals
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}

// API usage
$api = new AnimalAPI($pdo);
$api->handleRequest();
?>
