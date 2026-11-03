<?php
require_once "../../app/utils/session.php";
checkAdminLogin();
require_once "../../app/models/Kategori.php";
require_once "../../app/utils/log.php";

$id = $_GET['id'];
Kategori::delete($id);
logActivity("Menghapus kategori: $id");
header("Location: manage.php");
exit;
