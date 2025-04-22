<?php

session_start();

$data = json_decode(file_get_contents('php://input'), true);


// Check if session ID is passed
if (!isset($_POST['session_id'])) {
    echo json_encode(['success' => false, 'message' => 'Session ID is missing.']);
    exit();
}

$sessionId = $_POST['session_id'];  // Get session_id passed from the JS request
$userId = $_POST['user_id'];   






// Database connection
$host = 'localhost';
$dbname = 'motorpoolingsystem_dbs';
$username = 'root';
$password = '';

try {
    // Create a PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $e->getMessage()]);
    exit();
}


$stmt = $pdo->prepare("UPDATE riderbooking SET status = 'verified' WHERE riderbookingID = :ridebookingID AND riderId = :riderId");
$stmt->bindParam(':ridebookingID', $ridebookingID);
$stmt->bindParam(':riderId', $riderId);
$stmt->execute();

echo json_encode(['success' => true, 'message' => 'Ride verified and completed.']);
} catch (PDOException $e) {
echo json_encode(['success' => false, 'message' => 'Error verifying the ride: ' . $e->getMessage()]);
}

?>

