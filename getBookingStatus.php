    <?php
    // Start the session if needed (especially if using session data)

    session_start();

    // Check if session ID is passed
    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['session_id'])) {
        echo json_encode(['success' => false, 'message' => 'Session ID is missing.']);
        exit();
    }

    $sessionId = $data['session_id'];  // Get session_id passed from the JS request
    $userId = $data['user_id'];  // Get the user ID passed in the request


    // Log the user_id to check its value
    error_log("User ID passed: " . $userId); // This will log the value of user_id



    // Database connection (update with your own database credentials)
    $host = 'localhost';
    $dbname = 'motorpoolingsystem_dbs'; // Replace with your database name
    $username = 'root'; // Replace with your actual database username
    $password = ''; // Replace with your actual database password

    // Create a new PDO connection to the database
    try {
        $conn = new mysqli($host, $username, $password, $dbname);
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
        exit;
    }



    // Assuming you're getting user ID from the session
    $userId = $data['user_id'];
      // Get the user ID passed in the request

    // Prepare your query
    $query = "
 
    SELECT 
        r.riderbookingID, 
        ur.fullname AS rider_fullname, 
        ud.fullname AS driver_fullname, 
        r.pickupPoint, 
        r.destination, 
        r.contactNumber, 
        r.bookingType, 
               r.timeofPickUp, 
        r.numberofPassenger, 
        r.timeArrived, 
        r.rideDuration, 
        r.status,
        r.fk_driverId
    FROM 
        riderbooking r
    JOIN 
        user ur ON r.fk_userId = ur.userId
    LEFT JOIN 
        user ud ON r.fk_driverId = ud.userId
    WHERE 
        r.fk_userId = ?
        order by r.riderbookingID desc limit 1";


    $stmt = $conn->prepare($query);


    $stmt->bind_param("i", $userId); // Correct the variable to match the input
    $stmt->execute();

    // Fetch result
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        // Fetching the data
        $data = $result->fetch_assoc();
        
        // Returning JSON response
        echo json_encode(["success" => true, "data" => $data]);
    } else {
        // If no booking found for the user
        echo json_encode(["success" => false, "message" => "No booking found"]);
    }

    // Close the statement and database connection
    $stmt->close();
    $conn->close();
    ?>
