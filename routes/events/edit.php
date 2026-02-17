<?php
declare(strict_types=1);
requireAuth();
$userId = getCurrentUserId();
$eventId = (int)($_GET['id'] ?? 0);
$event = getEventByIdAndOwner($eventId, $userId);
if (!$event) {
    setFlashMessage('error', 'ไม่พบกิจกรรมหรือคุณไม่มีสิทธิ์แก้ไข');
    header('Location: /my-events');
    exit;
}
$images = getEventImages($eventId);
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $eventDate = $_POST['event_date'] ?? '';
    $endDate = $_POST['end_date'] ?? null;
    $maxParticipants = (int)($_POST['max_participants'] ?? 0);
    $deleteImages = $_POST['delete_images'] ?? [];
    if (empty($title)) {
        $errors['title'] = 'กรุณากรอกชื่อกิจกรรม';
    }
    if (empty($description)) {
        $errors['description'] = 'กรุณากรอกรายละเอียดกิจกรรม';
    }
    if (empty($location)) {
        $errors['location'] = 'กรุณากรอกสถานที่';
    }
    if (empty($eventDate)) {
        $errors['event_date'] = 'กรุณาเลือกวันที่จัดกิจกรรม';
    }
    if ($maxParticipants <= 0) {
        $errors['max_participants'] = 'จำนวนผู้เข้าร่วมต้องมากกว่า 0';
    }
    $approvedCount = getEventApprovedCount($eventId);
    if ($maxParticipants < $approvedCount) {
        $errors['max_participants'] = "จำนวนผู้เข้าร่วมไม่สามารถน้อยกว่าจำนวนที่อนุมัติแล้ว ($approvedCount คน)";
    }
    if (empty($errors)) {
        $success = updateEvent($eventId, [
            'title' => $title,
            'description' => $description,
            'location' => $location,
            'event_date' => $eventDate,
            'end_date' => $endDate,
            'max_participants' => $maxParticipants
        ]);
        if ($success) {
            if (!empty($deleteImages)) {
                $conn = getConnection();
                $placeholders = implode(',', array_fill(0, count($deleteImages), '?'));
                $stmt = $conn->prepare("DELETE FROM Event_Images WHERE id IN ($placeholders) AND event_id = ?");
                $types = str_repeat('i', count($deleteImages)) . 'i';
                $params = array_merge($deleteImages, [$eventId]);
                $stmt->bind_param($types, ...$params);
                $stmt->execute();
            }
            if (!empty($_FILES['images']['tmp_name'][0])) {
                $uploadedPaths = uploadEventImages($_FILES['images']);
                foreach ($uploadedPaths as $path) {
                    addEventImage($eventId, $path);
                }
            }
            setFlashMessage('success', 'แก้ไขกิจกรรมสำเร็จ');
            header('Location: /my-events');
            exit;
        } else {
            $errors['general'] = 'เกิดข้อผิดพลาด กรุณาลองใหม่';
        }
    }
}
$title = 'แก้ไขกิจกรรม';
$activePage = 'my-events';
$editMode = true;
renderView('event_form_content', ['errors' => $errors, 'editMode' => true, 'event' => $event, 'images' => $images, 'activePage' => $activePage]);
