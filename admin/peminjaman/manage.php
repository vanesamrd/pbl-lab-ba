<?php
require_once "../../app/utils/session.php";
checkAdminLogin();
require_once "../../app/models/Peminjaman.php";
$data = Peminjaman::all();
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

    /* Status Badges */
    .badge-status {
        font-size: 0.75rem;
        padding: 0.4em 0.8em;
        border-radius: 50px;
        font-weight: 600;
    }
    .status-menunggu { background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
    .status-disetujui { background-color: #d1e7dd; color: #0f5132; border: 1px solid #badbcc; }
    .status-ditolak { background-color: #f8d7da; color: #842029; border: 1px solid #f5c2c7; }

    /* Buttons - Updated for Bold Colors */
    .btn-action-text {
        font-size: 0.8rem;
        font-weight: 600;
        border-radius: 50px;
        padding: 0.5rem 1.2rem;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        border: none;
        text-decoration: none;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .btn-accept {
        background-color: #198754; /* Solid Green */
        color: #ffffff;
    }
    .btn-accept:hover {
        background-color: #146c43;
        color: #ffffff;
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(25, 135, 84, 0.3);
    }

    .btn-decline {
        background-color: #dc3545; /* Solid Red */
        color: #ffffff;
    }
    .btn-decline:hover {
        background-color: #b02a37;
        color: #ffffff;
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(220, 53, 69, 0.3);
    }

    .btn-remove {
        background-color: #343a40; /* Solid Dark Grey */
        color: #ffffff;
    }
    .btn-remove:hover {
        background-color: #23272b;
        color: #ffffff;
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(52, 58, 64, 0.3);
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
                        <li class="breadcrumb-item active text-primary-custom" aria-current="page">Manajemen Peminjaman</li>
                    </ol>
                </nav>
                <h3 class="fw-bold text-primary-custom mb-1">Manajemen Peminjaman</h3>
                <p class="text-muted small mb-0">Kelola pengajuan peminjaman laboratorium.</p>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="section-container">
            <!-- Toolbar: Search -->
            <div class="row mb-4">
                <div class="col-md-5 col-lg-4">
                    <div class="position-relative">
                        <i class="bi bi-search search-icon"></i>
                        <input type="text" id="searchInput" class="form-control search-input" placeholder="Cari peminjam, NIP, atau keperluan...">
                    </div>
                </div>
            </div>

            <?php if (empty($data)): ?>
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-inbox text-muted opacity-25" style="font-size: 4rem;"></i>
                    </div>
                    <h5 class="text-muted fw-bold">Belum ada pengajuan</h5>
                    <p class="text-muted small">Data peminjaman akan muncul di sini.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-custom table-hover align-middle mb-0" id="loanTable">
                        <thead>
                            <tr>
                                <th width="20%">Peminjam</th>
                                <th width="15%">Waktu</th>
                                <th width="20%">Keperluan</th>
                                <th width="10%">Status</th>
                                <th width="35%" class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data as $p): ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark search-target"><?= htmlspecialchars($p['nama_peminjam']) ?></div>
                                        <div class="small text-muted font-monospace search-target"><?= htmlspecialchars($p['nip']) ?></div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column small">
                                            <span class="fw-semibold text-primary-custom">
                                                <?= date('d M Y', strtotime($p['tanggal_mulai'])) ?>
                                            </span>
                                            <span class="text-muted">
                                                <?= htmlspecialchars(substr($p['waktu_mulai'], 0, 5)) ?> - <?= htmlspecialchars(substr($p['waktu_selesai'], 0, 5)) ?>
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-wrap search-target" style="min-width: 150px;">
                                            <?= htmlspecialchars($p['keperluan']) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php
                                        $statusRaw = strtolower($p['status']);
                                        $badgeClass = 'status-menunggu';
                                        $icon = 'bi-hourglass-split';
                                        $label = 'Menunggu';

                                        if (in_array($statusRaw, ['disetujui', 'approved'])) {
                                            $badgeClass = 'status-disetujui';
                                            $icon = 'bi-check-circle-fill';
                                            $label = 'Disetujui';
                                        } elseif (in_array($statusRaw, ['ditolak', 'rejected'])) {
                                            $badgeClass = 'status-ditolak';
                                            $icon = 'bi-x-circle-fill';
                                            $label = 'Ditolak';
                                        }
                                        ?>
                                        <span class="badge badge-status <?= $badgeClass ?>">
                                            <i class="bi <?= $icon ?> me-1"></i><?= $label ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <?php if ($statusRaw === 'menunggu' || $statusRaw === 'pending'): ?>
                                                <a href="set.php?id=<?= $p['id']; ?>&status=disetujui" class="btn-action-text btn-accept" onclick="return confirm('Setujui peminjaman ini?')">
                                                    <i class="bi bi-check-lg"></i> Terima
                                                </a>
                                                <a href="set.php?id=<?= $p['id']; ?>&status=ditolak" class="btn-action-text btn-decline" onclick="return confirm('Tolak peminjaman ini?')">
                                                    <i class="bi bi-x-lg"></i> Tolak
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted small fst-italic me-2 align-self-center border px-2 py-1 rounded bg-light">
                                                    <i class="bi bi-check2-all"></i> Selesai
                                                </span>
                                            <?php endif; ?>
                                            
                                            <a href="hapus.php?id=<?= $p['id']; ?>" class="btn-action-text btn-remove" onclick="return confirm('Hapus data peminjaman ini secara permanen?')" title="Hapus Data">
                                                <i class="bi bi-trash"></i> Hapus
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
    const table = document.getElementById('loanTable');
    const noResults = document.getElementById('noResults');

    if (searchInput && table) {
        searchInput.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            const bodyRows = table.querySelectorAll('tbody tr');
            let hasVisibleRow = false;
            
            bodyRows.forEach(row => {
                // Cari di dalam elemen yang memiliki class 'search-target'
                const targets = row.querySelectorAll('.search-target');
                let found = false;
                targets.forEach(target => {
                    if (target.innerText.toLowerCase().includes(filter)) {
                        found = true;
                    }
                });

                if (found) {
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