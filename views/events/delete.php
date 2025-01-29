<?php
require '../../config/db.php';
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

// Delete event
$stmt = $conn->prepare("DELETE FROM events WHERE id = :id");
$stmt->execute(['id' => $eventId]);

header('Location: list.php');
exit();
