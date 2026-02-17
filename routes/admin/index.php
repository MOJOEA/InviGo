<?php
declare(strict_types=1);
requireAdmin();

$conn = getConnection();

// Get statistics
$stats = [];

// Total users
$result = $conn->query("SELECT COUNT(*) as total FROM Users");
$stats['users'] = $result->fetch_assoc()['total'];

// Total events
$result = $conn->query("SELECT COUNT(*) as total FROM Events");
$stats['events'] = $result->fetch_assoc()['total'];

// Total registrations
$result = $conn->query("SELECT COUNT(*) as total FROM Registrations");
$stats['registrations'] = $result->fetch_assoc()['total'];

// Recent users
$result = $conn->query("SELECT id, name, email, created_at FROM Users ORDER BY created_at DESC LIMIT 5");
$recentUsers = $result->fetch_all(MYSQLI_ASSOC);

// Recent events
$result = $conn->query("SELECT e.id, e.title, e.event_date, u.name as creator 
                        FROM Events e 
                        JOIN Users u ON e.user_id = u.id 
                        ORDER BY e.created_at DESC LIMIT 5");
$recentEvents = $result->fetch_all(MYSQLI_ASSOC);

renderView('admin/dashboard', [
    'stats' => $stats,
    'recentUsers' => $recentUsers,
    'recentEvents' => $recentEvents,
    'activePage' => 'admin'
]);
