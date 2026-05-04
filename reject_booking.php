<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'captain') {
    die("Unauthorized");
}

include 'includes/config.php';

$booking_id = mysqli_real_escape_string($conn, $_POST['booking_id']);
$user_id = $_SESSION['user_id'];

// Update booking status
$update_query = "UPDATE bookings SET status = 'rejected', approved_by = $user_id, approval_date = NOW() 
                WHERE booking_id = $booking_id";

if ($conn->query($update_query)) {
    echo "Booking rejected successfully!";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>