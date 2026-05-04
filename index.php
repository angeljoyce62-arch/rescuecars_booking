<?php
session_start();

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'includes/config.php';
    
    // LOGIN LOGIC
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (hash('sha256', $password) == $user['password']) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['barangay_id'] = $user['barangay_id'];
            
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid username or password";
        }
    } else {
        $error = "Invalid username or password";
    }
    
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rescue Car Booking System - Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css?v=2">
</head>
<body class="login-page-bg">
    <div class="auth-container">
        <div class="auth-header">
            <h1><i class="fas fa-car-side"></i> Rescue Car</h1>
            <p>Booking System</p>
        </div>
        
        <div class="auth-content">
            <?php if (!empty($error)): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <!-- LOGIN FORM -->
            <form method="POST" action="">
                <div class="form-group">
                    <label for="login_username"><i class="fas fa-user"></i> Username</label>
                    <input type="text" id="login_username" name="username" required>
                </div>
                
                <div class="form-group password-group">
                    <label for="login_password"><i class="fas fa-lock"></i> Password</label>
                    <input type="password" id="login_password" name="password" required>
                    <i class="fas fa-eye toggle-password" onclick="togglePassword('login_password')" title="Show/Hide Password"></i>
                </div>
                
                <button type="submit" class="btn-submit">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>
            
            <div class="form-footer">
                Don't have an account? <a href="register.php">Register here</a>
            </div>
        </div>
    </div>
    
<script src="assets/js/main.js?v=2"></script>
</body>
</html>