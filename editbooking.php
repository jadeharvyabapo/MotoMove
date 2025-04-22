<?php
// Start the session
session_start();

// Database connection details
$host = 'localhost';
$dbname = 'motorpoolingsystem_dbs';  // Your database name
$username = 'root';  // Your database username (default is 'root' for XAMPP)
$password = '';  // Your database password (empty by default for XAMPP)

// Check if session ID and user ID are passed
$data = json_decode(file_get_contents('php://input'), true);

file_put_contents('php://stdout', "Received data: " . print_r($data, true) . "\n");



// Validate input
if (!isset($data['session_id']) || !isset($data['user_id']) || !isset($data['booking_id'])) {
    echo json_encode(['success' => false, 'message' => 'Session ID, User ID or Booking ID is missing.']);
    exit();
}

$sessionId = $data['session_id'];  // Get session_id passed from the JS request
$userId = $data['user_id'];        // Get the user ID passed in the request
$bookingId = $data['booking_id'];  // Get the booking ID passed in the request


$debugInfo = [
    "receivedBookingId" => $bookingId,
    "receivedUserId" => $userId
];

try {
    // Create a PDO instance for database connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    

    // Step 1: Fetch the current booking details to ensure it's editable
    $stmt = $pdo->prepare("SELECT * FROM riderbooking WHERE riderbookingID = :bookingId AND fk_userId = :userId order by riderbookingID desc limit 1");
    $stmt->bindParam(':bookingId', $bookingId, PDO::PARAM_INT);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();
    
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$booking) {
        // No booking found for the provided booking ID and user ID
        echo json_encode(['success' => false, 'message' => 'Booking not found or you are not authorized to edit this booking.']);
        exit();
    }

    // Step 2: Update the booking details based on the data passed from JavaScript
    $updatedData = [
        'pickupPoint' => $data['pickupPoint'],
        'destination' => $data['destination'],
        'contactNumber' => $data['contactNumber'],
        'bookingType' => $data['bookingType'],
        'timeofPickUp' => $data['timeofPickUp'],
        'numberofPassenger' => $data['numberofPassenger']
    ];

    // Step 3: Prepare the update query
    $setClause = [];
    foreach ($updatedData as $column => $value) {
        $setClause[] = "$column = :$column";
    }
    $setClauseString = implode(", ", $setClause);
    
    $updateQuery = "UPDATE riderbooking SET $setClauseString WHERE riderbookingID = :bookingId AND fk_userId = :userId";
    
    // Prepare the statement
    $stmt = $pdo->prepare($updateQuery);

    // Bind the parameters
    foreach ($updatedData as $column => $value) {
        $stmt->bindValue(":$column", $value);
    }
    $stmt->bindParam(':bookingId', $bookingId, PDO::PARAM_INT);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);

    // Execute the update query
    if ($stmt->execute()) {
        // After successful update, fetch the updated booking details
        $stmt = $pdo->prepare("SELECT * FROM riderbooking WHERE riderbookingID = :bookingId AND fk_userId = :userId");
        $stmt->bindParam(':bookingId', $bookingId, PDO::PARAM_INT);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();

        // Fetch the updated booking data
        $updatedBooking = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if the updated data was fetched successfully
        if ($updatedBooking) {
            // Return the updated booking data in the response
            echo json_encode([
                'success' => true,
                'message' => 'Booking updated successfully.',
                'updatedBooking' => $updatedBooking // Send the updated data
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to fetch updated booking data.']);
        }
    } else {
        // If update failed, return a failure response
        echo json_encode(['success' => false, 'message' => 'Failed to update booking.']);
    }
} catch (PDOException $e) {
    // Handle any database errors
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
