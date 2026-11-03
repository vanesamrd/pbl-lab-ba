<?php
require_once __DIR__ . "/../config/database.php";

class Dosen
{


    public static function all()
    {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM dosen ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public static function find($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM dosen WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public static function create($nama, $keahlian, $foto)
    {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO dosen (nama, keahlian, foto) VALUES (:nama, :keahlian, :foto)");
        return $stmt->execute(['nama' => $nama, 'keahlian' => $keahlian, 'foto' => $foto]);
    }


    public static function update($id, $nama, $keahlian, $foto = null)
    {
        global $pdo;
        if ($foto) {
            $stmt = $pdo->prepare("UPDATE dosen SET nama = :nama, keahlian = :keahlian, foto = :foto WHERE id = :id");
            return $stmt->execute(['nama' => $nama, 'keahlian' => $keahlian, 'foto' => $foto, 'id' => $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE dosen SET nama = :nama, keahlian = :keahlian WHERE id = :id");
            return $stmt->execute(['nama' => $nama, 'keahlian' => $keahlian, 'id' => $id]);
        }
    }

    public static function delete($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM dosen WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
