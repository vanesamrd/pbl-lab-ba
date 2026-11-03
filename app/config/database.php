<?php
$host = getenv('DB_HOST') ?: "localhost";
$port = getenv('DB_PORT') ?: "5432";
$dbname = getenv('DB_DATABASE') ?: "lab-ba"; 
$user = getenv('DB_USERNAME') ?: "postgres"; 
$password = getenv('DB_PASSWORD') ?: "your_password";    

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
