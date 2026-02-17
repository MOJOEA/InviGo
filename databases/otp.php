<?php
function getValidOtp(int $registrationId): ?array {
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT otp_code, expires_at FROM Otp_Codes WHERE registration_id = ? AND used = 0 AND expires_at > NOW() ORDER BY created_at DESC LIMIT 1");
    $stmt->bind_param("i", $registrationId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows === 1 ? $result->fetch_assoc() : null;
}
function createOtp(int $registrationId, string $otpCode, string $expiresAt): bool {
    $conn = getConnection();
    $stmt = $conn->prepare("INSERT INTO Otp_Codes (registration_id, otp_code, expires_at) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $registrationId, $otpCode, $expiresAt);
    return $stmt->execute();
}
function invalidateOtps(int $registrationId): bool {
    $conn = getConnection();
    $stmt = $conn->prepare("UPDATE Otp_Codes SET used = 1 WHERE registration_id = ? AND used = 0");
    $stmt->bind_param("i", $registrationId);
    return $stmt->execute();
}
function verifyOtp(string $otpCode, int $eventId): ?array {
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT o.*, r.id as reg_id, r.checked_in 
        FROM Otp_Codes o 
        JOIN Registrations r ON o.registration_id = r.id 
        WHERE o.otp_code = ? AND o.expires_at > NOW() AND o.used = 0 AND r.event_id = ?");
    $stmt->bind_param("si", $otpCode, $eventId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows === 1 ? $result->fetch_assoc() : null;
}
function markOtpUsed(int $otpId): bool {
    $conn = getConnection();
    $stmt = $conn->prepare("UPDATE Otp_Codes SET used = 1 WHERE id = ?");
    $stmt->bind_param("i", $otpId);
    return $stmt->execute();
}
