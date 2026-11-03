<?php
require_once "../../app/utils/session.php";
checkAdminLogin();
require_once "../../app/models/Galeri.php";

// Ambil data galeri berdasarkan id
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$galeri = Galeri::find($id);
if (!$galeri) {
    header("Location: manage.php");
    exit;
}

$message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $judul = $_POST['judul'] ?? '';
    $tanggal = $_POST['tanggal'] ?? '';
    $deskripsi = $_POST['deskripsi'] ?? '';
    $gambar = $galeri['gambar'];

    if (!empty($_FILES['gambar']['name'])) {
        $targetDir = "../../public/uploads/galeri/";
        $fileName = time() . '_' . basename($_FILES['gambar']['name']);
        $targetFile = $targetDir . $fileName;
        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $targetFile)) {
            // Hapus gambar lama jika ada dan bukan default (opsional, praktik baik)
            if (!empty($galeri['gambar']) && file_exists($targetDir . $galeri['gambar'])) {
                unlink($targetDir . $galeri['gambar']);
            }
            $gambar = $fileName;
        } else {
            $message = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>Gagal mengupload gambar.<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
        }
    }

    if (empty($message)) { // Hanya update jika tidak ada error upload
        if (Galeri::update($id, $judul, $deskripsi, $tanggal, $gambar)) {
            $message = "<div class='alert alert-success alert-dismissible fade show' role='alert'><i class='bi bi-check-circle-fill me-2'></i>Data galeri berhasil diperbarui.<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
            $galeri = Galeri::find($id); // refresh data
        } else {
            $message = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>Gagal memperbarui data database.<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
        }
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

    /* Form Elements */
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

    /* Image Preview Box */
    .img-preview-box {
        width: 100%;
        height: 250px;
        border-radius: 1rem;
        overflow: hidden;
        border: 2px solid #e9ecef;
        background-color: #f8fbff;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        position: relative;
    }

    .img-preview-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .no-img {
        color: #adb5bd;
        font-size: 3rem;
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
            <div class="col-lg-10">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="/lab-ba/admin/dashboard.php" class="text-decoration-none text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="manage.php" class="text-decoration-none text-muted">Manajemen Galeri</a></li>
                        <li class="breadcrumb-item active text-primary-custom" aria-current="page">Edit Galeri</li>
                    </ol>
                </nav>
                <h3 class="fw-bold text-primary-custom">Edit Dokumentasi</h3>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="section-container">
                    <?= $message ?>
                    
                    <form method="POST" enctype="multipart/form-data" autocomplete="off">
                        <div class="row g-5">
                            <!-- Kolom Kiri: Input Teks -->
                            <div class="col-md-7">
                                <div class="mb-3">
                                    <label class="form-label">Judul Kegiatan</label>
                                    <input type="text" name="judul" class="form-control" required value="<?= htmlspecialchars($galeri['judul']) ?>" placeholder="Nama kegiatan atau acara">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tanggal Pelaksanaan</label>
                                    <input type="date" name="tanggal" class="form-control" required value="<?= htmlspecialchars($galeri['tanggal']) ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Deskripsi Singkat</label>
                                    <textarea name="deskripsi" class="form-control" rows="6" placeholder="Jelaskan detail kegiatan..."><?= htmlspecialchars($galeri['deskripsi']) ?></textarea>
                                </div>
                            </div>

                            <!-- Kolom Kanan: Gambar -->
                            <div class="col-md-5">
                                <label class="form-label">Gambar Dokumentasi</label>
                                <div class="img-preview-box">
                                    <?php if (!empty($galeri['gambar'])): ?>
                                        <img src="../../public/uploads/galeri/<?= htmlspecialchars($galeri['gambar']) ?>" alt="Preview">
                                    <?php else: ?>
                                        <div class="no-img"><i class="bi bi-image"></i></div>
                                    <?php endif; ?>
                                </div>
                                <div class="mb-3">
                                    <label for="gambarInput" class="form-label small text-muted">Ganti Gambar (Opsional)</label>
                                    <input type="file" name="gambar" id="gambarInput" class="form-control form-control-sm">
                                    <div class="form-text small">Format: JPG, PNG. Disarankan rasio landscape.</div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-end gap-3 mt-4 pt-3 border-top">
                            <a href="../galeri/manage.php" class="btn btn-link text-decoration-none text-muted fw-bold">Batal</a>
                            <button type="submit" class="btn btn-accent rounded-pill px-4">
                                <i class="bi bi-save me-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>