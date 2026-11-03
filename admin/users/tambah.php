<?php
require_once "../../app/utils/session.php";
checkAdminLogin();
require_once "../../app/models/Admin.php";
require_once "../../app/utils/log.php";

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    Admin::create($_POST['username'], $_POST['password'], $_POST['nama']);
    logActivity("Menambah admin baru: {$_POST['username']}");
    header("Location: manage.php");
    exit;
}

include "../../views/layouts/header.php";
?>
<section class="py-4 min-vh-100 bg-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-9">
                <div class="card shadow-lg border-0 rounded-4 p-4 p-md-5">
                    <h3 class="fw-bold text-primary-custom mb-3">Tambah Admin</h3>
                    <?= $message ?>
                    <form method="POST" autocomplete="off">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control rounded-pill" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Username</label>
                            <input type="text" name="username" class="form-control rounded-pill" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password</label>
                            <input type="password" name="password" class="form-control rounded-pill" required>
                        </div>
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="manage.php" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-success">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>