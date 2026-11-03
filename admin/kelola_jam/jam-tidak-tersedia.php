<?php
require_once "../../app/utils/session.php";
checkAdminLogin();
require_once "../../app/models/JamTidakTersedia.php";
require_once "../../app/utils/log.php";

// Handle insert/delete (PRG harus sebelum output apapun)
$alert = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'add') {
        $tanggal = $_POST['tanggal'] ?? '';
        $mulai = $_POST['mulai'] ?? '';
        $selesai = $_POST['selesai'] ?? '';
        $alasan = $_POST['alasan'] ?? '';
        if ($tanggal && $mulai && $selesai && $alasan) {
            $validTimes = ["08:00", "09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00", "17:00", "18:00"];
            // Validasi tanggal minimal hari ini
            if (strtotime($tanggal) < strtotime(date('Y-m-d'))) {
                $alert = ['type' => 'danger', 'msg' => 'Tanggal tidak boleh sebelum hari ini!'];
            }
            // Validasi backend waktu mulai dan selesai
            elseif (!in_array($mulai, $validTimes) || !in_array($selesai, $validTimes)) {
                $alert = ['type' => 'danger', 'msg' => 'Waktu hanya boleh antara 08:00 sampai 18:00 dan kelipatan 1 jam!'];
            } else {
                // Cek bentrok dengan jadwal disetujui
                require_once '../../app/models/Peminjaman.php';
                if (!Peminjaman::isSlotAvailable($tanggal, $mulai, $selesai)) {
                    $alert = ['type' => 'danger', 'msg' => 'Jam tidak tersedia karena sudah ada peminjaman disetujui pada waktu tersebut!'];
                } else {
                    JamTidakTersedia::insertSP($tanggal, $mulai, $selesai, $alasan);
                    logActivity("Tambah jam tidak tersedia: $tanggal $mulai-$selesai ($alasan)");
                    // Refresh page to prevent resubmission
                    echo "<script>window.location.href='index.php';</script>";
                    exit;
                }
            }
        } else {
            $alert = ['type' => 'danger', 'msg' => 'Semua field wajib diisi!'];
        }
    } elseif (isset($_POST['action']) && $_POST['action'] === 'delete' && isset($_POST['id'])) {
        JamTidakTersedia::delete($_POST['id']);
        logActivity("Hapus jam tidak tersedia ID: " . $_POST['id']);
        $alert = ['type' => 'success', 'msg' => 'Jam tidak tersedia berhasil dihapus.'];
    }
}

$data = JamTidakTersedia::allByRange(date('Y-m-d', strtotime('-30 days')), date('Y-m-d', strtotime('+30 days')));
include "../../views/layouts/header.php";
?>

