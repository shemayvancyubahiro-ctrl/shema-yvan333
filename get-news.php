<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "kigeme_db");
if ($conn->connect_error) {
    echo json_encode([]);
    exit;
}

$result = $conn->query("SELECT id, title, content, category, event_date, created_at FROM news ORDER BY created_at DESC");
$items = [];
while ($row = $result->fetch_assoc()) {
    $items[] = $row;
}
echo json_encode($items);
$conn->close();