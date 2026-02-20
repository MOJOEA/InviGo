<?php
function getRegistrationById(int $id): ?array {
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT r.*, e.title as event_title, e.event_date, e.location, e.user_id as organizer_id, u.name as organizer_name 
        FROM Registrations r 
        JOIN Events e ON r.event_id = e.id 
        JOIN Users u ON e.user_id = u.id
        WHERE r.id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows === 1 ? $result->fetch_assoc() : null;
}
function getRegistrationByUserAndEvent(int $userId, int $eventId): ?array {
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT * FROM Registrations WHERE user_id = ? AND event_id = ?");
    $stmt->bind_param("ii", $userId, $eventId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows === 1 ? $result->fetch_assoc() : null;
}
function isUserRegistered(int $userId, int $eventId): bool {
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT id FROM Registrations WHERE user_id = ? AND event_id = ?");
    $stmt->bind_param("ii", $userId, $eventId);
    $stmt->execute();
    return $stmt->get_result()->num_rows > 0;
}
function getRegistrationsByUser(int $userId): array {
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT r.*, e.title, e.event_date, e.end_date, e.location, e.max_participants,
        u.name as organizer_name,
        (SELECT COUNT(*) FROM Registrations WHERE event_id = e.id AND status = 'approved') as approved_count
        FROM Registrations r 
        JOIN Events e ON r.event_id = e.id 
        JOIN Users u ON e.user_id = u.id
        WHERE r.user_id = ? 
        ORDER BY FIELD(r.status, 'approved', 'pending', 'rejected'), e.event_date DESC");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
function getRegistrationsByEvent(int $eventId): array {
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT r.*, u.name, u.email, u.birth_date, u.gender, u.profile_image,
        (SELECT otp_code FROM Otp_Codes WHERE registration_id = r.id AND used = 0 AND expires_at > NOW() ORDER BY created_at DESC LIMIT 1) as current_otp
        FROM Registrations r 
        JOIN Users u ON r.user_id = u.id 
        WHERE r.event_id = ? 
        ORDER BY FIELD(r.status, 'pending', 'approved', 'rejected'), r.created_at DESC");
    $stmt->bind_param("i", $eventId);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
function createRegistration(int $userId, int $eventId): int {
    $conn = getConnection();
    $stmt = $conn->prepare("INSERT INTO Registrations (event_id, user_id, status) VALUES (?, ?, 'pending')");
    $stmt->bind_param("ii", $eventId, $userId);
    $stmt->execute();
    return $conn->insert_id;
}
function updateRegistrationStatus(int $id, string $status): bool {
    $conn = getConnection();
    $stmt = $conn->prepare("UPDATE Registrations SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);
    return $stmt->execute();
}
function checkInRegistration(int $id): bool {
    $conn = getConnection();
    $stmt = $conn->prepare("UPDATE Registrations SET checked_in = 1 WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}
function deleteRegistration(int $id): bool {
    $conn = getConnection();
    $otpStmt = $conn->prepare("DELETE FROM Otp_Codes WHERE registration_id = ?");
    $otpStmt->bind_param("i", $id);
    $otpStmt->execute();
    $stmt = $conn->prepare("DELETE FROM Registrations WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

function clearEventRegistrations(int $eventId): bool {
    $conn = getConnection();
    $stmt = $conn->prepare("DELETE FROM Otp_Codes WHERE registration_id IN (SELECT id FROM Registrations WHERE event_id = ?)");
    $stmt->bind_param("i", $eventId);
    $stmt->execute();
    $stmt = $conn->prepare("DELETE FROM Registrations WHERE event_id = ?");
    $stmt->bind_param("i", $eventId);
    return $stmt->execute();
}
