<?php
header('Content-Type: application/json');

// Get the input data (JSON)
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Extract necessary data from the input with default values
$riderBookingID = $data['riderBookingID'] ?? null;
$rating = $data['rating'] ?? null;
$feedback = $data['feedback'] ?? null;
$riderId = $data['riderId'] ?? null;
$driverId = $data['driverId'] ?? null;

// Validate input
if ($riderBookingID === null || $riderId === null || $driverId === null) {
    echo json_encode(['success' => false, 'message' => 'Missing required data']);
    exit;
}

// Database connection details
$host = 'localhost';
$dbname = 'motorpoolingsystem_dbs';
$username = 'root';
$password = '';

try {
    // Create a PDO instance for database connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Start a transaction
    $pdo->beginTransaction();

    // Check if the rider booking ID exists in the riderbooking table
    $checkBookingStmt = $pdo->prepare("SELECT COUNT(*) FROM riderbooking WHERE riderbookingID = :riderBookingID");
    $checkBookingStmt->bindParam(':riderBookingID', $riderBookingID);
    $checkBookingStmt->execute();

    if ($checkBookingStmt->fetchColumn() == 0) {
        throw new Exception('Invalid Rider Booking ID');
    }

    // Check if feedback already exists for the given riderBookingID
    $checkFeedbackStmt = $pdo->prepare("SELECT COUNT(*) FROM riderfeedback WHERE fk_riderbookingId = :riderBookingID");
    $checkFeedbackStmt->bindParam(':riderBookingID', $riderBookingID);
    $checkFeedbackStmt->execute();

    if ($checkFeedbackStmt->fetchColumn() > 0) {
        // Feedback exists, perform an update
        $stmt = $pdo->prepare("UPDATE riderfeedback SET rate = :rating, comment = :feedback WHERE fk_riderbookingId = :riderBookingID");
    } else {
        // No feedback exists, perform an insert
        $stmt = $pdo->prepare("INSERT INTO riderfeedback (fk_riderbookingId, fk_riderId, fk_driverId, rate, comment) VALUES (:riderBookingID, :riderId, :driverId, :rating, :feedback)");
        $stmt->bindParam(':riderId', $riderId);
        $stmt->bindParam(':driverId', $driverId);
    }

    $stmt->bindParam(':riderBookingID', $riderBookingID);
    $stmt->bindParam(':rating', $rating);
    $stmt->bindParam(':feedback', $feedback);

    $stmt->execute();

    // Commit the transaction
    $pdo->commit();

    echo json_encode(['success' => true, 'message' => 'Feedback recorded successfully']);

} catch (Exception $e) {
    // An error occurred, rollback the transaction
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
