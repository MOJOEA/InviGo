<?php
declare(strict_types=1);
requireAuth();
$userId = getCurrentUserId();
$search = trim($_GET['search'] ?? '');
$startDate = $_GET['start_date'] ?? '';
$endDate = $_GET['end_date'] ?? '';
$filters = [
    'search' => $search,
    'start_date' => $startDate,
    'end_date' => $endDate
];
$events = getAllEvents($filters, 0);
foreach ($events as &$event) {
    $event['image'] = getFirstEventImage($event['id']);
    $event['approved_count'] = getEventApprovedCount($event['id']);
    $event['is_owner'] = ($event['user_id'] == $userId);
    if (!$event['is_owner']) {
        $reg = getRegistrationByUserAndEvent($userId, $event['id']);
        $event['user_registration_status'] = $reg ? 1 : 0;
        $event['registration_status'] = $reg['status'] ?? null;
    }
}
unset($event);
$title = 'ค้นหากิจกรรม';
$activePage = 'explore';
renderView('explore_content', [
    'events' => $events,
    'search' => $search,
    'startDate' => $startDate,
    'endDate' => $endDate,
    'activePage' => $activePage
]);
