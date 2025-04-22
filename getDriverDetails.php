<?php
// Start the session
session_start();

// Read the incoming JSON request
$data = json_decode(file_get_contents('php://input'), true);

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

    // Fetch all driver and vehicle details
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
            v.vehiclePlateNumber AS vehicle_registration_number,  -- Assuming you have 'vehiclePlateNumber' column in vehicle table
            v.vehicleRegistrationPhoto AS vehicle_registration_photo  -- Assuming you have 'vehicleRegistrationPhoto' column in vehicle table
        FROM driver d
LEFT JOIN vehicle v ON d.fk_vehicleId = v.vehicle_id
        LEFT JOIN user u ON d.fk_userId = u.userId  
  where d.registrationStatus = 'pending'
    ");

    $stmt->execute();
    
    // Fetch all driver and vehicle data
    $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Log the fetched data
    error_log(print_r($applications, true));  // Log the fetched data

    // Prepare response data
    if (empty($applications)) {
        $response = [
            'success' => false,
            'message' => 'No driver data found.'
        ];
    } else {
        $response = [
            'success' => true,
            'applications' => $applications
        ];
    }

    // Send the response as JSON
    echo json_encode($response);

} catch (PDOException $e) {
    // Handle any errors (e.g., database connection issues)
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
