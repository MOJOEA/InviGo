<?php
declare(strict_types=1);
requireAdmin();

$conn = getConnection();
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $eventId = (int)$_POST['event_id'];
    $stmt = $conn->prepare("DELETE FROM Events WHERE id = ?");
    $stmt->bind_param("i", $eventId);
    if ($stmt->execute()) {
        $success = 'ลบกิจกรรมสำเร็จ';
    } else {
        $errors[] = 'เกิดข้อผิดพลาดในการลบกิจกรรม';
    }
}

$result = $conn->query("SELECT e.id, e.title, e.event_date, e.location, e.status, e.created_at, u.name as creator,
                        (SELECT COUNT(*) FROM Registrations WHERE event_id = e.id) as total_registrations
                        FROM Events e 
                        JOIN Users u ON e.user_id = u.id 
                        ORDER BY e.created_at DESC");
$events = $result->fetch_all(MYSQLI_ASSOC);

renderView('admin/events', [
    'events' => $events,
    'errors' => $errors,
    'success' => $success,
    'activePage' => 'admin'
]);
