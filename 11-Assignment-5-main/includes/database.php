<?php

$hostname = 'db';
$dbName = 'course_registration';
$username = 'reg_user';
$password = 'reg_pass';
$conn = new mysqli($hostname, $username, $password, $dbName);

function getConnection(): mysqli
{
    global $conn;
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Load database functions
require_once DATABASES_DIR . '/students.php';
require_once DATABASES_DIR . '/courses.php';
require_once DATABASES_DIR . '/enrollment.php';
?>
