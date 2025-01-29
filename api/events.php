<?php
require '../config/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $event_id = isset($_GET['id']) ? intval($_GET['id']) : null;

    if ($event_id) {
        // Fetch details of a specific event
        $query = "SELECT id, name, description, date, location, max_capacity FROM events WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$event_id]);
        $event = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($event) {
            echo json_encode(['success' => true, 'event' => $event]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Event not found']);
        }
    } else {
        // Fetch all events
        $query = "SELECT id, name, description, date, location, max_capacity FROM events ORDER BY date ASC";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['success' => true, 'events' => $events]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
