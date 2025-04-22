<?php
// getRiderBookings.php

session_start();

// Check if session ID and user ID are passed
$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['session_id']) || !isset($data['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Session ID or User ID is missing.']);
    exit();
}

$sessionId = $data['session_id'];  // Get session_id passed from the JS request
$userId = $data['user_id'];        // Get the user ID passed in the request
$riderbookingId = $data['riderbookingID'];




// Log the user_id to check its value
error_log("User ID passed: " . $userId); 
error_log("riderbooking passed: " . $riderbookingId); // This will log the value of user_id

// Database connection details
$host = 'localhost';
$dbname = 'motorpoolingsystem_dbs';
$username = 'root';
$password = '';

try {
    // Create a PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query to get the accepted ride booking for the driver
    $stmt = $pdo->prepare("
    SELECT rb.riderbookingID, 
           rb.pickupPoint, 
           rb.destination, 
           rb.contactNumber, 
           rb.bookingType, 
           rb.timeofPickUp, 
           rb.numberofPassenger, 
           u.fullname AS passenger_name, 
           rb.status
    FROM riderbooking rb
    JOIN user u ON rb.fk_userId = u.userId
    WHERE rb.status = 'accepted' 
    AND rb.riderbookingID = :riderbookingId  -- Use a placeholder for riderbookingID
");

// Bind the parameters securely
$stmt->bindParam(':riderbookingId', $riderbookingId, PDO::PARAM_INT);  // Bind riderbookingId
$stmt->execute();

    // Fetch the results
    $rideRequests = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the data as JSON
    echo json_encode(['success' => true, 'rideRequests' => $rideRequests, ]);
} catch (PDOException $e) {
    // Handle any errors
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
