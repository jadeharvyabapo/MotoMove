<?php
// Start the session
session_start();

// Database connection details
$host = 'localhost';
$dbname = 'motorpoolingsystem_dbs';  // Replace with your database name
$username = 'root';  // Replace with your database username
$password = '';  // Replace with your database password

try {
    // Create a PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Log for debugging (optional)
    error_log("Database connection established.");

    // Fetch the driver ID and status from the POST request (JSON body)
    $data = json_decode(file_get_contents('php://input'), true);

    // Get the driver ID and status from the JSON body
    $driverId = isset($data['driver_id']) ? $data['driver_id'] : null;
    $status = isset($data['status']) ? $data['status'] : null;

    if ($driverId && $status) {
        // Update the application status of the driver using fk_userId instead of driver_id
        $stmt = $pdo->prepare("UPDATE driver SET registrationStatus = :status WHERE fk_userId = :driver_id");

        // Bind the parameters
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':driver_id', $driverId, PDO::PARAM_INT);

        // Execute the query
        $stmt->execute();

        // Return success response
        echo json_encode([
            'success' => true,
            'message' => 'Driver status updated successfully.'
        ]);
    } else {
        // If driver_id or status is missing in the request
        echo json_encode([
            'success' => false,
            'message' => 'Driver ID and status are required.'
        ]);
    }
} catch (PDOException $e) {
    // Handle any database connection errors
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
