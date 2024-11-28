<?php
include 'db.php';

header('Content-Type: application/json');

try {
    $query = "SELECT * FROM rooms";
    $stmt = $pdo->query($query);
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Add the full path to the room images
    foreach ($rooms as &$room) {
        $room['room_image_path'] = __DIR__ . '/uploads/' . $room['room_image']; // Local file system path
        $room['room_image_url'] = 'http://localhost/uploads/' . $room['room_image']; // URL for browser
    }
    
    

    echo json_encode([
        "success" => true,
        "rooms" => $rooms,
    ]);
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Failed to fetch rooms: " . $e->getMessage(),
    ]);
}
