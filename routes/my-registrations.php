<?php
declare(strict_types=1);
requireAuth();
$userId = getCurrentUserId();
$registrations = getRegistrationsByUser($userId);
foreach ($registrations as &$reg) {
    $reg['image'] = getFirstEventImage($reg['event_id']);
    $reg['otp'] = getValidOtp($reg['id']);
}
unset($reg);
$title = 'การลงทะเบียนของฉัน';
$activePage = 'my-registrations';
$otpData = $_SESSION['otp_data'] ?? null;
if ($otpData) {
    unset($_SESSION['otp_data']);
}
renderView('my_registrations_content', [
    'registrations' => $registrations,
    'activePage' => $activePage,
    'otpData' => $otpData,
    'showOtp' => isset($_GET['show_otp'])
]);
