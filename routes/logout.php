<?php
declare(strict_types=1);
requireAuth();
$_SESSION = [];
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', [
        'expires' => time() - 3600,
        'path' => '/',
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
}
session_destroy();
header('Location: /login');
exit;
