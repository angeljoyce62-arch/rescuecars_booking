<?php
// Test database connection
include 'includes/config.php';

echo "Database Connection Test:\n";
echo "========================\n\n";

// Test 1: Connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "✓ Database Connection: SUCCESS\n";
}

// Test 2: Check users table
$result = $conn->query("SELECT COUNT(*) as count FROM users");
if (!$result) {
    echo "✗ Users table query failed: " . $conn->error . "\n";
} else {
    $row = $result->fetch_assoc();
    echo "✓ Users table: EXISTS with " . $row['count'] . " records\n";
}

// Test 3: Check barangays table
$result = $conn->query("SELECT COUNT(*) as count FROM barangays");
if (!$result) {
    echo "✗ Barangays table query failed: " . $conn->error . "\n";
} else {
    $row = $result->fetch_assoc();
    echo "✓ Barangays table: EXISTS with " . $row['count'] . " records\n";
}

// Test 4: Check rescue_cars table
$result = $conn->query("SELECT COUNT(*) as count FROM rescue_cars");
if (!$result) {
    echo "✗ Rescue cars table query failed: " . $conn->error . "\n";
} else {
    $row = $result->fetch_assoc();
    echo "✓ Rescue cars table: EXISTS with " . $row['count'] . " records\n";
}

// Test 5: Check bookings table
$result = $conn->query("SELECT COUNT(*) as count FROM bookings");
if (!$result) {
    echo "✗ Bookings table query failed: " . $conn->error . "\n";
} else {
    $row = $result->fetch_assoc();
    echo "✓ Bookings table: EXISTS with " . $row['count'] . " records\n";
}

echo "\n✓ All tests completed!\n";
?>
