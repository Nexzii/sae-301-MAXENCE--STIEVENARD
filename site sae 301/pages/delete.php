<?php
include '../config.php';

$id = $_GET['id'];

// Récupérer l'animal pour supprimer la photo
$query = $pdo->prepare("SELECT photo FROM animaux WHERE id = ?");
$query->execute([$id]);
$animal = $query->fetch();

// Supprimer la photo du serveur
if ($animal && file_exists($animal['photo'])) {
    unlink($animal['photo']);
}

// Supprimer l'animal de la base de données
$stmt = $pdo->prepare("DELETE FROM animaux WHERE id = ?");
$stmt->execute([$id]);

header('Location: admin.php');
exit;
?>
