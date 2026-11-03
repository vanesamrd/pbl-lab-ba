<?php
// $week, $slots, $jadwal, $alert, $riwayat sudah tersedia dari controller

// Asumsi: $jamTidakTersedia juga tersedia dari controller jika tidak, akan dianggap array kosong.

function slotState($slot, $tanggal, $jadwal, $jamTidakTersedia = [])
{
    // Cek slot tidak tersedia dari jam_tidak_tersedia (Blocked by Admin)
    foreach ($jamTidakTersedia as $jtt) {
        if ($jtt['tanggal'] == $tanggal) {
            $mulai = substr($jtt['waktu_mulai'], 0, 5);
            $selesai = substr($jtt['waktu_selesai'], 0, 5);
            // Perbaiki: blokir slot jika $slot >= $mulai dan $slot < $selesai
            if ($slot >= $mulai && $slot < $selesai) {
                return 'secondary'; // abu-abu (Tidak Tersedia)
            }
            // Jika $slot == $selesai, blokir juga slot selesai (opsional, tergantung interval slot)
            if ($slot == $selesai) {
                return 'secondary';
            }
        }
    }
    // Cek jadwal peminjaman
    foreach ($jadwal as $b) {
        // Asumsi: tanggal_mulai dan tanggal_selesai sama jika peminjaman 1 hari
        if ($b['tanggal_mulai'] == $tanggal) {
            $mulai = substr($b['waktu_mulai'], 0, 5);
            $selesai = substr($b['waktu_selesai'], 0, 5);
            if ($slot >= $mulai && $slot < $selesai) {
                if ($b['status'] == 'disetujui') return 'danger'; // merah (Terisi)
                if ($b['status'] == 'menunggu') return 'warning'; // kuning (Menunggu)
                if ($b['status'] == 'ditolak') return 'light'; // putih/ringan (Ditolak/Tersedia)
                if ($b['status'] == 'tidak tersedia') return 'secondary'; // abu-abu (Tidak Tersedia)
            }
        }
    }
    return 'outline-primary'; // default biru/tersedia
}

// Untuk menampilkan nama hari dalam Bahasa Indonesia
function getIndonesianDayName($dateString)
{
    $timestamp = strtotime($dateString);
    $dayIndex = date('w', $timestamp);
    $dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    return $dayNames[$dayIndex];
}

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peminjaman Lab - Modern</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons untuk icon modern -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        /* Custom Colors and Styles */
        :root {
            --primary-custom: #0A2A43;
            --accent: #3FA2F7;
        }

        .text-primary-custom {
            color: var(--primary-custom) !important;
        }

        .bg-accent {
            background-color: var(--accent) !important;
        }

        .btn-accent {
            background-color: var(--accent);
            color: #fff;
            border: none;
            box-shadow: 0 4px 8px rgba(63, 162, 247, 0.3);
            transition: all 0.2s ease;
        }

        .btn-accent:hover {
            background-color: #2386d9;
            /* Slightly darker accent */
            box-shadow: 0 6px 12px rgba(63, 162, 247, 0.4);
            transform: translateY(-1px);
            color: #fff;
        }

        /* --- SECTION Container with Two Columns (New Design) --- */
        .section-container {
            /* Shadow yang timbul dan rounded */
            box-shadow: 0 8px 30px rgba(10, 42, 67, 0.1), 0 2px 8px rgba(63, 162, 247, 0.08);
            border-radius: 1.5rem;
            background-color: #fff;
            overflow: hidden;
            /* Penting untuk menjaga border-radius */
            display: flex;
            transition: transform 0.3s ease;
        }

        /* Kolom Formulir (Kiri) */
        .form-column {
            padding: 2.5rem;
            /* Garis pemisah/shadow internal */
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.02);
        }

        /* Kolom Jadwal (Kanan) */
        .schedule-column {
            background-color: #f7faff;
            /* Warna background lebih ringan untuk jadwal */
            padding: 2.5rem;
        }

        @media (max-width: 991.98px) {

            .form-column,
            .schedule-column {
                width: 100%;
                box-shadow: none;
                border-radius: 0;
            }

            .form-column {
                padding-bottom: 1.5rem;
            }

            .schedule-column {
                border-top: 1px solid rgba(0, 0, 0, 0.05);
                padding-top: 1.5rem;
            }
        }

        /* Slot Buttons Styling (Disesuaikan agar lebih rapi) */
        .slot-btn {
            width: 100%;
            height: 35px;
            font-weight: 500;
            font-size: 0.85rem;
            line-height: 20px;
            border-radius: 0.5rem;
            /* Lebih membulat */
            margin-bottom: 8px;
            transition: all 0.2s;
            cursor: default;
        }

        .slot-btn.danger {
            background: #dc3545;
            /* Merah */
            color: #fff;
            box-shadow: 0 1px 3px rgba(220, 53, 69, 0.4);
        }

        /* Tersedia (Default: outline-primary) */
        .slot-btn.outline-primary {
            background: #fff;
            border: 1px solid var(--accent);
            color: var(--accent);
        }

        /* Menunggu */
        .slot-btn.warning {
            border: 1px solid #ffc107;
            background: #fff3cd;
            color: #664d03;
        }

        /* Tidak Tersedia/Diblokir */
        .slot-btn.secondary {
            background: #e9ecef;
            color: #6c757d;
            border: 1px solid #ced4da;
        }

        /* Ditolak (light) */
        .slot-btn.light {
            background: #f8f9fa;
            color: #adb5bd;
            border: 1px dashed #ced4da;
            font-style: italic;
        }

        .slot-list-container {
            max-height: 550px;
            /* Batasi tinggi untuk scroll */
            overflow-y: auto;
            overflow-x: hidden;
            padding-right: 10px;
        }

        .slot-list-container::-webkit-scrollbar {
            width: 6px;
        }

        .slot-list-container::-webkit-scrollbar-thumb {
            background: #ced4da;
            border-radius: 10px;
        }

        .slot-list-container::-webkit-scrollbar-thumb:hover {
            background: #adb5bd;
        }
    </style>
