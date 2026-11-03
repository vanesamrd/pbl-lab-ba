<?php
// app/models/Kategori.php
require_once __DIR__ . '/../config/database.php';

class Kategori
{
    public static function all()
    {
        global $pdo;
        $stmt = $pdo->query('SELECT * FROM kategori ORDER BY nama');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find($id)
    {
        global $pdo;
        $stmt = $pdo->prepare('SELECT * FROM kategori WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($nama)
    {
        global $pdo;
        $stmt = $pdo->prepare('INSERT INTO kategori (nama) VALUES (?)');
        $stmt->execute([$nama]);
    }

    public static function update($id, $nama)
    {
        global $pdo;
        $stmt = $pdo->prepare('UPDATE kategori SET nama=? WHERE id=?');
        $stmt->execute([$nama, $id]);
    }

    public static function delete($id)
    {
        global $pdo;
        $stmt = $pdo->prepare('DELETE FROM kategori WHERE id=?');
        $stmt->execute([$id]);
    }
}
