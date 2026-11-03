<?php
require_once "../../app/utils/session.php";
checkAdminLogin();
require_once "../../app/models/Publikasi.php";
require_once "../../app/models/Dosen.php";
require_once "../../app/models/Kategori.php";
require_once "../../app/utils/log.php";

$id = $_GET['id'];
$p = Publikasi::find($id);
if (!$p) {
    header("Location: manage.php");
    exit;
}
$dosen_selected = array_map(function ($d) {
    return $d['id'];
}, Publikasi::getDosen($id));
$kategori_selected = array_map(function ($k) {
    return $k['id'];
}, Publikasi::getKategori($id));
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul = trim($_POST['judul'] ?? '');
    $tanggal = $_POST['tanggal'] ?? '';
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $link = trim($_POST['link'] ?? '');
    $dosen_ids = $_POST['dosen_ids'] ?? [];
    $kategori_ids = $_POST['kategori_ids'] ?? [];
    $file = $p['file'];
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
            $file = time() . '_' . preg_replace('/[^a-zA-Z0-9_.-]/', '', $_FILES['file']['name']);
            move_uploaded_file($_FILES['file']['tmp_name'], "../../public/uploads/publikasi/" . $file);
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
        Publikasi::update($id, $data, $dosen_ids, $kategori_ids);
        logActivity("Mengedit publikasi: $judul");
        $p = Publikasi::find($id);
        $dosen_selected = array_map(function ($d) {
            return $d['id'];
        }, Publikasi::getDosen($id));
        $kategori_selected = array_map(function ($k) {
            return $k['id'];
        }, Publikasi::getKategori($id));
        $message = "<div class='alert alert-success alert-dismissible fade show' role='alert'><i class='bi bi-check-circle-fill me-2'></i>Data publikasi berhasil diupdate.<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
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

    /* File Preview Box */
    .file-status-box {
        background-color: #f8fbff;
        border: 1px solid #cfe2ff;
        border-radius: 0.5rem;
        padding: 1rem;
        margin-top: 0.5rem;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #084298;
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
                        <li class="breadcrumb-item"><a href="manage.php" class="text-decoration-none text-muted">Manajemen Publikasi</a></li>
                        <li class="breadcrumb-item active text-primary-custom" aria-current="page">Edit Publikasi</li>
                    </ol>
                </nav>
                <h3 class="fw-bold text-primary-custom">Edit Data Publikasi</h3>
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
                                    <input type="text" name="judul" class="form-control" value="<?= htmlspecialchars($p['judul']) ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Tanggal Publikasi</label>
                                    <input type="date" name="tanggal" class="form-control" value="<?= htmlspecialchars($p['tanggal']) ?>">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Link SINTA / Jurnal <span class="text-danger">*</span></label>
                                    <input type="url" name="link" class="form-control" value="<?= htmlspecialchars($p['link']) ?>" required placeholder="https://...">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Dosen Peneliti <span class="text-danger">*</span></label>
                                    <select name="dosen_ids[]" class="form-select" multiple required style="min-height: 120px;">
                                        <?php foreach (Dosen::all() as $d): ?>
                                            <option value="<?= $d['id'] ?>" <?= in_array($d['id'], $dosen_selected) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($d['nama']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="form-text small">Tahan tombol <b>Ctrl</b> (Windows) atau <b>Command</b> (Mac) untuk memilih lebih dari satu.</div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Kategori Riset</label>
                                    <select name="kategori_ids[]" class="form-select" multiple style="min-height: 100px;">
                                        <?php foreach (Kategori::all() as $k): ?>
                                            <option value="<?= $k['id'] ?>" <?= in_array($k['id'], $kategori_selected) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($k['nama']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Kolom Kanan: Detail & File -->
                            <div class="col-md-6">
                                <h5 class="mb-4 pb-2 border-bottom text-muted small fw-bold text-uppercase">Detail & File</h5>

                                <div class="mb-4">
                                    <label class="form-label">Deskripsi / Abstrak</label>
                                    <textarea name="deskripsi" class="form-control" rows="8" placeholder="Tuliskan abstrak atau ringkasan publikasi..."><?= htmlspecialchars($p['deskripsi']) ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">File Dokumen</label>
                                    <input type="file" name="file" class="form-control" accept="application/pdf">
                                    
                                    <?php if ($p['file']): ?>
                                        <div class="file-status-box">
                                            <i class="bi bi-file-earmark-pdf-fill fs-5"></i>
                                            <div>
                                                <div>File saat ini tersedia</div>
                                                <a href="../../public/uploads/publikasi/<?= htmlspecialchars($p['file']) ?>" target="_blank" class="small text-decoration-none fw-bold">Lihat/Download</a>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="form-text mt-2">Belum ada file yang diunggah.</div>
                                    <?php endif; ?>
                                    <div class="form-text mt-1 text-muted">Format: PDF only. Maks 2MB.</div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-end gap-3 mt-5 pt-3 border-top">
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