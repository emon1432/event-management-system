<?php
require '../../config/db.php';
session_start();

if (!isset($_GET['event_id'])) {
    header('Location: ../events/list.php');
    exit();
}

$eventId = $_GET['event_id'];

// Fetch event details
$stmt = $conn->prepare("SELECT * FROM events WHERE id = :id");
$stmt->execute(['id' => $eventId]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    header('Location: ../events/list.php');
    exit();
}

// Check if the event is full
$attendeeCount = $conn->prepare("SELECT COUNT(*) FROM attendees WHERE event_id = :event_id");
$attendeeCount->execute(['event_id' => $eventId]);
$registeredCount = $attendeeCount->fetchColumn();

if ($registeredCount >= $event['max_capacity']) {
    die("This event is full. No more registrations allowed.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Securely hash the password

    try {
        $conn->beginTransaction(); // Start transaction to ensure atomic operations

        // Check if user already exists in `users` table
        $checkUserStmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
        $checkUserStmt->execute(['email' => $email]);
        $user = $checkUserStmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            // Insert new user into `users` table
            $insertUserStmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, 'user')");
            $insertUserStmt->execute([
                'username' => $username,
                'email' => $email,
                'password' => $password
            ]);
            $userId = $conn->lastInsertId(); // Get new user's ID
        } else {
            $userId = $user['id']; // Use existing user ID
        }

        // Check if user already registered for this event
        $checkStmt = $conn->prepare("SELECT COUNT(*) FROM attendees WHERE event_id = :event_id AND user_id = :user_id");
        $checkStmt->execute(['event_id' => $eventId, 'user_id' => $userId]);

        if ($checkStmt->fetchColumn() > 0) {
            die("You have already registered for this event.");
        }

        // Register attendee in `attendees` table (No name/email, only user_id and event_id)
        $stmt = $conn->prepare("INSERT INTO attendees (event_id, user_id) VALUES (:event_id, :user_id)");
        $stmt->execute([
            'event_id' => $eventId,
            'user_id' => $userId
        ]);

        $conn->commit(); // Commit transaction

        echo "<script>alert('Registration successful!'); window.location.href='../events/list.php';</script>";
    } catch (Exception $e) {
        $conn->rollBack(); // Rollback in case of error
        die("Error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register for Event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2>Register for <?php echo htmlspecialchars($event['name']); ?></h2>
        <p><strong>Date:</strong> <?php echo htmlspecialchars($event['date']); ?></p>
        <p><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></p>

        <form method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
            <a href="../events/list.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>

</html>