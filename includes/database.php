<?php
$hostname = 'mysql';
$dbName = 'InviGo';
$username = 'InviGo';
$password = 'InviGo';
$conn = new mysqli($hostname, $username, $password, $dbName);
function getConnection(): mysqli
{
    global $conn;
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}
require_once DATABASES_DIR . '/user.php';
require_once DATABASES_DIR . '/event.php';
require_once DATABASES_DIR . '/event_image.php';
require_once DATABASES_DIR . '/registration.php';
require_once DATABASES_DIR . '/otp.php';
require_once DATABASES_DIR . '/stats.php';
