<?php
require_once "../../app/utils/session.php";
checkAdminLogin();
require_once "../../app/models/Admin.php";
require_once "../../app/utils/log.php";

$data = Admin::all();
include "../../views/layouts/header.php";
?>

<section class="py-5 min-vh-100 bg-light">
    <div class="container">

        <!-- Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
                <?= $_SESSION['success'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
                <?= $_SESSION['error'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- Header -->
        <div class="d-flex flex-wrap align-items-center justify-content-between mb-4 gap-2">
            <h3 class="fw-bold text-primary-custom mb-0">Manajemen Admin</h3>

            <a href="tambah.php" class="btn btn-accent rounded-pill fw-semibold px-4 py-2 shadow-sm">
                <i class="bi bi-person-plus me-1"></i> Tambah Admin
            </a>
        </div>

        <!-- Card -->
        <div class="card shadow border-0 rounded-4 overflow-hidden">
            <div class="card-body p-4">

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="custom-thead">
                            <tr>
                                <th class="py-3">Nama</th>
                                <th class="py-3">Username</th>
                                <th class="py-3 text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($data as $a): ?>
                                <tr class="table-row-hover">
                                    <td><?= htmlspecialchars($a['nama_lengkap']) ?></td>
                                    <td class="text-muted"><?= htmlspecialchars($a['username']) ?></td>
                                    <td class="text-center">
                                        <a href="edit.php?id=<?= $a['id']; ?>" 
                                           class="btn btn-warning btn-sm rounded-pill px-3 shadow-sm">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </a>

                                        <?php if ($a['id'] != $_SESSION['admin_id']): ?>
                                            <form action="hapus.php" method="POST" style="display: inline;">
                                            <input type="hidden" name="id" value="<?= $a['id']; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm rounded-pill px-3 shadow-sm ms-1" 
                                                    onclick="return confirm('Hapus admin ini?')">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>

                    </table>
                </div>

                <div class="mt-4">
                    <a href="/lab-ba/admin/dashboard.php" class="btn btn-outline-danger rounded-pill px-4">
                        Kembali ke Dashboard
                    </a>
                </div>

            </div>
        </div>

    </div>
</section>

<style>
    .text-primary-custom {
        color: #0A2A43 !important;
    }

    .btn-accent {
        background: linear-gradient(135deg, #3FA2F7, #0A7BD8);
        color: #fff !important;
        border: none;
        transition: 0.3s ease;
    }

    .btn-accent:hover {
        opacity: .9;
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(63, 162, 247, 0.4);
    }

    .custom-thead {
        background: linear-gradient(135deg, #0A7BD8, #3FA2F7);
        color: #fff;
        border: none;
    }

    .table-row-hover:hover {
        background: #f3faff;
        transition: .25s ease;
    }

    .card {
        border-radius: 18px !important;
    }
</style>
