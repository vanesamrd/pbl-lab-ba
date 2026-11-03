<?php
require_once __DIR__ . "/../config/database.php";

class KontakLab
{
    public static function get()
    {
        global $pdo;
        return $pdo->query("SELECT * FROM kontak_lab ORDER BY id DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
    }

    public static function update($alamat, $email, $telepon, $website, $maps_embed)
    {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE kontak_lab SET alamat = :alamat, email = :email, telepon = :telepon, website = :website, maps_embed = :maps, updated_at = NOW() WHERE id = (SELECT id FROM kontak_lab ORDER BY id DESC LIMIT 1)");
        return $stmt->execute([
            'alamat' => $alamat,
            'email' => $email,
            'telepon' => $telepon,
            'website' => $website,
            'maps' => $maps_embed
        ]);
    }

    public static function create($alamat, $email, $telepon, $website, $maps_embed)
    {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO kontak_lab (alamat, email, telepon, website, maps_embed) VALUES (:alamat, :email, :telepon, :website, :maps)");
        return $stmt->execute([
            'alamat' => $alamat,
            'email' => $email,
            'telepon' => $telepon,
            'website' => $website,
            'maps' => $maps_embed
        ]);
    }
}