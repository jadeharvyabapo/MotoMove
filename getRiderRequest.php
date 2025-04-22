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
    $stmt = $pdo->prepare("
SELECT rb.riderbookingID, rb.pickupPoint, rb.destination, rb.contactNumber, 
       rb.bookingType, rb.timeofPickUp, rb.numberofPassenger, u.fullname AS passenger_name, rb.status
FROM riderbooking rb
JOIN user u ON rb.fk_userId = u.userId
WHERE rb.status = 'waiting'
ORDER BY CASE 
            WHEN rb.status = 'waiting' AND EXISTS (
              SELECT 1 FROM riderbooking rb2 WHERE rb2.fk_userId = rb.fk_userId AND rb2.status = 'rebooked'
            ) THEN 0 
            ELSE 1 
         END, rb.riderbookingID;

");
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
