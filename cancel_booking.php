<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized");
}

include 'includes/config.php';

$booking_id = mysqli_real_escape_string($conn, $_POST['booking_id']);
$user_id = $_SESSION['user_id'];

// Check if booking belongs to user and status is pending
$check_query = "SELECT * FROM bookings WHERE booking_id = $booking_id AND user_id = $user_id AND status = 'pending'";
$check_result = $conn->query($check_query);

if ($check_result->num_rows > 0) {
    $update_query = "UPDATE bookings SET status = 'cancelled' WHERE booking_id = $booking_id";
    
    if ($conn->query($update_query)) {
        echo "Booking cancelled successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "Cannot cancel this booking!";
}

$conn->close();
?>