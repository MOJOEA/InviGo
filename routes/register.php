<?php
declare(strict_types=1);
requireGuest();
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $birthDate = $_POST['birth_date'] ?? null;
    $gender = $_POST['gender'] ?? null;
    if (empty($name)) {
        $errors['name'] = 'กรุณากรอกชื่อ';
    } elseif (strlen($name) < 2) {
        $errors['name'] = 'ชื่อต้องมีความยาวอย่างน้อย 2 ตัวอักษร';
    }
    if (empty($email)) {
        $errors['email'] = 'กรุณากรอกอีเมล';
    } elseif (!isValidEmail($email)) {
        $errors['email'] = 'รูปแบบอีเมลไม่ถูกต้อง';
    }
    if (empty($password)) {
        $errors['password'] = 'กรุณากรอกรหัสผ่าน';
    } elseif (strlen($password) < 6) {
        $errors['password'] = 'รหัสผ่านต้องมีความยาวอย่างน้อย 6 ตัวอักษร';
    }
    if ($password !== $confirmPassword) {
        $errors['confirm_password'] = 'รหัสผ่านไม่ตรงกัน';
    }
    if (empty($birthDate)) {
        $errors['birth_date'] = 'กรุณาเลือกวันเกิด';
    } else {
        $birth = new DateTime($birthDate);
        $today = new DateTime();
        $age = $today->diff($birth)->y;
        if ($birth > $today) {
            $errors['birth_date'] = 'วันเกิดต้องไม่เป็นวันในอนาคต';
        } elseif ($age < 10) {
            $errors['birth_date'] = 'อายุต้องไม่ต่ำกว่า 10 ปี';
        } elseif ($age > 120) {
            $errors['birth_date'] = 'อายุต้องไม่เกิน 120 ปี';
        }
    }
    if (empty($gender)) {
        $errors['gender'] = 'กรุณาเลือกเพศ';
    }
    if (empty($errors)) {
        if (emailExists($email)) {
            $errors['email'] = 'อีเมลนี้มีผู้ใช้งานแล้ว';
        }
    }
    if (empty($errors)) {
        $userId = createUser([
            'name' => $name,
            'email' => $email,
            'password' => hashPassword($password),
            'birth_date' => $birthDate,
            'gender' => $gender
        ]);
        if ($userId) {
            setFlashMessage('success', 'สมัครสมาชิกสำเร็จ กรุณาเข้าสู่ระบบ');
            header('Location: /login');
            exit;
        } else {
            $errors['general'] = 'เกิดข้อผิดพลาด กรุณาลองใหม่';
        }
    }
}
renderView('register', ['errors' => $errors]);
