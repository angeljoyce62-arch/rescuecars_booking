<?php
// Rescue Cars Booking System - Database Setup Script
// Run: http://localhost/rescuecars_booking/run_setup.php

echo "<h2>Rescue Cars Booking - Database Setup</h2>";

include 'includes/config.php';

function execute_schema($conn, $sql_file) {
    $sql = file_get_contents($sql_file);
    if ($sql === false) {
        die("Error: Could not read $sql_file");
    }
    
    // Split into statements
    $statements = explode(';', $sql);
    $success_count = 0;
    
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (empty($statement) || strpos($statement, '--') === 0) continue;
        
        if ($conn->multi_query($statement)) {
            $success_count++;
            do {
                if ($result = $conn->store_result()) {
                    $result->free();
                }
            } while ($conn->next_result());
        } else {
            echo "<p style='color:red;'>Error: " . $conn->error . "</p><pre>" . htmlspecialchars($statement) . "</pre>";
        }
    }
    
    return $success_count;
}

echo "<p>Executing schema from database_schema.sql...</p>";
$executed = execute_schema($conn, 'database_schema.sql');

echo "<p><strong>$executed statements executed successfully!</strong></p>";
echo "<p>Test login: <strong>username: admin, password: admin123</strong></p>";
echo "<a href='test_db.php'>Test DB Connection</a> | <a href='index.php'>Go to Login</a>";

$conn->close();
?>
