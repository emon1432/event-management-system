<?php
require '../../config/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $event_id = intval($_POST['event_id']);

    // Check if event exists and has space
    $eventCheckQuery = "SELECT max_capacity, (SELECT COUNT(*) FROM attendees WHERE event_id = ?) AS registered FROM events WHERE id = ?";
    $stmt = $conn->prepare($eventCheckQuery);
    $stmt->execute([$event_id, $event_id]);
    $event = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$event) {
        echo json_encode(['success' => false, 'message' => 'Event not found.']);
        exit();
    }

    if ($event['registered'] >= $event['max_capacity']) {
        echo json_encode(['success' => false, 'message' => 'Event is full.']);
        exit();
    }

    // Insert user (if email is unique)
    $checkUserQuery = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($checkUserQuery);
    $stmt->execute([$email]);
    $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingUser) {
        $user_id = $existingUser['id'];
    } else {
        $insertUserQuery = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insertUserQuery);
        $stmt->execute([$username, $email, $password]);
        $user_id = $conn->lastInsertId();
    }

    // Register attendee
    $insertAttendeeQuery = "INSERT INTO attendees (user_id, event_id) VALUES (?, ?)";
    $stmt = $conn->prepare($insertAttendeeQuery);

    try {
        $stmt->execute([$user_id, $event_id]);
        echo json_encode(['success' => true, 'message' => 'Successfully registered for the event!']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Already registered for this event.']);
    }
}
