<?php
include_once 'header.php';

$host = 'localhost';
$dbname = 'hostel_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Fetch statistics
$roomCount = $pdo->query('SELECT COUNT(*) FROM rooms')->fetchColumn();
$userCount = $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();

// Fetch availability details
$bookedRooms = $pdo->query('SELECT COUNT(*) FROM bookings')->fetchColumn();
$availableRooms = $roomCount - $bookedRooms;
?>

<div class="ml-64 p-8">
    <h1 class="text-3xl font-bold mb-6">Dashboard</h1>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white shadow rounded p-4">
            <h2 class="text-gray-700 font-semibold">Total Rooms</h2>
            <p class="text-2xl font-bold text-blue-500"><?= $roomCount ?></p>
        </div>
        <div class="bg-white shadow rounded p-4">
            <h2 class="text-gray-700 font-semibold">Total Users</h2>
            <p class="text-2xl font-bold text-green-500"><?= $userCount ?></p>
        </div>
        <div class="bg-white shadow rounded p-4">
            <h2 class="text-gray-700 font-semibold">Booked Rooms</h2>
            <p class="text-2xl font-bold text-red-500"><?= $bookedRooms ?></p>
        </div>
        <div class="bg-white shadow rounded p-4">
            <h2 class="text-gray-700 font-semibold">Available Rooms</h2>
            <p class="text-2xl font-bold text-yellow-500"><?= $availableRooms ?></p>
        </div>
    </div>

    <!-- Chart -->
    <div class="bg-white shadow rounded p-6 flex justify-center items-center">
        <h2 class="text-xl font-semibold mb-4">Room Availability</h2>
        <!-- Container for the canvas -->
        <div class="w-65 h-66">
            <canvas id="roomChart"></canvas>
        </div>
    </div>


</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Render the availability chart
    const ctx = document.getElementById('roomChart').getContext('2d');
    const roomChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Available Rooms', 'Booked Rooms'],
            datasets: [{
                data: [<?= $availableRooms ?>, <?= $bookedRooms ?>],
                backgroundColor: ['#FBBF24', '#EF4444'],
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true, // Prevent resizing issues
            plugins: {
                legend: {
                    position: 'bottom',
                },
            },
        }
    });
</script>

<?php include_once 'footer.php'; ?>