<?php
require_once "../../app/utils/session.php";
checkAdminLogin();
require_once "../../app/models/Galeri.php";
$data = Galeri::all();
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

    /* Card Container */
    .section-container {
        background: #fff;
        border-radius: 1.5rem;
        box-shadow: 0 10px 40px rgba(10, 42, 67, 0.08);
        border: 1px solid rgba(63, 162, 247, 0.1);
        padding: 2rem;
        position: relative;
        overflow: hidden;
    }

    /* Search Bar */
    .search-input {
        border-radius: 50px;
        padding-left: 2.5rem;
        border: 1px solid #e0e0e0;
        background-color: #f8fbff;
        transition: all 0.3s;
    }

    .search-input:focus {
        background-color: #fff;
        border-color: var(--accent);
        box-shadow: 0 0 0 4px rgba(63, 162, 247, 0.1);
    }

    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #adb5bd;
    }

    /* Buttons */
    .btn-accent {
        background-color: var(--accent);
        color: #fff;
        border: none;
        box-shadow: 0 4px 10px rgba(63, 162, 247, 0.3);
        transition: all 0.2s;
        font-weight: 600;
        padding: 0.6rem 1.5rem;
    }

    .btn-accent:hover {
        background-color: var(--accent-hover);
        color: #fff;
        transform: translateY(-2px);
    }

    /* Table Styling */
    .table-custom thead th {
        background-color: #f8fbff;
        color: #6c757d;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        padding: 1.2rem 1rem;
        border-bottom: 2px solid #e9ecef;
    }

    .table-custom tbody td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f0f0f0;
    }

    .table-hover tbody tr:hover {
        background-color: #fafbff;
    }

    /* Thumbnail Image */
    .thumb-img {
        width: 80px;
        height: 55px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #dee2e6;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .thumb-placeholder {
        width: 80px;
        height: 55px;
        background-color: #f1f5f9;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #cbd5e1;
        font-size: 1.5rem;
    }

    .text-primary-custom { color: var(--primary-custom) !important; }
</style>

<section class="py-5 min-vh-100">
    <div class="container">
        
        <!-- Header Page -->
        <div class="row align-items-center mb-4 g-3">
            <div class="col-md-6">
            <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="/lab-ba/admin/dashboard.php" class="text-decoration-none text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item active text-primary-custom" aria-current="page">Manajemen Galeri</li>
                    </ol>
                </nav>
                <h3 class="fw-bold text-primary-custom mb-1">Manajemen Galeri</h3>
                <p class="text-muted small mb-0">Kelola dokumentasi kegiatan dan foto laboratorium.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="tambah.php" class="btn btn-accent rounded-pill">
                    <i class="bi bi-plus-lg me-2"></i>Tambah Galeri
                </a>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="section-container">
            <!-- Toolbar: Search -->
            <div class="row mb-4">
                <div class="col-md-5 col-lg-4">
                    <div class="position-relative">
                        <i class="bi bi-search search-icon"></i>
                        <input type="text" id="searchInput" class="form-control search-input" placeholder="Cari judul kegiatan...">
                    </div>
                </div>
            </div>

            <?php if (empty($data)): ?>
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-images text-muted opacity-25" style="font-size: 4rem;"></i>
                    </div>
                    <h5 class="text-muted fw-bold">Belum ada galeri</h5>
                    <p class="text-muted small">Silakan tambahkan dokumentasi kegiatan baru.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-custom table-hover align-middle mb-0" id="galeriTable">
                        <thead>
                            <tr>
                                <th width="15%">Preview</th>
                                <th width="45%">Judul Kegiatan</th>
                                <th width="20%">Tanggal</th>
                                <th width="20%" class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data as $g): ?>
                                <tr>
                                    <td>
                                        <?php if (!empty($g['gambar'])): ?>
                                            <img src="../../public/uploads/galeri/<?= htmlspecialchars($g['gambar']) ?>" class="thumb-img" alt="Thumbnail">
                                        <?php else: ?>
                                            <div class="thumb-placeholder">
                                                <i class="bi bi-image"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark search-target"><?= htmlspecialchars($g['judul']) ?></div>
                                        <?php if (!empty($g['deskripsi'])): ?>
                                            <div class="small text-muted text-truncate" style="max-width: 300px;">
                                                <?= htmlspecialchars($g['deskripsi']) ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center text-secondary">
                                            <i class="bi bi-calendar3 me-2 small"></i>
                                            <span><?= date('d M Y', strtotime($g['tanggal'])) ?></span>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="edit.php?id=<?= $g['id']; ?>" class="btn btn-warning btn-sm rounded-pill px-3 fw-bold text-dark border-0 shadow-sm" title="Edit Data">
                                                <i class="bi bi-pencil-square me-1"></i> Edit
                                            </a>
                                            <a href="hapus.php?id=<?= $g['id']; ?>" class="btn btn-danger btn-sm rounded-pill px-3 fw-bold border-0 shadow-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus galeri ini? Tindakan ini tidak dapat dibatalkan.')" title="Hapus Data">
                                                <i class="bi bi-trash me-1"></i> Hapus
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    
                    <!-- No Results State -->
                    <div id="noResults" class="text-center py-5 d-none">
                        <i class="bi bi-search text-muted opacity-50 mb-2 fs-3"></i>
                        <p class="text-muted">Data tidak ditemukan.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Back Button -->
        <div class="mt-4">
            <a href="../dashboard.php" class="btn btn-link text-decoration-none text-muted fw-bold ps-0 hover-primary">
            <a href="/lab-ba/admin/dashboard.php" class="btn btn-outline-danger rounded-pill px-4">
            Kembali ke Dashboard
            </a>
        </div>
    </div>
</section>

<!-- Script Pencarian Cepat -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const table = document.getElementById('galeriTable');
    const noResults = document.getElementById('noResults');

    if (searchInput && table) {
        searchInput.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            const bodyRows = table.querySelectorAll('tbody tr');
            let hasVisibleRow = false;
            
            bodyRows.forEach(row => {
                const text = row.innerText.toLowerCase();
                if (text.includes(filter)) {
                    row.style.display = '';
                    hasVisibleRow = true;
                } else {
                    row.style.display = 'none';
                }
            });

            if (!hasVisibleRow) {
                noResults.classList.remove('d-none');
                table.classList.add('d-none');
            } else {
                noResults.classList.add('d-none');
                table.classList.remove('d-none');
            }
        });
    }
});
</script>