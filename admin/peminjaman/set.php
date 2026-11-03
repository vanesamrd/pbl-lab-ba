<?php
require_once "../../app/models/Peminjaman.php";
require_once "../../app/utils/session.php";
require_once "../../app/utils/log.php";

checkAdminLogin();

Peminjaman::setStatus($_GET['id'], $_GET['status'], $_SESSION['admin_id']);
logActivity("Mengubah status peminjaman menjadi: {$_GET['status']}");

header("Location: manage.php");
exit;
