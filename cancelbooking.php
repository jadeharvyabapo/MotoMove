<?php
header('Content-Type: application/json');

// Database connection
$host = 'localhost';
$dbname = 'motorpoolingsystem_dbs';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get the data from the POST request
    $data = json_decode(file_get_contents('php://input'), true);
    $bookingId = $data['bookingId'];
    $userId = $data['userId'];

    // Debugging: Check received data
    error_log("Received bookingId: $bookingId, userId: $userId");

    // Update the booking status to 'cancelled'
    $stmt = $pdo->prepare("UPDATE riderbooking SET status = 'cancelled' WHERE riderbookingID = :bookingId AND fk_userId = :userId order by riderbookingID desc limit 1" );
    $stmt->bindParam(':bookingId', $bookingId, PDO::PARAM_INT);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Booking cancelled successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to cancel booking']);
    }
} catch (PDOException $e) {
    error_log("Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
