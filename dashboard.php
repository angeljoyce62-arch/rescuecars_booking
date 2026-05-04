<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Additional security check
if (!isset($_SESSION['role']) || !isset($_SESSION['barangay_id'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

include 'includes/config.php';

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Get user info
$user_query = "SELECT * FROM users WHERE user_id = $user_id";
$user_result = $conn->query($user_query);
$user = $user_result->fetch_assoc();

// Get statistics
$total_bookings = $conn->query("SELECT COUNT(*) as count FROM bookings WHERE user_id = $user_id")->fetch_assoc()['count'];
$pending_bookings = $conn->query("SELECT COUNT(*) as count FROM bookings WHERE user_id = $user_id AND status = 'pending'")->fetch_assoc()['count'];
$approved_bookings = $conn->query("SELECT COUNT(*) as count FROM bookings WHERE user_id = $user_id AND status = 'approved'")->fetch_assoc()['count'];

// Available cars (only for captains)
if ($role == 'captain') {
    $available_cars = $conn->query("SELECT COUNT(*) as count FROM rescue_cars WHERE barangay_id = {$user['barangay_id']} AND status = 'available'")->fetch_assoc()['count'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Asenso Rescue Car Booking System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css?v=2">
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-brand">
            <i class="fas fa-car-side"></i> Asenso Rescue Car Booking
        </div>
        <div class="navbar-right">
            <div class="user-info">
                <i class="fas fa-user-circle"></i>
                <span><?php echo htmlspecialchars($_SESSION['username']); ?> (<?php echo ucfirst($_SESSION['role']); ?>)</span>
            </div>
            <button class="btn-logout" onclick="logout()">Logout</button>
        </div>
    </div>
    
    <!-- Main Container -->
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <button class="menu-btn" onclick="toggleMenu()">
                <i class="fas fa-bars"></i> Menu
            </button>
            <nav class="nav-menu" id="navMenu">
                <button class="nav-link active" onclick="showSection('home', this)">
                    <i class="fas fa-home"></i> Home
                </button>
                <button class="nav-link" onclick="showSection('book-car', this)">
                    <i class="fas fa-calendar-check"></i> Book a Car
                </button>
                <button class="nav-link" onclick="showSection('my-bookings', this)">
                    <i class="fas fa-list"></i> My Bookings
                </button>
                <button class="nav-link" onclick="showSection('available-cars', this)">
                    <i class="fas fa-car"></i> Available Cars
                </button>
                <?php if ($role == 'captain'): ?>
                    <button class="nav-link" onclick="showSection('pending-bookings', this)">
                        <i class="fas fa-hourglass"></i> Pending Bookings
                    </button>
                    <button class="nav-link" onclick="showSection('manage-cars', this)">
                        <i class="fas fa-tools"></i> Manage Cars
                    </button>
                <?php endif; ?>
                <button class="nav-link" onclick="showSection('history', this)">
                    <i class="fas fa-history"></i> History
                </button>
                <button class="nav-link" onclick="showSection('settings', this)">
                    <i class="fas fa-cog"></i> Settings
                </button>
            </nav>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <!-- Home Section -->
            <section id="home" class="content-section active">
                <h2 class="page-title"><i class="fas fa-home"></i> Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
                <div class="stats-grid">
                    <div class="stat-card">
                        <i class="fas fa-book"></i>
                        <h3><?php echo $total_bookings; ?></h3>
                        <p>Total Bookings</p>
                    </div>
                    <div class="stat-card">
                        <i class="fas fa-clock"></i>
                        <h3><?php echo $pending_bookings; ?></h3>
                        <p>Pending</p>
                    </div>
                    <div class="stat-card">
                        <i class="fas fa-check-circle"></i>
                        <h3><?php echo $approved_bookings; ?></h3>
                        <p>Approved</p>
                    </div>
                    <?php if ($role == 'captain'): ?>
                    <div class="stat-card">
                        <i class="fas fa-car"></i>
                        <h3><?php echo $available_cars; ?></h3>
                        <p>Available Cars</p>
                    </div>
                    <?php endif; ?>
                </div>
            </section>
            
            <!-- Book a Car Section -->
            <section id="book-car" class="content-section">
                <h2 class="page-title"><i class="fas fa-calendar-check"></i> Book a Car</h2>
                <div class="form-card">
                    <form method="POST" action="process_booking.php">
                        <div class="form-group">
                            <label for="booking_barangay">Your Barangay:</label>
                            <input type="text" id="booking_barangay" value="<?php 
                                $barangay = $conn->query("SELECT barangay_name FROM barangays WHERE barangay_id = {$user['barangay_id']}")->fetch_assoc();
                                echo $barangay ? htmlspecialchars($barangay['barangay_name']) : 'Not assigned';
                            ?>" readonly>
                        </div>
                        
                        <div class="form-group">
                            <label for="booking_date">Booking Date:</label>
                            <input type="date" id="booking_date" name="booking_date" required>
                            <small style="color: #999;">Select a future date</small>
                        </div>
                        
                        <div style="display: flex; gap: 20px;">
                            <div class="form-group" style="flex: 1;">
                                <label for="start_time">Start Time:</label>
                                <input type="time" id="start_time" name="start_time" required>
                            </div>
                            
                            <div class="form-group" style="flex: 1;">
                                <label for="end_time">End Time:</label>
                                <input type="time" id="end_time" name="end_time" required>
                            </div>
                        </div>
                        
                        <!-- Available Rescue Cars Section -->
                        <div class="form-group">
                            <label>Available Rescue Cars:</label>
                            <div id="available-cars-container" style="
                                border: 2px dashed #ccc;
                                border-radius: 8px;
                                padding: 30px;
                                text-align: center;
                                min-height: 150px;
                                background: #f9f9f9;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                            ">
                                <div style="color: #999;">
                                    <i class="fas fa-info-circle"></i>
                                    <p>Select date, time, and barangay to see available cars</p>
                                </div>
                            </div>
                            <!-- Hidden input to store selected car -->
                            <input type="hidden" id="car_id" name="car_id" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="purpose">Purpose:</label>
                            <textarea id="purpose" name="purpose" rows="4" required placeholder="Enter the purpose of your booking"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="booking_type">Booking Type:</label>
                            <select id="booking_type" name="booking_type" required>
                                <option value="advance">Advance</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-check"></i> Book Car
                        </button>
                    </form>
                    <a href="dashboard.php" class="btn-back-dashboard" style="display: inline-block; margin-top: 20px; text-align: center; background: var(--primary-color); color: #fff; padding: 10px 24px; border-radius: 6px; text-decoration: none; font-weight: 600; transition: background 0.2s;">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                    </form>
                </div>
            </section>
            
            <!-- My Bookings Section -->
            <section id="my-bookings" class="content-section">
                <h2 class="page-title"><i class="fas fa-list"></i> My Bookings</h2>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Booking ID</th>
                                <th>Car</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Purpose</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $booking_query = "SELECT b.*, rc.car_name FROM bookings b 
                                           JOIN rescue_cars rc ON b.car_id = rc.car_id 
                                           WHERE b.user_id = $user_id 
                                           ORDER BY b.created_at DESC";
                            $booking_result = $conn->query($booking_query);
                            
                            if ($booking_result->num_rows > 0):
                                while ($booking = $booking_result->fetch_assoc()):
                            ?>
                            <tr>
                                <td>#<?php echo $booking['booking_id']; ?></td>
                                <td><?php echo htmlspecialchars($booking['car_name']); ?></td>
                                <td><?php echo $booking['booking_date']; ?></td>
                                <td><?php echo $booking['start_time'] . " - " . $booking['end_time']; ?></td>
                                <td><?php echo htmlspecialchars($booking['purpose']); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo $booking['status']; ?>">
                                        <?php echo ucfirst($booking['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($booking['status'] == 'pending'): ?>
                                        <button class="btn-action btn-cancel" onclick="cancelBooking(<?php echo $booking['booking_id']; ?>)">
                                            <i class="fas fa-times"></i> Cancel
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php 
                                endwhile;
                            else:
                            ?>
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 30px;">No bookings yet</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
            
            <!-- Available Cars Section -->
            <section id="available-cars" class="content-section">
                <h2 class="page-title"><i class="fas fa-car"></i> Available Cars</h2>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Car Name</th>
                                <th>Car Number</th>
                                <th>Plate Number</th>
                                <th>Barangay</th>
                                <th>Driver</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $car_query = "SELECT rc.*, b.barangay_name FROM rescue_cars rc 
                                       JOIN barangays b ON rc.barangay_id = b.barangay_id 
                                       WHERE rc.status = 'available'
                                       ORDER BY b.barangay_name";
                            $car_result = $conn->query($car_query);
                            
                            if ($car_result->num_rows > 0):
                                while ($car = $car_result->fetch_assoc()):
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($car['car_name']); ?></td>
                                <td><?php echo htmlspecialchars($car['car_number']); ?></td>
                                <td><?php echo htmlspecialchars($car['plate_number']); ?></td>
                                <td><?php echo htmlspecialchars($car['barangay_name']); ?></td>
                                <td><?php echo htmlspecialchars($car['driver_name']); ?></td>
                                <td>
                                    <span class="status-badge status-available">
                                        <i class="fas fa-check-circle"></i> Available
                                    </span>
                                </td>
                            </tr>
                            <?php 
                                endwhile;
                            else:
                            ?>
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 30px;">No available cars at the moment</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
            
            <!-- Pending Bookings Section (Captain Only) -->
            <?php if ($role == 'captain'): ?>
            <section id="pending-bookings" class="content-section">
                <h2 class="page-title"><i class="fas fa-hourglass"></i> Pending Booking Approvals</h2>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Booking ID</th>
                                <th>User</th>
                                <th>Car</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Purpose</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $barangay_id = $user['barangay_id'];
                            $pending_query = "SELECT b.*, rc.car_name, u.username FROM bookings b 
                                           JOIN rescue_cars rc ON b.car_id = rc.car_id 
                                           JOIN users u ON b.user_id = u.user_id
                                           WHERE b.status = 'pending' AND rc.barangay_id = $barangay_id
                                           ORDER BY b.created_at DESC";
                            $pending_result = $conn->query($pending_query);
                            
                            if ($pending_result->num_rows > 0):
                                while ($booking = $pending_result->fetch_assoc()):
                            ?>
                            <tr>
                                <td>#<?php echo $booking['booking_id']; ?></td>
                                <td><?php echo htmlspecialchars($booking['username']); ?></td>
                                <td><?php echo htmlspecialchars($booking['car_name']); ?></td>
                                <td><?php echo $booking['booking_date']; ?></td>
                                <td><?php echo $booking['start_time'] . " - " . $booking['end_time']; ?></td>
                                <td><?php echo htmlspecialchars($booking['purpose']); ?></td>
                                <td>
                                    <button class="btn-action btn-approve" onclick="approveBooking(<?php echo $booking['booking_id']; ?>)">
                                        <i class="fas fa-check"></i> Approve
                                    </button>
                                    <button class="btn-action btn-reject" onclick="rejectBooking(<?php echo $booking['booking_id']; ?>)">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                </td>
                            </tr>
                            <?php 
                                endwhile;
                            else:
                            ?>
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 30px;">No pending bookings</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
            
            <!-- Manage Cars Section (Captain Only) -->
            <section id="manage-cars" class="content-section">
                <h2 class="page-title"><i class="fas fa-tools"></i> Manage Cars</h2>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Car Name</th>
                                <th>Car Number</th>
                                <th>Plate Number</th>
                                <th>Driver</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $manage_cars_query = "SELECT * FROM rescue_cars WHERE barangay_id = $barangay_id ORDER BY car_name";
                            $manage_cars_result = $conn->query($manage_cars_query);
                            
                            while ($car = $manage_cars_result->fetch_assoc()):
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($car['car_name']); ?></td>
                                <td><?php echo htmlspecialchars($car['car_number']); ?></td>
                                <td><?php echo htmlspecialchars($car['plate_number']); ?></td>
                                <td><?php echo htmlspecialchars($car['driver_name']); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo str_replace(' ', '_', $car['status']); ?>">
                                        <?php echo ucfirst(str_replace('_', ' ', $car['status'])); ?>
                                    </span>
                                </td>
                                <td>
                                    <select onchange="updateCarStatus(<?php echo $car['car_id']; ?>, this.value)" style="padding: 6px; border-radius: 5px; border: 1px solid #ddd; cursor: pointer;">
                                        <option value="">Change Status</option>
                                        <option value="available">Available</option>
                                        <option value="in_use">In Use</option>
                                        <option value="maintenance">Maintenance</option>
                                    </select>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </section>
            <?php endif; ?>
            
            <!-- History Section -->
            <section id="history" class="content-section">
                <h2 class="page-title"><i class="fas fa-history"></i> Booking History</h2>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Booking ID</th>
                                <?php if ($role == 'captain'): ?>
                                <th>User</th>
                                <?php endif; ?>
                                <th>Car</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Purpose</th>
                                <th>Status</th>
                                <th>Processed On</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($role == 'captain') {
                                $barangay_id = $user['barangay_id'];
                                $history_query = "SELECT b.*, rc.car_name, u.username FROM bookings b 
                                               JOIN rescue_cars rc ON b.car_id = rc.car_id 
                                               JOIN users u ON b.user_id = u.user_id
                                               WHERE b.status != 'pending' AND rc.barangay_id = $barangay_id
                                               ORDER BY b.booking_date DESC, b.created_at DESC";
                            } else {
                                $history_query = "SELECT b.*, rc.car_name FROM bookings b 
                                               JOIN rescue_cars rc ON b.car_id = rc.car_id 
                                               WHERE b.user_id = $user_id AND b.status != 'pending'
                                               ORDER BY b.booking_date DESC, b.created_at DESC";
                            }
                            $history_result = $conn->query($history_query);
                            
                            if ($history_result->num_rows > 0):
                                while ($booking = $history_result->fetch_assoc()):
                            ?>
                            <tr>
                                <td>#<?php echo $booking['booking_id']; ?></td>
                                <?php if ($role == 'captain'): ?>
                                <td><?php echo htmlspecialchars($booking['username']); ?></td>
                                <?php endif; ?>
                                <td><?php echo htmlspecialchars($booking['car_name']); ?></td>
                                <td><?php echo $booking['booking_date']; ?></td>
                                <td><?php echo $booking['start_time'] . " - " . $booking['end_time']; ?></td>
                                <td><?php echo htmlspecialchars($booking['purpose']); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo $booking['status']; ?>">
                                        <?php echo ucfirst($booking['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo $booking['approval_date'] ? date('M d, Y g:i A', strtotime($booking['approval_date'])) : date('M d, Y g:i A', strtotime($booking['created_at'])); ?></td>
                            </tr>
                            <?php 
                                endwhile;
                            else:
                            ?>
                            <tr>
                                <td colspan="<?php echo $role == 'captain' ? 8 : 7; ?>" style="text-align: center; padding: 30px;">No history available</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
            
            <!-- Settings Section -->
            <section id="settings" class="content-section">
                <h2 class="page-title"><i class="fas fa-cog"></i> Settings</h2>
                
                <div class="form-card" style="margin-bottom: 30px;">
                    <h3 style="margin-bottom: 20px; color: var(--primary-color);"><i class="fas fa-user"></i> Profile Information</h3>
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" value="<?php echo htmlspecialchars($user['username']); ?>" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label>Role</label>
                        <input type="text" value="<?php echo ucfirst($user['role']); ?>" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label>Barangay</label>
                        <input type="text" value="<?php 
                            $barangay = $conn->query("SELECT barangay_name FROM barangays WHERE barangay_id = {$user['barangay_id']}")->fetch_assoc();
                            echo $barangay ? htmlspecialchars($barangay['barangay_name']) : 'Not assigned';
                        ?>" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label>Member Since</label>
                        <input type="text" value="<?php echo date('F d, Y', strtotime($user['created_at'])); ?>" readonly>
                    </div>
                </div>
                
                <div class="form-card">
                    <h3 style="margin-bottom: 20px; color: var(--primary-color);"><i class="fas fa-lock"></i> Change Password</h3>
                    <div id="password-message" style="margin-bottom: 15px; display: none;"></div>
                    <form id="change-password-form">
                        <div class="form-group password-group">
                            <label for="current_password">Current Password</label>
                            <input type="password" id="current_password" name="current_password" required>
                            <i class="fas fa-eye toggle-password" onclick="togglePassword('current_password')" title="Show/Hide Password" style="top: 42px;"></i>
                        </div>
                        
                        <div class="form-group password-group">
                            <label for="new_password">New Password</label>
                            <input type="password" id="new_password" name="new_password" required placeholder="At least 6 characters">
                            <i class="fas fa-eye toggle-password" onclick="togglePassword('new_password')" title="Show/Hide Password" style="top: 42px;"></i>
                        </div>
                        
                        <div class="form-group password-group">
                            <label for="confirm_password">Confirm New Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" required>
                            <i class="fas fa-eye toggle-password" onclick="togglePassword('confirm_password')" title="Show/Hide Password" style="top: 42px;"></i>
                        </div>
                        
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-key"></i> Update Password
                        </button>
                    </form>
                </div>
            </section>
        </div>
    </div>

<script src="assets/js/main.js?v=3"></script>
</body>
</html>