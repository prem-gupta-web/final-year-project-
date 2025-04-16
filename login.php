<?php
session_start();
include 'config.php';

if (isset($_GET['message'])) {
    echo '<div class="alert alert-warning" role="alert">' . htmlspecialchars($_GET['message']) . '</div>';
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and bind
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $hashed_password);
        $stmt->fetch();
        
        // Verify password
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $user_id;
            header("Location: index.php");
            exit();
        } else {
            $error_message = "Invalid email or password.";
        }
    } else {
        $error_message = "Invalid email or password.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/4c729db828.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: #f0f0f0;
            font-family: 'Poppins', sans-serif;
        }
        .navbar {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            padding: 1rem 2rem;
        }
        .navbar-brand {
            font-size: 2rem;
            letter-spacing: 1px;
        }
        .nav-link {
            transition: all 0.3s ease;
            font-weight: 500;
        }
        .nav-link:hover {
            color: #ffdd57 !important;
            transform: scale(1.1);
        }
        .login-card {
            background: linear-gradient(145deg, #1f2a40, #263b5e);
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
        }
        .btn-primary {
            background: linear-gradient(to right, #00c6ff, #0072ff);
            border: none;
            transition: transform 0.3s ease;
        }
        .btn-primary:hover {
            background: linear-gradient(to right, #0072ff, #00c6ff);
            transform: scale(1.05);
        }
        footer {
            background: #151515;
            padding: 2rem 0;
        }
        .social-icons a {
            color: #f0f0f0;
            margin: 0 12px;
            font-size: 1.8rem;
            transition: all 0.3s ease;
        }
        .social-icons a:hover {
            transform: scale(1.3);
            color: #ffdd57;
        }
    </style>
</head>
<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark sticky-top" style="background: linear-gradient(to right, #6a11cb, #2575fc);">
    <a class="navbar-brand fw-bold fs-4" href="index.php">
        <i class="fa fa-mobile-alt"></i> All To All Mobile
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse text-center" id="navbarNav">
        <ul class="navbar-nav mx-auto">
            <li class="nav-item"><a class="nav-link text-white" href="index.php"><i class="fa fa-home"></i> Home</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="about.php"><i class="fa fa-info-circle"></i> About</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="faq.php"><i class="fa fa-question-circle"></i> FAQ</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="contact.php"><i class="fa fa-phone-alt"></i> Contact</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="feedback.php"><i class="fa fa-comment-dots"></i> Feedback</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="location.php"><i class="fa fa-map-marker-alt"></i> Location</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="cart-view.php"><i class="fa fa-shopping-cart"></i> Cart</a></li>
        </ul>
    </div>
</nav>

<main class="container py-5">
    <div style="max-width: 400px; margin: 100px auto; padding: 40px; background: linear-gradient(135deg, #1f1f1f, #333); border-radius: 12px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);">
        <h2 style="text-align: center; background: linear-gradient(45deg, #00f2fe, #4facfe); -webkit-background-clip: text; -webkit-text-fill-color: transparent; text-shadow: 0 0 20px rgba(0, 242, 254, 0.7);">Login</h2>
        <?php if (isset($error)): ?>
            <div style="color: #ff4b2b; font-size: 0.9rem; margin-bottom: 1rem; text-align: center;"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST" action="" style="text-align: center;">
            <div class="mb-3">
                <label for="email" style="display: block; color: #e0e0e0; margin-bottom: 8px;">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required style="background: #2a2a2a; color: #e0e0e0; border: none; border-radius: 10px; padding: 10px 15px; width: 100%;">
            </div>
            <div class="mb-3">
                <label for="password" style="display: block; color: #e0e0e0; margin-bottom: 8px;">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required style="background: #2a2a2a; color: #e0e0e0; border: none; border-radius: 10px; padding: 10px 15px; width: 100%;">
            </div>
            <div class="mb-3" style="text-align: right;">
                <a href="register.php" style="color: #4facfe; text-decoration: none;">Don't have an account? Register</a>
            </div>
            <button type="submit" style="background: linear-gradient(135deg, #ff416c, #ff4b2b); color: #fff; transition: all 0.3s ease; padding: 12px 25px; border: none; border-radius: 25px; box-shadow: 0 0 15px rgba(255, 75, 43, 0.5); width: 100%; cursor: pointer;">Login</button>
        </form>
    </div>
</main>

<footer style="background: #121212; color: white; padding: 40px 0; text-align: center;">
    <div style="font-size: 24px;">
        <a href="https://www.facebook.com/profile.php?id=100091625616068" style="color: #4facfe; margin-right: 20px;"><i class="fab fa-facebook"></i></a>
        <a href="#" style="color: #4facfe; margin-right: 20px;"><i class="fab fa-twitter"></i></a>
        <a href="https://www.instagram.com/premguptaa_?utm_source=qr" style="color: #4facfe;"><i class="fab fa-instagram"></i></a>
    </div>
    <p style="margin-top: 20px;">&copy; 2025 Shopping Website. All Rights Reserved.</p>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
