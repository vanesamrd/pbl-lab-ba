<?php
require_once "../../app/utils/session.php";
checkAdminLogin();
require_once "../../app/models/Admin.php";
require_once "../../app/utils/log.php";

$id = $_GET['id'];
$a = Admin::find($id);

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    Admin::update($id, $_POST['username'], $_POST['nama']);
    if (!empty($_POST['password'])) {
        Admin::updatePassword($id, $_POST['password']);
        logActivity("Mengubah password admin: {$a['username']}");
    }
    logActivity("Mengubah data admin: {$a['username']}");
    $a = Admin::find($id);
    $message = "<div class='alert alert-success'>Data admin berhasil diupdate.</div>";
    // header("Location: manage.php");
    // exit;
}

include "../../views/layouts/header.php";
?>
<section class="py-4 min-vh-100 bg-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-9">
                <div class="card shadow-lg border-0 rounded-4 p-4 p-md-5">
                    <h3 class="fw-bold text-primary-custom mb-3">Edit Admin</h3>
                    <?= $message ?>
                    <form method="POST" autocomplete="off">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Lengkap</label>
                            <input type="text" name="nama" value="<?= htmlspecialchars($a['nama_lengkap']); ?>" class="form-control rounded-pill" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Username</label>
                            <input type="text" name="username" value="<?= htmlspecialchars($a['username']); ?>" class="form-control rounded-pill" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Ubah Password (opsional)</label>
                            <input type="password" name="password" class="form-control rounded-pill">
                        </div>
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="javascript:history.back()" class="btn btn-outline-danger rounded-pill">Kembali</a>
                            <button type="submit" class="btn rounded-pill btn-success">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>