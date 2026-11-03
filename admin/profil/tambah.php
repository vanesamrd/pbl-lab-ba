<?php
require_once "../../app/utils/session.php";
checkAdminLogin();
require_once "../../app/models/Profil.php";
require_once "../../app/utils/log.php";

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kategori = $_POST['kategori'];
    $judul = trim($_POST['judul']);
    $isi = trim($_POST['isi']);

    if (empty($kategori) || empty($isi)) {
        $message = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>Kategori dan Isi Konten wajib diisi.<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
    } else {
        Profil::create($kategori, $judul, $isi);
        logActivity("Menambah konten profil: $kategori");
        $message = "<div class='alert alert-success alert-dismissible fade show' role='alert'><i class='bi bi-check-circle-fill me-2'></i>Konten profil berhasil ditambahkan.<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
    }
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

    /* Container */
    .section-container {
        background: #fff;
        border-radius: 1.5rem;
        box-shadow: 0 10px 40px rgba(10, 42, 67, 0.08);
        border: 1px solid rgba(63, 162, 247, 0.1);
        padding: 3rem;
        position: relative;
    }

    /* Form Styles */
    .form-label {
        font-weight: 600;
        color: var(--primary-custom);
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
        border-radius: 0.5rem;
        padding: 0.75rem 1rem;
        border: 1px solid #dee2e6;
        transition: all 0.2s;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 4px rgba(63, 162, 247, 0.15);
    }

    textarea.form-control {
        border-radius: 1rem;
        line-height: 1.6;
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
        <!-- Breadcrumb & Header -->
        <div class="row justify-content-center mb-4">
            <div class="col-lg-8">
                <<nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="/lab-ba/admin/dashboard.php" class="text-decoration-none text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="manage.php" class="text-decoration-none text-muted">Manajemen Profil</a></li>
                        <li class="breadcrumb-item active text-primary-custom" aria-current="page">Tambah Baru</li>
                    </ol>
                </nav>
                <h3 class="fw-bold text-primary-custom">Tambah Profil Lab</h3>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="section-container">
                    <?= $message ?>
                    
                    <form method="POST" autocomplete="off">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label">Kategori <span class="text-danger">*</span></label>
                                <select name="kategori" class="form-select" required>
                                    <option value="" disabled selected>-- Pilih Kategori --</option>
                                    <option value="profile">Profile</option>
                                    <option value="visi">Visi</option>
                                    <option value="misi">Misi</option>
                                    <option value="tujuan">Tujuan</option>
                                    <option value="latar belakang">Latar Belakang</option>
                                    <option value="fasilitas">Fasilitas</option>
                                    <option value="fokus_riset">Fokus Riset</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Judul (Opsional)</label>
                                <input type="text" name="judul" class="form-control" placeholder="Contoh: Visi Utama">
                            </div>

                            <div class="col-12">
                                <label class="form-label">Isi Konten <span class="text-danger">*</span></label>
                                <textarea name="isi" class="form-control" rows="8" placeholder="Tuliskan deskripsi lengkap..." required></textarea>
                                <div class="form-text mt-2 text-muted">Gunakan baris baru (Enter) untuk membuat paragraf.</div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-end gap-3 mt-5 pt-3 border-top">
                            <a href="manage.php" class="btn btn-link text-decoration-none text-muted fw-bold">Batal</a>
                            <button type="submit" class="btn btn-accent rounded-pill px-4">
                                <i class="bi bi-save me-2"></i>Simpan Konten
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>