<?php
declare(strict_types=1);
requireAuth();
$userId = getCurrentUserId();
$events = getEventsByUser($userId);
foreach ($events as &$event) {
    $event['image'] = getFirstEventImage($event['id']);
}
unset($event);
$title = 'กิจกรรมของฉัน';
$activePage = 'my-events';
renderView('my_events_content', ['events' => $events, 'activePage' => $activePage]);
