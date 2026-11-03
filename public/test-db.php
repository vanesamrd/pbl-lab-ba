<?php
require_once "../app/config/database.php";

try {
    $stmt = $pdo->query("SELECT 1");
    echo "<h3 style='color: green;'>Koneksi ke PostgreSQL BERHASIL ✅</h3>";
} catch (Exception $e) {
    echo "<h3 style='color: red;'>Koneksi GAGAL ❌</h3>";
    echo $e->getMessage();
}
