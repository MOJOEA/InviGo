<?php
function getCourses(): mysqli_result|bool
{
    $conn = getConnection();
    $sql = 'SELECT * FROM courses';
    $result = $conn->query($sql);
    return $result;
}

function getCourseById(int $id): mysqli_result|bool
{
    $conn = getConnection();
    $sql = 'SELECT * FROM courses WHERE course_id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}

function getCoursesByKeyword(string $keyword): mysqli_result|bool
{
    $conn = getConnection();
    $sql = 'SELECT * FROM courses WHERE course_name LIKE ? OR course_code LIKE ?';
    $stmt = $conn->prepare($sql);
    $keyword = '%' . $keyword . '%';
    $stmt->bind_param('ss', $keyword, $keyword);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}

function insertCourse(array $course): bool
{
    $conn = getConnection();
    $sql = 'INSERT INTO courses (course_name, course_code, instructor) VALUES (?, ?, ?)';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sss', $course['name'], $course['code'], $course['instructor']);
    $stmt->execute();
    return $stmt->affected_rows > 0;
}
?>
