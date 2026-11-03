<?php
require_once __DIR__ . "/../config/database.php";

class Profil
{

    public static function all()
    {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM profil_lab ORDER BY id ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM profil_lab WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($kategori, $judul, $isi)
    {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO profil_lab (kategori, judul, isi) VALUES (:kategori, :judul, :isi)");
        return $stmt->execute(['kategori' => $kategori, 'judul' => $judul, 'isi' => $isi]);
    }

    public static function update($id, $kategori, $judul, $isi)
    {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE profil_lab SET kategori = :kategori, judul = :judul, isi = :isi WHERE id = :id");
        return $stmt->execute(['kategori' => $kategori, 'judul' => $judul, 'isi' => $isi, 'id' => $id]);
    }

    public static function delete($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM profil_lab WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
