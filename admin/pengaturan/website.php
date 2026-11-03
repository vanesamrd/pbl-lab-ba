<?php
require_once "../../app/utils/session.php";
checkAdminLogin();
require_once "../../app/models/Settings.php";
require_once "../../app/utils/log.php";


$settings = Settings::get();
if (!$settings) {
    $settings = [
        'site_name' => '',
        'footer_text' => '',
        'logo' => '',
        'copyright_text' => '',
        'social_instagram' => '',
        'social_facebook' => '',
        'social_youtube' => ''
    ];
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $site_name = $_POST['site_name'];
    $footer_text = $_POST['footer_text'];
    $copyright_text = $_POST['copyright_text'] ?? '';
    $social_instagram = $_POST['social_instagram'] ?? '';
    $social_facebook = $_POST['social_facebook'] ?? '';
    $social_youtube = $_POST['social_youtube'] ?? '';

    $logo_polinema = null;
    $logo_jti = null;
    $logo = null;

    // Pastikan folder ada
    $targetDir = "../../public/uploads/logo/";
    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

    if (!empty($_FILES['logo_polinema']['name'])) {
        $logo_polinema = time() . '_polinema_' . $_FILES['logo_polinema']['name'];
        move_uploaded_file($_FILES['logo_polinema']['tmp_name'], $targetDir . $logo_polinema);
    }

    if (!empty($_FILES['logo_jti']['name'])) {
        $logo_jti = time() . '_jti_' . $_FILES['logo_jti']['name'];
        move_uploaded_file($_FILES['logo_jti']['tmp_name'], $targetDir . $logo_jti);
    }

    if (!empty($_FILES['logo']['name'])) {
        $logo = time() . '_main_' . $_FILES['logo']['name'];
        move_uploaded_file($_FILES['logo']['tmp_name'], $targetDir . $logo);
    }

    // Logic update di model harus menyesuaikan apakah file diganti atau tidak
    if ($logo) {
        Settings::update($site_name, $footer_text, $logo, $copyright_text, $logo_polinema, $logo_jti, $social_instagram, $social_facebook, $social_youtube);
        logActivity("Mengubah pengaturan website + logo utama");
    } else {
        Settings::update($site_name, $footer_text, null, $copyright_text, $logo_polinema, $logo_jti, $social_instagram, $social_facebook, $social_youtube);
        logActivity("Mengubah pengaturan website");
    }

    header("Location: website.php");
    exit;
}

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

    /* Section Styling */
    .section-header {
        font-weight: 700;
        color: var(--primary-custom);
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 1rem;
        margin-bottom: 1.5rem;
    }

    .section-card {
        background: #fff;
        border-radius: 1.5rem;
        box-shadow: 0 10px 40px rgba(10, 42, 67, 0.08);
        border: 1px solid rgba(63, 162, 247, 0.1);
        padding: 2.5rem;
        margin-bottom: 2rem;
    }

    /* Form Styling */
    .form-label {
        font-weight: 600;
        color: var(--primary-custom);
        font-size: 0.9rem;
    }

    .form-control {
        border-radius: 0.5rem;
        padding: 0.75rem 1rem;
        border: 1px solid #dee2e6;
        transition: all 0.2s;
    }

    .form-control:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 4px rgba(63, 162, 247, 0.15);
    }

    /* Logo Upload Box */
    .logo-upload-container {
        text-align: center;
        padding: 1.5rem;
        border: 1px solid #e9ecef;
        border-radius: 1rem;
        background-color: #f8fbff;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.3s;
    }

    .logo-upload-container:hover {
        border-color: var(--accent);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .current-logo-box {
        height: 100px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        background: #fff;
        border-radius: 0.5rem;
        border: 1px dashed #dee2e6;
        padding: 10px;
    }

    .current-logo-box img {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
    }

    .no-logo {
        color: #adb5bd;
        font-size: 0.9rem;
        font-style: italic;
    }

    /* Buttons */
    .btn-accent {
        background-color: var(--accent);
        color: #fff;
        border: none;
        box-shadow: 0 4px 10px rgba(63, 162, 247, 0.3);
        transition: all 0.2s;
        font-weight: 600;
        padding: 0.7rem 2rem;
    }

    .btn-accent:hover {
        background-color: var(--accent-hover);
        color: #fff;
        transform: translateY(-2px);
    }
</style>

<section class="py-5 min-vh-100">
    <div class="container">
        <!-- Header Page -->
        <div class="row justify-content-center mb-4">
            <div class="col-lg-10">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="/lab-ba/admin/dashboard.php" class="text-decoration-none text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item active text-primary-custom" aria-current="page">Pengaturan</li>
                    </ol>
                </nav>
                <h3 class="fw-bold text-primary-custom">Pengaturan Website</h3>
            </div>
        </div>

        <form method="POST" enctype="multipart/form-data" autocomplete="off">
            <div class="row justify-content-center">
                <div class="col-lg-10">

                    <!-- 1. General Information -->
                    <div class="section-card">
                        <h5 class="section-header"><i class="bi bi-globe me-2"></i>Informasi Umum</h5>
                        <div class="mb-3">
                            <label class="form-label">Nama Website / Laboratorium</label>
                            <input type="text" name="site_name" class="form-control" value="<?= htmlspecialchars($settings['site_name']) ?>" required placeholder="Contoh: Laboratorium Business Analytics">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Footer Text (Ringkas)</label>
                                <input type="text" name="footer_text" class="form-control" value="<?= htmlspecialchars($settings['footer_text']) ?>" required placeholder="Teks singkat di footer">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Copyright Text</label>
                                <input type="text" name="copyright_text" class="form-control" value="<?= htmlspecialchars($settings['copyright_text'] ?? '') ?>" placeholder="© 2025 Lab BA. All rights reserved.">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Instagram</label>
                                <input type="text" name="social_instagram" class="form-control" value="<?= htmlspecialchars($settings['social_instagram'] ?? '') ?>" placeholder="Link Instagram">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Facebook</label>
                                <input type="text" name="social_facebook" class="form-control" value="<?= htmlspecialchars($settings['social_facebook'] ?? '') ?>" placeholder="Link Facebook">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">YouTube</label>
                                <input type="text" name="social_youtube" class="form-control" value="<?= htmlspecialchars($settings['social_youtube'] ?? '') ?>" placeholder="Link YouTube">
                            </div>
                        </div>
                    </div>

                    <!-- 2. Branding & Logos -->
                    <div class="section-card">
                        <h5 class="section-header"><i class="bi bi-images me-2"></i>Branding & Logo</h5>
                        <p class="text-muted small mb-4">Upload logo dengan format PNG (transparan) atau JPG agar tampilan maksimal.</p>

                        <div class="row g-4">
                            <!-- Logo Utama Website -->
                            <div class="col-md-4">
                                <div class="logo-upload-container">
                                    <div>
                                        <label class="form-label d-block text-center mb-2">Logo Website (Utama)</label>
                                        <div class="current-logo-box">
                                            <?php if (!empty($settings['logo'])): ?>
                                                <img src="../../public/uploads/logo/<?= htmlspecialchars($settings['logo']) ?>" alt="Logo Utama">
                                            <?php else: ?>
                                                <span class="no-logo">Belum ada logo</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div>
                                        <input type="file" name="logo" class="form-control form-control-sm text-muted">
                                    </div>
                                </div>
                            </div>

                            <!-- Logo Polinema -->
                            <div class="col-md-4">
                                <div class="logo-upload-container">
                                    <div>
                                        <label class="form-label d-block text-center mb-2">Logo Polinema</label>
                                        <div class="current-logo-box">
                                            <?php if (!empty($settings['logo_polinema'])): ?>
                                                <img src="../../public/uploads/logo/<?= htmlspecialchars($settings['logo_polinema']) ?>" alt="Logo Polinema">
                                            <?php else: ?>
                                                <span class="no-logo">Belum ada logo</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div>
                                        <input type="file" name="logo_polinema" class="form-control form-control-sm text-muted">
                                    </div>
                                </div>
                            </div>

                            <!-- Logo JTI -->
                            <div class="col-md-4">
                                <div class="logo-upload-container">
                                    <div>
                                        <label class="form-label d-block text-center mb-2">Logo JTI</label>
                                        <div class="current-logo-box">
                                            <?php if (!empty($settings['logo_jti'])): ?>
                                                <img src="../../public/uploads/logo/<?= htmlspecialchars($settings['logo_jti']) ?>" alt="Logo JTI">
                                            <?php else: ?>
                                                <span class="no-logo">Belum ada logo</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div>
                                        <input type="file" name="logo_jti" class="form-control form-control-sm text-muted">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-end gap-3">
                        <a href="../dashboard.php" class="btn btn-link text-decoration-none text-muted fw-bold">Batal</a>
                        <button type="submit" class="btn btn-accent rounded-pill px-5">
                            <i class="bi bi-save me-2"></i>Simpan Pengaturan
                        </button>
                    </div>

                </div>
            </div>
        </form>
    </div>
</section>