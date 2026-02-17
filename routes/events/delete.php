<?php
declare(strict_types=1);
requireAuth();
$userId = getCurrentUserId();
$eventId = (int)($_GET['id'] ?? 0);
$event = getEventByIdAndOwner($eventId, $userId);
if (!$event) {
    setFlashMessage('error', 'ไม่พบกิจกรรมหรือคุณไม่มีสิทธิ์ลบ');
    header('Location: /my-events');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (deleteEvent($eventId, $userId)) {
        setFlashMessage('success', 'ลบกิจกรรมสำเร็จ');
    } else {
        setFlashMessage('error', 'เกิดข้อผิดพลาดในการลบกิจกรรม');
    }
    header('Location: /my-events');
    exit;
}
header('Location: /my-events');
exit;