<style>
    :root {
        --primary-custom: #0A2A43;
        --accent: #3FA2F7;
        --accent-hover: #217bc9;
        --bg-light: #f4f7fa;
    }

    body {
        background-color: var(--bg-light);
        font-family: 'Segoe UI', Roboto, sans-serif;
    }

    /* Container & Cards */
    .section-container {
        background: #fff;
        border-radius: 1.5rem;
        box-shadow: 0 10px 40px rgba(10, 42, 67, 0.08);
        border: 1px solid rgba(63, 162, 247, 0.1);
        padding: 2rem;
        position: relative;
        overflow: hidden;
        margin-bottom: 2rem;
    }

    /* Typography */
    .text-primary-custom { color: var(--primary-custom) !important; }
    .form-label { font-weight: 600; font-size: 0.85rem; color: var(--primary-custom); }

    /* Form Inputs */
    .form-control, .form-select {
        border-radius: 0.5rem;
        border: 1px solid #dee2e6;
        padding: 0.6rem 1rem;
        font-size: 0.95rem;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 4px rgba(63, 162, 247, 0.1);
    }

    /* Buttons */
    .btn-accent {
        background-color: var(--accent);
        color: #fff;
        border: none;
        box-shadow: 0 4px 10px rgba(63, 162, 247, 0.3);
        transition: all 0.2s;
        font-weight: 600;
        border-radius: 50px;
    }
    .btn-accent:hover {
        background-color: var(--accent-hover);
        color: #fff;
        transform: translateY(-2px);
    }

    /* Button Delete with Text */
    .btn-delete-text {
        background-color: #f8d7da;
        color: #842029;
        border: 1px solid #f5c2c7;
        font-size: 0.8rem;
        font-weight: 600;
        padding: 0.4rem 1rem;
        border-radius: 50px;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }
    .btn-delete-text:hover {
        background-color: #dc3545;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(220, 53, 69, 0.2);
    }

    /* Table Styling */
    .table-custom thead th {
        background-color: #f8fbff;
        color: #6c757d;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        padding: 1rem;
        border-bottom: 2px solid #e9ecef;
    }
    .table-custom tbody td {
        padding: 1rem; vertical-align: middle; border-bottom: 1px solid #f0f0f0;
    }
    .table-hover tbody tr:hover { background-color: #fafbff; }
</style>

<section class="py-5 min-vh-100">
    <div class="container">
        <!-- Header Page -->
        <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
            <div>
                <h3 class="fw-bold text-primary-custom mb-1">Jadwal Tidak Tersedia</h3>
                <p class="text-muted small mb-0">Atur waktu libur atau pemeliharaan lab.</p>
            </div>
        </div>

        <?php if ($alert): ?>
            <div class="alert alert-<?= $alert['type'] ?> alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
                <i class="bi <?= $alert['type'] == 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill' ?> me-2"></i>
                <?= $alert['msg'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Form Tambah Jadwal -->
        <div class="section-container">
            <h5 class="fw-bold text-primary-custom mb-4 pb-2 border-bottom">
                <i class="bi bi-clock-history me-2 text-accent"></i>Tambah Waktu Tidak Tersedia
            </h5>
            
            <form method="POST" class="row g-3">
                <input type="hidden" name="action" value="add">
                
                <div class="col-md-3">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" required min="<?= date('Y-m-d') ?>">
                </div>
                
                <div class="col-md-2">
                    <label class="form-label">Jam Mulai</label>
                    <select name="mulai" class="form-select" required>
                        <option value="" disabled selected>Pilih...</option>
                        <?php 
                        $times = ["08:00", "09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00", "17:00", "18:00"];
                        foreach ($times as $t) echo "<option value='$t'>$t</option>"; 
                        ?>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label class="form-label">Jam Selesai</label>
                    <select name="selesai" class="form-select" required>
                        <option value="" disabled selected>Pilih...</option>
                        <?php foreach ($times as $t) echo "<option value='$t'>$t</option>"; ?>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Alasan / Keterangan</label>
                    <input type="text" name="alasan" class="form-control" placeholder="Contoh: Pemeliharaan Rutin" required>
                </div>
                
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-accent w-100 py-2">
                        <i class="bi bi-plus-lg me-1"></i> Tambah
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabel Data -->
        <div class="section-container p-0">
            <div class="p-4 pb-0">
                <h5 class="fw-bold text-primary-custom mb-3">Daftar Jadwal (±30 Hari)</h5>
            </div>
            
            <?php if (empty($data)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-calendar-x text-muted opacity-25" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-2 small">Tidak ada jadwal tidak tersedia yang tercatat.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-custom table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th width="20%">Tanggal</th>
                                <th width="20%">Waktu</th>
                                <th width="45%">Keterangan</th>
                                <th width="15%" class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data as $d): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center text-dark fw-semibold">
                                            <i class="bi bi-calendar-event me-2 text-secondary"></i>
                                            <?= date('d M Y', strtotime($d['tanggal'])) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            <?= htmlspecialchars(substr($d['waktu_mulai'], 0, 5)) ?> - <?= htmlspecialchars(substr($d['waktu_selesai'], 0, 5)) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-secondary"><?= htmlspecialchars($d['alasan']) ?></span>
                                    </td>
                                    <td class="text-end">
                                        <form method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?= $d['id'] ?>">
                                            <button type="submit" class="btn btn-delete-text" title="Hapus Jadwal">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- Back Button -->
        <div class="mt-4">
            <a href="../dashboard.php" class="btn btn-link text-decoration-none text-muted fw-bold ps-0 hover-primary">
                <a href="/lab-ba/admin/dashboard.php" class="btn btn-outline-danger rounded-pill px-4">
                        Kembali ke Dashboard    
            </a>
        </div>
    </div>
</section>