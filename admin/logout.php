<?php
require_once '../includes/auth.php';
session_destroy();
$base = getBaseUrl();
header("Location: $base/index.php");
exit();
