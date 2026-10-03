<?php
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "kigeme_db");
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["ok" => false]);
    exit;
}

$name    = trim($_POST["name"] ?? "");
$email   = trim($_POST["email"] ?? "");
$message = trim($_POST["message"] ?? "");

if ($name === "" || $message === "" ||
    strlen($name) > 100 || strlen($email) > 100 || strlen($message) > 1000) {
    http_response_code(400);
    echo json_encode(["ok" => false]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO messages (name, email, message) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $message);
$ok = $stmt->execute();

echo json_encode(["ok" => $ok]);

$stmt->close();
$conn->close();