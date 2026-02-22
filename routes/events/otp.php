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
$event = getEventById($eventId);

if (OTP_MODE === 'stateless') {
    $otpData = generateStatelessOtp($registration['id'], $eventId);
    $_SESSION['otp_data'] = [
        'code' => $otpData['code'],
        'expires' => $otpData['expires_at'],
        'event_title' => $event['title'],
        'event_date' => $event['event_date'],
        'location' => $event['location'],
        'organizer_name' => $event['organizer_name']
    ];
    header('Location: /my-registrations?show_otp=1');
    exit;
} else {
    $otpCode = generateOTP(6);
    $expiresAt = date('Y-m-d H:i:s', strtotime('+30 minutes'));
    if (createOtp($registration['id'], $otpCode, $expiresAt)) {
        $_SESSION['otp_data'] = [
            'code' => $otpCode,
            'expires' => $expiresAt,
            'event_title' => $event['title'],
            'event_date' => $event['event_date'],
            'location' => $event['location'],
            'organizer_name' => $event['organizer_name']
        ];
        header('Location: /my-registrations?show_otp=1');
        exit;
    } else {
        setFlashMessage('error', 'เกิดข้อผิดพลาดในการสร้าง OTP');
        header('Location: /my-registrations');
        exit;
    }
}
