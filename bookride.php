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

error_log("userid",$userId);


// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection (update with your own database credentials)
$host = 'localhost';
$dbname = 'motorpoolingsystem_dbs'; // Replace with your database name
$username = 'root'; // Replace with your actual database username
$password = ''; // Replace with your actual database password

header('Content-Type: application/json'); // Set the content type to JSON

try {
    // Create a PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $e->getMessage()]);
    exit();
}

// Start the session to access session variables




$stmt = $pdo->prepare("SELECT status FROM riderbooking WHERE fk_userId = :userId AND status != 'completed' AND status != 'cancelled' AND status != 'declined' AND status != 'rebooked'");
$stmt->bindParam(':userId', $userId);
$stmt->execute();
$riderStatus = $stmt->fetchColumn();


if ($riderStatus) { 
    // Rider is already in the queue and their status is not 'completed'
    echo json_encode(["success" => false, "message" => "You cannot book a new ride until your current booking is completed."]);
    exit();
}




// Check if the data is valid and the values are non-empty
if (
    !empty($data['bookingData']['pickupLocation']) &&
    !empty($data['bookingData']['dropoffLocation']) &&
    !empty($data['bookingData']['contactNumber']) &&
    !empty($data['bookingData']['pickupTime']) &&
    !empty($data['bookingData']['passengerCount'])

) {
    // Prepare the SQL query
    $stmt = $pdo->prepare("
        INSERT INTO riderbooking 
        (fk_userId, pickupPoint, destination, contactNumber, bookingType, timeofPickUp, numberofPassenger, status)
        VALUES 
        (:userId, :pickupLocation, :dropoffLocation, :contactNumber, :bookingType, :pickupTime, :passengerCount,'waiting')
    ");

    // Bind parameters
    $stmt->bindParam(':userId', $userId); // Get the user ID from the session
    $stmt->bindParam(':pickupLocation', $data['bookingData']['pickupLocation']);
    $stmt->bindParam(':dropoffLocation', $data['bookingData']['dropoffLocation']);
    $stmt->bindParam(':contactNumber', $data['bookingData']['contactNumber']);
    $stmt->bindParam(':bookingType', $data['bookingData']['bookingType']);
    $stmt->bindParam(':pickupTime', $data['bookingData']['pickupTime']);
    
    // Ensure passengerCount is an integer
    $passengerCount = (int)$data['bookingData']['passengerCount'];
    $stmt->bindParam(':passengerCount', $passengerCount, PDO::PARAM_INT);

    // Execute the query
    if ($stmt->execute()) {
        // Success response
        echo json_encode(["success" => true, "message" => "Booking successful!"]);
    } else {
        // Error response
        echo json_encode(["success" => false, "message" => "Error: Could not execute query."]);
    }
} else {
    // Invalid data response
    echo json_encode(["success" => false, "message" => "Invalid input data."  ]);
}