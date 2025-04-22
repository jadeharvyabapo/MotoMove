<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "User not logged in."]);
    exit();
}

// Get the form data
$fullName = $_POST['fullName']; // This should be fetched from the session
$address = $_POST['address'];
$licenseNumber = $_POST['licenseNumber'];
$licenseCategory = $_POST['licenseCategory'];
$contactNumber = $_POST['contactNumber'];
$birthdate = $_POST['birthdate'];
$emergencyContactName = $_POST['emergencyContactName'];
$emergencyContactNumber = $_POST['emergencyContactNumber'];
$motorcycleBrand = $_POST['motorcycleBrand'];
$motorcycleModel = $_POST['motorcycleModel'];
$cylinderCapacity = $_POST['cylinderCapacity'];
$licenseExpiryDate = $_POST['licenseExpiryDate'];
$vehicleRegNumber = $_POST['vehicleRegNumber'];
$driversPhoto = $_FILES['driversPhoto']; // File upload data
$driversLicense = $_FILES['driversLicense'];
$vehicleReg = $_FILES['vehicleReg'];

// Database connection
$host = 'localhost';
$dbname = 'motorpoolingsystem_dbs';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Insert the driver data into the database
    $stmt = $pdo->prepare("INSERT INTO driver (user_id, full_name, address, license_number, license_category, contact_number, birthdate, emergency_contact_name, emergency_contact_number, motorcycle_brand, motorcycle_model, cylinder_capacity, license_expiry_date, vehicle_reg_number, drivers_photo, drivers_license, vehicle_reg) 
    VALUES (:user_id, :full_name, :address, :license_number, :license_category, :contact_number, :birthdate, :emergency_contact_name, :emergency_contact_number, :motorcycle_brand, :motorcycle_model, :cylinder_capacity, :license_expiry_date, :vehicle_reg_number, :drivers_photo, :drivers_license, :vehicle_reg)");

    $stmt->execute([
        ':user_id' => $_SESSION['user_id'], // User ID from session
        ':full_name' => $fullName,
        ':address' => $address,
        ':license_number' => $licenseNumber,
        ':license_category' => $licenseCategory,
        ':contact_number' => $contactNumber,
        ':birthdate' => $birthdate,
        ':emergency_contact_name' => $emergencyContactName,
        ':emergency_contact_number' => $emergencyContactNumber,
        ':motorcycle_brand' => $motorcycleBrand,
        ':motorcycle_model' => $motorcycleModel,
        ':cylinder_capacity' => $cylinderCapacity,
        ':license_expiry_date' => $licenseExpiryDate,
        ':vehicle_reg_number' => $vehicleRegNumber,
        ':drivers_photo' => $driversPhoto['name'], // Example of how to handle file uploads
        ':drivers_license' => $driversLicense['name'],
        ':vehicle_reg' => $vehicleReg['name']
    ])};

    // Handle file upload
