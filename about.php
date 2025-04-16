<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All To All Mobile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/4c729db828.js" crossorigin="anonymous"></script>
    <script type="module" src="https://unpkg.com/@google/model-viewer@latest"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">


    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css">
<!-- Inline CSS for Hover Effects and Styling -->
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



    <!-- why choose all to all  -->
<section style="font-family: 'Poppins', sans-serif; background: linear-gradient(135deg, #141e30, #243b55); color: #e0e0e0; padding: 80px 20px; text-align: center; position: relative; overflow: hidden;">

<!-- Glowing Gradient Background -->
<div style="position: absolute; top: -10%; left: -10%; width: 300px; height: 300px; background: radial-gradient(circle, #76e1ff 0%, transparent 70%); filter: blur(100px); opacity: 0.5;"></div>
<div style="position: absolute; bottom: -10%; right: -10%; width: 300px; height: 300px; background: radial-gradient(circle, #ff7eb3 0%, transparent 70%); filter: blur(100px); opacity: 0.5;"></div>

<!-- Section Heading -->
<h2 style="font-size: 3.5rem; margin-bottom: 30px; color: #76e1ff; letter-spacing: 2px; text-shadow: 0 0 25px rgba(118, 225, 255, 0.7); animation: fadeInDown 1.5s ease-out;">
  Why Choose All To All Mobile
</h2>
<p style="font-size: 1.3rem; color: #b0bec5; margin-bottom: 50px; max-width: 800px; margin: 0 auto; animation: fadeInUp 1.5s ease-out;">
  Your ultimate destination for all mobile gadgets and accessories.
</p>

<!-- Main Grid Layout -->
<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 40px; align-items: center;">

  <!-- Left Column (Cards) -->
  <div style="display: flex; flex-direction: column; gap: 30px;">
    <?php $features = ['Wide Selection' => 'Find smartphones, tablets, and accessories you need.', 'Quality Guaranteed' => 'All products go through rigorous checks.', 'Best Prices' => 'Enjoy competitive prices on all gadgets.']; foreach ($features as $title => $desc) { ?>
      <div style="background: rgba(255, 255, 255, 0.1); padding: 25px; border-radius: 20px; backdrop-filter: blur(15px); box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5); transition: transform 0.3s; cursor: pointer;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
        <h3 style="color: #76e1ff;"><?php echo $title; ?></h3>
        <p><?php echo $desc; ?></p>
      </div>
    <?php } ?>
  </div>

  <!-- Center Image -->
  <div style="display: flex; align-items: center; justify-content: center; animation: zoomIn 2s ease-in-out;">
    <div style="max-width: 350px; border-radius: 25px; overflow: hidden; box-shadow: 0 15px 50px rgba(118, 225, 255, 0.4); border: 3px solid #76e1ff;">
      <img src="images/gaget.webp" alt="Gadget Showcase" style="width: 100%; height: auto;">
    </div>
  </div>

  <!-- Right Column (Cards) -->
  <div style="display: flex; flex-direction: column; gap: 30px;">
    <?php $benefits = ['Expert Advice' => 'Get expert recommendations on the latest gadgets.', 'Easy Shopping' => 'Seamless browsing and secure checkout.', 'After-Sale Support' => 'Reliable customer support for all your needs.']; foreach ($benefits as $title => $desc) { ?>
      <div style="background: rgba(255, 255, 255, 0.1); padding: 25px; border-radius: 20px; backdrop-filter: blur(15px); box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5); transition: transform 0.3s; cursor: pointer;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
        <h3 style="color: #76e1ff;"><?php echo $title; ?></h3>
        <p><?php echo $desc; ?></p>
      </div>
    <?php } ?>
  </div>

</div>
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