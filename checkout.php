<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'config.php';

// Check if the cart is empty
if (empty($_SESSION['cart'])) {
    header("Location: cart-view.php");
    exit();
}

// Function to calculate the total price of the cart
function calculateTotal() {
    $total = 0;
    foreach ($_SESSION['cart'] as $productId => $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['place_order'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zip = $_POST['zip'];
    $country = $_POST['country'];
    $total = calculateTotal();
    $order_date = date('Y-m-d H:i:s');
    $payment_method = $_POST['payment_method'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO orders (user_id, name, email, phone, address, city, state, zip, country, total, order_date, payment_method, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending Payment')");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    $stmt->bind_param("issssssssdss", $user_id, $name, $email, $phone, $address, $city, $state, $zip, $country, $total, $order_date, $payment_method);

    if ($stmt->execute()) {
        $_SESSION['order_id'] = $stmt->insert_id;
        if ($payment_method === 'UPI') {
            header('Location: upload_proof.php');
        } else {
            header('Location: order_success.php?order_id=' . $stmt->insert_id);
        }
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Fetch QR code path
$qr_result = $conn->query("SELECT qr_code FROM settings WHERE id = 1");
if ($qr_result) {
    $qr_row = $qr_result->fetch_assoc();
    $qr_code_path = $qr_row['qr_code'] ?? null;
} else {
    $qr_code_path = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Checkout | All To All Mobile</title>
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
        --accent-color: #00e0ff;
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
    
    /* Form styling */
    .checkout-container {
        background: rgba(30, 30, 30, 0.95);
        backdrop-filter: blur(12px);
        border-radius: 20px;
        box-shadow: var(--box-shadow);
        border: 1px solid rgba(255, 255, 255, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .checkout-container:hover {
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.6);
        transform: translateY(-5px);
    }
    
    .section-title {
        font-weight: 700;
        text-shadow: var(--glow-effect);
        position: relative;
        display: inline-block;
    }
    
    .section-title::after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 0;
        width: 100%;
        height: 3px;
        background: var(--accent-color);
        transform: scaleX(0.7);
        border-radius: 3px;
    }
    
    .form-control {
        background: rgba(37, 37, 37, 0.8);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: white;
        transition: all 0.3s ease;
        padding: 12px 15px;
        border-radius: 12px;
    }
    
    .form-control:focus {
        background: rgba(45, 45, 45, 0.9);
        border-color: var(--accent-color);
        box-shadow: 0 0 0 0.25rem rgba(0, 224, 255, 0.25);
        color: white;
    }
    
    /* Payment section */
    .payment-section {
        background: rgba(30, 30, 30, 0.85);
        backdrop-filter: blur(15px);
        border-radius: 20px;
        box-shadow: var(--box-shadow);
        border: 1px solid rgba(255, 255, 255, 0.1);
        transition: all 0.3s ease;
    }
    
    .payment-section:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.8);
    }
    
    .upi-id-container {
        background: rgba(26, 26, 26, 0.8);
        border-radius: 12px;
        padding: 12px 20px;
        display: inline-flex;
        align-items: center;
        gap: 15px;
        box-shadow: 0 0 15px rgba(0, 224, 255, 0.3);
        transition: all 0.3s ease;
    }
    
    .upi-id-container:hover {
        transform: scale(1.05);
        box-shadow: 0 0 25px rgba(0, 224, 255, 0.5);
    }
    
    .qr-container {
        background: rgba(45, 45, 45, 0.9);
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0 10px 20px rgba(0, 224, 255, 0.2);
        transition: all 0.3s ease;
    }
    
    .qr-container:hover {
        transform: scale(1.05);
    }
    
    /* Button styles */
    .btn-checkout {
        background: var(--button-gradient);
        border: none;
        border-radius: 15px;
        padding: 15px;
        font-weight: 700;
        font-size: 18px;
        letter-spacing: 1px;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.6);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .btn-checkout:hover {
        background: var(--button-hover-gradient);
        transform: translateY(-3px) scale(1.03);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.8);
    }
    
    .btn-checkout::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: 0.5s;
    }
    
    .btn-checkout:hover::before {
        left: 100%;
    }
    
    /* Animation classes */
    .fade-in {
        animation: fadeIn 0.8s ease-in-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .pulse {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(0, 224, 255, 0.4); }
        70% { box-shadow: 0 0 0 15px rgba(0, 224, 255, 0); }
        100% { box-shadow: 0 0 0 0 rgba(0, 224, 255, 0); }
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
        <!-- Checkout Form -->
        <div class="row justify-content-center">
            <div class="col-lg-8 fade-in">
                <div class="checkout-container p-4 p-md-5 mb-5">
                    <h2 class="text-center mb-4 section-title animate__animated animate__fadeInDown">
                        <i class="fas fa-shopping-bag me-2"></i>Complete Your Order
                    </h2>
                    
                    <form method="POST" action="checkout.php">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" name="phone" required>
                        </div>
                        
                        <div class="mb-4">
                            <label for="address" class="form-label">Shipping Address</label>
                            <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-4">
                                <label for="city" class="form-label">City</label>
                                <input type="text" class="form-control" id="city" name="city" required>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="state" class="form-label">State</label>
                                <input type="text" class="form-control" id="state" name="state" required>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="zip" class="form-label">ZIP Code</label>
                                <input type="text" class="form-control" id="zip" name="zip" required>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="country" class="form-label">Country</label>
                            <input type="text" class="form-control" id="country" name="country" required>
                        </div>
                        
                        <div class="mb-4">
                            <label for="payment_method" class="form-label">Payment Method</label>
                            <select class="form-control" id="payment_method" name="payment_method" required>
                                <option value="">Select payment method</option>
                                <option value="Cash on Delivery">Cash on Delivery</option>
                                <option value="UPI">UPI Payment</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-checkout w-100 mt-3" name="place_order">
                            <i class="fas fa-paper-plane me-2"></i>Place Order
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Payment Information -->
        <div class="row justify-content-center animate__animated animate__fadeInUp">
            <div class="col-lg-8">
                <div class="payment-section p-4 p-md-5 text-center">
                    <h3 class="mb-4 section-title">
                        <i class="fas fa-credit-card me-2"></i>Payment Information
                    </h3>
                    
                    <div class="mb-4">
                        <p class="lead">Total Amount: <span class="fw-bold text-accent">₹<?php echo number_format(calculateTotal(), 2); ?></span></p>
                    </div>
                    
                    <div class="upi-payment mb-5">
                        <h5 class="mb-3"><i class="fas fa-qrcode me-2"></i>UPI Payment</h5>
                        <div class="d-flex justify-content-center align-items-center mb-4">
                            <div class="upi-id-container me-3">
                                <span class="fw-bold text-accent">gprem6783@okicici</span>
                                <button onclick="copyUPI()" class="btn btn-sm btn-link p-0">
                                    <i class="fas fa-copy text-white"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="qr-container d-inline-block mb-3 pulse">
                            <img src="images/qr.jpeg" alt="UPI QR Code" width="200" class="img-fluid rounded">
                        </div>
                        <p class="text-muted">Scan the QR code to make payment</p>
                    </div>
                    
                    <div class="cod-info">
                        <h5 class="mb-3"><i class="fas fa-money-bill-wave me-2"></i>Cash on Delivery</h5>
                        <p>Pay when your order is delivered</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
    function copyUPI() {
        const upiId = 'gprem6783@okicici';
        navigator.clipboard.writeText(upiId).then(() => {
            // Create and show a custom notification
            const notification = document.createElement('div');
            notification.style.position = 'fixed';
            notification.style.bottom = '20px';
            notification.style.right = '20px';
            notification.style.backgroundColor = '#00c853';
            notification.style.color = 'white';
            notification.style.padding = '15px 25px';
            notification.style.borderRadius = '8px';
            notification.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
            notification.style.zIndex = '1000';
            notification.style.animation = 'fadeIn 0.3s, fadeOut 0.3s 2s forwards';
            notification.innerHTML = '<i class="fas fa-check-circle me-2"></i> UPI ID copied to clipboard!';
            
            document.body.appendChild(notification);
            
            // Remove the notification after animation
            setTimeout(() => {
                notification.remove();
            }, 2500);
        });
    }
    
    // Add animation to form elements on scroll
    document.addEventListener('DOMContentLoaded', () => {
        const inputs = document.querySelectorAll('.form-control');
        
        inputs.forEach(input => {
            input.addEventListener('focus', () => {
                input.parentElement.classList.add('animate__animated', 'animate__pulse');
            });
            
            input.addEventListener('blur', () => {
                input.parentElement.classList.remove('animate__animated', 'animate__pulse');
            });
        });
        
        // Payment method selection effect
        const paymentMethod = document.getElementById('payment_method');
        if (paymentMethod) {
            paymentMethod.addEventListener('change', function() {
                this.style.borderColor = '#00e0ff';
                this.style.boxShadow = '0 0 0 0.25rem rgba(0, 224, 255, 0.25)';
                
                setTimeout(() => {
                    this.style.borderColor = '';
                    this.style.boxShadow = '';
                }, 1000);
            });
        }
    });
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>