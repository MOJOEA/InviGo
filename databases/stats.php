<?php
function getEventRegistrationStats(int $eventId): array {
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
        SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
        SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected,
        SUM(CASE WHEN checked_in = 1 THEN 1 ELSE 0 END) as checked_in
        FROM Registrations WHERE event_id = ?");
    $stmt->bind_param("i", $eventId);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}
function getEventGenderStats(int $eventId): array {
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT u.gender, COUNT(*) as count 
        FROM Registrations r 
        JOIN Users u ON r.user_id = u.id 
        WHERE r.event_id = ? AND r.status = 'approved' 
        GROUP BY u.gender");
    $stmt->bind_param("i", $eventId);
    $stmt->execute();
    $result = $stmt->get_result();
    $stats = ['male' => 0, 'female' => 0, 'other' => 0];
    while ($row = $result->fetch_assoc()) {
        $stats[$row['gender']] = $row['count'];
    }
    return $stats;
}
function getEventAgeStats(int $eventId): array {
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT 
        CASE 
            WHEN TIMESTAMPDIFF(YEAR, u.birth_date, CURDATE()) BETWEEN 18 AND 25 THEN '18-25'
            WHEN TIMESTAMPDIFF(YEAR, u.birth_date, CURDATE()) BETWEEN 26 AND 35 THEN '26-35'
            WHEN TIMESTAMPDIFF(YEAR, u.birth_date, CURDATE()) >= 36 THEN '36+'
            ELSE 'unknown'
        END as age_group,
        COUNT(*) as count
        FROM Registrations r 
        JOIN Users u ON r.user_id = u.id 
        WHERE r.event_id = ? AND r.status = 'approved' 
        GROUP BY age_group");
    $stmt->bind_param("i", $eventId);
    $stmt->execute();
    $result = $stmt->get_result();
    $stats = ['18-25' => 0, '26-35' => 0, '36+' => 0, 'unknown' => 0];
    while ($row = $result->fetch_assoc()) {
        $stats[$row['age_group']] = $row['count'];
    }
    return $stats;
}
