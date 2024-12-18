<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config.php';



$response = ['success' => false, 'data' => []];

try {
    $espece = isset($_GET['espece']) ? htmlspecialchars($_GET['espece']) : '';
    $race = isset($_GET['race']) ? htmlspecialchars($_GET['race']) : '';
    $sexe = isset($_GET['sexe']) ? htmlspecialchars($_GET['sexe']) : '';
    $age = isset($_GET['age']) ? htmlspecialchars($_GET['age']) : '';

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

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $response['success'] = true;
    $response['data'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $response['error'] = "Erreur : " . $e->getMessage();
}

echo json_encode($response);
exit;
?>
