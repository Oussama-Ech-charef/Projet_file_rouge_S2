<?php
// config/database.php
// Connexion PDO centralisée - utilisation obligatoire de try/catch + PDOException
// Toutes les pages/actions doivent inclure ce fichier pour accéder à $pdo

$host = '127.0.0.1';
$dbname = 'cabinet_medical';
$username = 'root';
$password = '12345678';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

$pdo = null;
$pdo_error = null;
try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    // Connexion échouée : on conserve l'erreur pour affichage et on laisse $pdo à null
    // Cela permet à la Home de fonctionner en fallback statique (6 spécialités) même sans MySQL
    // Les pages Dashboard/View afficheront un message d'erreur explicite
    $pdo_error = $e->getMessage();
    $pdo = null;
}
