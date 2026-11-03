<?php
require_once __DIR__ . "/../config/database.php";

class Peminjaman
{
    public static function isSlotAvailable($tanggal, $mulai, $selesai)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM peminjaman_lab WHERE tanggal_mulai = :tanggal AND status = 'disetujui' AND ((waktu_mulai < :selesai AND waktu_selesai > :mulai))");
        $stmt->execute(['tanggal' => $tanggal, 'mulai' => $mulai, 'selesai' => $selesai]);
        return $stmt->fetchColumn() == 0;
    }

    public static function isSlotBlockedByAdmin($tanggal, $mulai, $selesai)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM jam_tidak_tersedia WHERE tanggal = :tanggal AND ((waktu_mulai < :selesai AND waktu_selesai > :mulai))");
        $stmt->execute(['tanggal' => $tanggal, 'mulai' => $mulai, 'selesai' => $selesai]);
        return $stmt->fetchColumn() > 0;
    }
    public static function allByRange($start, $end)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM peminjaman_lab WHERE tanggal_mulai BETWEEN :start AND :end");
        $stmt->execute(['start' => $start, 'end' => $end]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function riwayatUser($nama, $nip)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM peminjaman_lab WHERE nip = :nip OR nama_peminjam = :nama ORDER BY id DESC LIMIT 10");
        $stmt->execute(['nip' => $nip, 'nama' => $nama]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function delete($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM peminjaman_lab WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public static function all()
    {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM peminjaman_lab ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ambil data jadwal dari view
    public static function getJadwalView($limit = 100, $offset = 0)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM view_jadwal_peminjaman ORDER BY tanggal_mulai DESC LIMIT ? OFFSET ?");
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Create peminjaman menggunakan stored procedure
    public static function create($nama, $nip, $tanggal_mulai, $waktu_mulai, $waktu_selesai, $keperluan)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT tambah_peminjaman(?, ?, ?, ?, ?, ?) AS result");
        $stmt->execute([$nama, $nip, $tanggal_mulai, $waktu_mulai, $waktu_selesai, $keperluan]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['result']; // 'OK' atau pesan error dari procedure
    }

    public static function setStatus($id, $status, $admin_id)
    {
        global $pdo;
        $stmt = $pdo->prepare("
            UPDATE peminjaman_lab 
            SET status = :status, admin_id = :admin, updated_at = NOW() 
            WHERE id = :id
        ");
        return $stmt->execute(['status' => $status, 'admin' => $admin_id, 'id' => $id]);
    }

    public static function approved()
    {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM peminjaman_lab WHERE status = 'disetujui' ORDER BY tanggal_mulai ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}