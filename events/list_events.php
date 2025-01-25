<?php
require_once '../includes/db.php';
require_once '../includes/header.php';

// Fetch events from the database
$query = "SELECT * FROM events ORDER BY date ASC";
$result = $conn->query($query);
?>

<h2>All Events</h2>
<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Date</th>
            <th>Time</th>
            <th>Location</th>
            <th>Capacity</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo $row['date']; ?></td>
                <td><?php echo $row['time']; ?></td>
                <td><?php echo htmlspecialchars($row['location']); ?></td>
                <td><?php echo $row['capacity']; ?></td>
                <td>
                    <a href="edit_event.php?id=<?php echo $row['id']; ?>">Edit</a>
                    <a href="delete_event.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<?php include '../includes/footer.php'; ?>