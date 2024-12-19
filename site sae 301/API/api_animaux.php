<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config.php';

$response = ['success' => false, 'data' => []];

try {
    $espece = isset($_GET['espece']) ? htmlspecialchars($_GET['espece']) : '';
    $race = isset($_GET['race']) ? htmlspecialchars($_GET['race']) : '';
    $sexe = isset($_GET['sexe']) ? htmlspecialchars($_GET['sexe']) : '';
    $age = isset($_GET['age']) ? htmlspecialchars($_GET['age']) : '';
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 8; // Par défaut, 8 animaux
    $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;

    // Requête de base
    $query = "SELECT * FROM animaux WHERE 1=1";
    $params = [];

    if (!empty($espece)) {
        $query .= " AND espece = ?";
        $params[] = $espece;
    }
    if (!empty($race)) {
        $query .= " AND race = ?";
        $params[] = $race;
    }
    if (!empty($sexe)) {
        $query .= " AND sexe = ?";
        $params[] = $sexe;
    }
    if (!empty($age)) {
        $query .= " AND age = ?";
        $params[] = $age;
    }

    // Ajout des paramètres LIMIT et OFFSET sans passer par bindParam
    $query .= " LIMIT $limit OFFSET $offset";

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $response['success'] = true;
    $response['data'] = $result;
} catch (PDOException $e) {
    $response['error'] = "Erreur : " . $e->getMessage();
}

echo json_encode($response);
exit;
?>
