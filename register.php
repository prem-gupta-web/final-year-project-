<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if name, email, and password are set
    if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

        // Debugging: Print the received values
        echo "Received values: Name = $name, Email = $email";

        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $password);

        if ($stmt->execute()) {
            header("Location: login.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/4c729db828.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
    <style>
    body {
        background: #121212;
        font-family: 'Poppins', sans-serif;
        color: #e0e0e0;
    }
    .bg-gradient-dark {
        background: linear-gradient(145deg, #1f1f1f, #333);
    }
    .text-glow {
        background: linear-gradient(45deg, #00f2fe, #4facfe);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        text-shadow: 0 0 20px rgba(0, 242, 254, 0.7);
    }
    .btn-glow {
        background: linear-gradient(135deg, #ff416c, #ff4b2b);
        color: #fff;
        transition: all 0.3s ease;
        box-shadow: 0 0 15px rgba(255, 75, 43, 0.5);
    }
    .btn-glow:hover {
        transform: scale(1.05);
        box-shadow: 0 0 25px rgba(255, 75, 43, 0.8);
    }
    input.form-control {
        background: #2a2a2a;
        color: #e0e0e0;
        border: none;
    }
    input.form-control:focus {
        box-shadow: 0 0 10px #4facfe;
        border: 1px solid #4facfe;
    }
</style>
</head>
<body>
<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark sticky-top" style="background: linear-gradient(to right, #6a11cb, #2575fc);">
    <!-- Brand Logo -->
    <a class="navbar-brand fw-bold fs-4" href="index.php" style="font-family: 'Roboto', sans-serif; color: white; transition: all 0.3s ease;">
        <i class="fa fa-mobile-alt" style="font-size: 1.3rem; color: #f1f1f1;"></i> 
        <span style="color: #ffcc00;">All To All Mobile</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse text-center" id="navbarNav">
        <ul class="navbar-nav mx-auto">
            <li class="nav-item">
                <a class="nav-link text-white" href="index.php" style="font-size: 1.1rem; transition: color 0.3s ease;">
                    <i class="fa fa-home" style="margin-right: 8px; transition: transform 0.3s ease;"></i> Home
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="about.php" style="font-size: 1.1rem; transition: color 0.3s ease;">
                    <i class="fa fa-info-circle" style="margin-right: 8px; transition: transform 0.3s ease;"></i> About
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="faq.php" style="font-size: 1.1rem; transition: color 0.3s ease;">
                    <i class="fa fa-question-circle" style="margin-right: 8px; transition: transform 0.3s ease;"></i> FAQ
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="contact.php" style="font-size: 1.1rem; transition: color 0.3s ease;">
                    <i class="fa fa-phone-alt" style="margin-right: 8px; transition: transform 0.3s ease;"></i> Contact
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="feedback.php" style="font-size: 1.1rem; transition: color 0.3s ease;">
                    <i class="fa fa-comment-dots" style="margin-right: 8px; transition: transform 0.3s ease;"></i> Feedback
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="location.php" style="font-size: 1.1rem; transition: color 0.3s ease;">
                    <i class="fa fa-map-marker-alt" style="margin-right: 8px; transition: transform 0.3s ease;"></i> Location
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="cart-view.php" style="font-size: 1.1rem; transition: color 0.3s ease;">
                    <i class="fa fa-shopping-cart" style="margin-right: 8px; transition: transform 0.3s ease;"></i> Cart
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

<main class="container py-5" style="background: rgba(44, 62, 80, 0.9); border-radius: 20px; padding: 40px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5); max-width: 400px; width: 100%;">
        <section>
            <h2 class="text-center mb-4" style="color: #ecf0f1; text-shadow: 0 0 10px rgba(236, 240, 241, 0.8); letter-spacing: 1px;">Register</h2>
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="name" class="form-label" style="color: #bdc3c7;">Name:</label>
                    <input type="text" class="form-control" id="name" name="name" required style="background: rgba(236, 240, 241, 0.1); border: 1px solid #34495e; color: #ecf0f1;">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label" style="color: #bdc3c7;">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" required style="background: rgba(236, 240, 241, 0.1); border: 1px solid #34495e; color: #ecf0f1;">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label" style="color: #bdc3c7;">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" required style="background: rgba(236, 240, 241, 0.1); border: 1px solid #34495e; color: #ecf0f1;">
                </div>
                <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, #00bcd4, #2196f3); border: none; padding: 10px 20px; border-radius: 50px; color: white; font-weight: 700; letter-spacing: 1px; transition: all 0.3s ease; width: 100%; cursor: pointer; outline: none; text-transform: uppercase; margin-top: 20px;">Register</button>
            </form>
        </section>
    </main>


    <!-- Footer Section -->
    <footer class="bg-dark text-white py-4">
        <div class="container text-center">
        <div class="footer-icons">
    <a href="https://www.facebook.com/profile.php?id=100091625616068"><i class="fab fa-facebook"></i></a>
    <a href="#"><i class="fab fa-twitter"></i></a>
    <a href="https://www.instagram.com/premguptaa_?utm_source=qr"><i class="fab fa-instagram"></i></a>
        </div>
            <p>&copy; 2025 Shopping Website. All Rights Reserved.</p>

        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>