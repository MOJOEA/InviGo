<?php
declare(strict_types=1);
requireAuth();
$userId = getCurrentUserId();
$user = getUserById($userId);
if (!$user) {
    setFlashMessage('error', 'ไม่พบข้อมูลผู้ใช้');
    header('Location: /profile');
    exit;
}
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $birthDate = $_POST['birth_date'] ?? null;
    $gender = $_POST['gender'] ?? null;
    $profileImage = $user['profile_image'] ?? '';
    
    if (!empty($_FILES['profile_image']['tmp_name'])) {
        $uploadedImage = uploadProfileImage($_FILES['profile_image']);
        if ($uploadedImage) {
            $profileImage = $uploadedImage;
        }
    }
    
    if (empty($name)) {
        $errors['name'] = 'กรุณากรอกชื่อ';
    }
    
    if (empty($errors)) {
        $data = [
            'name' => $name,
            'birth_date' => $birthDate,
            'gender' => $gender,
            'profile_image' => $profileImage
        ];
        
        if (!empty($_POST['password'])) {
            $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }
        
        if (updateUser($userId, $data)) {
            setFlashMessage('success', 'แก้ไขโปรไฟล์สำเร็จ');
            header('Location: /profile');
            exit;
        } else {
            $errors['general'] = 'เกิดข้อผิดพลาด กรุณาลองใหม่';
        }
    }
}
$title = 'แก้ไขโปรไฟล์';
$activePage = 'profile';
renderView('profile_edit_content', [
    'user' => $user,
    'errors' => $errors
]);
