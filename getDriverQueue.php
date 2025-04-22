

    <?php
    // Start the session
    session_start();

    // Check if session ID and user ID are passed
    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['session_id']) || !isset($data['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Session ID or User ID is missing.']);
        exit();
    }
    
    $sessionId = $data['session_id'];  // Get session_id passed from the JS request
    $userId = $data['user_id'];        // Get the user ID passed in the request
    
    

    // Database connection details
    $host = 'localhost';
    $dbname = 'motorpoolingsystem_dbs'; // Replace with your database name
    $username = 'root'; // Replace with your database username
    $password = ''; // Replace with your database password

    try {
        // Create a PDO instance
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Fetch the driver queue data from the driverque table
        $stmt = $pdo->prepare("SELECT u.fullname AS driver_name, dq.fk_userId, dq.dateandtime, dq.status 
        FROM driverque dq 
        JOIN user u ON dq.fk_userId = u.userId
        WHERE dq.status = 'waiting'
        ORDER BY dq.dateandtime ASC");

    $stmt->execute();
        
        // Fetch all driver queue data
        $driverQueue = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Prepare response data
        $response = [
            'success' => true,
            'queue' => $driverQueue
        ];
        
        // Send the response as JSON
        echo json_encode($response);

    } catch (PDOException $e) {
        // Handle any errors (e.g., database connection issues)
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    ?>
