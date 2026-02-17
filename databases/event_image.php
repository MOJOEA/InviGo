<?php
function getEventImages(int $eventId): array {
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT * FROM Event_Images WHERE event_id = ?");
    $stmt->bind_param("i", $eventId);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
function getFirstEventImage(int $eventId): ?string {
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT image_path FROM Event_Images WHERE event_id = ? LIMIT 1");
    $stmt->bind_param("i", $eventId);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return $result['image_path'] ?? null;
}
function addEventImage(int $eventId, string $imagePath): bool {
    $conn = getConnection();
    $stmt = $conn->prepare("INSERT INTO Event_Images (event_id, image_path) VALUES (?, ?)");
    $stmt->bind_param("is", $eventId, $imagePath);
    return $stmt->execute();
}
function deleteEventImages(int $eventId): bool {
    $conn = getConnection();
    $stmt = $conn->prepare("DELETE FROM Event_Images WHERE event_id = ?");
    $stmt->bind_param("i", $eventId);
    return $stmt->execute();
}
