<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

// Include database connection
// require_once 'db.php';

$host = 'localhost';
$dbname = 'hostel_db';
$username = 'root';
$password = '';

// Create a new PDO instance
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

    // Set error mode to exception for better error handling
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Handle connection error
    die("Database connection failed: " . $e->getMessage());
}

// Initialize variables
$error = $success = '';
$room = [
    'room_id' => '',
    'room_type' => '',
    'room_description' => '',
    'room_price' => '',
    'room_image' => '',
];

// Check if a room ID is provided
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $roomId = $_GET['id'];

    // Fetch room data
    try {
        $stmt = $pdo->prepare("SELECT * FROM rooms WHERE room_id = :room_id");
        $stmt->execute([':room_id' => $roomId]);
        $room = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$room) {
            $error = "Room not found.";
        }
    } catch (PDOException $e) {
        $error = "Error fetching room details: " . $e->getMessage();
    }
}

// Handle form submission for updating the room
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['room_id'])) {
    $roomId = $_POST['room_id'];
    $roomType = $_POST['room_type'];
    $roomDescription = $_POST['room_description'];
    $roomPrice = $_POST['room_price'];
    $roomImage = '';

    // Check if a new image is uploaded
    if (isset($_FILES['room_image']) && $_FILES['room_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        $imageName = time() . '-' . $_FILES['room_image']['name'];
        $imagePath = $uploadDir . $imageName;

        if (move_uploaded_file($_FILES['room_image']['tmp_name'], $imagePath)) {
            $roomImage = $imagePath;
        } else {
            $error = "Failed to upload image.";
        }
    } else {
        // Keep existing image if no new image is uploaded
        $roomImage = $_POST['existing_image'];
    }

    // Update room in database
    if (!$error) {
        try {
            $sql = "UPDATE rooms SET room_type = :room_type, room_description = :room_description, 
                    room_price = :room_price, room_image = :room_image WHERE room_id = :room_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':room_type' => $roomType,
                ':room_description' => $roomDescription,
                ':room_price' => $roomPrice,
                ':room_image' => $roomImage,
                ':room_id' => $roomId,
            ]);
            $success = "Room updated successfully!";
        } catch (PDOException $e) {
            $error = "Error updating room: " . $e->getMessage();
        }
    }
}
?>

<?php include 'header.php'; ?>
<div class="container mx-auto mt-8">
    <h1 class="text-2xl font-bold mb-4">Edit Room</h1>

    <?php if ($error): ?>
        <div class="bg-red-100 text-red-700 p-4 rounded mb-4"><?= htmlspecialchars($error) ?></div>
    <?php elseif ($success): ?>
        <div class="bg-green-100 text-green-700 p-4 rounded mb-4"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <?php if ($room): ?>
        <form method="POST" enctype="multipart/form-data" class="space-y-4">
            <input type="hidden" name="room_id" value="<?= htmlspecialchars($room['room_id']) ?>">
            <div>
                <label for="room_type" class="block text-sm font-medium">Room Type</label>
                <input type="text" id="room_type" name="room_type"
                    class="w-full p-2 border rounded"
                    value="<?= htmlspecialchars($room['room_type']) ?>" required>
            </div>
            <div>
                <label for="room_description" class="block text-sm font-medium">Description</label>
                <textarea id="room_description" name="room_description"
                    class="w-full p-2 border rounded" required><?= htmlspecialchars($room['room_description']) ?></textarea>
            </div>
            <div>
                <label for="room_price" class="block text-sm font-medium">Price</label>
                <input type="number" id="room_price" name="room_price"
                    class="w-full p-2 border rounded"
                    value="<?= htmlspecialchars($room['room_price']) ?>" required>
            </div>
            <div>
                <label for="room_image" class="block text-sm font-medium">Image</label>
                <input type="file" id="room_image" name="room_image" class="w-full p-2 border rounded">
                <input type="hidden" name="existing_image" value="<?= htmlspecialchars($room['room_image']) ?>">
                <?php if ($room['room_image']): ?>
                    <img src="<?= htmlspecialchars($room['room_image']) ?>" alt="Room Image" class="w-32 h-32 mt-2 object-cover">
                <?php endif; ?>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update Room</button>
            <a href="manage_room.php" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</a>
        </form>
    <?php endif; ?>
</div>
<?php include 'footer.php'; ?>