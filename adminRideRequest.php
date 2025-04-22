<?php
// getRiderBookings.php

// Database connection details
$host = 'localhost';
$dbname = 'motorpoolingsystem_dbs';
$username = 'root';
$password = '';

try {
    // Create a PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query to get all rider bookings
    $stmt = $pdo->prepare("SELECT rb.riderbookingID, rb.pickupPoint, rb.destination, rb.contactNumber, 
                                  rb.bookingType, rb.timeofPickUp, rb.numberofPassenger, u.fullname AS passenger_name , rb.status
                           FROM riderbooking rb
                           JOIN user u ON rb.fk_userId = u.userId");  // Assuming the users table has userId and name
    $stmt->execute();

    // Fetch the results
    $rideRequests = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the data as JSON
    echo json_encode(['success' => true, 'rideRequests' => $rideRequests]);
} catch (PDOException $e) {
    // Handle any errors
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
