<?php
session_start();
include 'config.php';

// Enhanced security checks
if (!isset($_SESSION['user_id']) {
    header("Location: login.php?message=" . urlencode("Please log in to access your profile."));
    exit();
}

// Regenerate session ID to prevent fixation
if (!isset($_SESSION['regenerated'])) {
    session_regenerate_id(true);
    $_SESSION['regenerated'] = true;
}

$user_id = (int)$_SESSION['user_id'];
$error = '';
$success = '';

// Fetch user data
try {
    $stmt = $conn->prepare("SELECT username, email, phone, address, created_at FROM users WHERE id = ?");
    if (!$stmt) {
        throw new Exception("Database error: " . $conn->error);
    }
    
    $stmt->bind_param("i", $user_id);
    if (!$stmt->execute()) {
        throw new Exception("Failed to execute query: " . $stmt->error);
    }
    
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    
    if (!$user) {
        throw new Exception("User not found");
    }
    
} catch (Exception $e) {
    error_log($e->getMessage());
    $error = "We encountered an error while loading your profile. Please try again later.";
}

// Handle profile updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    
    // Basic validation
    if (!preg_match('/^[0-9]{10,15}$/', $phone)) {
        $error = "Please enter a valid phone number";
    } elseif (strlen($address) < 10) {
        $error = "Address must be at least 10 characters";
    } else {
        try {
            $stmt = $conn->prepare("UPDATE users SET phone = ?, address = ? WHERE id = ?");
            if (!$stmt) {
                throw new Exception("Database error: " . $conn->error);
            }
            
            $stmt->bind_param("ssi", $phone, $address, $user_id);
            if (!$stmt->execute()) {
                throw new Exception("Failed to update profile: " . $stmt->error);
            }
            
            $stmt->close();
            $success = "Profile updated successfully!";
            
            // Update session data if username/email was changed
            $_SESSION['username'] = $user['username'];
            
            // Refresh user data
            $stmt = $conn->prepare("SELECT username, email, phone, address, created_at FROM users WHERE id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            $stmt->close();
            
        } catch (Exception $e) {
            error_log($e->getMessage());
            $error = "We encountered an error while updating your profile. Please try again later.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Manage your All To All Mobile account profile">
    <title>My Profile | All To All Mobile</title>
    
    <!-- Preload resources -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" as="style">
    <link rel="preload" href="https://kit.fontawesome.com/4c729db828.js" as="script">
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    
    <!-- Favicon -->
    <link rel="icon" href="assets/favicon.ico" type="image/x-icon">
    
    <style>
        :root {
            --primary-bg: #121212;
            --secondary-bg: #1e1e1e;
            --primary-color: #ffffff;
            --secondary-color: rgba(255, 255, 255, 0.7);
            --highlight-color: #ffcc00;
            --error-color: #ff4444;
            --success-color: #00c851;
        }

        body {
            background-color: var(--primary-bg);
            color: var(--primary-color);
            font-family: 'Roboto', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Profile container */
        .profile-container {
            background: #0f0f0f;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(255, 215, 0, 0.2);
            padding: 2.5rem;
            margin: 2rem auto;
            max-width: 800px;
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .profile-header {
            text-align: center;
            margin-bottom: 2rem;
            position: relative;
        }

        .profile-header h1 {
            color: var(--highlight-color);
            font-weight: 700;
            text-shadow: 0 0 10px rgba(255, 204, 0, 0.5);
            letter-spacing: 1px;
        }

        .profile-header::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: var(--highlight-color);
            border-radius: 3px;
        }

        /* Avatar styles */
        .avatar-container {
            display: flex;
            justify-content: center;
            margin-bottom: 1.5rem;
        }

        .avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--highlight-color);
            box-shadow: 0 0 20px rgba(255, 204, 0, 0.3);
            transition: all 0.3s ease;
        }

        .avatar:hover {
            transform: scale(1.05);
            box-shadow: 0 0 25px rgba(255, 204, 0, 0.5);
        }

        .avatar-upload {
            position: relative;
            display: inline-block;
        }

        .avatar-upload-label {
            position: absolute;
            bottom: 0;
            right: 10px;
            background: var(--highlight-color);
            color: #000;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .avatar-upload-label:hover {
            transform: scale(1.1);
        }

        .avatar-upload-input {
            display: none;
        }

        /* Profile details */
        .profile-details {
            margin-bottom: 2rem;
        }

        .detail-group {
            margin-bottom: 1.2rem;
        }

        .detail-label {
            color: var(--highlight-color);
            font-weight: 500;
            margin-bottom: 0.3rem;
            display: flex;
            align-items: center;
        }

        .detail-label i {
            margin-right: 8px;
            font-size: 1.1rem;
        }

        .detail-value {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 5px;
            padding: 0.8rem 1rem;
            word-break: break-word;
        }

        /* Form styles */
        .profile-form .form-control {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--primary-color);
            border-radius: 5px;
            padding: 0.8rem 1rem;
        }

        .profile-form .form-control:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: var(--highlight-color);
            color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(255, 204, 0, 0.25);
        }

        .profile-form .form-control::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }

        /* Buttons */
        .btn-profile {
            background: linear-gradient(135deg, #ff9a00, #ffcc00);
            border: none;
            color: #000;
            font-weight: 600;
            padding: 0.6rem 1.5rem;
            border-radius: 5px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-profile:hover {
            background: linear-gradient(135deg, #ffcc00, #ff9a00);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 204, 0, 0.3);
        }

        .btn-change-password {
            background: transparent;
            border: 1px solid var(--highlight-color);
            color: var(--highlight-color);
            padding: 0.6rem 1.5rem;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .btn-change-password:hover {
            background: rgba(255, 204, 0, 0.1);
        }

        /* Stats cards */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin: 2rem 0;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            padding: 1.2rem;
            text-align: center;
            transition: all 0.3s ease;
            border-left: 3px solid var(--highlight-color);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.08);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .stat-number {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--highlight-color);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 0.9rem;
            color: var(--secondary-color);
        }

        /* Messages */
        .alert-message {
            border-radius: 5px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid;
        }

        .alert-error {
            background: rgba(255, 68, 68, 0.1);
            border-left-color: var(--error-color);
            color: var(--error-color);
        }

        .alert-success {
            background: rgba(0, 200, 81, 0.1);
            border-left-color: var(--success-color);
            color: var(--success-color);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .profile-container {
                padding: 1.5rem;
            }
            
            .avatar {
                width: 100px;
                height: 100px;
            }
            
            .stats-container {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 576px) {
            .profile-container {
                padding: 1.2rem;
                border-radius: 10px;
            }
            
            .stats-container {
                grid-template-columns: 1fr;
            }
            
            .btn-profile, .btn-change-password {
                width: 100%;
                margin-bottom: 0.5rem;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation Bar (consistent with other pages) -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top" style="background: linear-gradient(to right, #212121, rgb(3, 39, 166));">
        <div class="container">
            <a class="navbar-brand fw-bold fs-4" href="index.php" onclick="playNavSound()">
                <i class="fa fa-mobile-alt" style="font-size: 1.3rem; color: #f1f1f1;"></i> 
                <span style="color: #ffcc00;">All To All Mobile</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse text-center" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php" onclick="playNavSound()">
                            <i class="fa fa-home"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php" onclick="playNavSound()">
                            <i class="fa fa-info-circle"></i> About
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="faq.php" onclick="playNavSound()">
                            <i class="fa fa-question-circle"></i> FAQ
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php" onclick="playNavSound()">
                            <i class="fa fa-phone-alt"></i> Contact
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="feedback.php" onclick="playNavSound()">
                            <i class="fa fa-comment-dots"></i> Feedback
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="location.php" onclick="playNavSound()">
                            <i class="fa fa-map-marker-alt"></i> Location
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cart-view.php" onclick="playNavSound()">
                            <i class="fa fa-shopping-cart"></i> Cart
                            <span id="cart-count" class="badge bg-danger ms-1">0</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="my_order.php" onclick="playNavSound()">
                            <i class="fa fa-box"></i> My Orders
                        </a>
                    </li>
                </ul>
                
                <!-- User dropdown -->
                <div class="dropdown ms-lg-3 text-center">
                    <a class="btn btn-outline-light dropdown-toggle" href="#" role="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle me-1"></i> <?php echo htmlspecialchars($user['username']); ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item active" href="profile.php"><i class="fas fa-user me-2"></i>Profile</a></li>
                        <li><a class="dropdown-item" href="settings.php"><i class="fas fa-cog me-2"></i>Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <main class="container py-4">
        <div class="profile-container">
            <!-- Profile Header -->
            <div class="profile-header">
                <h1><i class="fas fa-user-circle me-2"></i>My Profile</h1>
            </div>
            
            <!-- Messages -->
            <?php if ($error): ?>
                <div class="alert-message alert-error">
                    <i class="fas fa-exclamation-circle me-2"></i><?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert-message alert-success">
                    <i class="fas fa-check-circle me-2"></i><?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>
            
            <!-- Avatar Section -->
            <div class="avatar-container">
                <div class="avatar-upload">
                    <img src="assets/default-avatar.jpg" alt="Profile Avatar" class="avatar" id="avatarPreview">
                    <label for="avatarInput" class="avatar-upload-label" title="Change Avatar">
                        <i class="fas fa-camera"></i>
                    </label>
                    <input type="file" id="avatarInput" class="avatar-upload-input" accept="image/*">
                </div>
            </div>
            
            <!-- User Stats -->
            <div class="stats-container">
                <div class="stat-card">
                    <div class="stat-number" id="orders-count">0</div>
                    <div class="stat-label">Total Orders</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number" id="wishlist-count">0</div>
                    <div class="stat-label">Wishlist</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo date('M Y', strtotime($user['created_at'])); ?></div>
                    <div class="stat-label">Member Since</div>
                </div>
            </div>
            
            <!-- Profile Details -->
            <div class="profile-details">
                <div class="detail-group">
                    <div class="detail-label"><i class="fas fa-user"></i> Username</div>
                    <div class="detail-value"><?php echo htmlspecialchars($user['username']); ?></div>
                </div>
                
                <div class="detail-group">
                    <div class="detail-label"><i class="fas fa-envelope"></i> Email</div>
                    <div class="detail-value"><?php echo htmlspecialchars($user['email']); ?></div>
                </div>
                
                <form method="POST" class="profile-form">
                    <div class="detail-group">
                        <div class="detail-label"><i class="fas fa-phone"></i> Phone Number</div>
                        <input type="tel" class="form-control" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" placeholder="Enter your phone number" required>
                    </div>
                    
                    <div class="detail-group">
                        <div class="detail-label"><i class="fas fa-map-marker-alt"></i> Address</div>
                        <textarea class="form-control" name="address" rows="3" placeholder="Enter your full address" required><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="d-flex flex-wrap justify-content-between mt-4">
                        <button type="submit" class="btn btn-profile">
                            <i class="fas fa-save me-2"></i>Save Changes
                        </button>
                        <a href="change-password.php" class="btn btn-change-password">
                            <i class="fas fa-key me-2"></i>Change Password
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Footer (consistent with other pages) -->
    <footer class="mt-auto" style="background: rgba(31, 28, 44, 0.7); backdrop-filter: blur(10px); color: white; padding: 40px 0; border-top-left-radius: 40px; border-top-right-radius: 40px; box-shadow: 0 -10px 30px rgba(0, 0, 0, 0.4);">
        <div class="container text-center">
            <p class="fs-4 mb-4" style="font-weight: 600; letter-spacing: 1.5px;">Stay Connected with Us</p>
            <div class="social-links mb-4">
                <a href="https://www.facebook.com/profile.php?id=100091625616068" class="text-decoration-none mx-2" aria-label="Facebook"><i class="fab fa-facebook fa-2x" style="color: #3b5998;"></i></a>
                <a href="#" class="text-decoration-none mx-2" aria-label="Twitter"><i class="fab fa-twitter fa-2x" style="color: #1da1f2;"></i></a>
                <a href="https://www.instagram.com/premguptaa_?utm_source=qr" class="text-decoration-none mx-2" aria-label="Instagram"><i class="fab fa-instagram fa-2x" style="color: #e4405f;"></i></a>
                <a href="#" class="text-decoration-none mx-2" aria-label="LinkedIn"><i class="fab fa-linkedin fa-2x" style="color: #0077b5;"></i></a>
                <a href="#" class="text-decoration-none mx-2" aria-label="YouTube"><i class="fab fa-youtube fa-2x" style="color: #ff0000;"></i></a>
            </div>
            <p class="mb-2">&copy; 2025 All To All Mobile. All Rights Reserved.</p>
            <p class="text-muted mb-0">Crafted with creativity & precision by <span class="text-warning">Prem Gupta</span></p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/4c729db828.js" crossorigin="anonymous"></script>
    
    <script>
        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Avatar preview
            const avatarInput = document.getElementById('avatarInput');
            const avatarPreview = document.getElementById('avatarPreview');
            
            avatarInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        avatarPreview.src = event.target.result;
                        
                        // In a real app, you would upload the image here
                        // uploadAvatar(file);
                    };
                    reader.readAsDataURL(file);
                }
            });
            
            // Fetch user stats
            fetchUserStats();
            
            // Update cart count
            updateCartCount();
        });
        
        function playNavSound() {
            // Play a subtle sound on navigation
            const audio = new Audio('assets/nav-click.mp3');
            audio.volume = 0.3;
            audio.play().catch(e => console.log("Audio play failed:", e));
        }
        
        function fetchUserStats() {
            // Simulate fetching user stats
            setTimeout(() => {
                document.getElementById('orders-count').textContent = '5';
                document.getElementById('wishlist-count').textContent = '3';
            }, 800);
            
            // In a real app:
            // fetch('get_user_stats.php')
            //     .then(response => response.json())
            //     .then(data => {
            //         document.getElementById('orders-count').textContent = data.orders;
            //         document.getElementById('wishlist-count').textContent = data.wishlist;
            //     });
        }
        
        function updateCartCount() {
            // In a real app, you would fetch this from your server
            fetch('get_cart_count.php')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('cart-count').textContent = data.count || 0;
                })
                .catch(error => {
                    console.error('Error fetching cart count:', error);
                });
        }
        
        function uploadAvatar(file) {
            // In a real app, you would implement avatar upload
            const formData = new FormData();
            formData.append('avatar', file);
            
            fetch('upload_avatar.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Avatar updated successfully!', 'success');
                } else {
                    showAlert(data.message || 'Failed to update avatar', 'error');
                }
            })
            .catch(error => {
                showAlert('Network error while updating avatar', 'error');
                console.error('Error:', error);
            });
        }
        
        function showAlert(message, type) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert-message alert-${type}`;
            alertDiv.innerHTML = `
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} me-2"></i>
                ${message}
            `;
            
            const container = document.querySelector('.profile-container');
            container.insertBefore(alertDiv, container.firstChild);
            
            setTimeout(() => {
                alertDiv.remove();
            }, 5000);
        }
    </script>
</body>
</html>