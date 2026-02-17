<?php

$studentId = $_SESSION['student_id'] ?? null;
if (!$studentId) {
    header('Location: /login');
    exit;
}

$enrollmentsResult = getEnrollmentsByStudent($studentId);

$message = $_GET['message'] ?? null;

renderView('enrolled', ['enrollments' => $enrollmentsResult, 'message' => $message]);
?>
