<?php
require '../config/db.php';
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $date = trim($_POST['date']);
    $location = trim($_POST['location']);
    $max_capacity = (int) $_POST['max_capacity'];
    $created_by = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO events (name, description, date, location, max_capacity, created_by) 
                            VALUES (:name, :description, :date, :location, :max_capacity, :created_by)");
    $stmt->execute([
        'name' => $name,
        'description' => $description,
        'date' => $date,
        'location' => $location,
        'max_capacity' => $max_capacity,
        'created_by' => $created_by
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
    <title>Create Event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2>Create New Event</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Event Name</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea id="description" name="description" class="form-control" rows="4" required></textarea>
            </div>
            <div class="mb-3">
                <label for="date" class="form-label">Date</label>
                <input type="datetime-local" id="date" name="date" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="location" class="form-label">Location</label>
                <input type="text" id="location" name="location" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="max_capacity" class="form-label">Maximum Capacity</label>
                <input type="number" id="max_capacity" name="max_capacity" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Create Event</button>
        </form>
    </div>
</body>

</html>