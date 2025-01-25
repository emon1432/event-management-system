<?php
require_once '../config.php';

function executeQuery($query, $params = [])
{
    global $conn;
    $stmt = $conn->prepare($query);
    if ($params) {
        $types = str_repeat('s', count($params));
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    return $stmt;
}
