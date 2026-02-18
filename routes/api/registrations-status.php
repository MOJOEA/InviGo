<?php
declare(strict_types=1);
requireAuth();
header('Content-Type: application/json');
$userId = getCurrentUserId();
$registrations = getRegistrationsByUser($userId);
$result = [];
foreach ($registrations as $reg) {
    $result[] = [
        'id' => $reg['id'],
        'status' => $reg['status'],
        'checked_in' => $reg['checked_in'] ?? false,
        'otp' => getValidOtp($reg['id'])
    ];
}
echo json_encode($result);
