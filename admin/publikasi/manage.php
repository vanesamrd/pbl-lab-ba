<?php
require_once "../../app/utils/session.php";
checkAdminLogin();
require_once "../../app/models/Publikasi.php";
require_once "../../app/models/Dosen.php";
require_once "../../app/models/Kategori.php";
require_once "../../app/utils/log.php";

// Ambil data (limit 100 untuk admin)
$publikasi = Publikasi::all(100, 0);
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
        padding: 2rem;
        position: relative;
        overflow: hidden;
    }

    /* Typography */
    .text-primary-custom { color: var(--primary-custom) !important; }

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

    /* Badges */
    .badge-dosen {
        background-color: #e0f2fe;
        color: #0284c7;
        font-weight: 600;
        border: 1px solid #bae6fd;
        font-size: 0.75rem;
    }

    .badge-kategori {
        background-color: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
        font-size: 0.75rem;
    }

    /* Link Icons */
    .action-link {
        text-decoration: none;
        font-weight: 600;
        font-size: 0.85rem;
        padding: 5px 10px;
        border-radius: 6px;
        transition: background 0.2s;
    }
    .link-pdf { color: #dc3545; background: #fff0f1; }
    .link-pdf:hover { background: #ffe0e3; }
    
    .link-url { color: #0d6efd; background: #f0f7ff; }
    .link-url:hover { background: #e0efff; }
</style>

<section class="py-5 min-vh-100">
    <div class="container">
        <!-- Header Page -->
        <div class="row align-items-center mb-4 g-3">
            <div class="col-md-6">
            <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="/lab-ba/admin/dashboard.php" class="text-decoration-none text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item active text-primary-custom" aria-current="page">Manajemen Publikasi</li>
                    </ol>
                </nav>
                <h3 class="fw-bold text-primary-custom mb-1">Manajemen Publikasi</h3>
                <p class="text-muted small mb-0">Kelola data riset, jurnal, dan publikasi ilmiah.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="tambah.php" class="btn btn-accent rounded-pill">
                    <i class="bi bi-plus-lg me-2"></i>Tambah Publikasi
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
                        <input type="text" id="searchInput" class="form-control search-input" placeholder="Cari judul publikasi...">
                    </div>
                </div>
            </div>

            <?php if (empty($publikasi)): ?>
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-journal-text text-muted opacity-25" style="font-size: 4rem;"></i>
                    </div>
                    <h5 class="text-muted fw-bold">Belum ada publikasi</h5>
                    <p class="text-muted small">Silakan tambahkan data publikasi baru.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-custom table-hover align-middle mb-0" id="pubTable">
                        <thead>
                            <tr>
                                <th width="30%">Judul Publikasi</th>
                                <th width="20%">Penulis (Dosen)</th>
                                <th width="15%">Kategori</th>
                                <th width="10%">Tanggal</th>
                                <th width="10%">File/Link</th>
                                <th width="15%" class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($publikasi as $p): ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark search-target"><?= htmlspecialchars($p['judul']) ?></div>
                                    </td>
                                    <td>
                                        <?php $dosen = Publikasi::getDosen($p['id']); ?>
                                        <div class="d-flex flex-wrap gap-1">
                                            <?php foreach ($dosen as $ds): ?>
                                                <span class="badge badge-dosen rounded-pill"><?= htmlspecialchars($ds['nama']) ?></span>
                                            <?php endforeach; ?>
                                            <?php if (empty($dosen)): ?>
                                                <span class="text-muted small fst-italic">-</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php $kategori = Publikasi::getKategori($p['id']); ?>
                                        <div class="d-flex flex-wrap gap-1">
                                            <?php if ($kategori): ?>
                                                <?php foreach ($kategori as $kat): ?>
                                                    <span class="badge badge-kategori rounded-1"><?= htmlspecialchars($kat['nama']) ?></span>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <span class="text-muted small">-</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-secondary small fw-semibold">
                                            <?= date('d/m/Y', strtotime($p['tanggal'])) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <?php if ($p['file']): ?>
                                                <a href="/lab-ba/public/uploads/publikasi/<?= htmlspecialchars($p['file']) ?>" target="_blank" class="action-link link-pdf" title="Lihat PDF">
                                                    <i class="bi bi-file-earmark-pdf"></i> PDF
                                                </a>
                                            <?php endif; ?>
                                            
                                            <?php if ($p['link']): ?>
                                                <a href="<?= htmlspecialchars($p['link']) ?>" target="_blank" class="action-link link-url" title="Buka Link">
                                                    <i class="bi bi-link-45deg"></i> Url
                                                </a>
                                            <?php endif; ?>
                                            
                                            <?php if (!$p['file'] && !$p['link']): ?>
                                                <span class="text-muted small">-</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="edit.php?id=<?= $p['id']; ?>" class="btn btn-warning btn-sm rounded-pill px-3 fw-bold text-dark border-0 shadow-sm" title="Edit Data">
                                                <i class="bi bi-pencil-square me-1"></i> Edit
                                            </a>
                                            <a href="hapus.php?id=<?= $p['id']; ?>" class="btn btn-danger btn-sm rounded-pill px-3 fw-bold border-0 shadow-sm" onclick="return confirm('Hapus publikasi ini? Tindakan ini tidak dapat dibatalkan.')" title="Hapus Data">
                                                <i class="bi bi-trash me-1"></i> Hapus
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    
                    <!-- No Results -->
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
            <a href="javascript:history.back()" class="btn btn-outline-danger rounded-pill px-4">
            Kembali
            </a>
        </div>
    </div>
</section>

<!-- Script Pencarian Cepat -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const table = document.getElementById('pubTable');
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