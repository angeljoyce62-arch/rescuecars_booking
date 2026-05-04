<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

include 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $car_id = isset($_POST['car_id']) ? mysqli_real_escape_string($conn, $_POST['car_id']) : '';
    $booking_date = mysqli_real_escape_string($conn, $_POST['booking_date']);
    $start_time = mysqli_real_escape_string($conn, $_POST['start_time']);
    $end_time = mysqli_real_escape_string($conn, $_POST['end_time']);
    $purpose = mysqli_real_escape_string($conn, $_POST['purpose']);
    // Force booking_type to 'advance' only
    $booking_type = 'advance';
    
    // Validate car_id
    if (empty($car_id)) {
        die("Please select a car!");
    }
    
    // Validate booking date is not in the past
    if (strtotime($booking_date) < strtotime(date('Y-m-d'))) {
        die("Booking date cannot be in the past!");
    }
    
    // Validate times
    if ($start_time >= $end_time) {
        die("Start time must be before end time!");
    }
    
    // Check if car is available at that time
    $check_query = "SELECT * FROM bookings WHERE car_id = '$car_id' AND booking_date = '$booking_date' AND status IN ('pending', 'approved') AND start_time < '$end_time' AND end_time > '$start_time'";
    
    $check_result = $conn->query($check_query);
    
    if (!$check_result) {
        die("Database error: " . $conn->error);
    }
    
    if ($check_result->num_rows > 0) {
        die("This car is not available at the selected time!");
    }
    
    // Insert booking
    $insert_query = "INSERT INTO bookings (user_id, car_id, booking_date, start_time, end_time, purpose, booking_type, status)
                    VALUES ('$user_id', '$car_id', '$booking_date', '$start_time', '$end_time', '$purpose', '$booking_type', 'pending')";
    
    if ($conn->query($insert_query)) {
        $_SESSION['success'] = "Booking created successfully!";
        header("Location: dashboard.php");
        exit();
    } else {
        die("Error creating booking: " . $conn->error);
    }
}

$conn->close();
?>