<?php
declare(strict_types=1);
requireAuth();
$userId = getCurrentUserId();
$eventId = (int)($_GET['id'] ?? 0);
$registration = getRegistrationByUserAndEvent($userId, $eventId);
if (!$registration) {
    setFlashMessage('error', 'ไม่พบการลงทะเบียน');
    header('Location: /my-registrations');
    exit;
}
if ($registration['checked_in']) {
    setFlashMessage('error', 'ไม่สามารถยกเลิกการลงทะเบียนได้เนื่องจากคุณได้เช็คชื่อเข้างานแล้ว');
    header('Location: /my-registrations');
    exit;
}
if (deleteRegistration($registration['id'])) {
    setFlashMessage('success', 'ยกเลิกการลงทะเบียนสำเร็จ');
} else {
    setFlashMessage('error', 'เกิดข้อผิดพลาด กรุณาลองใหม่');
}
header('Location: /my-registrations');
exit;
