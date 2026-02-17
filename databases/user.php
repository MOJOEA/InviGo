<?php
function getUserById(int $id): ?array {
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT id, name, email, birth_date, gender, profile_image, created_at FROM Users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows === 1 ? $result->fetch_assoc() : null;
}
function getUserByEmail(string $email): ?array {
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT id, name, email, password, birth_date, gender, profile_image FROM Users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows === 1 ? $result->fetch_assoc() : null;
}
function emailExists(string $email): bool {
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT id FROM Users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    return $stmt->get_result()->num_rows > 0;
}
function createUser(array $data): int {
    $conn = getConnection();
    $stmt = $conn->prepare("INSERT INTO Users (name, email, password, birth_date, gender) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $data['name'], $data['email'], $data['password'], $data['birth_date'], $data['gender']);
    $stmt->execute();
    return $conn->insert_id;
}
function updateUser(int $id, array $data): bool {
    $conn = getConnection();
    $fields = [];
    $values = [];
    $types = '';
    foreach (['name', 'email', 'password', 'birth_date', 'gender', 'profile_image'] as $field) {
        if (isset($data[$field])) {
            $fields[] = "$field = ?";
            $values[] = $data[$field];
            $types .= is_int($data[$field]) ? 'i' : 's';
        }
    }
    if (empty($fields)) return false;
    $sql = "UPDATE Users SET " . implode(', ', $fields) . " WHERE id = ?";
    $values[] = $id;
    $types .= 'i';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$values);
    return $stmt->execute();
}
function deleteUser(int $id): bool {
    $conn = getConnection();
    $stmt = $conn->prepare("DELETE FROM Users WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}
