<?php
declare(strict_types=1);
requireAuth();
header('Content-Type: application/json');
$userId = getCurrentUserId();
$eventId = (int)($_GET['event_id'] ?? 0);
$event = getEventByIdAndOwner($eventId, $userId);
if (!$event) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}
$stats = getEventRegistrationStats($eventId);
$registrations = getRegistrationsByEvent($eventId);
$result = [
    'stats' => $stats,
    'registrations' => array_map(fn($r) => [
        'id' => $r['id'],
        'name' => $r['user_name'],
        'status' => $r['status'],
        'checked_in' => $r['checked_in'] ?? false,
        'gender' => $r['gender'] ?? 'other',
        'age' => $r['age'] ?? null,
        'created_at' => $r['created_at']
    ], $registrations)
];
echo json_encode($result);
