<?php
// config/db.php

$host = '127.0.0.1'; // ou 'localhost'
$dbname = 'qcm_app';
$username = 'root';
$password = 'root'; // À modifier si vous avez un mot de passe root sur MAMP

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    
    // Configuration pour générer des exceptions en cas d'erreur
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Mode de récupération par défaut : tableau associatif
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Ne pas exposer les détails de l'erreur en production, mais utile en dev
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
