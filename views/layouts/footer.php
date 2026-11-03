<?php
// Pastikan path ini sesuai dengan struktur folder Anda saat di-include
// Menggunakan __DIR__ agar lebih robust jika di-include dari berbagai lokasi
if (file_exists(__DIR__ . '/../../app/models/Settings.php')) {
    require_once __DIR__ . '/../../app/models/Settings.php';
} elseif (file_exists('../app/models/Settings.php')) {
    require_once '../app/models/Settings.php';
}

// Fallback jika class Settings belum di-load
if (!class_exists('Settings')) {
    // Definisi dummy class atau handle error jika perlu, 
    // tapi kita gunakan data default saja di bawah.
}

$S = (class_exists('Settings') && method_exists('Settings', 'get')) ? Settings::get() : null;

if (!$S) {
    $S = [
        'site_name' => 'Lab Business Analytics',
        'footer_text' => 'Laboratorium Business Analytics Universitas Anda',
        'logo' => ''
    ];
}
?>

<style>
    /* Footer Styling */
    .footer-wrapper {
        background-color: #0A2A43;
        /* Primary Custom Color */
        color: #e0e6ed;
        font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        font-size: 0.95rem;
    }

    .footer-brand-title {
        font-weight: 700;
        letter-spacing: 0.5px;
        color: #ffffff;
        font-size: 1.1rem;
    }

    .footer-desc {
        color: #aeb9cc;
        line-height: 1.6;
        font-size: 0.9rem;
    }

    .footer-heading {
        color: #ffffff;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 0.85rem;
        margin-bottom: 1.2rem;
        position: relative;
        display: inline-block;
    }

    .footer-heading::after {
        content: '';
        display: block;
        width: 30px;
        height: 2px;
        background-color: #3FA2F7;
        /* Accent Color */
        margin-top: 5px;
        border-radius: 2px;
    }

    .footer-link {
        color: #aeb9cc;
        text-decoration: none;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        margin-bottom: 0.8rem;
    }

    .footer-link i {
        color: #3FA2F7;
        margin-right: 10px;
        font-size: 1rem;
        transition: transform 0.3s ease;
    }

    .footer-link:hover {
        color: #ffffff;
        transform: translateX(5px);
    }

    .footer-link:hover i {
        transform: translateX(2px);
    }

    .footer-bottom {
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        padding-top: 1.5rem;
        margin-top: 1rem;
        font-size: 0.85rem;
        color: #8da2b5;
    }

    .footer-logo {
        height: 45px;
        width: auto;
        object-fit: contain;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
    }
</style>

<footer class="footer-wrapper pt-5 pb-4 mt-auto">
    <div class="container">
        <div class="row gy-4 justify-content-between">

            <!-- 1. Brand & About -->
            <div class="col-lg-5 col-md-6">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <?php if (!empty($S['logo'])): ?>
                        <img src="/lab-ba/public/uploads/logo/<?= htmlspecialchars($S['logo']) ?>" alt="Logo" class="footer-logo">
                    <?php else: ?>
                        <div class="bg-white rounded-3 d-flex align-items-center justify-content-center p-2" style="width:48px;height:48px;">
                            <i class="bi bi-bar-chart-fill fs-4 text-primary"></i>
                        </div>
                    <?php endif; ?>

                    <div>
                        <h5 class="footer-brand-title mb-0"><?= htmlspecialchars($S['site_name'] ?? 'Lab Business Analytics') ?></h5>
                        <small class="text-white-50" style="font-size: 0.75rem;">Politeknik Negeri Malang</small>
                    </div>
                </div>

                <p class="footer-desc mb-4">
                    <?= nl2br(htmlspecialchars($S['footer_text'] ?? 'Laboratorium Business Analytics Universitas Anda')) ?>
                </p>

                <div class="d-flex gap-3">
                    <a href="<?= !empty($S['social_instagram']) ? htmlspecialchars($S['social_instagram']) : '#' ?>" target="_blank" class="text-white-50 hover-white text-decoration-none"><i class="bi bi-instagram fs-5"></i></a>
                    <a href="<?= !empty($S['social_facebook']) ? htmlspecialchars($S['social_facebook']) : '#' ?>" target="_blank" class="text-white-50 hover-white text-decoration-none"><i class="bi bi-facebook fs-5"></i></a>
                    <a href="<?= !empty($S['social_youtube']) ? htmlspecialchars($S['social_youtube']) : '#' ?>" target="_blank" class="text-white-50 hover-white text-decoration-none"><i class="bi bi-youtube fs-5"></i></a>
                    <a href="#" class="text-white-50 hover-white text-decoration-none"><i class="bi bi-globe fs-5"></i></a>
                </div>
            </div>

            <!-- 2. Quick Links (Admin/System) -->
            <div class="col-lg-3 col-md-6 offset-lg-1">
                <h6 class="footer-heading">Navigasi Utama</h6>
                <ul class="list-unstyled">
                    <li><a href="index.php" class="footer-link"><i class="bi bi-speedometer2"></i> Dashboard Admin</a></li>
                    <li><a href="../public/galeri.php" class="footer-link"><i class="bi bi-images"></i> Galeri Kegiatan</a></li>
                    <li><a href="../public/dosen.php" class="footer-link"><i class="bi bi-person-video3"></i> Data Dosen</a></li>
                    <li><a href="../public/publikasi.php" class="footer-link"><i class="bi bi-journal-richtext"></i> Publikasi & Riset</a></li>
                </ul>
            </div>

            <!-- 3. Information (Public) -->
            <div class="col-lg-3 col-md-6">
                <h6 class="footer-heading">Informasi & Layanan</h6>
                <ul class="list-unstyled">
                    <li><a href="../public/berita.php" class="footer-link"><i class="bi bi-newspaper"></i> Berita Terbaru</a></li>
                    <li><a href="../public/peminjaman.php" class="footer-link"><i class="bi bi-calendar-check"></i> Jadwal Peminjaman</a></li>
                    <li><a href="#kontak" class="footer-link"><i class="bi bi-envelope-paper"></i> Kontak & Lokasi</a></li>
                </ul>
            </div>

        </div>

        <!-- Copyright -->
        <div class="footer-bottom">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start mb-2 mb-md-0">
                    <?php if (!empty($S['copyright_text'])): ?>
                        <?= htmlspecialchars($S['copyright_text']) ?>
                    <?php else: ?>
                        &copy; <?= date('Y') ?> <strong><?= htmlspecialchars($S['site_name'] ?? 'Lab Business Analytics') ?></strong>. All Rights Reserved.
                    <?php endif; ?>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <span class="small">Designed with <i class="bi bi-heart-fill text-danger mx-1" style="font-size:0.7rem;"></i> for <strong>Polinema</strong></span>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>