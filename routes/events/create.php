<?php
declare(strict_types=1);
requireAuth();
$userId = getCurrentUserId();
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $eventDate = $_POST['event_date'] ?? '';
    $endDate = $_POST['end_date'] ?? null;
    $maxParticipants = (int)($_POST['max_participants'] ?? 0);
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
    if (empty($errors)) {
        $eventId = createEvent([
            'user_id' => $userId,
            'title' => $title,
            'description' => $description,
            'location' => $location,
            'event_date' => $eventDate,
            'end_date' => $endDate,
            'max_participants' => $maxParticipants
        ]);
        if ($eventId) {
            if (!empty($_FILES['images']['tmp_name'][0])) {
                $uploadedPaths = uploadEventImages($_FILES['images']);
                foreach ($uploadedPaths as $path) {
                    addEventImage($eventId, $path);
                }
            }
            setFlashMessage('success', 'สร้างกิจกรรมสำเร็จ');
            header('Location: /my-events');
            exit;
        } else {
            $errors['general'] = 'เกิดข้อผิดพลาด กรุณาลองใหม่';
        }
    }
}
$title = 'สร้างกิจกรรมใหม่';
$activePage = 'my-events';
$editMode = false;
renderView('event_form_content', ['errors' => $errors, 'editMode' => false, 'activePage' => $activePage]);
