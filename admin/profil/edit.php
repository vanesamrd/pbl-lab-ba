<?php
require_once "../../app/utils/session.php";
checkAdminLogin();
require_once "../../app/models/Profil.php";
require_once "../../app/utils/log.php";

$id = $_GET['id'];
$p = Profil::find($id);

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    Profil::update($id, $_POST['kategori'], $_POST['judul'], $_POST['isi']);
    logActivity("Mengedit konten profil: {$p['kategori']} → {$_POST['kategori']}");
    $p = Profil::find($id);
    $message = "<div class='alert alert-success'>Konten profil berhasil diupdate.</div>";
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
                    <h3 class="fw-bold text-primary-custom mb-3">Edit Konten Profil</h3>
                    <?= $message ?>
                    <form method="POST" autocomplete="off">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Kategori</label>
                            <input type="text" name="kategori" class="form-control rounded-pill" value="<?= htmlspecialchars($p['kategori']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Judul</label>
                            <input type="text" name="judul" class="form-control rounded-pill" value="<?= htmlspecialchars($p['judul']); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Isi Konten</label>
                            <textarea name="isi" class="form-control" rows="6" required><?= htmlspecialchars($p['isi']); ?></textarea>
                        </div>
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="manage.php" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>