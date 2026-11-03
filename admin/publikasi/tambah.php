<?php
require_once "../../app/utils/session.php";
checkAdminLogin();
require_once "../../app/models/Publikasi.php";
require_once "../../app/models/Dosen.php";
require_once "../../app/models/Kategori.php";
require_once "../../app/utils/log.php";

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul = trim($_POST['judul'] ?? '');
    $tanggal = $_POST['tanggal'] ?? '';
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $link = trim($_POST['link'] ?? '');
    $dosen_ids = $_POST['dosen_ids'] ?? [];
    $kategori_ids = $_POST['kategori_ids'] ?? [];
    $file = '';
    // Validasi
    if (!$judul) {
        $message = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>Judul wajib diisi.<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
    } elseif (!$link) {
        $message = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>Link SINTA wajib diisi.<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
    } elseif (count($dosen_ids) < 1) {
        $message = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>Minimal 1 dosen wajib dipilih.<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
    } elseif (!empty($_FILES['file']['name'])) {
        $ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
        $size = $_FILES['file']['size'];
        if ($ext !== 'pdf' || $size > 2 * 1024 * 1024) {
            $message = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>File harus PDF dan maksimal 2MB.<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
        } else {
            // Pastikan folder upload ada
            $targetDir = "../../public/uploads/publikasi/";
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            
            $file = time() . '_' . preg_replace('/[^a-zA-Z0-9_.-]/', '', $_FILES['file']['name']);
            move_uploaded_file($_FILES['file']['tmp_name'], $targetDir . $file);
        }
    }
    if (!$message) {
        $data = [
            'judul' => $judul,
            'tanggal' => $tanggal,
            'file' => $file,
            'link' => $link,
            'deskripsi' => $deskripsi
        ];
        Publikasi::create($data, $dosen_ids, $kategori_ids);
        logActivity("Menambah publikasi: $judul");
        $message = "<div class='alert alert-success alert-dismissible fade show' role='alert'><i class='bi bi-check-circle-fill me-2'></i>Publikasi berhasil ditambahkan.<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
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
            <div class="row justify-content-center mb-4">
            <div class="col-lg-10">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="/lab-ba/admin/dashboard.php" class="text-decoration-none text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="manage.php" class="text-decoration-none text-muted">Manajemen Publikasii</a></li>
                        <li class="breadcrumb-item active text-primary-custom" aria-current="page">Tambah Baru</li>
                    </ol>
                </nav>
                <h3 class="fw-bold text-primary-custom">Tambah Publikasi Baru</h3>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="section-container">
                    <?= $message ?>
                    
                    <form method="POST" enctype="multipart/form-data" autocomplete="off">
                        <div class="row g-5">
                            <!-- Kolom Kiri: Informasi Utama -->
                            <div class="col-md-6">
                                <h5 class="mb-4 pb-2 border-bottom text-muted small fw-bold text-uppercase">Informasi Utama</h5>
                                
                                <div class="mb-3">
                                    <label class="form-label">Judul Publikasi <span class="text-danger">*</span></label>
                                    <input type="text" name="judul" class="form-control" required placeholder="Judul lengkap jurnal/riset">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Tanggal Publikasi</label>
                                    <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Link SINTA / Jurnal <span class="text-danger">*</span></label>
                                    <input type="url" name="link" class="form-control" required placeholder="https://...">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Dosen Peneliti <span class="text-danger">*</span></label>
                                    <select name="dosen_ids[]" class="form-select" multiple required style="min-height: 120px;">
                                        <?php foreach (Dosen::all() as $d): ?>
                                            <option value="<?= $d['id'] ?>"> <?= htmlspecialchars($d['nama']) ?> </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="form-text small">Tahan tombol <b>Ctrl</b> (Windows) atau <b>Command</b> (Mac) untuk memilih lebih dari satu.</div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Kategori Riset</label>
                                    <select name="kategori_ids[]" class="form-select" multiple style="min-height: 100px;">
                                        <?php foreach (Kategori::all() as $k): ?>
                                            <option value="<?= $k['id'] ?>"> <?= htmlspecialchars($k['nama']) ?> </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Kolom Kanan: Detail & File -->
                            <div class="col-md-6">
                                <h5 class="mb-4 pb-2 border-bottom text-muted small fw-bold text-uppercase">Detail & File</h5>

                                <div class="mb-4">
                                    <label class="form-label">Deskripsi / Abstrak</label>
                                    <textarea name="deskripsi" class="form-control" rows="8" placeholder="Tuliskan abstrak atau ringkasan publikasi..."></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">File Dokumen (Opsional)</label>
                                    <div class="p-4 border rounded bg-light text-center">
                                        <i class="bi bi-file-earmark-arrow-up text-primary fs-2"></i>
                                        <div class="mt-2">
                                            <input type="file" name="file" class="form-control form-control-sm" accept="application/pdf">
                                        </div>
                                        <div class="form-text mt-2 text-muted">Hanya file PDF. Maksimal 2MB.</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-end gap-3 mt-5 pt-3 border-top">
                            <a href="manage.php" class="btn btn-link text-decoration-none text-muted fw-bold">Batal</a>
                            <button type="submit" class="btn btn-accent rounded-pill px-4">
                                <i class="bi bi-plus-circle me-2"></i>Simpan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>