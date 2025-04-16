<?php
session_start();
include 'config.php';

if (!isset($_SESSION['upload_success'])) {
    header('Location: index.php');
    exit();
}

$upload_success = $_SESSION['upload_success'];
unset($_SESSION['upload_success']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation | All To All Mobile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <script src="https://kit.fontawesome.com/4c729db828.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    
    <style>
    :root {
        --primary-bg: #121212;
        --secondary-bg: #1e1e1e;
        --primary-color: #ffffff;
        --secondary-color: rgba(255, 255, 255, 0.7);
        --highlight-color: #ffcc00;
        --accent-color: #00e0ff;
        --success-color: #00ff99;
        --navbar-bg-gradient: linear-gradient(to right, #212121, #0d0d0d);
        --button-gradient: linear-gradient(135deg, #00e0ff, #007bff);
        --button-hover-gradient: linear-gradient(135deg, #007bff, #00e0ff);
        --box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        --glow-effect: 0 0 15px rgba(0, 224, 255, 0.5);
    }
    
    body {
        background-color: var(--primary-bg);
        color: var(--primary-color);
        font-family: 'Poppins', sans-serif;
        overflow-x: hidden;
    }
    
    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }
    
    ::-webkit-scrollbar-track {
        background: var(--primary-bg);
    }
    
    ::-webkit-scrollbar-thumb {
        background: var(--accent-color);
        border-radius: 10px;
    }
    
    /* Navbar styles */
    .navbar {
        background: linear-gradient(to right, #212121, rgb(3, 39, 166)) !important;
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.5);
    }
    
    .navbar-brand {
        font-weight: 700;
        letter-spacing: 1px;
    }
    
    .navbar-brand span {
        color: var(--highlight-color);
    }
    
    .nav-link {
        position: relative;
        padding: 8px 15px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .nav-link:hover {
        color: var(--highlight-color) !important;
        transform: translateY(-2px);
    }
    
    .nav-link i {
        transition: transform 0.3s ease;
    }
    
    .nav-link:hover i {
        transform: rotate(15deg);
    }
    
    /* Confirmation container */
    .confirmation-container {
        background: rgba(30, 30, 30, 0.95);
        backdrop-filter: blur(12px);
        border-radius: 20px;
        box-shadow: var(--box-shadow);
        border: 1px solid rgba(255, 255, 255, 0.1);
        max-width: 600px;
        margin: 2rem auto;
        padding: 2.5rem;
        text-align: center;
    }
    
    .confirmation-container:hover {
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.8);
    }
    
    .confirmation-title {
        font-weight: 700;
        text-shadow: 0 0 20px rgba(0, 255, 153, 0.7);
        color: var(--success-color);
        position: relative;
        margin-bottom: 1.5rem;
    }
    
    .confirmation-title i {
        margin-right: 10px;
    }
    
    /* Success alert */
    .alert-success-custom {
        background: rgba(0, 255, 153, 0.1);
        color: var(--success-color);
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid rgba(0, 255, 153, 0.3);
        box-shadow: 0 0 15px rgba(0, 255, 153, 0.4);
        margin-bottom: 2rem;
    }
    
    /* Button styles */
    .btn-continue {
        background: var(--button-gradient);
        border: none;
        border-radius: 15px;
        padding: 12px 30px;
        font-weight: 600;
        font-size: 1.1rem;
        letter-spacing: 0.5px;
        box-shadow: 0 8px 20px rgba(0, 224, 255, 0.3);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    .btn-continue:hover {
        background: var(--button-hover-gradient);
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 12px 25px rgba(0, 224, 255, 0.4);
    }
    
    .btn-continue i {
        margin-right: 8px;
    }
    
    /* Footer styles */
    footer {
        background: linear-gradient(to right, #212121, #0d0d0d);
        padding: 2rem 0;
        margin-top: 3rem;
    }
    
    .social-description {
        color: var(--secondary-color);
        margin-bottom: 1.5rem;
    }
    
    .social-icons {
        display: flex;
        justify-content: center;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .social-icon {
        color: white;
        font-size: 1.5rem;
        transition: all 0.3s ease;
    }
    
    .social-icon:hover {
        color: var(--accent-color);
        transform: translateY(-5px) scale(1.2);
    }
    
    /* Animation classes */
    .fade-in {
        animation: fadeIn 0.8s ease-in-out;
    }
    
    .pulse {
        animation: pulse 2s infinite;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(0, 255, 153, 0.4); }
        70% { box-shadow: 0 0 0 15px rgba(0, 255, 153, 0); }
        100% { box-shadow: 0 0 0 0 rgba(0, 255, 153, 0); }
    }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold fs-4" href="index.php">
                <i class="fa fa-mobile-alt me-2"></i>
                <span>All To All Mobile</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php"><i class="fa fa-home"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cart-view.php"><i class="fa fa-shopping-cart"></i> Cart</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="my_order.php"><i class="fa fa-box"></i> My Orders</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container py-5">
        <!-- Confirmation Message -->
        <div class="confirmation-container fade-in">
            <h2 class="confirmation-title animate__animated animate__fadeInDown">
                <i class="fas fa-check-circle"></i>Payment Proof Uploaded
            </h2>
            
            <div class="alert alert-success-custom animate__animated animate__fadeInUp">
                <?php echo $upload_success; ?>
                <div class="mt-3">
                    Your order is now <strong>pending verification</strong>. We will notify you once the payment is confirmed.
                </div>
            </div>
            
            <div class="order-status mb-4">
                <div class="progress" style="height: 8px; background: rgba(255,255,255,0.1); border-radius: 10px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" 
                         role="progressbar" 
                         style="width: 50%; background-color: var(--success-color);" 
                         aria-valuenow="50" 
                         aria-valuemin="0" 
                         aria-valuemax="100">
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-2">
                    <span>Payment Uploaded</span>
                    <span>Processing</span>
                    <span>Completed</span>
                </div>
            </div>
            
            <div class="d-grid">
                <a href="order_success.php" class="btn btn-continue">
                    <i class="fas fa-shopping-bag me-2"></i>Continue Shopping
                </a>
            </div>
            
            <div class="mt-4 text-muted">
                <p>Need help? <a href="contact.php" style="color: var(--accent-color);">Contact our support team</a></p>
            </div>
        </div>
    </main>

    <!-- Footer Section -->
    <footer>
        <div class="container text-center">
            <p class="social-description">Stay connected with us on social media:</p>
            <div class="social-icons">
                <a href="https://www.facebook.com/profile.php?id=100091625616068" class="social-icon"><i class="fab fa-facebook"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                <a href="https://www.instagram.com/premguptaa_?utm_source=qr" class="social-icon"><i class="fab fa-instagram"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-linkedin"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-youtube"></i></a>
            </div>
            <p>&copy; 2025 All To All Mobile. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Add animation to elements when they come into view
    document.addEventListener('DOMContentLoaded', function() {
        const elements = document.querySelectorAll('.animate-on-scroll');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate__animated', 'animate__fadeInUp');
                }
            });
        }, { threshold: 0.1 });
        
        elements.forEach(element => {
            observer.observe(element);
        });
        
        // Add pulse animation to success icon
        const successIcon = document.querySelector('.confirmation-title i');
        if (successIcon) {
            setInterval(() => {
                successIcon.classList.toggle('animate__pulse');
            }, 2000);
        }
    });
    </script>
</body>
</html>