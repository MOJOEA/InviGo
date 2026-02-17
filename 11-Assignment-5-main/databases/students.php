<?php
function getStudents(): mysqli_result|bool
{
    $conn = getConnection();
    $sql = 'SELECT * FROM students';
    $result = $conn->query($sql);
    return $result;
}

function getStudentById(int $id): mysqli_result|bool
{
    $conn = getConnection();
    $sql = 'SELECT * FROM students WHERE student_id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}

function getStudentByEmail(string $email): mysqli_result|bool
{
    $conn = getConnection();
    $sql = 'SELECT * FROM students WHERE email = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}

function checkLogin(string $email, string $password): bool
{
    $result = getStudentByEmail($email);
    if ($result && $result->num_rows > 0) {
        $student = $result->fetch_assoc();
        return password_verify($password, $student['password']);
    }
    return false;
}

function updateStudentPassword(int $id, string $hashed_password): bool
{
    $conn = getConnection();
    $sql = 'UPDATE students SET password = ? WHERE student_id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $hashed_password, $id);
    $stmt->execute();
    return $stmt->affected_rows > 0;
}
?>
