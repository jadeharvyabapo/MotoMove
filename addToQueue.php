<?php
// addDriverQueue.php

// Start the session (make sure session_start() is called at the beginning of your file)
session_start();

// Check if session ID is passed
$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['session_id'])) {
    echo json_encode(['success' => false, 'message' => 'Session ID is missing.']);
    exit();
}

$sessionId = $data['session_id'];  // Get session_id passed from the JS request
$userId = $data['user_id'];  // Get the user ID from the session



// Your database connection details
$host = 'localhost';
$dbname = 'motorpoolingsystem_dbs'; // Replace with your database name
$username = 'root'; // Replace with your actual database username
$password = ''; // Replace with your actual database password

try {
    // Create a PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Ensure the user is logged in by checking the session for userId
  


    // Check if the driver is already in the queue with "waiting" or "driving" status
    $stmt = $pdo->prepare("SELECT * FROM driverque WHERE fk_userId = :userId AND status IN ('waiting', 'driving')");
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Driver is already in the queue with either 'waiting' or 'driving' status
        echo json_encode(['success' => false, 'message' => 'You are already in the queue or in booked']);
        exit;
    }

    // Get orderofdriver (auto-increment based on the current number of available drivers in the queue)
    $stmt = $pdo->prepare("SELECT COUNT(*) AS count FROM driverque WHERE status = 'waiting'");
    $stmt->execute();
    $driverCount = $stmt->fetch(PDO::FETCH_ASSOC);
    $orderofdriver = $driverCount['count'] + 1; // Set the order to the next number in the queue

    // Get the current date and time
    $dateandtime = date('Y-m-d H:i:s');
    $status = 'waiting'; // Set the default status for the driver

    // Prepare the SQL statement to insert the driver into the queue
    $stmt = $pdo->prepare("INSERT INTO driverque (fk_userId , dateandtime, status) 
                           VALUES (:userId, :dateandtime, :status)");

    // Bind parameters
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':dateandtime', $dateandtime);
    $stmt->bindParam(':status', $status);

    // Execute the query to insert the driver into the queue
    $stmt->execute();

    // Return success response
    echo json_encode(['success' => true, 'message' => 'Driver added to the queue with order ' . $orderofdriver]);

} catch (PDOException $e) {
    // Handle any errors (e.g., connection issues or insert failures)
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
