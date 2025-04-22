<?php
session_start();

// Check if session ID and user ID are passed
if (!isset($_POST['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User ID is missing.']);
    exit();
}

$userId = $_POST['user_id'];  // Get the user ID from the POST data

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection settings
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

// Decode driver and vehicle data from JSON
$driverData = json_decode($_POST['driverData'], true);
$vehicleData = json_decode($_POST['vehicleData'], true);

// Process uploaded driver and vehicle photos
$driverPhoto = $_FILES['driverPhoto'];
$driverLicensePhoto = $_FILES['driverLicensePhoto'];
$vehicleRegistrationPhoto = $_FILES['vehicleRegistrationPhoto'];

// Ensure uploads directory exists
$uploadsDir = 'uploads/';
if (!is_dir($uploadsDir)) {
    mkdir($uploadsDir, 0777, true);
}

// Function to handle file upload and sanitize file names
function uploadFile($file, $uploadsDir, $prefix) {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $fileName = uniqid($prefix . '_') . '.' . $fileExtension;
    $filePath = $uploadsDir . $fileName;

    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        return $filePath;
    }

    return null;
}

$driverPhotoPath = uploadFile($driverPhoto, $uploadsDir, 'driver');
$driverLicensePhotoPath = uploadFile($driverLicensePhoto, $uploadsDir, 'license');
$vehicleRegistrationPhotoPath = uploadFile($vehicleRegistrationPhoto, $uploadsDir, 'vehicle');

if (!$driverPhotoPath || !$driverLicensePhotoPath || !$vehicleRegistrationPhotoPath) {
    echo json_encode(['success' => false, 'message' => 'File upload failed.']);
    exit();
}

try {
    // Insert into vehicle table first
    $vehicleStmt = $pdo->prepare("INSERT INTO vehicle 
        (vehiclebrand, vehicleModel, cylindercapacity, vehiclePlateNumber, vehicleRegistrationPhoto)
        VALUES 
        (:brand, :model, :capacity, :plate, :registrationPhoto)");
    
    $vehicleStmt->bindParam(':brand', $vehicleData['vehicleBrand']);
    $vehicleStmt->bindParam(':model', $vehicleData['vehicleModel']);
    $vehicleStmt->bindParam(':capacity', $vehicleData['cylinderCapacity']);
    $vehicleStmt->bindParam(':plate', $vehicleData['vehiclePlateNumber']);
    $vehicleStmt->bindParam(':registrationPhoto', $vehicleRegistrationPhotoPath);
    $vehicleStmt->execute();

    $vehicleId = $pdo->lastInsertId();

    // Insert into driver table
    $driverStmt = $pdo->prepare("INSERT INTO driver 
        (fk_userId, fk_vehicleId, address, licenseNumber, contactNumber, birthdate, emergencyContactName, 
        emergencyContactNumber, licenseExpiryDate, driverphoto, driverlicensephoto, registrationStatus)
        VALUES 
        (:userId, :vehicleId, :address, :licenseNumber, :contactNumber, :birthdate, :emergencyContactName, 
        :emergencyContactNumber, :licenseExpiryDate, :driverPhoto, :driverLicensePhoto, 'pending')");

    $driverStmt->bindParam(':userId', $userId);
    $driverStmt->bindParam(':vehicleId', $vehicleId);
    $driverStmt->bindParam(':address', $driverData['address']);
    $driverStmt->bindParam(':licenseNumber', $driverData['licenseNumber']);
    $driverStmt->bindParam(':contactNumber', $driverData['contactNumber']);
    $driverStmt->bindParam(':birthdate', $driverData['birthdate']);
    $driverStmt->bindParam(':emergencyContactName', $driverData['emergencyContactName']);
    $driverStmt->bindParam(':emergencyContactNumber', $driverData['emergencyContactNumber']);
    $driverStmt->bindParam(':licenseExpiryDate', $driverData['licenseExpiryDate']);
    $driverStmt->bindParam(':driverPhoto', $driverPhotoPath);
    $driverStmt->bindParam(':driverLicensePhoto', $driverLicensePhotoPath);
    $driverStmt->execute();

    echo json_encode(['success' => true, 'message' => 'Driver application submitted successfully.']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}

?>
