<?php
require_once __DIR__ . "/../config/database.php";

function logActivity($aktivitas) {
    if(!isset($_SESSION)) { session_start(); }

    $admin_id = $_SESSION['admin_id'] ?? null;
    $ip = $_SERVER['REMOTE_ADDR'] ?? null;
    $agent = $_SERVER['HTTP_USER_AGENT'] ?? null;

    global $pdo;
    $stmt = $pdo->prepare("
        INSERT INTO log_aktivitas_admin (admin_id, aktivitas, ip_address, user_agent)
        VALUES (:admin_id, :aktivitas, :ip, :agent)
    ");
    $stmt->execute([
        'admin_id' => $admin_id,
        'aktivitas' => $aktivitas,
        'ip' => $ip,
        'agent' => $agent
    ]);
}
