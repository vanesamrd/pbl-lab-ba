<?php
require_once "../../app/utils/session.php";
checkAdminLogin();
require_once "../../app/models/Dosen.php";
$data = Dosen::all();
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

    /* Search Bar Styling */
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

    /* Avatar */
    .avatar-circle {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #fff;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .avatar-placeholder {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background-color: #e0f2fe;
        color: #0284c7;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.1rem;
        border: 2px solid #fff;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
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

    /* Empty State */
    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
    }
</style>

<section class="py-5 min-vh-100">
    <div class="container">

        <!-- Header Page -->
        <div class="row align-items-center mb-4 g-3">
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="/lab-ba/admin/dashboard.php" class="text-decoration-none text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item active text-primary-custom" aria-current="page">Manajemen Dosen</li>
                    </ol>
                </nav>
                <h3 class="fw-bold text-primary-custom mb-1">Manajemen Dosen</h3>
                <p class="text-muted small mb-0">Kelola data dosen.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="tambah.php" class="btn btn-accent rounded-pill">
                    <i class="bi bi-plus-lg me-2"></i>Tambah Dosen
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
                        <input type="text" id="searchInput" class="form-control search-input" placeholder="Cari nama atau keahlian...">
                    </div>
                </div>
            </div>

            <?php if (empty($data)): ?>
                <div class="empty-state">
                    <div class="mb-3">
                        <i class="bi bi-people text-muted opacity-25" style="font-size: 4rem;"></i>
                    </div>
                    <h5 class="text-muted fw-bold">Belum ada data dosen</h5>
                    <p class="text-muted small">Silakan tambahkan data dosen baru untuk memulai.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-custom table-hover align-middle mb-0" id="dosenTable">
                        <thead>
                            <tr>
                                <th width="50%">Nama Dosen</th>
                                <th width="30%">Bidang Keahlian</th>
                                <th width="20%" class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data as $d): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <?php if (!empty($d['foto'])): ?>
                                                    <img src="../../public/uploads/dosen/<?= htmlspecialchars($d['foto']) ?>" class="avatar-circle" alt="Foto">
                                                <?php else: ?>
                                                    <div class="avatar-placeholder">
                                                        <?= strtoupper(substr($d['nama'] ?? 'U', 0, 1)) ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark search-target"><?= htmlspecialchars($d['nama'] ?? '-') ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if (!empty($d['keahlian'])): ?>
                                            <span class="badge bg-light text-primary border border-primary-subtle fw-normal px-3 py-2 rounded-pill search-target">
                                                <?= htmlspecialchars($d['keahlian']) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted small fst-italic">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="edit.php?id=<?= $d['id']; ?>" class="btn btn-warning btn-sm rounded-pill px-3 fw-bold text-dark border-0 shadow-sm" title="Edit Data">
                                                <i class="bi bi-pencil-square me-1"></i> Edit
                                            </a>
                                            <a href="hapus.php?id=<?= $d['id']; ?>" class="btn btn-danger btn-sm rounded-pill px-3 fw-bold border-0 shadow-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data dosen ini? Tindakan ini tidak dapat dibatalkan.')" title="Hapus Data">
                                                <i class="bi bi-trash me-1"></i> Hapus
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <!-- Pesan jika pencarian tidak ditemukan -->
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
    </div>
</section

    <!-- Script Pencarian Cepat (Client Side) -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const table = document.getElementById('dosenTable');
        const noResults = document.getElementById('noResults');

        if (searchInput && table) {
            searchInput.addEventListener('keyup', function() {
                const filter = this.value.toLowerCase();
                const rows = table.getElementsByTagName('tr');
                let hasVisibleRow = false;

                // Loop semua baris tbody (mulai index 1 karena index 0 adalah thead di struktur HTML collection tr table)
                // Tapi karena getElementsByTagName('tr') mengambil head juga, kita perlu hati-hati.
                // Lebih aman querySelectorAll di tbody.

                const bodyRows = table.querySelectorAll('tbody tr');

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
                    table.classList.add('d-none'); // Sembunyikan header tabel jika kosong
                } else {
                    noResults.classList.add('d-none');
                    table.classList.remove('d-none');
                }
            });
        }
    });
</script>