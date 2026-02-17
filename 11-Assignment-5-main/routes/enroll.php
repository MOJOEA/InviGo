<?php

$studentId = $_SESSION['student_id'] ?? null;
if (!$studentId) {
    header('Location: /login');
    exit;
}

$courseId = (int)($_GET['id'] ?? 0);
if ($courseId <= 0) {
    header('Location: /');
    exit;
}

if (enrollCourse($studentId, $courseId)) {
    header('Location: /courses?message=' . urlencode('ลงทะเบียนวิชาสำเร็จ'));
} else {
    header('Location: /courses?message=' . urlencode('ไม่สามารถลงทะเบียนวิชานี้ได้ (อาจลงทะเบียนแล้ว)'));
}
exit;
?>
