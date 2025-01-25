<?php
require_once '../includes/db.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM events WHERE id = ?";
    $stmt = executeQuery($query, [$id]);

    if ($stmt->affected_rows > 0) {
        header('Location: list_events.php');
        exit;
    }
}
header('Location: list_events.php');
exit;
