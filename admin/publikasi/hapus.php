<?php
require_once "../../app/utils/session.php";
checkAdminLogin();
require_once "../../app/models/Publikasi.php";
require_once "../../app/utils/log.php";

$id = $_GET['id'];
$p = Publikasi::find($id);
if ($p && $p['file'] && file_exists("../../public/uploads/publikasi/" . $p['file'])) {
    unlink("../../public/uploads/publikasi/" . $p['file']);
}
Publikasi::delete($id);
logActivity("Menghapus publikasi: {$p['judul']}");
header("Location: manage.php");
exit;
