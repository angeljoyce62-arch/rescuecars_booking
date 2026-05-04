<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'captain') {
    die("Unauthorized");
}

include 'includes/config.php';

$car_id = mysqli_real_escape_string($conn, $_POST['car_id']);
$status = mysqli_real_escape_string($conn, $_POST['status']);
$barangay_id = $_SESSION['barangay_id'];

// Verify car belongs to captain's barangay
$check_query = "SELECT * FROM rescue_cars WHERE car_id = $car_id AND barangay_id = $barangay_id";
$check_result = $conn->query($check_query);

if ($check_result->num_rows > 0) {
    $update_query = "UPDATE rescue_cars SET status = '$status' WHERE car_id = $car_id";
    
    if ($conn->query($update_query)) {
        echo "Car status updated successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "Unauthorized!";
}

$conn->close();
?>