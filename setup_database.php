<?php
/**
 * Rescue Car Booking System - Database Setup Script
 * This script imports the database tables and data
 */

set_time_limit(60);
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include config
require_once 'includes/config.php';

echo "<h1>🔧 Database Setup for Rescue Car Booking System</h1>";
echo "<hr>";

// Check if database exists
$db_check = new mysqli(DB_HOST, DB_USER, DB_PASS);
if ($db_check->connect_error) {
    die("<div style='color: red;'><strong>Error:</strong> Cannot connect to database server: " . $db_check->connect_error . "</div>");
}

// Select database or create it
if (!$db_check->select_db(DB_NAME)) {
    echo "<p><strong>Creating database: " . DB_NAME . "</strong></p>";
    $sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci";
    if ($db_check->query($sql)) {
        echo "<div style='color: green;'>✓ Database created successfully</div>";
    } else {
        die("<div style='color: red;'><strong>Error creating database:</strong> " . $db_check->error . "</div>");
    }
    $db_check->select_db(DB_NAME);
} else {
    echo "<div style='color: blue;'>✓ Database already exists</div>";
}

$db_check->close();

// Now use the connection from config
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("<div style='color: red;'><strong>Connection Error:</strong> " . $conn->connect_error . "</div>");
}

// Read and execute SQL file
$sql_file = 'rescuecars_booking.sql';
if (!file_exists($sql_file)) {
    die("<div style='color: red;'><strong>Error:</strong> SQL file not found: $sql_file</div>");
}

echo "<p><strong>Importing SQL from:</strong> $sql_file</p>";
echo "<hr>";

$sql_content = file_get_contents($sql_file);

// Split SQL statements
$statements = array_filter(array_map('trim', preg_split('/;(?=(?:[^\']*\'[^\']*\')*[^\']*$)/', $sql_content)));

$success_count = 0;
$error_count = 0;
$tables_created = array();

foreach ($statements as $statement) {
    if (empty($statement) || substr(trim($statement), 0, 2) == '--') {
        continue;
    }
    
    if ($conn->query($statement)) {
        // Extract table name if it's a CREATE TABLE statement
        if (stripos($statement, 'CREATE TABLE') !== false) {
            preg_match('/CREATE TABLE `?(\w+)`?/i', $statement, $matches);
            if (isset($matches[1])) {
                $tables_created[] = $matches[1];
                echo "<div style='color: green;'>✓ Created table: <strong>" . $matches[1] . "</strong></div>";
            }
        }
        $success_count++;
    } else {
        // Ignore certain error messages that are expected
        if (stripos($conn->error, 'already exists') !== false) {
            echo "<div style='color: orange;'>⚠ Table already exists (skipped)</div>";
        } else if (!empty($conn->error)) {
            echo "<div style='color: red;'>✗ Error: " . $conn->error . "</div>";
            echo "<div style='font-size: 12px; color: #666;'>Statement: " . substr($statement, 0, 100) . "...</div>";
            $error_count++;
        }
    }
}

echo "<hr>";
echo "<h2>Import Results</h2>";
echo "<div style='background: #f0f0f0; padding: 15px; border-radius: 5px;'>";
echo "<p><strong>✓ Successful statements:</strong> $success_count</p>";
if ($error_count > 0) {
    echo "<p><strong>⚠ Errors encountered:</strong> $error_count (some may be expected)</p>";
}
echo "</div>";

// Verify tables exist
echo "<h2>Table Verification</h2>";
$tables_to_check = array('users', 'barangays', 'rescue_cars', 'bookings', 'car_availability_log');
$all_tables_exist = true;

foreach ($tables_to_check as $table) {
    $result = $conn->query("SHOW TABLES LIKE '$table'");
    if ($result && $result->num_rows > 0) {
        $count_result = $conn->query("SELECT COUNT(*) as cnt FROM $table");
        $count = $count_result->fetch_assoc()['cnt'];
        echo "<div style='color: green;'>✓ <strong>$table</strong> - $count records</div>";
    } else {
        echo "<div style='color: red;'>✗ <strong>$table</strong> - NOT FOUND</div>";
        $all_tables_exist = false;
    }
}

echo "<hr>";

if ($all_tables_exist) {
    echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; border: 1px solid #c3e6cb;'>";
    echo "<h2 style='margin: 0 0 10px 0; color: #155724;'>✓ DATABASE SETUP COMPLETE</h2>";
    echo "<p style='margin: 0;'>All tables have been created and imported successfully!</p>";
    echo "<p style='margin: 10px 0 0 0;'><strong>Test Account:</strong></p>";
    echo "<ul>";
    echo "<li>Username: <strong>IAN</strong></li>";
    echo "<li>Password: <strong>123456</strong></li>";
    echo "<li>Role: <strong>Captain</strong></li>";
    echo "</ul>";
    echo "<p><a href='index.php' style='background: #155724; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; display: inline-block;'>Go to Login Page →</a></p>";
    echo "</div>";
} else {
    echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px; border: 1px solid #f5c6cb;'>";
    echo "<h2 style='margin: 0 0 10px 0; color: #721c24;'>✗ SETUP INCOMPLETE</h2>";
    echo "<p style='margin: 0;'>Some tables are missing. Please check the errors above.</p>";
    echo "</div>";
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Database Setup</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background: #f9f9f9;
        }
        hr { border: 1px solid #ddd; margin: 20px 0; }
        h1 { color: #333; }
        h2 { color: #555; margin-top: 20px; }
    </style>
</head>
<body>
</body>
</html>
