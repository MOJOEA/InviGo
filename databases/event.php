<?php
function getEventById(int $id): ?array {
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT e.*, u.name as organizer_name, u.profile_image as organizer_image FROM Events e LEFT JOIN Users u ON e.user_id = u.id WHERE e.id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows === 1 ? $result->fetch_assoc() : null;
}
function getEventByIdAndOwner(int $eventId, int $userId): ?array {
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT * FROM Events WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $eventId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows === 1 ? $result->fetch_assoc() : null;
}
function getAllEvents(array $filters = [], int $excludeUserId = 0): array {
    $conn = getConnection();
    $whereConditions = [];
    $params = [];
    $types = '';
    if (!empty($filters['search'])) {
        $whereConditions[] = "(e.title LIKE ? OR e.description LIKE ? OR e.location LIKE ?)";
        $searchParam = "%" . $filters['search'] . "%";
        $params[] = $searchParam;
        $params[] = $searchParam;
        $params[] = $searchParam;
        $types .= 'sss';
    }
    if (!empty($filters['start_date'])) {
        $whereConditions[] = "e.event_date >= ?";
        $params[] = $filters['start_date'] . ' 00:00:00';
        $types .= 's';
    }
    if (!empty($filters['end_date'])) {
        $whereConditions[] = "e.event_date <= ?";
        $params[] = $filters['end_date'] . ' 23:59:59';
        $types .= 's';
    }
    if ($excludeUserId > 0) {
        $whereConditions[] = "e.user_id != ?";
        $params[] = $excludeUserId;
        $types .= 'i';
    }
    $whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';
    $sql = "SELECT e.*, u.name as organizer_name FROM Events e LEFT JOIN Users u ON e.user_id = u.id $whereClause ORDER BY e.event_date ASC";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        error_log("SQL Prepare Error: " . $conn->error);
        return [];
    }
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    if (!$stmt->execute()) {
        error_log("SQL Execute Error: " . $stmt->error);
        return [];
    }
    $result = $stmt->get_result();
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}
function getEventsByUser(int $userId): array {
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT e.*, 
        (SELECT COUNT(*) FROM Registrations r WHERE r.event_id = e.id AND r.status = 'pending') as pending_count,
        (SELECT COUNT(*) FROM Registrations r WHERE r.event_id = e.id AND r.status = 'approved') as approved_count,
        (SELECT COUNT(*) FROM Registrations r WHERE r.event_id = e.id AND r.checked_in = 1) as checked_in_count
        FROM Events e WHERE e.user_id = ? ORDER BY e.event_date DESC");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
function createEvent(array $data): int {
    $conn = getConnection();
    $stmt = $conn->prepare("INSERT INTO Events (user_id, title, description, location, event_date, end_date, max_participants) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssi", $data['user_id'], $data['title'], $data['description'], $data['location'], $data['event_date'], $data['end_date'], $data['max_participants']);
    $stmt->execute();
    return $conn->insert_id;
}
function updateEvent(int $id, array $data): bool {
    $conn = getConnection();
    $stmt = $conn->prepare("UPDATE Events SET title = ?, description = ?, location = ?, event_date = ?, end_date = ?, max_participants = ? WHERE id = ?");
    $stmt->bind_param("ssssssi", $data['title'], $data['description'], $data['location'], $data['event_date'], $data['end_date'], $data['max_participants'], $id);
    return $stmt->execute();
}
function deleteEvent(int $id, int $userId): bool {
    $conn = getConnection();
    $stmt = $conn->prepare("DELETE FROM Events WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id, $userId);
    return $stmt->execute();
}
function isEventFull(int $eventId): bool {
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT max_participants FROM Events WHERE id = ?");
    $stmt->bind_param("i", $eventId);
    $stmt->execute();
    $event = $stmt->get_result()->fetch_assoc();
    if (!$event) return true;
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM Registrations WHERE event_id = ? AND status = 'approved'");
    $stmt->bind_param("i", $eventId);
    $stmt->execute();
    $count = $stmt->get_result()->fetch_assoc()['count'];
    return $count >= $event['max_participants'];
}
function getEventApprovedCount(int $eventId): int {
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM Registrations WHERE event_id = ? AND status = 'approved'");
    $stmt->bind_param("i", $eventId);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc()['count'] ?? 0;
}
