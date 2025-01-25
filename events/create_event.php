<?php
require_once '../includes/db.php';
require_once '../includes/header.php';

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: ../auth/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $date = $_POST['date'];
    $time = $_POST['time'];
    $location = trim($_POST['location']);
    $capacity = intval($_POST['capacity']);
    $errors = [];

    // Validation
    if (empty($name) || empty($description) || empty($date) || empty($time) || empty($location) || empty($capacity)) {
        $errors[] = "All fields are required.";
    } elseif ($capacity <= 0) {
        $errors[] = "Capacity must be a positive number.";
    }

    if (empty($errors)) {
        // Insert event into database
        $query = "INSERT INTO events (name, description, date, time, location, capacity, created_by) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = executeQuery($query, [$name, $description, $date, $time, $location, $capacity, $_SESSION['user']['id']]);

        if ($stmt->affected_rows > 0) {
            header('Location: list_events.php');
            exit;
        } else {
            $errors[] = "Failed to create the event. Please try again.";
        }
    }
}
?>

<h2>Create Event</h2>
<?php if (!empty($errors)): ?>
    <div class="errors">
        <?php foreach ($errors as $error): ?>
            <p><?php echo $error; ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form method="POST">
    <input type="text" name="name" placeholder="Event Name" required>
    <textarea name="description" placeholder="Event Description" required></textarea>
    <input type="date" name="date" required>
    <input type="time" name="time" required>
    <input type="text" name="location" placeholder="Event Location" required>
    <input type="number" name="capacity" placeholder="Max Capacity" required>
    <button type="submit">Create Event</button>
</form>
<?php include '../includes/footer.php'; ?>