<?php
declare(strict_types=1);
requireAuth();
$eventId = (int)($_GET['id'] ?? 0);
$event = getEventById($eventId);
if (!$event) {
    setFlashMessage('error', 'ไม่พบกิจกรรม');
    header('Location: /explore');
    exit;
}
$userId = getCurrentUserId();
$isOwnEvent = $event['user_id'] == $userId;
$images = getEventImages($eventId);
$approvedCount = getEventApprovedCount($eventId);
$isFull = $approvedCount >= $event['max_participants'];
$endDate = $event['end_date'] ?? $event['event_date'];
$isPast = strtotime($endDate) < time();
$registration = null;
if (!$isOwnEvent) {
    $registration = getRegistrationByUserAndEvent($userId, $eventId);
}
$title = sanitize($event['title']) . ' - InviGo';
$activePage = $isOwnEvent ? 'my-events' : 'explore';
renderView('event_detail_content', [
    'event' => $event,
    'images' => $images,
    'approvedCount' => $approvedCount,
    'isFull' => $isFull,
    'isPast' => $isPast,
    'isOwnEvent' => $isOwnEvent,
    'registration' => $registration,
    'activePage' => $activePage
]);
