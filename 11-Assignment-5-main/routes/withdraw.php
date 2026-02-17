<?php

$studentId = $_SESSION['student_id'] ?? null;
if (!$studentId) {
    header('Location: /login');
    exit;
}

$courseId = (int)($_GET['id'] ?? 0);
if ($courseId <= 0) {
    header('Location: /enrolled');
    exit;
}

if (withdrawCourse($studentId, $courseId)) {
    header('Location: /enrolled?message=' . urlencode('ถอนวิชาสำเร็จ'));
} else {
    header('Location: /enrolled?message=' . urlencode('ไม่สามารถถอนวิชานี้ได้'));
}
exit;
?>
