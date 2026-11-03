<?php
$host = "localhost";
$port = "5432";
$dbname = "lab-ba"; 
$user = "postgres"; 
$password = "your_password";    

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}