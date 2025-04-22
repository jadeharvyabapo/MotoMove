<?php
// Start the session
session_start();

// Database connection details
$host = 'localhost';
$dbname = 'motorpoolingsystem_dbs';  // Replace with your database name
$username = 'root';  // Replace with your database username
$password = '';  // Replace with your database password

try {
    // Create a PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch the user_id from the POST request
    $userId = isset($_POST['user_id']) ? $_POST['user_id'] : null;

    if ($userId) {
        // Fetch the status of the driver using fk_userId from the driver table
        $stmt = $pdo->prepare("SELECT registrationStatus FROM driver WHERE fk_userId = :user_id");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        // Fetch the result
        $status = $stmt->fetchColumn();

        if ($status) {
            // Return success response with the status
            echo json_encode([
                'success' => true,
                'status' => $status
            ]);
        } else {
            // If the driver is not found
            echo json_encode([
                'success' => false,
                'message' => 'Driver not found or no status available.'
            ]);
        }
    } else {
        // If no user_id is passed
        echo json_encode([
            'success' => false,
            'message' => 'User ID is required.'
        ]);
    }
} catch (PDOException $e) {
    // Handle any database connection errors
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
