<?php

$studentId = $_SESSION['student_id'] ?? null;
if (!$studentId) {
    header('Location: /login');
    exit;
}

$keyword = $_GET['keyword'] ?? '';
if ($keyword) {
    $coursesResult = getCoursesByKeyword($keyword);
} else {
    $coursesResult = getCourses();
}

$enrollmentsResult = getEnrollmentsByStudent($studentId);

renderView('courses', ['courses' => $coursesResult, 'enrollments' => $enrollmentsResult, 'keyword' => $keyword]);
?>
