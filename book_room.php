<?php
require_once 'db.php';

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    $room_id = $data['room_id'] ?? null;
    $user_name = $data['user_name'] ?? null;
    $user_email = $data['user_email'] ?? null;

    if (!$room_id || !$user_name || !$user_email) {
        echo json_encode(["success" => false, "message" => "All fields are required."]);
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO bookings (room_id, user_name, user_email) VALUES (:room_id, :user_name, :user_email)");
        $stmt->bindParam(':room_id', $room_id);
        $stmt->bindParam(':user_name', $user_name);
        $stmt->bindParam(':user_email', $user_email);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Room booked successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to book room."]);
        }
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
}
