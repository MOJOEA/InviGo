<?php
declare(strict_types=1);
requireAdmin();

$conn = getConnection();
$errors = [];
$success = '';

// Handle delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $userId = (int)$_POST['user_id'];
    // Prevent deleting self
    if ($userId === getCurrentUserId()) {
        $errors[] = 'ไม่สามารถลบตัวเองได้';
    } else {
        $stmt = $conn->prepare("DELETE FROM Users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        if ($stmt->execute()) {
            $success = 'ลบผู้ใช้สำเร็จ';
        } else {
            $errors[] = 'เกิดข้อผิดพลาดในการลบผู้ใช้';
        }
    }
}

// Get all users
$result = $conn->query("SELECT id, name, email, birth_date, gender, role, created_at FROM Users ORDER BY created_at DESC");
$users = $result->fetch_all(MYSQLI_ASSOC);

renderView('admin/users', [
    'users' => $users,
    'errors' => $errors,
    'success' => $success,
    'activePage' => 'admin'
]);
