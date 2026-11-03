<?php
require_once "../../app/utils/session.php";
checkAdminLogin();
require_once "../../app/models/Profil.php";
require_once "../../app/utils/log.php";

$p = Profil::find($_GET['id']);
Profil::delete($_GET['id']);
logActivity("Menghapus konten profil: {$p['kategori']}");

header("Location: manage.php");
exit;
