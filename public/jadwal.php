<?php
require_once "../app/models/Peminjaman.php";
include "../views/layouts/header.php";
$data = Peminjaman::all();
?>

<section class="bg-white py-5 min-vh-100">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold mb-1 text-primary-custom">Jadwal Peminjaman Laboratorium</h2>
            <div class="text-muted">Daftar jadwal pemakaian laboratorium yang telah disetujui</div>
        </div>
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Peminjam</th>
                                <th>Tanggal</th>
                                <th>Waktu</th>
                                <th>Status Pengajuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data as $d): ?>
                                <tr>
                                    <td class="fw-semibold text-primary-custom"><?= htmlspecialchars($d['nama_peminjam']) ?></td>
                                    <td>
                                        <?= htmlspecialchars($d['tanggal_mulai']) ?>
                                        <?php if ($d['tanggal_selesai']): ?>
                                            <span class="mx-1">→</span> <?= htmlspecialchars($d['tanggal_selesai']) ?>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($d['waktu_mulai']) ?> - <?= htmlspecialchars($d['waktu_selesai']) ?></td>
                                    <td>
                                        <?php
                                        $status = strtolower($d['status']);
                                        $badge = 'secondary';
                                        if ($status === 'disetujui' || $status === 'approved') $badge = 'success';
                                        elseif ($status === 'ditolak' || $status === 'rejected') $badge = 'danger';
                                        elseif ($status === 'menunggu' || $status === 'pending') $badge = 'warning';
                                        ?>
                                        <span class="badge bg-<?= $badge ?>"><?= htmlspecialchars($d['status']) ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="container mb-5">
        <div class="d-grid mt-4">
            <a href="index.php" class="btn btn-outline-danger rounded-pill fw-bold shadow-sm">Kembali</a>
        </div>
    </div>

</section>

<style>
    .text-primary-custom {
        color: #0A2A43 !important;
    }
</style>

<?php include "../views/layouts/footer.php"; ?>