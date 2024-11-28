<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

// include 'db.php';

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

try {
    $stmt = $pdo->query("SELECT * FROM rooms");
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching rooms: " . $e->getMessage());
}
?>

<?php include 'header.php'; ?>
<div class="container mx-auto mt-8">
    <h1 class="text-2xl font-bold mb-4">Manage Rooms</h1>
    <table class="min-w-full bg-white rounded-lg shadow-lg">
        <thead class="bg-blue-500 text-white">
            <tr>
                <th class="py-3 px-4 text-left">ID</th>
                <th class="py-3 px-4 text-left">Type</th>
                <th class="py-3 px-4 text-left">Description</th>
                <th class="py-3 px-4 text-left">Price</th>
                <th class="py-3 px-4 text-left">Image</th>
                <th class="py-3 px-4 text-left">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rooms as $room): ?>
                <tr class="border-b hover:bg-gray-100">
                    <td class="py-3 px-4"><?= htmlspecialchars($room['room_id']) ?></td>
                    <td class="py-3 px-4"><?= htmlspecialchars($room['room_type']) ?></td>
                    <td class="py-3 px-4"><?= htmlspecialchars($room['room_description']) ?></td>
                    <td class="py-3 px-4">$<?= htmlspecialchars($room['room_price']) ?></td>
                    <td class="py-3 px-4">
                        <img src="<?= htmlspecialchars($room['room_image']) ?>" class="w-16 h-16 object-cover">
                    </td>
                    <td class="py-3 px-4 flex space-x-2">
                        <a href="edit_room.php?id=<?= $room['room_id'] ?>" class="px-2 py-1 bg-yellow-500 text-white rounded">Edit</a>
                        <form method="POST" action="delete_room.php" onsubmit="return confirm('Delete this room?')">
                            <input type="hidden" name="room_id" value="<?= $room['room_id'] ?>">
                            <button type="submit" class="px-2 py-1 bg-red-500 text-white rounded">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include 'footer.php'; ?>
