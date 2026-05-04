# Rescue Car Booking System - Fixed & Setup Guide

## ✓ Issues Fixed

### 1. **Broken HTML Link Tag in register.php** ✓ FIXED
**Error:** Missing `<link rel="stylesheet"` tag
```php
// BEFORE (Line 98):
href="assets/css/style.css?v=3"

// AFTER:
<link rel="stylesheet" href="assets/css/style.css?v=3">
```

### 2. **Database Connection** ✓ VERIFIED
- Database: `rescuecars_booking`
- Host: `localhost`
- User: `root`
- Password: (empty)
- All tables exist and contain data

### 3. **File Structure** ✓ VERIFIED
```
rescuecars_booking/
├── includes/
│   ├── config.php          ✓ Database configuration
│   └── header.php          ✓ Header template
├── assets/
│   ├── css/
│   │   └── style.css       ✓ Main stylesheet
│   └── js/
│       └── main.js         ✓ JavaScript functions
├── images/
│   └── rescuecar.jpg       ✓ Background image
├── index.php               ✓ Login page
├── register.php            ✓ Registration page (FIXED)
├── dashboard.php           ✓ Main dashboard
└── [other pages]           ✓ All working
```

### 4. **CSS & JS Loading** ✓ VERIFIED
- Both files are correctly referenced in HTML
- Font Awesome CDN linked for icons
- All styles applied correctly

### 5. **Password Field Warning** ℹ HARMLESS
The console warning "Password form NOT found" is just a JavaScript debug message. The password toggle functionality works perfectly via the `togglePassword()` function.

## 🚀 How to Use

### Step 1: Access the System
Open your browser and go to:
```
http://localhost/rescuecars_booking/
```

### Step 2: Verify System Health
Check the diagnostics page:
```
http://localhost/rescuecars_booking/diagnostics.php
```

### Step 3: Login with Test Account
- **Username:** IAN
- **Password:** 123456
- **Role:** Captain (Barangay Manager)
- **Barangay:** Sanroque

## 📋 System Features

### For Citizens:
- ✓ Register account
- ✓ Browse available rescue cars
- ✓ Book a car
- ✓ View booking history
- ✓ Cancel pending bookings
- ✓ Update profile

### For Captains:
- ✓ All citizen features
- ✓ Approve/reject bookings
- ✓ Manage rescue cars
- ✓ Update car availability status
- ✓ View pending bookings

## 🔧 Technical Stack

- **Backend:** PHP 8.2+
- **Database:** MySQL/MariaDB
- **Frontend:** HTML5, CSS3, JavaScript
- **Icons:** Font Awesome 6.0
- **Server:** Apache (via XAMPP)

## 🗄️ Database Schema

### Users Table
- user_id (Primary Key)
- username (Unique)
- password (hashed with SHA256)
- phone
- role (citizen/captain)
- barangay_id (Foreign Key)
- created_at

### Rescue Cars Table
- car_id (Primary Key)
- barangay_id (Foreign Key)
- car_name
- car_number (Unique)
- plate_number
- status (available/in_use/maintenance)
- driver_name
- created_at

### Bookings Table
- booking_id (Primary Key)
- user_id (Foreign Key)
- car_id (Foreign Key)
- booking_date
- start_time
- end_time
- purpose
- booking_type
- status (pending/approved/rejected/completed/cancelled)
- approved_by (Foreign Key - nullable)
- approval_date (nullable)
- created_at

### Barangays Table
- barangay_id (Primary Key)
- barangay_name (Unique)
- captain_id (Foreign Key - nullable)
- total_cars
- created_at

## 🔐 Security Features

- ✓ SHA256 password hashing
- ✓ SQL prepared statements (using mysqli)
- ✓ Session-based authentication
- ✓ User role-based access control
- ✓ CSRF protection ready

## 📝 Important Notes

1. **File Paths:** All CSS and JS files are correctly linked via relative paths
2. **Database:** Tables are already created and populated with sample data
3. **Background Image:** rescuecar.jpg is now set as site-wide background
4. **Timezone:** Set to Asia/Manila (adjustable in config.php)
5. **Charset:** UTF-8 for international character support

## 🐛 Troubleshooting

### Issue: "Failed to open stream: No such file or directory"
**Solution:** Ensure `includes/config.php` exists and path is correct
- ✓ Already verified and working

### Issue: "Table doesn't exist"
**Solution:** Import the SQL dump from the database file
- ✓ Already done and verified

### Issue: CSS/JS not loading
**Solution:** Check browser console for 404 errors
- ✓ Files are in correct paths: `assets/css/` and `assets/js/`

### Issue: Database connection failed
**Solution:** Verify XAMPP MySQL is running and credentials are correct
- ✓ Default credentials: root/empty password

## 📞 Support

If you encounter any issues:
1. Check the diagnostics page: `/diagnostics.php`
2. Verify setup: `/verify_setup.php`
3. Check PHP error log
4. Ensure XAMPP services are running

## ✅ Final Checklist

- [x] Database created and imported
- [x] All PHP files use correct config path
- [x] CSS and JS files accessible
- [x] Background image set
- [x] Test credentials available
- [x] All tables verified
- [x] Security measures in place

---
**System Status:** ✓ READY FOR USE
Last Updated: May 4, 2026
