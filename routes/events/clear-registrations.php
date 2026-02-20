<?php
declare(strict_types=1);
requireAuth();
$userId = getCurrentUserId();
$eventId = (int)($_GET['id'] ?? 0);
$event = getEventByIdAndOwner($eventId, $userId);
if (!$event) {
    setFlashMessage('error', 'ไม่พบกิจกรรมหรือคุณไม่มีสิทธิ์จัดการ');
    header('Location: /my-events');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (clearEventRegistrations($eventId)) {
        setFlashMessage('success', 'เคลียร์การลงทะเบียนสำเร็จ');
    } else {
        setFlashMessage('error', 'เกิดข้อผิดพลาดในการเคลียร์การลงทะเบียน');
    }
    header("Location: /events/$eventId/manage");
    exit;
}
header("Location: /events/$eventId/manage");
exit;
