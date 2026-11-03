<?php
require_once "../../app/utils/session.php";
checkAdminLogin();
require_once "../../app/models/Berita.php";
require_once "../../app/utils/log.php";

$errors = [];
$success_msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = trim($_POST['judul'] ?? '');
    $isi = trim($_POST['isi'] ?? '');
    $penulis = trim($_POST['penulis'] ?? '');
    $tanggal = trim($_POST['tanggal'] ?? date('Y-m-d'));
    $gambar = $_FILES['gambar'] ?? null;

    if ($judul === '') $errors[] = 'Judul wajib diisi.';
    if ($isi === '') $errors[] = 'Isi berita wajib diisi.';
    if ($penulis === '') $errors[] = 'Penulis wajib diisi.';

    $gambarName = '';
    if ($gambar && $gambar['tmp_name']) {
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($gambar['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            $errors[] = 'Format gambar harus jpg, jpeg, png, atau webp.';
        } elseif ($gambar['size'] > 2 * 1024 * 1024) {
            $errors[] = 'Ukuran gambar maksimal 2MB.';
        } else {
            // Pastikan folder upload ada
            $targetDir = "../../public/uploads/berita/";
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $gambarName = uniqid('berita_') . '.' . $ext;
            move_uploaded_file($gambar['tmp_name'], $targetDir . $gambarName);
        }
    }

    if (!$errors) {
        Berita::create([
            'judul' => $judul,
            'isi' => $isi,
            'penulis' => $penulis,
            'tanggal' => $tanggal,
            'gambar' => $gambarName
        ]);
        logActivity("Menambah berita: $judul");
        $success_msg = "Berita berhasil ditambahkan.";
        // header("Location: manage.php");
        // exit;
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

    /* Upload Box */
    .upload-box {
        background-color: #f8fbff;
        border: 2px dashed #cfe2ff;
        border-radius: 1rem;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s;
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
        <!-- Breadcrumb & Header -->
        <div class="row justify-content-center mb-4">
            <div class="col-lg-10">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="/lab-ba/admin/dashboard.php" class="text-decoration-none text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="manage.php" class="text-decoration-none text-muted">Manajemen Berita</a></li>
                        <li class="breadcrumb-item active text-primary-custom" aria-current="page">Tambah Baru</li>
                    </ol>
                </nav>
                <h3 class="fw-bold text-primary-custom">Tambah Berita Baru</h3>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="section-container">

                    <!-- Alert Messages -->
                    <?php if ($errors): ?>
                        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                            <ul class="mb-0 ps-3">
                                <?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if ($success_msg): ?>
                        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i><?= $success_msg ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form method="post" enctype="multipart/form-data" autocomplete="off">
                        <div class="row g-5">
                            <!-- Kolom Kiri: Informasi Utama -->
                            <div class="col-md-6">
                                <h5 class="mb-4 pb-2 border-bottom text-muted small fw-bold text-uppercase">Informasi Berita</h5>

                                <div class="mb-3">
                                    <label class="form-label">Judul Berita <span class="text-danger">*</span></label>
                                    <input type="text" name="judul" class="form-control" required placeholder="Contoh: Kunjungan Industri ke Jakarta" value="<?= htmlspecialchars($_POST['judul'] ?? '') ?>">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Penulis <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-person"></i></span>
                                        <input type="text" name="penulis" class="form-control border-start-0 ps-0" required placeholder="Nama penulis" value="<?= htmlspecialchars($_POST['penulis'] ?? '') ?>">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Tanggal Publikasi</label>
                                    <input type="date" name="tanggal" class="form-control" required value="<?= htmlspecialchars($_POST['tanggal'] ?? date('Y-m-d')) ?>">
                                </div>
                            </div>

                            <!-- Kolom Kanan: Konten & Media -->
                            <div class="col-md-6">
                                <h5 class="mb-4 pb-2 border-bottom text-muted small fw-bold text-uppercase">Konten & Media</h5>

                                <div class="mb-3">
                                    <label class="form-label">Gambar Sampul</label>
                                    <div class="upload-box">
                                        <i class="bi bi-image text-primary mb-2" style="font-size: 2rem; opacity: 0.6;"></i>
                                        <div class="small text-muted mb-2">Upload Gambar (JPG/PNG/WEBP)</div>
                                        <input type="file" name="gambar" accept="image/*" class="form-control form-control-sm">
                                        <div class="form-text mt-1 text-muted small">Maksimal ukuran 2MB.</div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Isi Berita <span class="text-danger">*</span></label>
                                    <textarea name="isi" class="form-control" rows="8" placeholder="Tuliskan isi berita lengkap di sini..." required><?= htmlspecialchars($_POST['isi'] ?? '') ?></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-end gap-3 mt-5 pt-3 border-top">
                            <a href="manage.php" class="btn btn-link text-decoration-none text-muted fw-bold">Batal</a>
                            <button type="submit" class="btn btn-accent rounded-pill px-4">
                                <i class="bi bi-save me-2"></i>Simpan Berita
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>