</head>

<body class="bg-light">
    <main class="container py-5">
        <!-- WRAPPER UTAMA DUA KOLOM -->
        <div class="section-container mb-5">
            <div class="row w-100 mx-0 g-0">
                <!-- KOLOM FORMULIR (KIRI) -->
                <div class="col-lg-5 col-md-12 form-column">
                    <h3 class="fw-bold mb-4 text-primary-custom">Formulir Peminjaman Lab</h3>

                    <?php if (!empty($alert)): ?>
                        <!-- Menggunakan kelas Bootstrap 5 -->
                        <div class="alert alert-<?= $alert['type'] ?> alert-dismissible fade show rounded-3" role="alert">
                            <?= $alert['msg'] ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" id="formPeminjaman">
                        <div class="mb-3">
                            <label for="nama" class="form-label small fw-semibold">Nama Peminjam <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control" placeholder="Nama lengkap" required value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label for="nip" class="form-label small fw-semibold">NIM/NIP <span class="text-danger">*</span></label>
                            <input type="text" name="nip" class="form-control" placeholder="NIM atau NIP" required value="<?= htmlspecialchars($_POST['nip'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label small fw-semibold">Email <span class="text-danger">*</span></label>
                            <!-- Tambahkan field Email sesuai permintaan -->
                            <input type="email" name="email" class="form-control" placeholder="Email Anda" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tanggal" class="form-label small fw-semibold">Tanggal <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal" class="form-control" required value="<?= htmlspecialchars($_POST['tanggal'] ?? date('Y-m-d')) ?>" min="<?= date('Y-m-d') ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="keperluan" class="form-label small fw-semibold">Keperluan <span class="text-danger">*</span></label>
                                <input type="text" name="keperluan" class="form-control" placeholder="Tujuan peminjaman" required value="<?= htmlspecialchars($_POST['keperluan'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="mulai" class="form-label small fw-semibold">Jam Mulai <span class="text-danger">*</span></label>
                                <select name="mulai" class="form-select" required>
                                    <option value="" disabled selected>Pilih jam mulai</option>
                                    <?php foreach ($slots as $s): ?>
                                        <option value="<?= $s ?>" <?= ($_POST['mulai'] ?? '') === $s ? 'selected' : '' ?>><?= $s ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="selesai" class="form-label small fw-semibold">Jam Selesai <span class="text-danger">*</span></label>
                                <select name="selesai" class="form-select" required>
                                    <option value="" disabled selected>Pilih jam selesai</option>
                                    <?php foreach ($slots as $s): ?>
                                        <option value="<?= $s ?>" <?= ($_POST['selesai'] ?? '') === $s ? 'selected' : '' ?>><?= $s ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-accent w-100 mt-2 fw-bold rounded-pill">
                            <i class="bi bi-send-fill me-2"></i> Pinjam Lab
                        </button>
                    </form>
                </div>

                <!-- KOLOM JADWAL (KANAN) -->
                <div class="col-lg-7 col-md-12 schedule-column">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mb-0 fw-bold text-primary-custom">Jadwal Slot Lab</h4>
                        <a href="peminjaman.php?tanggal=<?= htmlspecialchars($week[0]->format('Y-m-d')) ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                            <i class="bi bi-arrow-clockwise me-1"></i> Refresh
                        </a>
                    </div>

                    <p class="text-muted small mb-3">
                        Slot tersedia dari **<?= getIndonesianDayName($week[0]->format('Y-m-d')) ?>, <?= $week[0]->format('d M') ?>** hingga **<?= getIndonesianDayName($week[6]->format('Y-m-d')) ?>, <?= $week[6]->format('d M Y') ?>**.
                    </p>

                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <span class="badge text-bg-primary"><i class="bi bi-check-circle-fill me-1"></i> Tersedia</span>
                        <span class="badge text-bg-danger"><i class="bi bi-x-circle-fill me-1"></i> Terisi (Disetujui)</span>
                        <span class="badge text-bg-warning text-dark"><i class="bi bi-clock-fill me-1"></i> Menunggu</span>
                        <span class="badge text-bg-secondary"><i class="bi bi-slash-circle-fill me-1"></i> Tidak Tersedia/Diblokir</span>
                    </div>

                    <!-- Jadwal Mingguan -->
                    <div class="slot-list-container">
                        <div class="row flex-nowrap g-2">
                            <?php foreach ($week as $day):
                                $dateStr = $day->format('Y-m-d');
                                $isToday = $dateStr === date('Y-m-d');
                            ?>
                                <div class="col-3 col-sm-3 col-md-3 col-lg-2">
                                    <div class="text-center fw-bold mb-1 p-2 rounded-top <?= $isToday ? 'bg-accent text-white' : 'bg-light text-primary-custom' ?>">
                                        <?= getIndonesianDayName($dateStr) ?>
                                    </div>
                                    <div class="text-center small mb-2 p-1 border-bottom <?= $isToday ? 'border-accent' : 'border-light' ?>" style="background-color: #fff;">
                                        <?= $day->format('d/m') ?>
                                    </div>
                                    <?php foreach ($slots as $index => $slot):
                                        // Skip the last slot if we display slots as intervals (start time only)
                                        if (!isset($slots[$index + 1])) continue;

                                        $state = slotState($slot, $dateStr, $jadwal, isset($jamTidakTersedia) ? $jamTidakTersedia : []);
                                    ?>
                                        <div class="slot-btn <?= $state ?> rounded text-center" style="cursor: default;">
                                            <?= $slot ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Navigasi Minggu -->
                    <div class="mt-4 d-flex justify-content-center">
                        <?php
                        // Clone day[0] dan modifikasi untuk mendapatkan tanggal minggu sebelumnya dan selanjutnya
                        $prevWeekDate = (clone $week[0])->modify('-7 days')->format('Y-m-d');
                        $nextWeekDate = (clone $week[0])->modify('+7 days')->format('Y-m-d');
                        ?>
                        <a href="?tanggal=<?= $prevWeekDate ?>" class="btn btn-outline-secondary btn-sm me-2 rounded-pill"><i class="bi bi-arrow-left"></i> Sebelumnya</a>
                        <a href="?tanggal=<?= $nextWeekDate ?>" class="btn btn-outline-secondary btn-sm rounded-pill">Selanjutnya <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIWAYAT PEMINJAMAN  -->
        <div class="mt-5 pt-3">
            <h4 class="mb-3 fw-bold text-primary-custom">Riwayat Peminjaman Saya</h4>
            <div class="section-container">
            <div class="container py-5">
                    <div class="section-conatiner shadow-sm rounded-4">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama Peminjam</th>
                                        <th>Tanggal</th>
                                        <th>Waktu</th>
                                        <th>Keperluan</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($riwayat)): ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted fst-italic py-3">Belum ada riwayat peminjaman.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($riwayat as $r): ?>
                                            <tr>
                                                <td class="fw-semibold text-primary-custom"><?= htmlspecialchars($r['nama_peminjam']) ?></td>
                                                <td>
                                                    <?= date('d M Y', strtotime($r['tanggal_mulai'])) ?>
                                                    <?php if ($r['tanggal_selesai'] && $r['tanggal_selesai'] != $r['tanggal_mulai']): ?>
                                                        <span class="mx-1">→</span> <?= date('d M Y', strtotime($r['tanggal_selesai'])) ?>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= htmlspecialchars(substr($r['waktu_mulai'], 0, 5)) ?> - <?= htmlspecialchars(substr($r['waktu_selesai'], 0, 5)) ?></td>
                                                <td><?= htmlspecialchars($r['keperluan']) ?></td>
                                                <td>
                                                    <?php
                                                    $status = strtolower($r['status']);
                                                    $badge = 'secondary';
                                                    if ($status === 'disetujui' || $status === 'approved') $badge = 'danger';
                                                    elseif ($status === 'ditolak' || $status === 'rejected') $badge = 'secondary';
                                                    elseif ($status === 'menunggu' || $status === 'pending') $badge = 'warning';
                                                    elseif ($status === 'tidak tersedia') $badge = 'secondary';
                                                    ?>
                                                    <span class="badge bg-<?= $badge ?>"><?= htmlspecialchars(ucwords($r['status'])) ?></span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-grid mt-3 mb-5">
            <a href="index.php" class="btn btn-outline-danger w-50 mt-1 fw-bold rounded-pill">Kembali Ke Beranda</a>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>