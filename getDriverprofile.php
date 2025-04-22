<?php
// Start the session
session_start();

// Retrieve the user_id from the query string
$userId = $_GET['user_id'] ?? null;

if (!$userId) {
    echo json_encode(['success' => false, 'message' => 'No user ID provided']);
    exit;
}

// Database connection details
$host = 'localhost';
$dbname = 'motorpoolingsystem_dbs'; // Replace with your database name
$username = 'root'; // Replace with your database username
$password = ''; // Replace with your database password

try {
    // Create a PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Log for debugging
    error_log("Database connection established.");

    // Prepare the SQL query
    $stmt = $pdo->prepare("
        SELECT 
            d.fk_userId AS driver_id, 
            u.fullname AS driver_name, 
            d.address,  
            d.licenseNumber AS license_number,
            d.contactNumber AS contact_number, 
            d.birthdate, 
            d.licenseExpiryDate AS license_expiry_date,
            d.driverphoto, 
            d.driverlicensephoto,
            v.vehiclePlateNumber AS vehicle_registration_number,  
            v.vehicleRegistrationPhoto AS vehicle_registration_photo
        FROM driver d
        LEFT JOIN vehicle v ON d.fk_vehicleId = v.vehicle_id
        LEFT JOIN user u ON d.fk_userId = u.userId  
        WHERE d.fk_userId = :userId
    ");

    // Bind the $userId variable to the query
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);

    // Execute the query
    $stmt->execute();

    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if data was returned
    if ($result) {
        // Return the result as a JSON response
        echo json_encode([
            'success' => true,
            'driverName' => $result['driver_name'],
            'driverphoto' => $result['driverphoto'], // Can be null or a file name
            'vehicleRegistrationNumber' => $result['vehicle_registration_number'],
            'vehicleRegistrationPhoto' => $result['vehicle_registration_photo'],
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No driver found for this user']);
    }

} catch (PDOException $e) {
    // Handle the error and log it
    error_log("Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error connecting to the database or fetching data']);
}
?>
