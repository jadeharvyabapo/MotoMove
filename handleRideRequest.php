    <?php

    session_start();

    $data = json_decode(file_get_contents('php://input'), true);


    // Check if session ID is passed
    if (!isset($_POST['session_id'])) {
        echo json_encode(['success' => false, 'message' => 'Session ID is missing.']);
        exit();
    }

    $sessionId = $_POST['session_id'];  // Get session_id passed from the JS request
    $userId = $_POST['user_id'];   






    // Database connection
    $host = 'localhost';
    $dbname = 'motorpoolingsystem_dbs';
    $username = 'root';
    $password = '';

    try {
        // Create a PDO connection
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $e->getMessage()]);
        exit();
    }

    error_log(print_r($_POST, true));  // Log POST data to the PHP error log

    // Handle the action (accept/decline)
    $action = $_POST['action'];
    $ridebookingId = $_POST['ridebookingID'];

    // Get the driver's ID from the session
    $driverId = $userId;  // Assuming the user ID is stored in session

    // Step 1: Get the first driver in the queue
    $stmt = $pdo->prepare("SELECT fk_userId FROM driverque WHERE status = 'waiting' ORDER BY dateandtime ASC LIMIT 1");
    $stmt->execute();
    $firstDriver = $stmt->fetch(PDO::FETCH_ASSOC);

    // Step 2: Check if the logged-in driver is the first in the queue
    if ($firstDriver && $firstDriver['fk_userId'] == $driverId) {
        // Step 3: Handle the ride request (accept/decline)
        if ($action == 'accept') {
            // Update the driver's status to 'accepted' in the queue
      // Update the driver's status to 'driving'
$stmt = $pdo->prepare("UPDATE driverque SET status = 'driving' WHERE fk_userId = :driverId");
$stmt->bindParam(':driverId', $driverId);
$stmt->execute();

// Update the rider booking's status and associate the driver
$stmt = $pdo->prepare("UPDATE riderbooking SET status = 'accepted', fk_driverId = :driverId WHERE riderbookingID = :ridebookingId");
$stmt->bindParam(':driverId', $driverId);
$stmt->bindParam(':ridebookingId', $ridebookingId);
$stmt->execute();

        

            echo json_encode([
                'success' => true,
                'message' => 'Ride accepted, driver status updated to "accepted".',
                'driverId' => $driverId  // Add driverId to the response
            ]);

        } elseif ($action == 'decline') {
            // Update the driver's status to 'declined' in the queue
            $stmt = $pdo->prepare("UPDATE driverque SET status = 'waiting' WHERE fk_userId = :driverId");
            $stmt->bindParam(':driverId', $driverId);
            $stmt->execute();

            $stmt = $pdo->prepare("UPDATE riderbooking SET status = 'declined' WHERE riderbookingID = :ridebookingId");
            $stmt->bindParam(':ridebookingId', $ridebookingId);
            $stmt->execute();

            echo json_encode(['success' => true, 'message' => 'Ride declined, driver status updated to "declined".']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'You are not the first driver in the queue or you have booked. Please wait for your turn.']);
    }
    ?>
