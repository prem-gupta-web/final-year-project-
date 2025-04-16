<?php
session_start();
include 'config.php';

if (!isset($_GET['order_id'])) {
    die("Invalid request.");
}

$order_id = $_GET['order_id'];

// Update the order status to 'Confirmed'
$stmt = $conn->prepare("UPDATE orders SET status = 'Confirmed' WHERE id = ?");
if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}

$stmt->bind_param("i", $order_id);

if ($stmt->execute()) {
    echo "";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <main class="container py-5">
        <h2 class="text-center mb-4">Order Confirmation</h2>
        <p class="text-center">Your order has been confirmed. Thank you for shopping with us!</p>
        <div class="text-center">
            <a href="index.php" class="btn btn-primary">Continue Shopping</a>
        </div>
    </main>
     <!-- Footer Section -->
     <footer class="bg-dark text-white py-4">
        <div class="container text-center">
            <p class="social-description">Stay connected with us on social media:</p>
            <div class="social-icons">
                <a href="https://www.facebook.com/profile.php?id=100091625616068" class="social-icon"><i class="fa-brands fa-facebook"></i></a>
                <a href="#" class="social-icon"><i class="fa-brands fa-twitter"></i></a>
                <a href="https://www.instagram.com/premguptaa_?utm_source=qr" class="social-icon"><i class="fa-brands fa-instagram"></i></a>
                <a href="#" class="social-icon"><i class="fa-brands fa-linkedin"></i></a>
                <a href="#" class="social-icon"><i class="fa-brands fa-youtube"></i></a>
            </div>
            <p>&copy; 2025 Shopping Website. All Rights Reserved.</p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>