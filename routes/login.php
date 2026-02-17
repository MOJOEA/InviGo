<?php
declare(strict_types=1);
requireGuest();
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if (empty($email)) {
        $errors['email'] = 'กรุณากรอกอีเมล';
    } elseif (!isValidEmail($email)) {
        $errors['email'] = 'รูปแบบอีเมลไม่ถูกต้อง';
    }
    if (empty($password)) {
        $errors['password'] = 'กรุณากรอกรหัสผ่าน';
    }
    if (empty($errors)) {
        $user = getUserByEmail($email);
        if ($user && verifyPassword($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            setFlashMessage('success', 'เข้าสู่ระบบสำเร็จ');
            header('Location: /explore');
            exit;
        } else {
            $errors['general'] = 'อีเมลหรือรหัสผ่านไม่ถูกต้อง';
        }
    }
}
renderView('login', ['errors' => $errors]);
