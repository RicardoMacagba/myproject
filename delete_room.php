<?php
require_once 'db.php';

// Check if ID is provided via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['room_id'])) {
    $roomId = $_POST['room_id'];

    try {
        // Prepare and execute delete query
        $sql = 'DELETE FROM rooms WHERE room_id = :room_id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':room_id', $roomId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Room deleted successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete room.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>
