<?php
require_once "../../app/utils/session.php";
checkAdminLogin();
require_once "../../app/models/Galeri.php";
require_once "../../app/utils/log.php";

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal = $_POST['tanggal'];

    $gambar = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];

    // Pastikan folder upload ada
    $targetDir = "../../public/uploads/galeri/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    // Rename file agar unik (opsional, tapi disarankan)
    $fileName = time() . '_' . basename($gambar);
    
    if (move_uploaded_file($tmp, $targetDir . $fileName)) {
        Galeri::create($judul, $deskripsi, $fileName, $tanggal);
        logActivity("Menambah foto galeri: $judul");
        $message = "<div class='alert alert-success alert-dismissible fade show' role='alert'><i class='bi bi-check-circle-fill me-2'></i>Foto galeri berhasil ditambahkan.<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
    } else {
        $message = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>Gagal mengupload gambar.<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
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
    }

    /* Upload Box Styling */
    .upload-box {
        background-color: #f8fbff;
        border: 2px dashed #cfe2ff;
        border-radius: 1rem;
        padding: 2rem;
        text-align: center;
        transition: all 0.3s;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        min-height: 250px;
    }
    
    .upload-box:hover {
        border-color: var(--accent);
        background-color: #f0f7ff;
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
        <!-- Breadcrumb / Header -->
        <div class="row justify-content-center mb-4">
            <div class="col-lg-10">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="/lab-ba/admin/dashboard.php" class="text-decoration-none text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="manage.php" class="text-decoration-none text-muted">Manajemen Galeri</a></li>
                        <li class="breadcrumb-item active text-primary-custom" aria-current="page">Tambah Baru</li>
                    </ol>
                </nav>
                <h3 class="fw-bold text-primary-custom">Tambah Dokumentasi</h3>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="section-container">
                    <?= $message ?>
                    
                    <form method="POST" enctype="multipart/form-data" autocomplete="off">
                        <div class="row g-5">
                            <!-- Kolom Kiri: Input Data -->
                            <div class="col-md-7">
                                <div class="mb-3">
                                    <label class="form-label">Judul Kegiatan</label>
                                    <input type="text" name="judul" class="form-control" required placeholder="Contoh: Workshop Data Science">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tanggal Kegiatan</label>
                                    <input type="date" name="tanggal" class="form-control" required value="<?= date('Y-m-d') ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Deskripsi</label>
                                    <textarea name="deskripsi" class="form-control" rows="5" placeholder="Tuliskan deskripsi singkat kegiatan ini..."></textarea>
                                </div>
                            </div>

                            <!-- Kolom Kanan: Upload Foto -->
                            <div class="col-md-5">
                                <label class="form-label d-block">Upload Foto</label>
                                <div class="upload-box">
                                    <i class="bi bi-card-image text-primary mb-3" style="font-size: 3rem; opacity: 0.5;"></i>
                                    <div class="fw-bold text-dark mb-1">Pilih Gambar</div>
                                    <div class="small text-muted mb-3">Format JPG/PNG, Max 5MB</div>
                                    <input type="file" name="gambar" class="form-control form-control-sm w-75" required>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-end gap-3 mt-4 pt-3 border-top">
                            <a href="../galeri/manage.php" class="btn btn-link text-decoration-none text-muted fw-bold">Batal</a>
                            <button type="submit" class="btn btn-accent rounded-pill px-4">
                                <i class="bi bi-save me-2"></i>Simpan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>