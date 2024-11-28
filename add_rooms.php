<?php include 'header.php'; ?>

<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

// Database connection parameters
$host = 'localhost';
$dbname = 'hostel_db';
$username = 'root';
$password = '';

// Create a new PDO instance
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_type = $_POST['room_type'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = $_FILES['room_image']; // Updated to handle file uploads

    try {
        // Validate and process the uploaded image
        if ($image['error'] === UPLOAD_ERR_OK) {
            $imageTmpPath = $image['tmp_name'];
            $imageOriginalName = $image['name'];
            $imageExtension = pathinfo($imageOriginalName, PATHINFO_EXTENSION);

            // Generate a unique hashed filename
            $hashedName = hash('sha256', time() . $imageOriginalName) . '.' . $imageExtension;

            // Define upload directory
            $uploadDir = __DIR__ . '/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $imageDestination = $uploadDir . $hashedName;

            // Move the file to the uploads directory
            if (move_uploaded_file($imageTmpPath, $imageDestination)) {
                // Save room data to the database
                $sql = "INSERT INTO rooms (room_type, room_description, room_price, room_image) 
                        VALUES (:room_type, :description, :price, :image)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':room_type' => $room_type,
                    ':description' => $description,
                    ':price' => $price,
                    ':image' => $hashedName,
                ]);
                $success = "Room added successfully!";
            } else {
                $error = "Failed to upload the image.";
            }
        } else {
            $error = "Error uploading image: " . $image['error'];
        }
    } catch (PDOException $e) {
        $error = "Error adding room: " . $e->getMessage();
    }
}
?>

<div class="container mx-auto mt-8">
    <h1 class="text-2xl font-bold mb-4">Add Room</h1>
    <?php if (isset($success)): ?>
        <div class="bg-green-100 text-green-700 p-4 rounded mb-4"><?= $success ?></div>
    <?php elseif (isset($error)): ?>
        <div class="bg-red-100 text-red-700 p-4 rounded mb-4"><?= $error ?></div>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data" class="space-y-4">
        <div>
            <label for="room_type" class="block text-sm font-medium">Room Type</label>
            <input type="text" id="room_type" name="room_type" class="w-full p-2 border rounded" required>
        </div>
        <div>
            <label for="description" class="block text-sm font-medium">Description</label>
            <textarea id="description" name="description" class="w-full p-2 border rounded" required></textarea>
        </div>
        <div>
            <label for="price" class="block text-sm font-medium">Price</label>
            <input type="number" id="price" name="price" class="w-full p-2 border rounded" required>
        </div>
        <div>
            <label for="room_image" class="block text-sm font-medium">Room Image</label>
            <input type="file" id="room_image" name="room_image" class="w-full p-2 border rounded" required>
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Add Room</button>
    </form>
</div>

<?php include 'footer.php'; ?>
