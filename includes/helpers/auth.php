<?php
function isLoggedIn(): bool
{
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}
function getCurrentUserId(): ?int
{
    return isLoggedIn() ? (int)$_SESSION['user_id'] : null;
}
function getCurrentUser(): ?array
{
    if (!isLoggedIn()) {
        return null;
    }
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT id, name, email, birth_date, gender, profile_image FROM Users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        return $result->fetch_assoc();
    }
    return null;
}
function requireAuth(): void
{
    if (!isLoggedIn()) {
        header('Location: /login');
        exit;
    }
}
function requireGuest(): void
{
    if (isLoggedIn()) {
        header('Location: /explore');
        exit;
    }
}
