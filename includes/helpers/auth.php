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

function isAdmin(): bool
{
    if (!isLoggedIn()) {
        return false;
    }
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT role FROM Users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        return $user['role'] == 1;
    }
    return false;
}

function requireAdmin(): void
{
    requireAuth();
    if (!isAdmin()) {
        http_response_code(403);
        renderView('403');
        exit;
    }
}
