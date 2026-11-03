<?php
require_once "../../app/utils/session.php";
checkAdminLogin();
require_once "../../app/models/Dosen.php";
require_once "../../app/utils/log.php";

$id = $_GET['id'];
$d = Dosen::find($id);
unlink("../../public/uploads/dosen/" . $d['foto']);

Dosen::delete($id);
logActivity("Menghapus dosen: {$d['nama']}");

header("Location: manage.php");
exit;
