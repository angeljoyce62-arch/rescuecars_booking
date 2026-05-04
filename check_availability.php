<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

include 'includes/config.php';

// Respond only to POST AJAX request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Gather input
    $booking_date = mysqli_real_escape_string($conn, $_POST['booking_date']);
    $start_time = mysqli_real_escape_string($conn, $_POST['start_time']);
    $end_time = mysqli_real_escape_string($conn, $_POST['end_time']);

    // ** Automatically get user's barangay from session **
    $barangay_id = isset($_SESSION['barangay_id']) ? intval($_SESSION['barangay_id']) : 0;

    $response = [
        "cars" => [],
        "available_cars" => [],
    ];

    if (!$barangay_id) {
        // User not assigned to a barangay
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }

    // 1. Get all rescue cars in the user's barangay
    $cars_query = "SELECT * FROM rescue_cars WHERE barangay_id = $barangay_id";
    $cars_result = $conn->query($cars_query);

    while ($car = $cars_result->fetch_assoc()) {
        $car_id = $car['car_id'];

        // 2. Check car is available for selected date/time
        $booking_check = $conn->query(
            "SELECT * FROM bookings 
            WHERE car_id = $car_id 
            AND booking_date = '$booking_date'
            AND status IN ('pending', 'approved')
            AND start_time < '$end_time' AND end_time > '$start_time'"
        );

        // 3. Car must be marked as "available" AND not have overlapping bookings
        $is_available = ($booking_check->num_rows == 0) && ($car['status'] == 'available');

        // List all cars for rendering, and separately track only available car_ids
        $response['cars'][] = $car;
        if ($is_available) {
            $response['available_cars'][] = $car_id;
        }
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
$conn->close();
?>