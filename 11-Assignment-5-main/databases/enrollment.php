<?php
function getEnrollmentsByStudent(int $studentId): mysqli_result|bool
{
    $conn = getConnection();
    $sql = 'SELECT e.*, c.course_name, c.course_code, c.instructor FROM enrollment e JOIN courses c ON e.course_id = c.course_id WHERE e.student_id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $studentId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}

function isEnrolled(int $studentId, int $courseId): bool
{
    $conn = getConnection();
    $sql = 'SELECT * FROM enrollment WHERE student_id = ? AND course_id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $studentId, $courseId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

function enrollCourse(int $studentId, int $courseId): bool
{
    if (isEnrolled($studentId, $courseId)) {
        return false; // Already enrolled
    }
    $conn = getConnection();
    $sql = 'INSERT INTO enrollment (student_id, course_id, enrollment_date) VALUES (?, ?, CURDATE())';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $studentId, $courseId);
    $stmt->execute();
    return $stmt->affected_rows > 0;
}

function withdrawCourse(int $studentId, int $courseId): bool
{
    $conn = getConnection();
    $sql = 'DELETE FROM enrollment WHERE student_id = ? AND course_id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $studentId, $courseId);
    $stmt->execute();
    return $stmt->affected_rows > 0;
}
?>
