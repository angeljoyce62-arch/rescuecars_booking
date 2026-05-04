// Toggle Menu
function toggleMenu() {
    const navMenu = document.getElementById('navMenu');
    navMenu.classList.toggle('active');
}

// Show/Hide Sections
function showSection(sectionId, element) {
    // Hide all sections
    const sections = document.querySelectorAll('.content-section');
    sections.forEach(section => section.classList.remove('active'));
    
    // Remove active class from all nav links
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => link.classList.remove('active'));
    
    // Show selected section
    const activeSection = document.getElementById(sectionId);
    if (activeSection) {
        activeSection.classList.add('active');
    }
    
    // Add active class to clicked nav link
    if (element) {
        element.classList.add('active');
    }
}

// Toggle Password Visibility
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    if (field.type === 'password') {
        field.type = 'text';
    } else {
        field.type = 'password';
    }
}

// Logout Function
function logout() {
    if (confirm('Are you sure you want to logout?')) {
        window.location.href = 'logout.php';
    }
}

// Check Car Availability and Display as Cards
function checkCarAvailability() {
    const bookingDate = document.getElementById('booking_date').value;
    const startTime = document.getElementById('start_time').value;
    const endTime = document.getElementById('end_time').value;
    const container = document.getElementById('available-cars-container');
    
    // Reset selection
    document.getElementById('car_id').value = '';
    
    // If any field is empty, show placeholder message
    if (!bookingDate || !startTime || !endTime) {
        container.innerHTML = `
            <div style="color: #999;">
                <i class="fas fa-info-circle"></i>
                <p>Select date, time, and barangay to see available cars</p>
            </div>
        `;
        return;
    }
    
    // Validate times
    if (startTime >= endTime) {
        container.innerHTML = `
            <div class="error-message" style="margin: 0;">
                <i class="fas fa-exclamation-circle"></i>
                Start time must be before end time!
            </div>
        `;
        return;
    }
    
    // Send request to check availability
    const formData = new FormData();
    formData.append('booking_date', bookingDate);
    formData.append('start_time', startTime);
    formData.append('end_time', endTime);
    
    fetch('check_availability.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.cars && data.cars.length > 0) {
            // Display available cars as cards
            let carHtml = '<div style="display: grid; gap: 15px;">';
            
            data.cars.forEach(car => {
                const isAvailable = data.available_cars.includes(car.car_id);
                const carId = car.car_id;
                
                if (isAvailable) {
                    carHtml += `
                        <div class="car-card" onclick="selectCar(${carId}, '${car.car_name}')" style="
                            padding: 20px;
                            border: 2px solid #e0e0e0;
                            border-radius: 8px;
                            cursor: pointer;
                            transition: all 0.3s ease;
                            background: white;
                        " onmouseover="this.style.borderColor='#667eea'; this.style.boxShadow='0 4px 12px rgba(102, 126, 234, 0.2)';" onmouseout="this.style.borderColor='#e0e0e0'; this.style.boxShadow='none';">
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <i class="fas fa-car" style="font-size: 32px; color: #667eea;"></i>
                                <div style="text-align: left; flex: 1;">
                                    <h4 style="margin: 0 0 5px 0; color: #333;">${car.car_name}</h4>
                                    <p style="margin: 0; color: #666; font-size: 14px;">
                                        <strong>Plate:</strong> ${car.plate_number}
                                    </p>
                                    <p style="margin: 5px 0 0 0; color: #666; font-size: 14px;">
                                        <strong>Driver:</strong> ${car.driver_name}
                                    </p>
                                </div>
                                <div style="text-align: center;">
                                    <span style="
                                        background: #d4edda;
                                        color: #155724;
                                        padding: 8px 12px;
                                        border-radius: 20px;
                                        font-size: 12px;
                                        font-weight: bold;
                                    ">
                                        <i class="fas fa-check-circle"></i> AVAILABLE
                                    </span>
                                </div>
                            </div>
                        </div>
                    `;
                } else {
                    carHtml += `
                        <div class="car-card-disabled" style="
                            padding: 20px;
                            border: 2px solid #ccc;
                            border-radius: 8px;
                            cursor: not-allowed;
                            background: #f9f9f9;
                            opacity: 0.6;
                        ">
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <i class="fas fa-car" style="font-size: 32px; color: #999;"></i>
                                <div style="text-align: left; flex: 1;">
                                    <h4 style="margin: 0 0 5px 0; color: #666;">${car.car_name}</h4>
                                    <p style="margin: 0; color: #999; font-size: 14px;">
                                        <strong>Plate:</strong> ${car.plate_number}
                                    </p>
                                    <p style="margin: 5px 0 0 0; color: #999; font-size: 14px;">
                                        <strong>Driver:</strong> ${car.driver_name}
                                    </p>
                                </div>
                                <div style="text-align: center;">
                                    <span style="
                                        background: #f8d7da;
                                        color: #721c24;
                                        padding: 8px 12px;
                                        border-radius: 20px;
                                        font-size: 12px;
                                        font-weight: bold;
                                    ">
                                        <i class="fas fa-times-circle"></i> NOT AVAILABLE
                                    </span>
                                </div>
                            </div>
                        </div>
                    `;
                }
            });
            
            carHtml += '</div>';
            container.innerHTML = carHtml;
        } else {
            container.innerHTML = `
                <div class="error-message" style="margin: 0;">
                    <i class="fas fa-exclamation-circle"></i>
                    No cars available for the selected date and time
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error checking availability:', error);
        container.innerHTML = `
            <div class="error-message" style="margin: 0;">
                <i class="fas fa-exclamation-circle"></i>
                Error checking car availability
            </div>
        `;
    });
}

// Select a car
function selectCar(carId, carName) {
    document.getElementById('car_id').value = carId;
    
    // Highlight selected car
    const cards = document.querySelectorAll('.car-card');
    cards.forEach(card => {
        card.style.borderColor = '#e0e0e0';
        card.style.boxShadow = 'none';
        card.style.background = 'white';
    });
    
    // Highlight the selected card
    event.currentTarget.style.borderColor = '#667eea';
    event.currentTarget.style.boxShadow = '0 4px 12px rgba(102, 126, 234, 0.3)';
    event.currentTarget.style.background = '#f0f4ff';
}

// Cancel Booking
function cancelBooking(bookingId) {
    if (confirm('Are you sure you want to cancel this booking?')) {
        const formData = new FormData();
        formData.append('booking_id', bookingId);
        
        fetch('cancel_booking.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
            location.reload();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while canceling the booking');
        });
    }
}

// Approve Booking (Captain Only)
function approveBooking(bookingId) {
    if (confirm('Are you sure you want to approve this booking?')) {
        const formData = new FormData();
        formData.append('booking_id', bookingId);
        
        fetch('approve_booking.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
            location.reload();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while approving the booking');
        });
    }
}

// Reject Booking (Captain Only)
function rejectBooking(bookingId) {
    if (confirm('Are you sure you want to reject this booking?')) {
        const formData = new FormData();
        formData.append('booking_id', bookingId);
        
        fetch('reject_booking.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
            location.reload();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while rejecting the booking');
        });
    }
}

// Update Car Status (Captain Only)
function updateCarStatus(carId, status) {
    if (!status) {
        alert('Please select a status');
        return;
    }
    
    const formData = new FormData();
    formData.append('car_id', carId);
    formData.append('status', status);
    
    fetch('update_car_status.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        alert(data);
        location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the car status');
    });
}

// Change Password
function changePassword() {
    const currentPassword = document.getElementById('current_password').value;
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    const messageDiv = document.getElementById('password-message');
    
    // Client-side validation
    if (!currentPassword || !newPassword || !confirmPassword) {
        showPasswordMessage('All fields are required', 'error');
        return;
    }
    
    if (newPassword.length < 6) {
        showPasswordMessage('New password must be at least 6 characters long', 'error');
        return;
    }
    
    if (newPassword !== confirmPassword) {
        showPasswordMessage('New passwords do not match', 'error');
        return;
    }
    
    const formData = new FormData();
    formData.append('current_password', currentPassword);
    formData.append('new_password', newPassword);
    formData.append('confirm_password', confirmPassword);
    
    fetch('update_password.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Server returned ' + response.status);
        }
        return response.text().then(text => {
            try {
                return JSON.parse(text);
            } catch (e) {
                console.error('Invalid JSON response:', text);
                throw new Error('Server returned invalid response. Check console for details.');
            }
        });
    })
    .then(data => {
        if (data.success) {
            showPasswordMessage(data.message, 'success');
            document.getElementById('change-password-form').reset();
        } else {
            showPasswordMessage(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showPasswordMessage(error.message || 'An error occurred while changing the password', 'error');
    });
}

function showPasswordMessage(message, type) {
    const messageDiv = document.getElementById('password-message');
    messageDiv.style.display = 'block';
    messageDiv.className = type === 'success' ? 'success-message' : 'error-message';
    messageDiv.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i> ${message}`;
}

// Update Barangay Field
function updateBarangayField() {
    const role = document.getElementById('register_role');
    const barangayFieldCaptain = document.getElementById('barangay_field_captain');
    const barangayFieldCitizen = document.getElementById('barangay_field_citizen');
    
    if (!role) return;
    
    const selectedRole = role.value;
    
    if (selectedRole === 'captain') {
        if (barangayFieldCaptain) barangayFieldCaptain.style.display = 'block';
        if (barangayFieldCitizen) barangayFieldCitizen.style.display = 'none';
        
        const barangaySelect = document.getElementById('register_barangay');
        if (barangaySelect) barangaySelect.required = true;
        
        const barangaySelectCitizen = document.getElementById('register_barangay_citizen');
        if (barangaySelectCitizen) barangaySelectCitizen.required = false;
    } else if (selectedRole === 'citizen') {
        if (barangayFieldCaptain) barangayFieldCaptain.style.display = 'none';
        if (barangayFieldCitizen) barangayFieldCitizen.style.display = 'block';
        
        const barangaySelect = document.getElementById('register_barangay');
        if (barangaySelect) barangaySelect.required = false;
        
        const barangaySelectCitizen = document.getElementById('register_barangay_citizen');
        if (barangaySelectCitizen) barangaySelectCitizen.required = false;
    } else {
        if (barangayFieldCaptain) barangayFieldCaptain.style.display = 'none';
        if (barangayFieldCitizen) barangayFieldCitizen.style.display = 'none';
        
        const barangaySelect = document.getElementById('register_barangay');
        if (barangaySelect) barangaySelect.required = false;
        
        const barangaySelectCitizen = document.getElementById('register_barangay_citizen');
        if (barangaySelectCitizen) barangaySelectCitizen.required = false;
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    const registerRole = document.getElementById('register_role');
    if (registerRole) {
        updateBarangayField();
    }
    
    // Attach password form submit listener
    const passwordForm = document.getElementById('change-password-form');
    if (passwordForm) {
        console.log('Password form found, attaching listener');
        passwordForm.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('Form submit intercepted');
            changePassword();
        });
    } else {
        console.error('Password form NOT found');
    }
    
    // Add event listeners for car availability check
    const bookingDate = document.getElementById('booking_date');
    const startTime = document.getElementById('start_time');
    const endTime = document.getElementById('end_time');
    
    if (bookingDate) bookingDate.addEventListener('change', checkCarAvailability);
    if (startTime) startTime.addEventListener('change', checkCarAvailability);
    if (endTime) endTime.addEventListener('change', checkCarAvailability);
});
