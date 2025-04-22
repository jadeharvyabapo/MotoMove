<?php
// getDashboardStats.php

// Include your database connection details
$host = 'localhost';
$dbname = 'motorpoolingsystem_dbs'; 
$username = 'root'; 
$password = ''; 

header('Content-Type: application/json'); // Set the content type to JSON

try {
    // Create a PDO instance for DB connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $e->getMessage()]);
    exit();
}

// Initialize response array
$response = [
    'success' => false,
    'message' => '',
    'totalUsers' => 0,
    'driversInQueue' => 0,
    'rideRequests' => 0,
    'completedRides' => 0
];

try {
    // Fetch total users
    $query = "SELECT COUNT(*) as count FROM user";
    $stmt = $pdo->query($query);
    $response['totalUsers'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

    // Fetch drivers in queue
    $query = "SELECT COUNT(*) as count FROM driverque WHERE status = 'waiting'";
    $stmt = $pdo->query($query);
    $response['driversInQueue'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

    // Fetch ride requests
    $query = "SELECT COUNT(*) as count FROM riderbooking WHERE status = 'waiting'"; // Corrected comparison
    $stmt = $pdo->query($query);
    $response['rideRequests'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

    // Fetch completed rides
    $query = "SELECT COUNT(*) as count FROM riderbooking WHERE status = 'completed'";
    $stmt = $pdo->query($query);
    $response['completedRides'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

    $response['success'] = true;
} catch (Exception $e) {
    $response['message'] = 'Error fetching dashboard stats: ' . $e->getMessage();
}

// Send JSON response
echo json_encode($response);

// Close the database connection (PDO does not require explicit close)
?>
