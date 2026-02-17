<?php

$studentId = $_SESSION['student_id'] ?? null;
if (!$studentId) {
    header('Location: /login');
    exit;
}

$result = getStudentById($studentId);
$student = $result->fetch_assoc();

$coursesResult = getCourses();
$enrollmentsResult = getEnrollmentsByStudent($studentId);
$enrollmentCount = $enrollmentsResult->num_rows;

$message = $_GET['message'] ?? null;

renderView('home', ['student' => $student, 'courses' => $coursesResult, 'enrollments' => $enrollmentsResult, 'enrollmentCount' => $enrollmentCount, 'message' => $message]);
?>
