<?php
session_start();
session_destroy();
header("Location: /lab-ba/public/login.php");
require_once "../app/utils/log.php";
logActivity("Logout dari sistem");
exit;
