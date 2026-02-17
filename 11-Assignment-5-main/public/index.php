<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Define constants
define('ROOT_DIR', dirname(__DIR__));
define('INCLUDES_DIR', ROOT_DIR . '/includes');
define('ROUTES_DIR', ROOT_DIR . '/routes');
define('TEMPLATES_DIR', ROOT_DIR . '/templates');
define('DATABASES_DIR', ROOT_DIR . '/databases');

// Include required files
require_once INCLUDES_DIR . '/database.php';
require_once INCLUDES_DIR . '/router.php';
require_once INCLUDES_DIR . '/view.php';

// Public routes that don't require login
const PUBLIC_ROUTES = ['/login'];

if (in_array(strtolower($_SERVER['REQUEST_URI']), PUBLIC_ROUTES)) {
    dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
    exit;
} elseif (isset($_SESSION['student_id']) && isset($_SESSION['timestamp']) && time() - $_SESSION['timestamp'] < 3600) { // 1 hour session
    $_SESSION['timestamp'] = time();
    dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
} else {
    unset($_SESSION['student_id']);
    unset($_SESSION['timestamp']);
    header('Location: /login');
    exit;
}
?>
