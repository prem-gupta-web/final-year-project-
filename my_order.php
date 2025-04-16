<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?message=Please log in to view your orders.");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user's orders with more detailed information
$stmt = $conn->prepare("SELECT o.*, COUNT(oi.id) as item_count 
                       FROM orders o 
                       LEFT JOIN order_items oi ON o.id = oi.order_id 
                       WHERE o.user_id = ? 
                       GROUP BY o.id 
                       ORDER BY o.created_at DESC");
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders | All To All Mobile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <script src="https://kit.fontawesome.com/4c729db828.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
    <style>
        :root {
            --primary-bg: #121212;
            --secondary-bg: #1e1e1e;
            --primary-color: #ffffff;
            --secondary-color: rgba(255, 255, 255, 0.7);
            --highlight-color: #ffcc00;
            --navbar-bg-gradient: linear-gradient(to right, #212121, #0d0d0d);
            --button-gradient: linear-gradient(to right, #6a11cb, #2575fc);
            --button-hover-gradient: linear-gradient(to right, #2575fc, #6a11cb);
            --logout-button-gradient: linear-gradient(to right, #cb2d3e, #ef473a);  
            --logout-button-hover-gradient: linear-gradient(to right, #ef473a, #cb2d3e);
            --box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        
        body {
            background-color: var(--primary-bg);
            color: var(--primary-color);
            font-family: 'Roboto', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--primary-bg);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--highlight-color);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #ffb300;
        }
        
        /* Order status colors */
        .status-completed {
            color: #00ff99;
            text-shadow: 0 0 8px rgba(0, 255, 153, 0.5);
        }
        
        .status-pending {
            color: #ffcc00;
            text-shadow: 0 0 8px rgba(255, 204, 0, 0.5);
        }
        
        .status-cancelled {
            color: #ff4444;
            text-shadow: 0 0 8px rgba(255, 68, 68, 0.5);
        }
        
        .status-processing {
            color: #4d88ff;
            text-shadow: 0 0 8px rgba(77, 136, 255, 0.5);
        }

        /* Main content */
        .main-container {
            flex: 1;
            padding-bottom: 40px;
        }
        
        .orders-container {
            background: linear-gradient(135deg, #1a1a1a, #0f0f0f);
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(255, 215, 0, 0.15);
            border: 1px solid rgba(255, 204, 0, 0.1);
            padding: 30px;
            margin-top: 30px;
            position: relative;
            overflow: hidden;
        }
        
        .orders-container::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 204, 0, 0.05) 0%, transparent 70%);
            animation: rotateGradient 20s linear infinite;
            z-index: 0;
        }
        
        @keyframes rotateGradient {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .page-title {
            color: var(--highlight-color);
            font-weight: 700;
            text-shadow: 0 0 15px rgba(255, 204, 0, 0.5);
            letter-spacing: 1.5px;
            position: relative;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid rgba(255, 204, 0, 0.3);
        }
        
        .page-title::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100px;
            height: 2px;
            background: linear-gradient(90deg, var(--highlight-color), transparent);
        }
        
        .orders-table-container {
            max-height: 500px;
            overflow-y: auto;
            border-radius: 10px;
            background: rgba(30, 30, 30, 0.7);
            padding: 2px;
            scrollbar-width: thin;
            scrollbar-color: var(--highlight-color) #222;
            position: relative;
            z-index: 1;
            border: 1px solid rgba(255, 204, 0, 0.1);
        }
        
        .orders-table {
            background: rgba(25, 25, 25, 0.8);
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 0;
        }
        
        .orders-table thead {
            background: linear-gradient(135deg, #ff9a00, #ffcc00);
            color: #111;
            font-weight: bold;
            position: sticky;
            top: 0;
            z-index: 2;
        }
        
        .orders-table th {
            padding: 15px;
            text-align: center;
            vertical-align: middle;
            border-bottom: 2px solid rgba(0, 0, 0, 0.2);
        }
        
        .orders-table td {
            padding: 12px;
            vertical-align: middle;
            border-bottom: 1px solid rgba(255, 204, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .order-row:hover {
            background: rgba(255, 204, 0, 0.1) !important;
            transform: translateX(5px);
        }
        
        .view-proof-btn {
            background: linear-gradient(135deg, #ff9a00, #ffcc00);
            color: #0f0f0f;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            padding: 8px 15px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }
        
        .view-proof-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 204, 0, 0.4);
            color: #0f0f0f;
        }
        
        .no-orders {
            padding: 40px;
            text-align: center;
            color: var(--secondary-color);
            font-size: 1.2rem;
        }
        
        .no-orders-icon {
            font-size: 3rem;
            color: var(--highlight-color);
            margin-bottom: 20px;
            display: block;
        }
        
        .order-id {
            color: var(--highlight-color);
            font-weight: bold;
            letter-spacing: 1px;
        }
        
        .order-total {
            font-weight: bold;
            color: #ff6666;
        }
        
        /* Footer */
        .footer {
            background: rgba(31, 28, 44, 0.9);
            backdrop-filter: blur(10px);
            color: white;
            padding: 40px 0;
            border-top-left-radius: 40px;
            border-top-right-radius: 40px;
            box-shadow: 0 -10px 30px rgba(0, 0, 0, 0.4);
            margin-top: auto;
        }
        
        .social-icons a {
            color: white;
            font-size: 1.8rem;
            margin: 0 10px;
            transition: all 0.3s ease;
            display: inline-block;
        }
        
        .social-icons a:hover {
            transform: translateY(-5px) scale(1.2);
            text-shadow: 0 0 15px currentColor;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .orders-container {
                padding: 20px;
            }
            
            .orders-table th, 
            .orders-table td {
                padding: 10px 5px;
                font-size: 0.9rem;
            }
            
            .view-proof-btn {
                padding: 5px 10px;
                font-size: 0.8rem;
            }
        }
        
        @media (max-width: 576px) {
            .orders-container {
                padding: 15px;
            }
            
            .page-title {
                font-size: 1.5rem;
            }
        }
            /* Navbar text hover effect */
    .nav-link:hover {
        color: #ffcc00 !important;
        text-decoration: none;
        transform: scale(1.1);
    }

    /* Navbar links and icons */
    .nav-link {
        font-weight: 500;
        transition: color 0.3s ease, transform 0.3s ease;
    }

    .nav-link i {
        margin-right: 8px;
        transition: transform 0.3s ease;
    }

    .nav-link:hover i {
        transform: rotate(10deg);
    }

    /* Navbar branding hover effect */
    .navbar-brand:hover {
        color: #ffcc00 !important;
    }

    /* Navbar responsive styles */
    @media (max-width: 991px) {
        .navbar-nav {
            text-align: left;
        }
        .navbar-nav .nav-item {
            margin-left: 0;
        }
    }
    </style>
</head>

<body>
 <!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark sticky-top" style="background: linear-gradient(to right, #212121,rgb(3, 39, 166));">
    <!-- Brand Logo -->
    <a class="navbar-brand fw-bold fs-4" href="index.php" style="font-family: 'Roboto', sans-serif; color: #ffffff; transition: all 0.3s ease;" onclick="playNavSound()">
        <i class="fa fa-mobile-alt" style="font-size: 1.3rem; color: #f1f1f1;"></i> 
        <span style="color: #ffcc00;">All To All Mobile</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse text-center" id="navbarNav">
        <ul class="navbar-nav mx-auto">
            <li class="nav-item">
                <a class="nav-link" href="index.php" style="color: #ffffff; font-size: 1.1rem; transition: color 0.3s ease;" onclick="playNavSound()">
                    <i class="fa fa-home" style="margin-right: 8px; transition: transform 0.3s ease;"></i> Home
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="about.php" style="color: #ffffff; font-size: 1.1rem; transition: color 0.3s ease;" onclick="playNavSound()">
                    <i class="fa fa-info-circle" style="margin-right: 8px; transition: transform 0.3s ease;"></i> About
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="faq.php" style="color: #ffffff; font-size: 1.1rem; transition: color 0.3s ease;" onclick="playNavSound()">
                    <i class="fa fa-question-circle" style="margin-right: 8px; transition: transform 0.3s ease;"></i> FAQ
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="contact.php" style="color: #ffffff; font-size: 1.1rem; transition: color 0.3s ease;" onclick="playNavSound()">
                    <i class="fa fa-phone-alt" style="margin-right: 8px; transition: transform 0.3s ease;"></i> Contact
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="feedback.php" style="color: #ffffff; font-size: 1.1rem; transition: color 0.3s ease;" onclick="playNavSound()">
                    <i class="fa fa-comment-dots" style="margin-right: 8px; transition: transform 0.3s ease;"></i> Feedback
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="location.php" style="color: #ffffff; font-size: 1.1rem; transition: color 0.3s ease;" onclick="playNavSound()">
                    <i class="fa fa-map-marker-alt" style="margin-right: 8px; transition: transform 0.3s ease;"></i> Location
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="cart-view.php" style="color: #ffffff; font-size: 1.1rem; transition: color 0.3s ease;" onclick="playNavSound()">
                    <i class="fa fa-shopping-cart" style="margin-right: 8px; transition: transform 0.3s ease;"></i> Cart
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="my_order.php" style="color: #ffffff; font-size: 1.1rem; transition: color 0.3s ease;" onclick="playNavSound()">
                    <i class="fa fa-box" style="margin-right: 8px; transition: transform 0.3s ease;"></i> My Orders
                </a>
            </li>
        </ul>       
    </div>
</nav>


    <div class="main-container">
        <div class="container">
            <div class="orders-container animate__animated animate__fadeIn">
                <h1 class="page-title animate__animated animate__fadeInDown">
                    <i class="fas fa-box-open"></i> My Order History
                </h1>
                
                <div class="orders-table-container">
                    <?php if ($result->num_rows > 0): ?>
                        <table class="table orders-table">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Date</th>
                                    <th>Items</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr class="order-row">
                                        <td class="order-id">#<?php echo $row['id']; ?></td>
                                        <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                                        <td><?php echo $row['item_count']; ?> item(s)</td>
                                        <td>
                                            <span class="status-<?php echo strtolower($row['status']); ?>">
                                                <?php echo $row['status']; ?>
                                            </span>
                                        </td>
                                        <td class="order-total">₹<?php echo number_format($row['total'], 2); ?></td>
                                        <td>
                                            <?php if ($row['payment_proof']): ?>
                                                <a href="uploads/<?php echo $row['payment_proof']; ?>" target="_blank" class="view-proof-btn">
                                                    <i class="fas fa-receipt"></i> View Proof
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted">No proof</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="no-orders">
                            <i class="fas fa-box-open no-orders-icon"></i>
                            <h3>No Orders Found</h3>
                            <p>You haven't placed any orders yet. Start shopping to see your orders here!</p>
                            <a href="index.php" class="btn btn-warning mt-3">
                                <i class="fas fa-shopping-bag"></i> Shop Now
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Section -->
    <footer class="footer">
        <div class="container text-center">
            <p class="fs-4 mb-4" style="font-weight: 600; letter-spacing: 1.5px;">Stay Connected with Us</p>
            <div class="social-icons mb-4">
                <a href="https://www.facebook.com/profile.php?id=100091625616068" title="Facebook"><i class="fab fa-facebook"></i></a>
                <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
                <a href="https://www.instagram.com/premguptaa_?utm_source=qr" title="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="#" title="LinkedIn"><i class="fab fa-linkedin"></i></a>
                <a href="#" title="YouTube"><i class="fab fa-youtube"></i></a>
            </div>
            <p class="mb-2">&copy; 2025 All To All Mobile. All Rights Reserved.</p>
            <p class="text-muted" style="font-style: italic;">
                Crafted with <i class="fas fa-heart" style="color: #ff6b81;"></i> by <span style="color: #ffcc00; font-weight: bold;">Prem Gupta</span>
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add animation to order rows when page loads
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('.order-row');
            rows.forEach((row, index) => {
                row.style.animationDelay = `${index * 0.1}s`;
                row.classList.add('animate__animated', 'animate__fadeInUp');
            });
        });
        
        // Function to play navigation sound (if you implement it)
        function playNavSound() {
            // Implementation for navigation sound effect
        }
    </script>
</body>
</html>