<?php

session_start();

// Check if session ID is passed
$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['session_id'])) {
    echo json_encode(['success' => false, 'message' => 'Session ID is missing.']);
    exit();
}

$sessionId = $data['session_id'];  // Get session_id passed from the JS request
$userId = $data['user_id'];  // Get the user ID from the session


// Database connection (update with your own database credentials)
$host = 'localhost';
$dbname = 'motorpoolingsystem_dbs'; // Replace with your database name
$username = 'root'; // Replace with your actual database username
$password = ''; // Replace with your actual database password

try {
    // Create the PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Debug: Check the session ID being passed

    // Assuming that you have a session variable that stores the user ID:
   

    // Fetch user info based on session_id
    // Make sure the column session_id exists in your database
    $stmt = $pdo->prepare("SELECT userId, fullname, email FROM user WHERE userId = :userId");
    $stmt->bindParam(':userId', $userId);
    $stmt->execute();

    // Fetch the user data
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo json_encode(['success' => true, 'message' => 'User info fetched successfully.', 'user' => $user]);
    } else {
        echo json_encode(['success' => false, 'message' => 'User not found.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $e->getMessage()]);
}

?>
