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
    $stmt = $pdo->query("SELECT id, email FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching users: " . $e->getMessage());
}
?>

<?php include 'header.php'; ?>
<div class="container mx-auto mt-8">
    <h1 class="text-2xl font-bold mb-4">Manage Users</h1>
    <table class="min-w-full bg-white rounded-lg shadow-lg">
        <thead class="bg-blue-500 text-white">
            <tr>
                <th class="py-3 px-4 text-left">ID</th>
                <th class="py-3 px-4 text-left">Email</th>
                <th class="py-3 px-4 text-left">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr class="border-b hover:bg-gray-100">
                    <td class="py-3 px-4"><?= htmlspecialchars($user['id']) ?></td>
                    <td class="py-3 px-4"><?= htmlspecialchars($user['email']) ?></td>
                    <td class="py-3 px-4">
                        <form method="POST" action="delete_user.php" onsubmit="return confirm('Delete this user?')">
                            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                            <button type="submit" class="px-2 py-1 bg-red-500 text-white rounded">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include 'footer.php'; ?>
