<?php
// Database connection details
$host = 'localhost';
$dbname = 'motorpoolingsystem_dbs';
$username = 'root';
$password = '';

// Receive and decode the JSON data
$data = json_decode(file_get_contents('php://input'), true);

// Check if all required data is present
if (!isset($data['riderbookingID']) || !isset($data['timeArrived']) || !isset($data['rideDuration']) || !isset($data['driverId'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required data']);
    exit;
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Start a transaction
    $pdo->beginTransaction();

    // Update the riderbooking table
    $stmt = $pdo->prepare("UPDATE riderbooking SET 
                           status = 'completed', 
                           timeArrived = :timeArrived, 
                           rideDuration = :rideDuration 
                           WHERE riderbookingID = :riderbookingID");

    $stmt->bindParam(':timeArrived', $data['timeArrived']);
    $stmt->bindParam(':rideDuration', $data['rideDuration']);
    $stmt->bindParam(':riderbookingID', $data['riderbookingID']);
    $stmt->execute();

    // Update the driver's status to 'completed' in the driverqueue table
    $stmt = $pdo->prepare("UPDATE driverque SET status = 'completed' 
                           WHERE fk_userId = :driverId");
    $stmt->bindParam(':driverId', $data['driverId']);
    $stmt->execute();

    // Commit the transaction
    $pdo->commit();

    echo json_encode([
        'success' => true, 
        'message' => 'Ride and driver status updated successfully',
        'data' => [
            'riderbookingID' => $data['riderbookingID'],
            'timeArrived' => $data['timeArrived'],
            'rideDuration' => $data['rideDuration'],
            'driverId' => $data['driverId']
        ]
    ]);
} catch(PDOException $e) {
    // Rollback the transaction if an error occurred
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
