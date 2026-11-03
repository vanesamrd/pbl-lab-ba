<?php
require_once __DIR__ . "/../config/database.php";

class Galeri
{

    public static function all()
    {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM galeri ORDER BY tanggal DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM galeri WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($judul, $deskripsi, $gambar, $tanggal)
    {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO galeri (judul, deskripsi, gambar, tanggal) VALUES (:judul, :deskripsi, :gambar, :tanggal)");
        return $stmt->execute(['judul' => $judul, 'deskripsi' => $deskripsi, 'gambar' => $gambar, 'tanggal' => $tanggal]);
    }

    public static function update($id, $judul, $deskripsi, $tanggal, $gambar = null)
    {
        global $pdo;
        if ($gambar) {
            $stmt = $pdo->prepare("UPDATE galeri SET judul = :judul, deskripsi = :deskripsi, gambar = :gambar, tanggal = :tanggal WHERE id = :id");
            return $stmt->execute(['judul' => $judul, 'deskripsi' => $deskripsi, 'gambar' => $gambar, 'tanggal' => $tanggal, 'id' => $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE galeri SET judul = :judul, deskripsi = :deskripsi, tanggal = :tanggal WHERE id = :id");
            return $stmt->execute(['judul' => $judul, 'deskripsi' => $deskripsi, 'tanggal' => $tanggal, 'id' => $id]);
        }
    }

    public static function delete($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM galeri WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
