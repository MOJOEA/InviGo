<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/../includes/config.php';
require_once INCLUDES_DIR . '/database.php';
require_once INCLUDES_DIR . '/router.php';
require_once INCLUDES_DIR . '/view.php';
require_once INCLUDES_DIR . '/helpers/auth.php';
require_once INCLUDES_DIR . '/helpers/date.php';
require_once INCLUDES_DIR . '/helpers/sanitize.php';
require_once INCLUDES_DIR . '/helpers/password.php';
require_once INCLUDES_DIR . '/helpers/otp.php';
require_once INCLUDES_DIR . '/helpers/flash.php';
require_once INCLUDES_DIR . '/helpers/format.php';
require_once INCLUDES_DIR . '/utils/upload.php';

$requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestPath = strtolower(trim($requestPath, '/'));
$requestPath = $requestPath === '' ? '/' : '/' . $requestPath;
if (in_array($requestPath, PUBLIC_ROUTES)) {
    dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
    exit;
}
if (!isLoggedIn()) {
    header('Location: /login');
    exit;
}
if (isset($_SESSION['timestamp']) && (time() - $_SESSION['timestamp'] > 1800)) {
    session_destroy();
    header('Location: /login');
    exit;
}
$_SESSION['timestamp'] = time();
dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);