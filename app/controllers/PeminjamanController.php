<?php
require_once __DIR__ . '/../models/Peminjaman.php';

class PeminjamanController
{
    public static function getWeekRange($startDate = null)
    {
        $date = $startDate ? new DateTime($startDate) : new DateTime();
        $days = [];
        $count = 0;
        while ($count < 7) {
            $dow = (int)$date->format('N'); // 1=Senin, 7=Minggu
            if ($dow >= 1 && $dow <= 5) { // Senin-Jumat
                $days[] = clone $date;
                $count++;
            }
            $date->modify('+1 day');
        }
        return $days;
    }

    public static function getTimeSlots()
    {
        $slots = [];
        for ($h = 8; $h <= 18; $h++) {
            $slots[] = sprintf('%02d:00', $h);
        }
        return $slots;
    }

    public static function getJadwal($start, $end)
    {
        return Peminjaman::allByRange($start, $end);
    }

    public static function getRiwayat($nama, $nip)
    {
        return Peminjaman::riwayatUser($nama, $nip);
    }

    public static function insertPeminjaman($nama, $nip, $tanggal, $mulai, $selesai, $keperluan)
    {
        // Validasi semua field wajib diisi
        if (!$nama || !$nip || !$tanggal || !$mulai || !$selesai || !$keperluan) {
            return ['type' => 'danger', 'msg' => 'Semua field wajib diisi!'];
        }
        // Cek slot sudah disetujui orang lain
        if (!Peminjaman::isSlotAvailable($tanggal, $mulai, $selesai)) {
            return ['type' => 'danger', 'msg' => 'Slot sudah disetujui orang lain, silakan pilih waktu lain.'];
        }
        // Cek slot tidak tersedia oleh admin
        if (Peminjaman::isSlotBlockedByAdmin($tanggal, $mulai, $selesai)) {
            return ['type' => 'warning', 'msg' => 'Slot tidak tersedia, silakan pilih waktu lain.'];
        }
        // Insert peminjaman
        Peminjaman::create($nama, $nip, $tanggal, $tanggal, $mulai, $selesai, $keperluan);
        return ['type' => 'success', 'msg' => 'Pengajuan berhasil dikirim!'];
    }
}
