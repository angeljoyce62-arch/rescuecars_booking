<?php
/**
 * Rescue Car Booking System - Setup Verification & Quick Fix
 * This script verifies all system components and provides troubleshooting
 */

// Suppress errors initially
error_reporting(E_ALL);
ini_set('display_errors', 1);

$status = array(
    'errors' => array(),
    'warnings' => array(),
    'success' => array(),
    'info' => array()
);

// 1. Check file existence
$required_files = array(
    'includes/config.php',
    'assets/css/style.css',
    'assets/js/main.js',
    'index.php',
    'register.php',
    'dashboard.php',
    'images/rescuecars.jpg'
);

foreach ($required_files as $file) {
    if (file_exists($file)) {
        $status['success'][] = "File exists: $file";
    } else {
        $status['errors'][] = "Missing file: $file";
    }
}

// 2. Check config file
if (file_exists('includes/config.php')) {
    include 'includes/config.php';
    
    if (isset($conn)) {
        if ($conn->connect_error) {
            $status['errors'][] = "Database connection failed: " . $conn->connect_error;
        } else {
            $status['success'][] = "Database connection successful";
            
            // Check tables
            $tables = array('users', 'barangays', 'rescue_cars', 'bookings', 'car_availability_log');
            foreach ($tables as $table) {
                $result = $conn->query("SHOW TABLES LIKE '$table'");
                if ($result && $result->num_rows > 0) {
                    $count_result = $conn->query("SELECT COUNT(*) as cnt FROM $table");
                    $count = $count_result->fetch_assoc()['cnt'];
                    $status['success'][] = "Table '$table' exists with $count records";
                } else {
                    $status['errors'][] = "Table '$table' not found in database";
                }
            }
        }
    } else {
        $status['errors'][] = "Database connection variable not set";
    }
} else {
    $status['errors'][] = "Config file not found";
}

// 3. Check directory permissions
$dirs = array('includes', 'assets', 'assets/css', 'assets/js', 'images');
foreach ($dirs as $dir) {
    if (is_dir($dir)) {
        if (is_writable($dir)) {
            $status['info'][] = "Directory writable: $dir";
        } else {
            $status['warnings'][] = "Directory not writable: $dir";
        }
    } else {
        $status['errors'][] = "Directory not found: $dir";
    }
}

// Output results
$hasErrors = !empty($status['errors']);

echo "=== RESCUE CAR BOOKING SYSTEM - SETUP STATUS ===\n\n";

if ($status['errors']) {
    echo "ERRORS (" . count($status['errors']) . "):\n";
    foreach ($status['errors'] as $error) {
        echo "  ✗ $error\n";
    }
    echo "\n";
}

if ($status['warnings']) {
    echo "WARNINGS (" . count($status['warnings']) . "):\n";
    foreach ($status['warnings'] as $warning) {
        echo "  ⚠ $warning\n";
    }
    echo "\n";
}

if ($status['success']) {
    echo "SUCCESS (" . count($status['success']) . "):\n";
    foreach ($status['success'] as $success) {
        echo "  ✓ $success\n";
    }
    echo "\n";
}

if ($status['info']) {
    echo "INFO:\n";
    foreach ($status['info'] as $info) {
        echo "  ℹ $info\n";
    }
    echo "\n";
}

if (!$hasErrors) {
    echo "=== ✓ SYSTEM IS READY ===\n";
    echo "The system is properly configured and ready to use.\n";
    echo "Visit: http://localhost/rescuecars_booking/\n";
} else {
    echo "=== ✗ SYSTEM HAS ERRORS ===\n";
    echo "Please fix the errors above before using the system.\n";
}

echo "\n=== DEFAULT TEST CREDENTIALS ===\n";
echo "Username: IAN\n";
echo "Password: 123456\n";
echo "Role: Captain\n";
echo "Barangay: Sanroque\n";

?>
