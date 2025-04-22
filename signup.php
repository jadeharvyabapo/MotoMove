<?php
// Database connection (update with your own database credentials)
$host = 'localhost';
$dbname = 'motorpoolingsystem_dbs'; // Replace with your database name
$username = 'root'; // Replace with your actual database username
$password = ''; // Replace with your actual database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $e->getMessage()]);
    exit();
}

// Get the data from POST request
$data = json_decode(file_get_contents("php://input"));

// Validate data
if (!isset($data->fullname) || !isset($data->email) || !isset($data->password) || !isset($data->role)) {
    echo json_encode(['success' => false, 'message' => 'Please fill out all fields.']);
    exit();
}

// Prepare the SQL statement to prevent SQL injection
$stmt = $pdo->prepare("INSERT INTO  user (fullname, email, password, role) VALUES (:fullname, :email, :password, :role)");

// Hash the password for security
$hashedPassword = password_hash($data->password, PASSWORD_DEFAULT);

// Bind parameters
$stmt->bindParam(':fullname', $data->fullname);
$stmt->bindParam(':email', $data->email);
$stmt->bindParam(':password', $hashedPassword);
$stmt->bindParam(':role', $data->role);

try {
    // Execute the statement
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Signup successful!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'An error occurred during signup. Please try again.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}





?>