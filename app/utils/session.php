<?php
function checkAdminLogin()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['admin_id'])) {
        header("Location: /lab-ba/public/login.php");
        exit;
    }
}
