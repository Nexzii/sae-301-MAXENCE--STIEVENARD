<?php
$host = 'localhost';
$dbname = 'site_articles';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}


return [
    'google_maps_api_key' => 'VOTRE_CLE_API',
    'petfinder_api_key' => 'VOTRE_CLE_API',
    'sendgrid_api_key' => 'VOTRE_CLE_API',
];
?>