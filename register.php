<?php
session_start();

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

require_once 'includes/config.php';

$error = '';
$success = '';
$barangays_result = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // REGISTRATION LOGIC
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    
    // Handle both barangay field names (for captain and citizen)
    $barangay_id = NULL;
    if (!empty($_POST['barangay_id'])) {
        $barangay_id = mysqli_real_escape_string($conn, $_POST['barangay_id']);
    } elseif (!empty($_POST['barangay_id_citizen'])) {
        $barangay_id = mysqli_real_escape_string($conn, $_POST['barangay_id_citizen']);
    }
    
    // Validation
    if (strlen($username) < 3) {
        $error = "Username must be at least 3 characters long";
    } 
    elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long";
    } 
    elseif ($password !== $confirm_password) {
        $error = "Passwords do not match";
    } 
    elseif ($role == 'captain' && empty($barangay_id)) {
        $error = "Please select a barangay";
    }
    else {
        // Check if username already exists
        $check_username = "SELECT * FROM users WHERE username = '$username'";
        $check_result = $conn->query($check_username);
        
        if ($check_result->num_rows > 0) {
            $error = "Username already exists. Please choose another username.";
        }
        // Check if captain already exists for the selected barangay
        elseif ($role == 'captain') {
            $check_captain = "SELECT * FROM users WHERE role = 'captain' AND barangay_id = '$barangay_id'";
            $captain_result = $conn->query($check_captain);
            
            if ($captain_result->num_rows > 0) {
                $error = "A captain is already registered for this barangay. Please contact your barangay administrator.";
            } else {
                // Register the captain
                $hashed_password = hash('sha256', $password);
                $insert_query = "INSERT INTO users (username, password, role, barangay_id) 
                               VALUES ('$username', '$hashed_password', '$role', '$barangay_id')";
                
                if ($conn->query($insert_query)) {
                    $success = "Captain account created successfully! You can now login.";
                } else {
                    $error = "Error registering captain: " . $conn->error;
                }
            }
        } 
        else {
            // Register citizen
            $hashed_password = hash('sha256', $password);
            $insert_query = "INSERT INTO users (username, password, role, barangay_id) 
                           VALUES ('$username', '$hashed_password', '$role', '$barangay_id')";
            
            if ($conn->query($insert_query)) {
                $success = "Account created successfully! You can now login.";
            } else {
                $error = "Error creating account: " . $conn->error;
            }
        }
    }
}

// Get barangays for registration
$barangays_query = "SELECT * FROM barangays ORDER BY barangay_name";
$barangays_result = $conn->query($barangays_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rescue Car Booking System - Register</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="assets/css/style.css?v=3">
</head>
<body class="login-page-bg">
    <div class="auth-container">
        <div class="auth-header">
            <h1><i class="fas fa-car-side"></i> Rescue Car</h1>
            <p>Create Account</p>
        </div>
        
        <div class="auth-content">
            <?php if (!empty($error)): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="success-message">
                    <i class="fas fa-check-circle"></i>
                    <?php echo htmlspecialchars($success); ?>
                </div>
                <div style="text-align: center; margin-top: 20px;">
                    <p>Redirecting to login in 3 seconds...</p>
                    <script>
                        setTimeout(function() {
                            window.location.href = 'index.php';
                        }, 3000);
                    </script>
                </div>
            <?php else: ?>
            
            <!-- REGISTER FORM -->
            <form method="POST" action="">
                <div class="role-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>Citizens:</strong> Can book rescue cars for their needs.<br>
                    <strong>Captains:</strong> Manage cars & approve bookings (1 per barangay)
                </div>
                
                <div class="form-group">
                    <label for="register_username"><i class="fas fa-user"></i> Username <span class="required">*</span></label>
                    <input type="text" id="register_username" name="username" placeholder="At least 3 characters" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="register_password"><i class="fas fa-lock"></i> Password <span class="required">*</span></label>
                    <input type="password" id="register_password" name="password" placeholder="At least 6 characters" required>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password"><i class="fas fa-lock"></i> Confirm Password <span class="required">*</span></label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
        
                <div class="form-group">
                    <label for="register_role"><i class="fas fa-user-tag"></i> Role <span class="required">*</span></label>
                    <select id="register_role" name="role" onchange="updateBarangayField()" required>
                        <option value="">Select Role</option>
                        <option value="citizen" <?php echo (isset($_POST['role']) && $_POST['role'] == 'citizen') ? 'selected' : ''; ?>>Citizen</option>
                        <option value="captain" <?php echo (isset($_POST['role']) && $_POST['role'] == 'captain') ? 'selected' : ''; ?>>Captain (Barangay Manager)</option>
                    </select>
                </div>
                
                <div class="form-group" id="barangay_field_captain" style="display: none;">
                    <label for="register_barangay"><i class="fas fa-map-marker-alt"></i> Barangay <span class="required">*</span></label>
                    <select id="register_barangay" name="barangay_id">
                        <option value="">Select Barangay</option>
                        <?php
                        $barangays_result->data_seek(0);
                        while ($barangay = $barangays_result->fetch_assoc()):
                        ?>
                        <option value="<?php echo $barangay['barangay_id']; ?>" <?php echo (isset($_POST['barangay_id']) && $_POST['barangay_id'] == $barangay['barangay_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($barangay['barangay_name']); ?>
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="form-group" id="barangay_field_citizen" style="display: none;">
                    <label for="register_barangay_citizen"><i class="fas fa-map-marker-alt"></i> Barangay (Optional)</label>
                    <select id="register_barangay_citizen" name="barangay_id_citizen">
                        <option value="">Select Your Barangay</option>
                        <?php
                        $barangays_result->data_seek(0);
                        while ($barangay = $barangays_result->fetch_assoc()):
                        ?>
                        <option value="<?php echo $barangay['barangay_id']; ?>" <?php echo (isset($_POST['barangay_id_citizen']) && $_POST['barangay_id_citizen'] == $barangay['barangay_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($barangay['barangay_name']); ?>
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <button type="submit" class="btn-submit">
                    <i class="fas fa-user-plus"></i> Create Account
                </button>
            </form>
            
            <div class="form-footer">
                Already have an account? <a href="index.php">Login here</a>
            </div>
            
            <?php endif; ?>
        </div>
    </div>
    
<script src="assets/js/main.js?v=2"></script>
</body>
</html>