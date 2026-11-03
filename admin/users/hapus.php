<?php
require_once "../../app/utils/session.php";
checkAdminLogin();
require_once "../../app/models/Admin.php";
require_once "../../app/utils/log.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $idToDelete = $_POST['id'];
    
    if ($idToDelete == $_SESSION['admin_id']) {
        // Tidak boleh hapus diri sendiri
        $_SESSION['error'] = "Anda tidak dapat menghapus akun admin yang sedang login.";
        header("Location: manage.php");
        exit;
    }
    
    $a = Admin::find($idToDelete);
    if ($a) {
        Admin::delete($idToDelete);
        logActivity("Menghapus admin: {$a['username']}");
        $_SESSION['success'] = "Admin berhasil dihapus.";
    }
} else {
    $_SESSION['error'] = "Permintaan tidak valid.";
}

header("Location: manage.php");
exit;
