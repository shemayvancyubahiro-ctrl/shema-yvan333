<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "kigeme_db");

if ($conn->connect_error) {
    echo json_encode(["ok" => false, "error" => "database connection failed"]);
    exit;
}

$name    = trim($_POST['name'] ?? '');
$email   = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');

if ($name === '' || $message === '') {
    echo json_encode(["ok" => false, "error" => "name and message are required"]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO messages (name, email, message) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $message);

if ($stmt->execute()) {
    echo json_encode(["ok" => true]);
} else {
    echo json_encode(["ok" => false, "error" => "could not save"]);
}

$stmt->close();
$conn->close();