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
renderView('my_registrations_content', ['registrations' => $registrations, 'activePage' => $activePage]);
