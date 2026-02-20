<?php
declare(strict_types=1);
requireAdmin();

$conn = getConnection();
$errors = [];
$success = '';
$eventId = (int)($_GET['id'] ?? 0);

// Get event info
$eventStmt = $conn->prepare("SELECT e.*, u.name as creator FROM Events e JOIN Users u ON e.user_id = u.id WHERE e.id = ?");
$eventStmt->bind_param("i", $eventId);
$eventStmt->execute();
$event = $eventStmt->get_result()->fetch_assoc();

if (!$event) {
    setFlashMessage('error', 'ไม่พบกิจกรรม');
    header('Location: /admin/events');
    exit;
}

// Handle delete registration
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_registration') {
    $regId = (int)$_POST['registration_id'];
    if (deleteRegistration($regId)) {
        $success = 'ลบการลงทะเบียนสำเร็จ';
    } else {
        $errors[] = 'เกิดข้อผิดพลาดในการลบการลงทะเบียน';
    }
}

// Get all registrations for this event
$regStmt = $conn->prepare("SELECT r.*, u.name, u.email, u.birth_date, u.gender, u.profile_image 
    FROM Registrations r 
    JOIN Users u ON r.user_id = u.id 
    WHERE r.event_id = ? 
    ORDER BY FIELD(r.status, 'pending', 'approved', 'rejected'), r.created_at DESC");
$regStmt->bind_param("i", $eventId);
$regStmt->execute();
$registrations = $regStmt->get_result()->fetch_all(MYSQLI_ASSOC);

renderView('admin/event-registrations', [
    'event' => $event,
    'registrations' => $registrations,
    'errors' => $errors,
    'success' => $success,
    'activePage' => 'admin'
]);
