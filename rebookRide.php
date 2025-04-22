<?php
header('Content-Type: application/json');

// Database connection details
$host = 'localhost';
$dbname = 'motorpoolingsystem_dbs';
$username = 'root';
$password = '';

// Get the input data (JSON)
$json = file_get_contents('php://input');
$data = json_decode($json, true);

$riderbookingID = $data['riderbookingID'] ?? null;

if (!$riderbookingID) {
    echo json_encode(['success' => false, 'message' => 'Booking ID is missing.']);
    exit();
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Start transaction
    $pdo->beginTransaction();

    // Get the details of the declined booking
   // Get the details of the original booking
$getBookingQuery = "SELECT fk_userId, pickupPoint, destination, contactNumber, bookingType, timeofPickUp, numberofPassenger FROM riderbooking WHERE riderbookingID = :riderbookingID";
$getBookingStmt = $pdo->prepare($getBookingQuery);
$getBookingStmt->bindParam(':riderbookingID', $riderbookingID, PDO::PARAM_INT);
$getBookingStmt->execute();
$bookingDetails = $getBookingStmt->fetch(PDO::FETCH_ASSOC);

if (!$bookingDetails) {
    throw new Exception("Original booking not found.");
}

// Prepare the data for the new booking
$newBookingData = [
    'userId' => $bookingDetails['fk_userId'],
    'pickupPoint' => $bookingDetails['pickupPoint'],
    'destination' => $bookingDetails['destination'],
    'contactNumber' => $bookingDetails['contactNumber'],
    'bookingType' => $bookingDetails['bookingType'],
    'timeofPickUp' => $bookingDetails['timeofPickUp'],
    'numberofPassenger' => $bookingDetails['numberofPassenger']
];

// Insert a new booking with the same details
$insertQuery = "INSERT INTO riderbooking (fk_userId, pickupPoint, destination, contactNumber, bookingType, timeofPickUp, numberofPassenger, status) 
                VALUES (:userId, :pickupPoint, :destination, :contactNumber, :bookingType, :timeofPickUp, :numberofPassenger, 'waiting')";
$insertStmt = $pdo->prepare($insertQuery);
$insertStmt->execute($newBookingData);


    $newBookingId = $pdo->lastInsertId();

    // Update the status of the old booking to 'rebooked'
    $updateQuery = "UPDATE riderbooking SET status = 'rebooked' WHERE riderbookingID = :riderbookingID";
    $updateStmt = $pdo->prepare($updateQuery);
    $updateStmt->bindParam(':riderbookingID', $riderbookingID, PDO::PARAM_INT);
    $updateStmt->execute();

    $pdo->commit();
    echo json_encode(['success' => true, 'message' => 'Ride rebooked successfully.', 'newBookingId' => $newBookingId]);

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
