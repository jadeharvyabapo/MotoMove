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

// Handle login
if (isset($data->action) && $data->action === 'login') {
    // Validate data
    if (!isset($data->email) || !isset($data->password) || !isset($data->role)) {
        echo json_encode(['success' => false, 'message' => 'Please fill out all fields.']);
        exit();
    }

    

    // Prepare the SQL statement to prevent SQL injection
    $stmt = $pdo->prepare("SELECT * FROM user WHERE email = :email AND role = :role");
    $stmt->bindParam(':email', $data->email);
    $stmt->bindParam(':role', $data->role);

    try {
        // Execute the statement
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify password
        if ($user && password_verify($data->password, $user['password'])) {
            // Successful login

            // Start the session and set the user_id session variable
            session_start();
            $token = bin2hex(random_bytes(16)); // Generate a unique token
            $_SESSION[$token] = $user['userId'];

            echo json_encode([
                'success' => true,
                'message' => 'Login successful!',
                'user' => $user,
                'driverRegistrationStatus' => $user['DriverRegistrationStatus'] // Include this
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid email, password, or role.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}
?>
