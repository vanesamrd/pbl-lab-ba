<?php
require_once "../../app/utils/session.php";
checkAdminLogin();
require_once "../../app/models/Kategori.php";
require_once "../../app/utils/log.php";

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = trim($_POST['nama']); // Sedikit pembersihan input
    
    if (!empty($nama)) {
        Kategori::create($nama);
        logActivity("Menambah kategori: $nama");
        $message = "<div class='alert alert-success alert-dismissible fade show' role='alert'><i class='bi bi-check-circle-fill me-2'></i>Kategori berhasil ditambahkan.<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
    } else {
        $message = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>Nama kategori wajib diisi.<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
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
            <div class="col-lg-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="/lab-ba/admin/dashboard.php" class="text-decoration-none text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="manage.php" class="text-decoration-none text-muted">Manajemen Kategori</a></li>
                        <li class="breadcrumb-item active text-primary-custom" aria-current="page">Tambah Baru</li>
                    </ol>
                </nav>
                <h3 class="fw-bold text-primary-custom">Tambah Kategori Baru</h3>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="section-container">
                    <?= $message ?>
                    
                    <form method="POST" autocomplete="off">
                        <div class="mb-4">
                            <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control" required placeholder="Contoh: Data Science, Machine Learning">
                            <div class="form-text small mt-2 text-muted">Gunakan nama yang singkat dan jelas.</div>
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