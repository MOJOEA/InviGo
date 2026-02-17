<?php
declare(strict_types=1);
requireAuth();
$userId = getCurrentUserId();
$user = getUserById($userId);
if (!$user) {
    setFlashMessage('error', 'ไม่พบข้อมูลผู้ใช้');
    header('Location: /explore');
    exit;
}
$userEvents = getEventsByUser($userId);
$userRegistrations = getRegistrationsByUser($userId);
$title = 'โปรไฟล์ของฉัน';
$activePage = 'profile';
renderView('profile_content', [
    'user' => $user,
    'events' => $userEvents,
    'registrations' => $userRegistrations,
    'activePage' => $activePage
]);
