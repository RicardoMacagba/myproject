

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
    .chart-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    canvas {
        display: block;
    }
</style>


</head>
<body class="bg-gray-100">

    <header class="bg-blue-500 text-white shadow">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold">Hostel Management</h1>
            <nav>
                <ul class="flex space-x-4">
                    <li><a href="index.php" class="hover:underline">Dashboard</a></li>
                    <li><a href="add_rooms.php" class="hover:underline">Add Room</a></li>
                    <li><a href="manage_room.php" class="hover:underline">Manage Rooms</a></li>
                    <li><a href="manage_user.php" class="hover:underline">Users</a></li>
                    <li><a href="logout.php" class="hover:underline">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>