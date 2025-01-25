<?php
require_once '../includes/db.php';
require_once '../includes/header.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: list_events.php');
    exit;
}

$id = $_GET['id'];
$query = "SELECT * FROM events WHERE id = ?";
$stmt = executeQuery($query, [$id]);
$event = $stmt->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $date = $_POST['date'];
    $time = $_POST['time'];
    $location = trim($_POST['location']);
    $capacity = intval($_POST['capacity']);
    $errors = [];

    if (empty($name) || empty($description) || empty($date) || empty($time) || empty($location) || empty($capacity)) {
        $errors[] = "All fields are required.";
    }

    if (empty($errors)) {
        $query = "UPDATE events SET name = ?, description = ?, date = ?, time = ?, location = ?, capacity = ? WHERE id = ?";
        $stmt = executeQuery($query, [$name, $description, $date, $time, $location, $capacity, $id]);

        if ($stmt->affected_rows > 0) {
            header('Location: list_events.php');
            exit;
        } else {
            $errors[] = "Failed to update the event. Please try again.";
        }
    }
}
?>

<h2>Edit Event</h2>
<?php if (!empty($errors)): ?>
    <div class="errors">
        <?php foreach ($errors as $error): ?>
            <p><?php echo $error; ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form method="POST">
    <input type="text" name="name" value="<?php echo htmlspecialchars($event['name']); ?>" required>
    <textarea name="description" required><?php echo htmlspecialchars($event['description']); ?></textarea>
    <input type="date" name="date" value="<?php echo $event['date']; ?>" required>
    <input type="time" name="time" value="<?php echo $event['time']; ?>" required>
    <input type="text" name="location" value="<?php echo htmlspecialchars($event['location']); ?>" required>
    <input type="number" name="capacity" value="<?php echo $event['capacity']; ?>" required>
    <button type="submit">Update Event</button>
</form>
<?php include '../includes/footer.php'; ?>