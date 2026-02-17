<?php
declare(strict_types=1);
requireAuth();
$userId = getCurrentUserId();
$eventId = (int)($_GET['id'] ?? 0);
$registration = getRegistrationByUserAndEvent($userId, $eventId);
if (!$registration || $registration['status'] !== 'approved') {
    setFlashMessage('error', 'ไม่พบการลงทะเบียนหรือยังไม่ได้รับการอนุมัติ');
    header('Location: /my-registrations');
    exit;
}
if ($registration['checked_in']) {
    setFlashMessage('info', 'คุณได้เช็คชื่อเข้างานแล้ว');
    header('Location: /my-registrations');
    exit;
}
$event = getEventById($eventId);
if (!$event) {
    setFlashMessage('error', 'ไม่พบกิจกรรม');
    header('Location: /my-registrations');
    exit;
}
invalidateOtps($registration['id']);
$otpCode = generateOTP(6);
$expiresAt = date('Y-m-d H:i:s', strtotime('+30 minutes'));
if (createOtp($registration['id'], $otpCode, $expiresAt)) {
    $title = 'รหัส OTP เข้างาน';
    $activePage = 'my-registrations';
    renderView('otp_display_content', [
        'otp_code' => $otpCode,
        'expires_at' => $expiresAt,
        'event' => array_merge($registration, $event),
        'activePage' => $activePage
    ]);
} else {
    setFlashMessage('error', 'เกิดข้อผิดพลาดในการสร้าง OTP');
    header('Location: /my-registrations');
    exit;
}
