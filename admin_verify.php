<?php
session_start();
include 'config.php';

$sql = "SELECT * FROM orders WHERE payment_proof IS NOT NULL AND status = 'Pending Payment'";
$result = $conn->query($sql);

if ($result === false) {
    die("Error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Verification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top shadow">
        <div class="container">
            <a class="navbar-brand fw-bold fs-4" href="index.php"><i class="fa fa-mobile-alt"></i> All To All Mobile</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse text-center" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link text-white" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="about.php">About</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="faq.php">FAQ</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="contact.php">Contact</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="feedback.php">FB</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="location.php">Location</a></li>                  
                    <li class="nav-item"><a class="nav-link text-white" href="cart-view.php"><i class="fa fa-shopping-cart"></i> Cart</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container py-5">
        <h2 class="text-center mb-4">Admin Payment Verification</h2>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Order ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Total Amount</th>
                    <th>Payment Proof</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td>₹<?php echo $row['total']; ?></td>
                        <td><a href="uploads/<?php echo $row['payment_proof']; ?>" target="_blank">View Proof</a></td>
                        <td>
                            <form method="POST" action="verify_payment.php">
                                <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="btn btn-success btn-sm" name="confirm_payment">Confirm Payment</button>
                                <button type="submit" class="btn btn-danger btn-sm" name="reject_payment">Reject Payment</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>

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