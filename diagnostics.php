<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Diagnostics - Rescue Car Booking</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 30px;
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #667eea;
            padding-bottom: 10px;
        }
        .test-section {
            margin: 20px 0;
            padding: 15px;
            border-left: 4px solid #667eea;
            background: #f9f9f9;
        }
        .test-item {
            margin: 10px 0;
            padding: 10px;
            border-radius: 4px;
        }
        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        .info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        .code {
            background: #f4f4f4;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 System Diagnostics - Rescue Car Booking System</h1>
        
        <?php
        $all_pass = true;
        
        // Test 1: PHP Version
        echo '<div class="test-section">';
        echo '<h2>PHP & Server Information</h2>';
        echo '<div class="test-item info">';
        echo '<strong>PHP Version:</strong> ' . phpversion() . '<br>';
        echo '<strong>Server Software:</strong> ' . $_SERVER['SERVER_SOFTWARE'] . '<br>';
        echo '<strong>Current Directory:</strong> ' . getcwd();
        echo '</div>';
        echo '</div>';
        
        // Test 2: File Structure
        echo '<div class="test-section">';
        echo '<h2>File Structure</h2>';
        
        $files_to_check = array(
            'includes/config.php' => 'Config file',
            'assets/css/style.css' => 'CSS file',
            'assets/js/main.js' => 'JavaScript file',
            'images/rescuecars.jpg' => 'Logo image'
        );
        
        foreach ($files_to_check as $file => $desc) {
            if (file_exists($file)) {
                echo '<div class="test-item success">✓ ' . $desc . ' (<span class="code">' . $file . '</span>) - EXISTS</div>';
            } else {
                echo '<div class="test-item error">✗ ' . $desc . ' (<span class="code">' . $file . '</span>) - MISSING!</div>';
                $all_pass = false;
            }
        }
        
        echo '</div>';
        
        // Test 3: Database Connection
        echo '<div class="test-section">';
        echo '<h2>Database Connection</h2>';
        
        include 'includes/config.php';
        
        if ($conn->connect_error) {
            echo '<div class="test-item error">✗ Connection Failed: ' . $conn->connect_error . '</div>';
            $all_pass = false;
        } else {
            echo '<div class="test-item success">✓ Connected to <span class="code">' . DB_NAME . '</span> on <span class="code">' . DB_HOST . '</span></div>';
            
            // Test 4: Database Tables
            echo '<div class="test-section" style="border-left-color: #28a745; margin: 10px 0 0 0; padding: 10px;">';
            echo '<strong>Database Tables:</strong><br>';
            
            $tables = array('users', 'barangays', 'rescue_cars', 'bookings', 'car_availability_log');
            
            foreach ($tables as $table) {
                $result = $conn->query("SELECT COUNT(*) as count FROM $table");
                if ($result) {
                    $row = $result->fetch_assoc();
                    echo '<div class="test-item success">✓ <span class="code">' . $table . '</span> - ' . $row['count'] . ' records</div>';
                } else {
                    echo '<div class="test-item error">✗ <span class="code">' . $table . '</span> - Query failed: ' . $conn->error . '</div>';
                    $all_pass = false;
                }
            }
            
            echo '</div>';
        }
        
        echo '</div>';
        
        // Test 5: Session
        echo '<div class="test-section">';
        echo '<h2>Session Information</h2>';
        
        if (isset($_SESSION['user_id'])) {
            echo '<div class="test-item info">Session Active - User: ' . htmlspecialchars($_SESSION['username']) . '</div>';
        } else {
            echo '<div class="test-item info">No active session</div>';
        }
        
        echo '</div>';
        
        // Test 6: Key Features Check
        echo '<div class="test-section">';
        echo '<h2>System Status</h2>';
        
        if ($all_pass) {
            echo '<div class="test-item success"><strong>✓ ALL CHECKS PASSED</strong></div>';
            echo '<div class="test-item info">The system is ready to use! You can proceed to <a href="index.php">login page</a></div>';
        } else {
            echo '<div class="test-item error"><strong>✗ SOME ISSUES DETECTED</strong></div>';
            echo '<div class="test-item warning">Please check the errors above and fix them before proceeding.</div>';
        }
        
        echo '</div>';
        
        // Test 7: Configuration Summary
        echo '<div class="test-section">';
        echo '<h2>Configuration Summary</h2>';
        echo '<div class="test-item info">';
        echo '<strong>Database Host:</strong> <span class="code">' . DB_HOST . '</span><br>';
        echo '<strong>Database Name:</strong> <span class="code">' . DB_NAME . '</span><br>';
        echo '<strong>Database User:</strong> <span class="code">' . DB_USER . '</span><br>';
        echo '<strong>Timezone:</strong> <span class="code">' . date_default_timezone_get() . '</span><br>';
        echo '<strong>Character Set:</strong> <span class="code">utf8mb4</span>';
        echo '</div>';
        echo '</div>';
        
        // Quick Links
        echo '<div class="test-section">';
        echo '<h2>Quick Links</h2>';
        echo '<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 10px;">';
        echo '<a href="index.php" style="padding: 10px; background: #667eea; color: white; border-radius: 4px; text-decoration: none; text-align: center;">Login Page</a>';
        echo '<a href="register.php" style="padding: 10px; background: #764ba2; color: white; border-radius: 4px; text-decoration: none; text-align: center;">Register Page</a>';
        echo '<a href="dashboard.php" style="padding: 10px; background: #28a745; color: white; border-radius: 4px; text-decoration: none; text-align: center;">Dashboard</a>';
        echo '</div>';
        echo '</div>';
        
        ?>
    </div>
</body>
</html>
