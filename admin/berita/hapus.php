<?php
require_once "../../app/utils/session.php";
checkAdminLogin();
require_once "../../app/models/Berita.php";
require_once "../../app/utils/log.php";
$id = $_GET['id'];
$b = Berita::find($id);
if ($b && $b['gambar'] && file_exists("../../public/uploads/berita/" . $b['gambar'])) {
    unlink("../../public/uploads/berita/" . $b['gambar']);
}
Berita::delete($id);
logActivity("Menghapus berita: {$b['judul']}");
header("Location: manage.php");
exit;
