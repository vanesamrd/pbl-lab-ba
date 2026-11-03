<?php
$host = getenv('DB_HOST') ?: "localhost";
$port = getenv('DB_PORT') ?: "5432";
$dbname = getenv('DB_DATABASE') ?: "lab-ba"; 
$user = getenv('DB_USERNAME') ?: "postgres"; 
$password = getenv('DB_PASSWORD') ?: "your_password";    

$dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
if ($host !== 'localhost' && $host !== '127.0.0.1') {
    $dsn .= ";sslmode=require";
}

try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
