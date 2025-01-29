<?php
require '../config/db.php';
session_start();

// Fetch all attendees with event details
$query = "SELECT attendees.id, users.username, users.email, events.name AS event_name, attendees.registration_date 
          FROM attendees
          JOIN users ON attendees.user_id = users.id
          JOIN events ON attendees.event_id = events.id
          ORDER BY attendees.registration_date DESC";
$stmt = $conn->prepare($query);
$stmt->execute();
$attendees = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendees List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4 d-flex justify-content-between">
            <span>Attendees List</span>
            <a href="export_attendees.php" class="btn btn-success">Export CSV</a>
        </h2>
        <div class="mb-3">
            <input type="text" id="searchInput" class="form-control" placeholder="Search by name or event...">
        </div>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Event</th>
                    <th>Registration Date</th>
                </tr>
            </thead>
            <tbody id="attendeeTable">
                <?php foreach ($attendees as $index => $attendee): ?>
                    <tr>
                        <td><?= $index + 1; ?></td>
                        <td><?= htmlspecialchars($attendee['username']); ?></td>
                        <td><?= htmlspecialchars($attendee['email']); ?></td>
                        <td><?= htmlspecialchars($attendee['event_name']); ?></td>
                        <td><?= htmlspecialchars($attendee['registration_date']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="../events/list.php" class="btn btn-secondary">Back to Events</a>
    </div>

    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#attendeeTable tr');

            rows.forEach(row => {
                let username = row.cells[1].textContent.toLowerCase();
                let event = row.cells[3].textContent.toLowerCase();

                if (username.includes(filter) || event.includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</body>

</html>