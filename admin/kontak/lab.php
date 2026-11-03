<?php
require_once "../../app/utils/session.php";
checkAdminLogin();
require_once "../../app/models/KontakLab.php";
require_once "../../app/utils/log.php";

$kontak = KontakLab::get();
if (!$kontak) {
    $kontak = [
        'alamat' => '',
        'email' => '',
        'telepon' => '',
        'website' => '',
        'maps_embed' => ''
    ];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $alamat = $_POST['alamat'];
    $email = $_POST['email'];
    $telepon = $_POST['telepon'];
    $website = $_POST['website'];
    $maps_embed = $_POST['maps_embed'];

    if (KontakLab::get()) {
        KontakLab::update($alamat, $email, $telepon, $website, $maps_embed);
        logActivity("Update kontak lab");
    } else {
        KontakLab::create($alamat, $email, $telepon, $website, $maps_embed);
        logActivity("Create kontak lab");
    }
    header("Location: lab.php");
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
        margin-bottom: 0.5rem;
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

    textarea.form-control {
        border-radius: 1rem;
        resize: vertical;
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
            <div class="col-lg-8">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="/lab-ba/admin/dashboard.php" class="text-decoration-none text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item active text-primary-custom" aria-current="page">Kontak Lab</li>
                    </ol>
                </nav>
                <h3 class="fw-bold text-primary-custom">Pengaturan Kontak & Lokasi</h3>
            </div>
        </div>

        <form method="POST" autocomplete="off">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    
                    <div class="section-card">
                        <h5 class="section-header"><i class="bi bi-geo-alt me-2"></i>Informasi Kontak</h5>
                        
                        <!-- Row 1: Alamat -->
                        <div class="mb-4">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea name="alamat" class="form-control" rows="3" required placeholder="Jalan Soekarno Hatta No.9..."><?= htmlspecialchars($kontak['alamat']) ?></textarea>
                        </div>

                        <!-- Row 2: Kontak Digital (Grid) -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Email Resmi</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" class="form-control border-start-0 ps-0" value="<?= htmlspecialchars($kontak['email']) ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nomor Telepon / WhatsApp</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-telephone"></i></span>
                                    <input type="text" name="telepon" class="form-control border-start-0 ps-0" value="<?= htmlspecialchars($kontak['telepon']) ?>" required>
                                </div>
                            </div>
                        </div>

                        <!-- Row 3: Website -->
                        <div class="mb-4">
                            <label class="form-label">Website Utama</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-globe"></i></span>
                                <input type="text" name="website" class="form-control border-start-0 ps-0" value="<?= htmlspecialchars($kontak['website']) ?>" placeholder="https://...">
                            </div>
                        </div>

                        <!-- Row 4: Google Maps -->
                        <div class="mb-4">
                            <label class="form-label d-flex justify-content-between">
                                <span>Embed Google Maps (Iframe)</span>
                                <a href="https://www.google.com/maps" target="_blank" class="small text-decoration-none fw-normal"><i class="bi bi-box-arrow-up-right me-1"></i>Buka Maps</a>
                            </label>
                            <textarea name="maps_embed" class="form-control font-monospace small text-muted" placeholder='<iframe src="https://www.google.com/maps/embed?..."></iframe>' rows="4"><?= htmlspecialchars($kontak['maps_embed']) ?></textarea>
                            <div class="form-text mt-2 text-muted">Salin kode HTML iframe dari menu "Bagikan" -> "Sematkan peta" di Google Maps.</div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-end gap-3 pt-3 border-top">
                            <a href="../dashboard.php" class="btn btn-link text-decoration-none text-muted fw-bold">Batal</a>
                            <button type="submit" class="btn btn-accent rounded-pill px-5">
                                <i class="bi bi-save me-2"></i>Simpan Perubahan
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </form>
    </div>
</section>