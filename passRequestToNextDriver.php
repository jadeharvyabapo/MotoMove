<?php
// FILEPATH: /c:/xampp/htdocs/Motor-Cycle-Website-main/passRequestToNextDriver.php

// Database connection details
$host = 'localhost';
$dbname = 'motorpoolingsystem_dbs';
$username = 'root';
$password = '';

// Get the ride booking ID from the POST request
$ridebookingId = $_POST['ridebookingID'];

try {
    // Create a PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Start a transaction
    $pdo->beginTransaction();

    // Get the next available driver
    $stmt = $pdo->prepare("SELECT fk_userId FROM driverque WHERE status = 'waiting' ORDER BY dateandtime ASC LIMIT 1 OFFSET 1");
    $stmt->execute();
    $nextDriver = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($nextDriver) {
        $nextDriverId = $nextDriver['fk_userId'];

        // Update the ride request with the new driver
        $updateStmt = $pdo->prepare("UPDATE riderbooking SET fk_driverId = ?, status = 'pending' WHERE riderbookingID = ?");
        $updateStmt->execute([$nextDriverId, $ridebookingId]);


        // Commit the transaction
        $pdo->commit();

        echo json_encode(['success' => true, 'message' => 'Request passed to the next driver successfully']);
    } else {
        // No available drivers
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'No available drivers at the moment']);
    }
} catch (PDOException $e) {
    // An error occurred, rollback the transaction
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
