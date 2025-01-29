<?php
require '../../config/db.php';
session_start();

// Set headers for CSV file download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=attendees.csv');

$output = fopen('php://output', 'w');

// CSV column headers
fputcsv($output, ['Username', 'Email', 'Event', 'Registration Date']);

// Fetch attendee data
$query = "SELECT users.username, users.email, events.name AS event_name, attendees.registration_date 
          FROM attendees
          JOIN users ON attendees.user_id = users.id
          JOIN events ON attendees.event_id = events.id
          ORDER BY attendees.registration_date DESC";
$stmt = $conn->prepare($query);
$stmt->execute();
$attendees = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Output each row as CSV
foreach ($attendees as $attendee) {
    fputcsv($output, $attendee);
}

fclose($output);
exit();
