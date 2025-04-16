<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'config.php';
include 'cart.php';

$cart = new Cart();
$cartItems = $cart->getCartItems();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/4c729db828.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css">
    <script type="module" src="https://unpkg.com/@google/model-viewer@latest"></script>


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
         /* Custom scrollbar */
         ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--primary-bg);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--highlight-color);
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #ffb300;
        }

        body {
            background-color: var(--primary-bg);
            color: var(--primary-color);
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
        }

    .text-gradient {
        background: linear-gradient(45deg, #6a11cb, #2575fc);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .table {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    .table-hover tbody tr:hover {
        background-color: #f1f9ff;
        transition: all 0.3s ease;
    }
    .btn-outline-danger {
        transition: all 0.3s ease;
    }
    .btn-outline-danger:hover {
        background-color: #ff4d4f;
        color: white;
    }
    .btn-primary {
        background: linear-gradient(135deg, #2575fc, #6a11cb);
        border: none;
        transition: transform 0.3s ease;
    }
    .btn-primary:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
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

<!-- Inline CSS for Hover Effects and Styling -->
<style>
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

<main class="container py-5">
    <h2 class="text-center mb-5 display-4 fw-bold text-gradient">🛒 Your Shopping Cart</h2>
    <div class="cart-list shadow-lg p-4 rounded-4 bg-light">
        <?php if (!empty($cartItems)): ?>
            <table class="table table-hover text-center rounded-3 overflow-hidden">
                <thead class="table-primary">
                    <tr>
                        <th>Product</th>
                        <th>Image</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $totalPrice = 0;
                    foreach ($cartItems as $productId => $item):
                        $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
                        $stmt->bind_param("i", $productId);
                        $stmt->execute();
                        $product = $stmt->get_result()->fetch_assoc();
                        if ($product) {
                            $itemTotal = $product['price'] * $item['quantity'];
                            $totalPrice += $itemTotal;
                    ?>
                    <tr class="align-middle">
                        <td class="fw-semibold text-dark">📱 <?php echo htmlspecialchars($product['name']); ?></td>
                        <td>
                            <?php if (pathinfo($product['image'], PATHINFO_EXTENSION) === 'glb'): ?>
                                <model-viewer src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" ar auto-rotate camera-controls style="width: 80px; height: 80px;" class="rounded-3 shadow-sm"></model-viewer>
                            <?php else: ?>
                                <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="img-thumbnail rounded-3 shadow-sm" width="80">
                            <?php endif; ?>
                        </td>
                        <td class="fw-bold">x<?php echo htmlspecialchars($item['quantity']); ?></td>
                        <td class="text-success fw-semibold">₹<?php echo number_format($product['price'], 2); ?></td>
                        <td class="text-primary fw-bold">₹<?php echo number_format($itemTotal, 2); ?></td>
                        <td>
                            <a href="remove-from-cart.php?product_id=<?php echo $productId; ?>" class="btn btn-outline-danger btn-sm rounded-pill">Remove</a>
                        </td>
                    </tr>
                    <?php
                        }
                    endforeach;
                    ?>
                </tbody>
            </table>
            <div class="cart-total text-end mt-4">
                <h3 class="fw-bold text-dark">Total: <span class="text-success">₹<?php echo number_format($totalPrice, 2); ?></span></h3>
            </div>
            <div class="text-center mt-4">
                <a href="checkout.php" class="btn btn-lg btn-primary rounded-3 shadow-lg px-5 py-2">Proceed to Checkout ➡️</a>
            </div>
        <?php else: ?>
            <div class="alert alert-warning text-center py-5 rounded-3 shadow">🛍️ Your cart is empty. Start shopping now!</div>
        <?php endif; ?>
    </div>
</main>

   <!-- Footer Section -->
<footer style="background: rgba(31, 28, 44, 0.7); backdrop-filter: blur(10px); color: white; padding: 40px 0; border-top-left-radius: 40px; border-top-right-radius: 40px; box-shadow: 0 -10px 30px rgba(0, 0, 0, 0.4); font-family: 'Poppins', sans-serif; text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.6);">
    <div style="text-align: center; max-width: 1000px; margin: auto;">
        <p style="font-size: 1.6rem; margin-bottom: 25px; letter-spacing: 1.5px; font-weight: 600;">Stay Connected with Us</p>
        <div style="margin: 30px 0; display: flex; justify-content: center; gap: 20px; flex-wrap: wrap;">
            <a href="https://www.facebook.com/profile.php?id=100091625616068" style="color: #3b5998; font-size: 2rem; text-decoration: none; transition: all 0.3s ease;" onmouseover="this.style.textShadow='0 0 15px #3b5998', this.style.transform='scale(1.3)'" onmouseout="this.style.textShadow='none', this.style.transform='scale(1)'"><i class="fa-brands fa-facebook"></i></a>
            <a href="#" style="color: #1da1f2; font-size: 2rem; text-decoration: none; transition: all 0.3s ease;" onmouseover="this.style.textShadow='0 0 15px #1da1f2', this.style.transform='scale(1.3)'" onmouseout="this.style.textShadow='none', this.style.transform='scale(1)'"><i class="fa-brands fa-twitter"></i></a>
            <a href="https://www.instagram.com/premguptaa_?utm_source=qr" style="color: #e4405f; font-size: 2rem; text-decoration: none; transition: all 0.3s ease;" onmouseover="this.style.textShadow='0 0 15px #e4405f', this.style.transform='scale(1.3)'" onmouseout="this.style.textShadow='none', this.style.transform='scale(1)'"><i class="fa-brands fa-instagram"></i></a>
            <a href="#" style="color: #0077b5; font-size: 2rem; text-decoration: none; transition: all 0.3s ease;" onmouseover="this.style.textShadow='0 0 15px #0077b5', this.style.transform='scale(1.3)'" onmouseout="this.style.textShadow='none', this.style.transform='scale(1)'"><i class="fa-brands fa-linkedin"></i></a>
            <a href="#" style="color: #ff0000; font-size: 2rem; text-decoration: none; transition: all 0.3s ease;" onmouseover="this.style.textShadow='0 0 15px #ff0000', this.style.transform='scale(1.3)'" onmouseout="this.style.textShadow='none', this.style.transform='scale(1)'"><i class="fa-brands fa-youtube"></i></a>
        </div>
        <p style="font-size: 1.1rem; margin-top: 30px; font-weight: 500;">&copy; 2025 Shopping Website. All Rights Reserved.</p>
        <p style="font-size: 1rem; margin-top: 15px; color: #dcdcdc; font-style: italic; letter-spacing: 1px;">Crafted with creativity & precision by <span style="color: #ff6b81; font-weight: bold;">Prem Gupta</span></p>
    </div>
</footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>