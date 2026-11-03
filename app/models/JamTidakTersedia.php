<?php
require_once __DIR__ . '/../config/database.php';

class JamTidakTersedia
{
    public static function allByRange($start, $end)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM jam_tidak_tersedia WHERE tanggal BETWEEN :start AND :end");
        $stmt->execute(['start' => $start, 'end' => $end]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Insert jam tidak tersedia menggunakan stored procedure
    public static function insertSP($tanggal, $mulai, $selesai, $alasan = 'Tidak Tersedia')
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT set_jam_tidak_tersedia(?, ?, ?, ?) AS result");
        $stmt->execute([$tanggal, $mulai, $selesai, $alasan]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['result'] ?? true;
    }

    public static function delete($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM jam_tidak_tersedia WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}