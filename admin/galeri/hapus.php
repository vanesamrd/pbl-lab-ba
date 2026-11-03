<?php
require_once "../../app/utils/session.php";
checkAdminLogin();
require_once "../../app/models/Galeri.php";
require_once "../../app/utils/log.php";

$g = Galeri::find($_GET['id']);
unlink("../../public/uploads/galeri/" . $g['gambar']);

Galeri::delete($_GET['id']);
logActivity("Menghapus foto galeri: {$g['judul']}");

header("Location: manage.php");
exit;
