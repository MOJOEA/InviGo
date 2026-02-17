<?php
declare(strict_types=1);
requireAuth();
$userId = getCurrentUserId();
$eventId = (int)($_GET['id'] ?? 0);
$event = getEventById($eventId);
if (!$event) {
    setFlashMessage('error', 'ไม่พบกิจกรรม');
    header('Location: /explore');
    exit;
}
if (isUserRegistered($userId, $eventId)) {
    setFlashMessage('error', 'คุณได้ลงทะเบียนกิจกรรมนี้แล้ว');
    header('Location: /explore');
    exit;
}
if (isEventFull($eventId)) {
    setFlashMessage('error', 'กิจกรรมนี้เต็มแล้ว');
    header('Location: /explore');
    exit;
}
$endDateStr = $event['end_date'] ?? $event['event_date'];
$eventDate = new DateTime($endDateStr);
$now = new DateTime();
if ($eventDate < $now) {
    setFlashMessage('error', 'กิจกรรมนี้สิ้นสุดแล้ว');
    header('Location: /explore');
    exit;
}
if ($event['user_id'] == $userId) {
    setFlashMessage('error', 'ไม่สามารถลงทะเบียนกิจกรรมของตัวเองได้');
    header('Location: /explore');
    exit;
}
$registrationId = createRegistration($userId, $eventId);
if ($registrationId) {
    setFlashMessage('success', 'ลงทะเบียนสำเร็จ กรุณารอการอนุมัติจากผู้จัดงาน');
} else {
    setFlashMessage('error', 'เกิดข้อผิดพลาด กรุณาลองใหม่');
}
header('Location: /explore');
exit;
