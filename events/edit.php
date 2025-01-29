<?php
require '../config/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: list.php');
    exit();
}

$eventId = $_GET['id'];

// Fetch event details
$stmt = $conn->prepare("SELECT * FROM events WHERE id = :id");
$stmt->execute(['id' => $eventId]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    header('Location: list.php');
    exit();
}

// Handle event update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $date = trim($_POST['date']);
    $location = trim($_POST['location']);
    $max_capacity = (int) $_POST['max_capacity'];

    $stmt = $conn->prepare("UPDATE events SET name = :name, description = :description, date = :date, location = :location, max_capacity = :max_capacity WHERE id = :id");
    $stmt->execute([
        'name' => $name,
        'description' => $description,
        'date' => $date,
        'location' => $location,
        'max_capacity' => $max_capacity,
        'id' => $eventId
    ]);

    header('Location: list.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2>Edit Event</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Event Name</label>
                <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($event['name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea id="description" name="description" class="form-control" rows="4" required><?php echo htmlspecialchars($event['description']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="date" class="form-label">Date</label>
                <input type="datetime-local" id="date" name="date" class="form-control" value="<?php echo htmlspecialchars($event['date']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="location" class="form-label">Location</label>
                <input type="text" id="location" name="location" class="form-control" value="<?php echo htmlspecialchars($event['location']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="max_capacity" class="form-label">Maximum Capacity</label>
                <input type="number" id="max_capacity" name="max_capacity" class="form-control" value="<?php echo $event['max_capacity']; ?>" required>
            </div>
            <button type="submit" class="btn btn-success">Update Event</button>
            <a href="list.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>

</html>