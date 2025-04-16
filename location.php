<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Location</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/4c729db828.js" crossorigin="anonymous"></script>
    
    <link rel="stylesheet" href="style.css">
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
    <!-- Our Location Section -->
<section style="background: linear-gradient(135deg, #141e30, #243b55); padding: 60px 0; color: #e0e0e0; font-family: 'Poppins', sans-serif;">
    <div class="container text-center">
        <h2 style="color: #76e1ff; font-size: 2.5rem; font-weight: bold; text-shadow: 0 0 20px rgba(118, 225, 255, 0.8);">📍 Our Location</h2>
        <p style="color: #b0bec5; margin-bottom: 30px; font-size: 1.2rem;">Visit us at our store or find us on the map below:</p>

        <!-- Map -->
        <div style="box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6); border-radius: 20px; overflow: hidden; margin-bottom: 30px;">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3649.8516312543214!2d79.9468741153965!3d23.18093961322826!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3981ae54afc54d77%3A0x1c8a6eb7c6b8ed5c!2sJayanti%20Complex%2C%20Jabalpur!5e0!3m2!1sen!2sin!4v1694467866453!5m2!1sen!2sin"
                width="100%" 
                height="400" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>

        <!-- Contact Info -->
        <div style="background: rgba(0, 0, 0, 0.6); backdrop-filter: blur(15px); padding: 30px; border-radius: 20px; box-shadow: 0 15px 30px rgba(0, 0, 0, 0.5); display: inline-block; max-width: 600px; width: 100%;">
            <p style="font-size: 1.1rem; margin-bottom: 15px;"><i class="fa-solid fa-location-dot" style="color: #76e1ff; margin-right: 10px;"></i> Jayanti Complex, Jabalpur, India</p>
            <p style="font-size: 1.1rem; margin-bottom: 15px;"><i class="fa-solid fa-phone" style="color: #00e676; margin-right: 10px;"></i> +91 8839895124</p>
            <p style="font-size: 1.1rem;"><i class="fa-solid fa-envelope" style="color: #2196f3; margin-right: 10px;"></i> alltoall@gmail.com</p>
        </div>
    </div>
</section>

<!-- Font Awesome Icons -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

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

    <script>
    // Optional: Add hover effect on submit button
    document.querySelector('.btn-submit').addEventListener('mouseover', function() {
        this.style.backgroundColor = '#ff9800';
    });

    document.querySelector('.btn-submit').addEventListener('mouseout', function() {
        this.style.backgroundColor = '#ffbc00';
    });
</script>
</body>
</html>