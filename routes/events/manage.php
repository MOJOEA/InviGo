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
$stats = getEventRegistrationStats($eventId);
$genderStats = getEventGenderStats($eventId);
$ageStats = getEventAgeStats($eventId);
$registrations = getRegistrationsByEvent($eventId);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['otp_code'])) {
    $otpCode = trim($_POST['otp_code'] ?? '');
    if (strlen($otpCode) === 6) {
        $otpData = verifyOtp($otpCode, $eventId);
        if ($otpData) {
            markOtpUsed($otpData['id']);
            checkInRegistration($otpData['reg_id']);
            setFlashMessage('success', 'เช็คชื่อสำเร็จ');
        } else {
            setFlashMessage('error', 'รหัส OTP ไม่ถูกต้องหรือหมดอายุ');
        }
    } else {
        setFlashMessage('error', 'รหัส OTP ต้องมี 6 หลัก');
    }
    header("Location: /events/$eventId/manage");
    exit;
}
if (isset($_GET['action']) && isset($_GET['registration_id'])) {
    $registrationId = (int)$_GET['registration_id'];
    $action = $_GET['action'];
    if (in_array($action, ['approve', 'reject'])) {
        $status = $action === 'approve' ? 'approved' : 'rejected';
        if (updateRegistrationStatus($registrationId, $status)) {
            setFlashMessage('success', $action === 'approve' ? 'อนุมัติการลงทะเบียนสำเร็จ' : 'ปฏิเสธการลงทะเบียนสำเร็จ');
        }
    }
    header("Location: /events/$eventId/manage");
    exit;
}
$title = 'จัดการกิจกรรม';
$activePage = 'my-events';
renderView('manage_event_content', [
    'event' => $event,
    'stats' => $stats,
    'genderStats' => $genderStats,
    'ageStats' => $ageStats,
    'registrations' => $registrations,
    'activePage' => $activePage
]);
