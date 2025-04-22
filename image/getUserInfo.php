<?php
session_start();

if (isset($_SESSION['user_fullname'])) {
    echo json_encode(['success' => true, 'fullname' => $_SESSION['user_fullname']]);
} else {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
}
?>
