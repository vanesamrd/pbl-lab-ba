<?php
require_once "../../app/utils/session.php";
checkAdminLogin();
require_once "../../app/models/Dosen.php";
require_once "../../app/models/Kategori.php";
require_once "../../app/utils/log.php";

$id = $_GET['id'];
$d = Dosen::find($id);

if (!$d) {
    die("Data dosen tidak ditemukan.");
}

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $keahlian = $_POST['keahlian'];
    $kategori_id = $_POST['kategori_id'];
    if (!empty($_FILES['foto']['name'])) {
        $foto = $_FILES['foto']['name'];
        $tmp = $_FILES['foto']['tmp_name'];
        if (!empty($d['foto']) && file_exists("../../public/uploads/dosen/" . $d['foto'])) {
            unlink("../../public/uploads/dosen/" . $d['foto']);
        }
        move_uploaded_file($tmp, "../../public/uploads/dosen/" . $foto);
    } else {
        $foto = $d['foto'];
    }
    // Backend logic tetap (Note: pastikan method update di Model menerima parameter yang sesuai)
    Dosen::update($id, $nama, $keahlian, $foto);
    logActivity("Mengedit dosen: {$d['nama']} → $nama");
    $d = Dosen::find($id); // refresh data
    $message = "<div class='alert alert-success alert-dismissible fade show role='alert'><i class='bi bi-check-circle-fill me-2'></i>Data dosen berhasil diperbarui.<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
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

    /* Image Preview */
    .current-photo-container {
        width: 120px;
        height: 120px;
        border-radius: 1rem;
        overflow: hidden;
        border: 2px solid #e9ecef;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        margin-bottom: 1rem;
        background-color: #f8fbff;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .current-photo-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .no-photo {
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
        <!-- Breadcrumb / Header -->
        <div class="row justify-content-center mb-4">
            <div class="col-lg-8">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="/lab-ba/admin/dashboard.php" class="text-decoration-none text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="manage.php" class="text-decoration-none text-muted">Manajemen Dosen</a></li>
                        <li class="breadcrumb-item active text-primary-custom" aria-current="page">Edit Data</li>
                    </ol>
                </nav>
                <h3 class="fw-bold text-primary-custom">Edit Data Dosen</h3>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="section-container">
                    <?= $message ?>
                    
                    <form method="POST" enctype="multipart/form-data" autocomplete="off">
                        <div class="row g-4">
                            <!-- Kolom Kiri: Input Data -->
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($d['nama']); ?>" required placeholder="Contoh: Dr. John Doe, M.Kom">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Bidang Keahlian</label>
                                    <input type="text" name="keahlian" class="form-control" value="<?= htmlspecialchars($d['keahlian']); ?>" required placeholder="Contoh: Data Mining, AI">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Kategori</label>
                                    <select name="kategori_id" class="form-select" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        <?php foreach (Kategori::all() as $k): ?>
                                            <option value="<?= $k['id'] ?>" <?= (isset($d['kategori_id']) && $d['kategori_id'] == $k['id']) ? 'selected' : '' ?>><?= htmlspecialchars($k['nama']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Kolom Kanan: Foto -->
                            <div class="col-md-4">
                                <label class="form-label d-block">Foto Profil</label>
                                <div class="current-photo-container mx-auto mx-md-0">
                                    <?php if (!empty($d['foto']) && file_exists("../../public/uploads/dosen/" . $d['foto'])): ?>
                                        <img src="../../public/uploads/dosen/<?= htmlspecialchars($d['foto']); ?>" alt="Foto Dosen">
                                    <?php else: ?>
                                        <div class="no-photo"><i class="bi bi-person"></i></div>
                                    <?php endif; ?>
                                </div>
                                <div class="mb-3">
                                    <label for="fotoInput" class="form-label small text-muted">Ganti Foto (Opsional)</label>
                                    <input type="file" name="foto" id="fotoInput" class="form-control form-control-sm text-muted">
                                    <div class="form-text small">Format: JPG, PNG. Maks 2MB.</div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-end gap-3 mt-4 pt-3 border-top">
                            <a href="../dosen/manage.php" class="btn btn-link text-decoration-none text-muted fw-bold">Batal</a>
                            <button type="submit" class="btn btn-accent rounded-pill">
                                <i class="bi bi-save me-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>