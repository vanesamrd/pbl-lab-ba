<?php
require_once "../../app/utils/session.php";
checkAdminLogin();
require_once "../../app/models/Dosen.php";
require_once "../../app/models/Kategori.php";
require_once "../../app/utils/log.php";

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $keahlian = $_POST['keahlian'];
    $kategori_id = $_POST['kategori_id'];
    $foto = $_FILES['foto']['name'];
    $tmp = $_FILES['foto']['tmp_name'];
    
    // Pastikan folder upload ada
    if (!is_dir("../../public/uploads/dosen/")) {
        mkdir("../../public/uploads/dosen/", 0777, true);
    }

    move_uploaded_file($tmp, "../../public/uploads/dosen/" . $foto);
    Dosen::create($nama, $keahlian, $foto);
    logActivity("Menambah dosen: $nama");
    $message = "<div class='alert alert-success alert-dismissible fade show' role='alert'><i class='bi bi-check-circle-fill me-2'></i>Dosen berhasil ditambahkan.<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
    // header("Location: manage.php");
    // exit;
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
    
    .upload-box {
        background-color: #f8fbff;
        border: 2px dashed #cfe2ff;
        border-radius: 1rem;
        padding: 2rem;
        text-align: center;
        transition: all 0.3s;
    }
    
    .upload-box:hover {
        border-color: var(--accent);
        background-color: #f0f7ff;
    }
</style>

<section class="py-5 min-vh-100">
    <div class="container">
        <!-- Breadcrumb / Header -->
        <div class="row justify-content-center mb-4">
            <div class="col-lg-8">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="/lab-ba/admin/dashboard.php" class="text-decoration-none text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="manage.php" class="text-decoration-none text-muted">Manajemen Dosen</a></li>
                        <li class="breadcrumb-item active text-primary-custom" aria-current="page">Tambah Baru</li>
                    </ol>
                </nav>
                <h3 class="fw-bold text-primary-custom">Tambah Dosen Baru</h3>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="section-container">
                    <?= $message ?>
                    
                    <form method="POST" enctype="multipart/form-data" autocomplete="off">
                        <div class="row g-4">
                            <!-- Kolom Kiri: Input Data -->
                            <div class="col-md-7">
                                <div class="mb-3">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input type="text" name="nama" class="form-control" required placeholder="Contoh: Dr. John Doe, M.Kom">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Bidang Keahlian</label>
                                    <input type="text" name="keahlian" class="form-control" required placeholder="Contoh: Data Mining, AI">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Kategori</label>
                                    <select name="kategori_id" class="form-select" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        <?php foreach (Kategori::all() as $k): ?>
                                            <option value="<?= $k['id'] ?>"><?= htmlspecialchars($k['nama']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Kolom Kanan: Foto Upload -->
                            <div class="col-md-5">
                                <label class="form-label d-block">Foto Profil</label>
                                <div class="upload-box h-100 d-flex flex-column justify-content-center align-items-center">
                                    <i class="bi bi-cloud-arrow-up text-primary mb-2" style="font-size: 2rem;"></i>
                                    <div class="small text-muted mb-2">Upload Foto</div>
                                    <input type="file" name="foto" class="form-control form-control-sm" required>
                                    <div class="form-text small mt-2">Format: JPG/PNG</div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-end gap-3 mt-4 pt-3 border-top">
                            <a href="../dosen/manage.php" class="btn btn-link text-decoration-none text-muted fw-bold">Batal</a>
                            <button type="submit" class="btn btn-accent rounded-pill px-4">
                                <i class="bi bi-plus-lg me-2"></i>Simpan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>