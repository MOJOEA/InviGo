<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (checkLogin($email, $password)) {
        $result = getStudentByEmail($email);
        $student = $result->fetch_assoc();
        $_SESSION['student_id'] = $student['student_id'];
        $_SESSION['timestamp'] = time();
        header('Location: /');
        exit;
    } else {
        renderView('login', ['error' => 'อีเมลหรือรหัสผ่านไม่ถูกต้อง']);
    }
} else {
    renderView('login');
}
?>
