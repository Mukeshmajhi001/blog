<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn() {
    return isset($_SESSION['admin_id']);
}

function getBaseUrl() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host     = $_SERVER['HTTP_HOST'];
    $script   = str_replace('\\', '/', $_SERVER['SCRIPT_FILENAME']);
    $docroot  = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
    $base     = str_replace($docroot, '', $script);
    $parts    = explode('/', trim($base, '/'));
    $blogRoot = '';
    foreach ($parts as $part) {
        if (in_array($part, ['admin', 'mks75@2062'])) break;
        $blogRoot .= '/' . $part;
    }
    return $protocol . '://' . $host . $blogRoot;
}

function requireLogin() {
    if (!isLoggedIn()) {
        $base = getBaseUrl();
        header("Location: $base/mks75@2062/login.php");
        exit();
    }
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}
