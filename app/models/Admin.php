<?php
require_once __DIR__ . "/../config/database.php";

class Admin
{

    public static function all()
    {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM admin ORDER BY id ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM admin WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($username, $password, $nama)
    {
        global $pdo;
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO admin (username, password, nama_lengkap) VALUES (:u, :p, :n)");
        return $stmt->execute(['u' => $username, 'p' => $hash, 'n' => $nama]);
    }

    public static function update($id, $username, $nama)
    {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE admin SET username = :u, nama_lengkap = :n, updated_at = NOW() WHERE id = :id");
        return $stmt->execute(['u' => $username, 'n' => $nama, 'id' => $id]);
    }

    public static function updatePassword($id, $password)
    {
        global $pdo;
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE admin SET password = :p, updated_at = NOW() WHERE id = :id");
        return $stmt->execute(['p' => $hash, 'id' => $id]);
    }

    public static function delete($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM admin WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
