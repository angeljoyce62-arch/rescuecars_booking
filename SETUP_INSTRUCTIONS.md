# 🚀 FINAL SETUP INSTRUCTIONS

## Current Status
✅ **Background image CSS fixed**  
❌ **Database tables need to be imported** ← DO THIS FIRST!

---

## ⚠️ CRITICAL: Database Setup (DO THIS NOW!)

### Step 1: Import Database Tables
1. Make sure XAMPP MySQL is running
2. Open your browser and go to:
   ```
   http://localhost/rescuecars_booking/setup_database.php
   ```
3. The page will automatically import all tables and data
4. Wait for the success message

### Expected Output:
```
✓ Database created successfully
✓ Created table: barangays
✓ Created table: rescue_cars
✓ Created table: users
✓ Created table: bookings
✓ Created table: car_availability_log

DATABASE SETUP COMPLETE
Test Account:
- Username: IAN
- Password: 123456
```

---

## 🎨 Background Image (ALREADY DONE)

The rescuecar.jpg background has been configured in `assets/css/style.css`:

```css
body.login-page-bg {
    background-image: url('../../images/rescuecar.jpg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed;
    /* ... plus overlay for readability ... */
}
```

This means:
- ✅ Login page will show rescuecar.jpg background
- ✅ Register page will show rescuecar.jpg background
- ✅ Text overlay for readability
- ✅ Works on all devices

---

## 🔐 Test Login After Database Import

**After setup_database.php completes:**

1. Go to: `http://localhost/rescuecars_booking/`
2. Login with:
   - **Username:** IAN
   - **Password:** 123456
3. You'll see the dashboard with rescued car booking features

---

## 📋 Complete Workflow

```
1. Ensure XAMPP is running
   ├─ Apache: Running
   └─ MySQL: Running

2. Run database setup
   └─ Visit: setup_database.php

3. See background image
   └─ Visit: index.php (login page)
   └─ Should see rescuecar.jpg background

4. Login
   └─ Username: IAN
   └─ Password: 123456

5. Use dashboard
   └─ Book cars
   └─ Manage bookings
   └─ (Captain features available)
```

---

## 🔍 Verification Commands

### Check if MySQL is running:
```bash
# In PowerShell:
Get-Service MySQL*
```

### Check if Apache is running:
```bash
# In PowerShell:
Get-Service Apache*
```

---

## 📁 Key Files Modified

| File | Change | Status |
|------|--------|--------|
| `assets/css/style.css` | Added rescuecar.jpg background to body.login-page-bg | ✅ |
| `rescuecars_booking.sql` | Created SQL import file | ✅ |
| `setup_database.php` | Created one-click database import script | ✅ |
| `register.php` | Fixed broken CSS link tag | ✅ |

---

## ❌ If Database Import Fails

### Try these steps:

1. **Via phpMyAdmin:**
   - Go to: `http://localhost/phpmyadmin`
   - Select: rescuecars_booking (or create it)
   - Click: Import
   - Browse: `rescuecars_booking.sql`
   - Click: Go

2. **Via MySQL Command Line:**
   ```bash
   mysql -u root -p rescuecars_booking < rescuecars_booking.sql
   ```
   (Press Enter when asked for password, as default is empty)

3. **Check config.php:**
   - Verify: `includes/config.php` has correct credentials
   - Default: root / empty password / rescuecars_booking

---

## ✅ What You Should See

### After setup_database.php:
- Green checkmarks for all tables created
- "DATABASE SETUP COMPLETE" message
- Test account information displayed

### On Login Page (index.php):
- Rescue car background image visible
- Purple gradient overlay for text readability
- Login form centered on top

### After Login:
- Dashboard with statistics
- Menu to book cars
- View bookings
- Manage cars (as Captain)

---

## 🎯 Summary of Fixes

1. **Background Image** ✅ DONE
   - CSS configured to show rescuecar.jpg
   - Overlay for readability
   - Fixed positioning

2. **Database Setup** ⏳ NEEDS SETUP
   - SQL file created: `rescuecars_booking.sql`
   - Setup script: `setup_database.php`
   - Run setup script first!

3. **HTML Issues** ✅ FIXED
   - register.php CSS link: FIXED

---

## 📞 Quick Links

- **Setup Database:** `http://localhost/rescuecars_booking/setup_database.php`
- **Login Page:** `http://localhost/rescuecars_booking/index.php`
- **Register:** `http://localhost/rescuecars_booking/register.php`
- **Diagnostics:** `http://localhost/rescuecars_booking/diagnostics.php`
- **phpMyAdmin:** `http://localhost/phpmyadmin`

---

## 🎓 Default Credentials (After Setup)

```
Username: IAN
Password: 123456
Role: Captain (Full access to all features)
Barangay: Sanroque
```

---

**⚠️ IMPORTANT:** Run `setup_database.php` FIRST before trying to login!

`http://localhost/rescuecars_booking/setup_database.php`
