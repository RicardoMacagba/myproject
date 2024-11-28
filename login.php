<?php
// Include database connection
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve posted username and password
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the username and password are not empty
    if (!empty($username) && !empty($password)) {
        try {
            // Prepare and execute the query to find the user by username
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Check if the password matches
                if (password_verify($password, $user['password'])) {
                    // Password is correct, login successful
                    echo json_encode(['success' => true, 'message' => 'Login successful']);
                } else {
                    // Password is incorrect
                    echo json_encode(['success' => false, 'message' => 'Invalid password']);
                }
            } else {
                // No user found with the given username
                echo json_encode(['success' => false, 'message' => 'User not found']);
            }
        } catch (PDOException $e) {
            // Handle any database errors
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Please fill in both fields']);
    }
}
?>
