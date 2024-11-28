<?php
require_once 'db.php';
$stmt = $pdo->query("SELECT bookings.*, rooms.room_type FROM bookings JOIN rooms ON bookings.room_id = rooms.room_id");
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<table>
    <thead>
        <tr>
            <th>Booking ID</th>
            <th>Room Type</th>
            <th>User Name</th>
            <th>User Email</th>
            <th>Booked At</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($bookings as $booking): ?>
        <tr>
            <td><?= htmlspecialchars($booking['booking_id']) ?></td>
            <td><?= htmlspecialchars($booking['room_type']) ?></td>
            <td><?= htmlspecialchars($booking['user_name']) ?></td>
            <td><?= htmlspecialchars($booking['user_email']) ?></td>
            <td><?= htmlspecialchars($booking['created_at']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
