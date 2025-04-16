<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shopping_website";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO contacts (name, email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $message);

    // Execute the statement
    if ($stmt->execute()) {
        $success_message = "Thank you for contacting us, $name! We'll get back to you soon.";
    } else {
        $error_message = "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - All To All Mobile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-bg: #121212;
            --secondary-bg: #1e1e1e;
            --primary-color: #ffffff;
            --secondary-color: rgba(255, 255, 255, 0.7);
            --highlight-color: #ffcc00;
            --accent-blue: #76e1ff;
            --contact-bg: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
            --form-bg: rgba(255, 255, 255, 0.1);
            --box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
            --transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.1);
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

        body {
            background-color: var(--primary-bg);
            color: var(--primary-color);
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
        }

        /* Contact Section */
        .contact-section {
            background: var(--contact-bg);
            border-radius: 20px;
            box-shadow: var(--box-shadow);
            overflow: hidden;
            position: relative;
            padding: 5rem 2rem;
            margin: 5rem auto;
            max-width: 1200px;
            min-height: 70vh;
            display: flex;
            align-items: center;
        }

        .contact-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(60deg, rgba(255, 0, 150, 0.2), rgba(0, 229, 255, 0.2));
            filter: blur(100px);
            animation: pulse 8s infinite alternate;
            z-index: 0;
        }

        @keyframes pulse {
            0% { opacity: 0.3; transform: scale(1); }
            50% { opacity: 0.5; }
            100% { opacity: 0.3; transform: scale(1.1); }
        }

        .contact-content {
            position: relative;
            z-index: 1;
            width: 100%;
        }

        .contact-image {
            max-width: 80%;
            border: 6px solid rgba(255, 255, 255, 0.1);
            padding: 20px;
            backdrop-filter: blur(15px);
            background: var(--form-bg);
            border-radius: 50%;
            transition: var(--transition);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .contact-image:hover {
            transform: scale(1.05) rotate(5deg);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4);
        }

        .contact-form-container {
            background: var(--form-bg);
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(20px);
            transition: var(--transition);
        }

        .contact-form-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4);
        }

        .contact-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: var(--highlight-color);
            position: relative;
            display: inline-block;
        }

        .contact-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 60px;
            height: 3px;
            background: var(--accent-blue);
            border-radius: 3px;
        }

        .contact-description {
            color: var(--secondary-color);
            margin-bottom: 2rem;
            font-size: 1.1rem;
            line-height: 1.6;
        }

        /* Form Elements */
        .form-group {
            position: relative;
            margin-bottom: 2rem;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: var(--primary-color);
            padding: 1rem;
            border-radius: 12px;
            transition: var(--transition);
            width: 100%;
        }

        .form-control:focus {
            border-color: var(--highlight-color);
            box-shadow: 0 0 20px rgba(255, 204, 0, 0.4);
            outline: none;
            background: rgba(255, 255, 255, 0.1);
        }

        .floating-label {
            position: absolute;
            top: 1rem;
            left: 1rem;
            color: var(--secondary-color);
            transition: var(--transition);
            pointer-events: none;
            font-size: 1rem;
        }

        .form-control:focus + .floating-label,
        .form-control:not(:placeholder-shown) + .floating-label {
            top: -0.8rem;
            left: 0.8rem;
            font-size: 0.85rem;
            color: var(--highlight-color);
            background: var(--contact-bg);
            padding: 0 0.5rem;
        }

        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }

        .submit-btn {
            background: linear-gradient(135deg, #00f5a8, #00d4ff);
            border: none;
            color: #fff;
            padding: 1rem 2.5rem;
            border-radius: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 8px 30px rgba(0, 229, 255, 0.6);
            transition: var(--transition);
            width: 100%;
            cursor: pointer;
        }

        .submit-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 229, 255, 0.8);
            background: linear-gradient(135deg, #00d4ff, #00f5a8);
        }

        /* Alert Messages */
        .alert {
            border-radius: 12px;
            padding: 1rem 1.5rem;
            margin-bottom: 2rem;
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(0, 200, 83, 0.9), rgba(0, 230, 118, 0.9));
            color: white;
        }

        .alert-danger {
            background: linear-gradient(135deg, rgba(255, 61, 0, 0.9), rgba(255, 107, 0, 0.9));
            color: white;
        }

        /* Footer */
        .footer {
            background: rgba(31, 28, 44, 0.9);
            backdrop-filter: blur(10px);
            color: white;
            padding: 3rem 0;
            border-top-left-radius: 40px;
            border-top-right-radius: 40px;
            box-shadow: 0 -10px 30px rgba(0, 0, 0, 0.4);
            position: relative;
            z-index: 1;
        }

        .social-links {
            margin: 2rem 0;
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .social-link {
            color: white;
            font-size: 1.5rem;
            text-decoration: none;
            transition: var(--transition);
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
        }

        .social-link:hover {
            transform: translateY(-5px) scale(1.1);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .facebook:hover { background: #3b5998; }
        .twitter:hover { background: #1da1f2; }
        .instagram:hover { background: linear-gradient(45deg, #405de6, #5851db, #833ab4, #c13584, #e1306c, #fd1d1d); }
        .linkedin:hover { background: #0077b5; }
        .youtube:hover { background: #ff0000; }

        /* Responsive adjustments */
        @media (max-width: 992px) {
            .contact-section {
                padding: 3rem 1.5rem;
                margin: 3rem auto;
            }
            
            .contact-form-container {
                padding: 2rem;
            }
            
            .contact-title {
                font-size: 2rem;
            }
        }

        @media (max-width: 768px) {
            .contact-section {
                padding: 2rem 1rem;
                margin: 2rem auto;
            }
            
            .contact-image {
                max-width: 70%;
                margin-bottom: 2rem;
            }
            
            .contact-form-container {
                padding: 1.5rem;
            }
            
            .contact-title {
                font-size: 1.8rem;
            }
            
            .submit-btn {
                padding: 0.8rem 2rem;
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

    <!-- Contact Section -->
    <section class="contact-section">
        <div class="container contact-content">
            <div class="row align-items-center">
                <div class="col-lg-6 text-center mb-5 mb-lg-0">
                    <img src="images/contact.png" 
                         class="contact-image img-fluid" 
                         alt="Contact Us">
                </div>
                
                <div class="col-lg-6">
                    <div class="contact-form-container">
                        <h2 class="contact-title">Get in Touch</h2>
                        <p class="contact-description">We're here to help! Send us your questions, feedback, or inquiries and we'll respond as soon as possible.</p>
                        
                        <?php if (isset($success_message)): ?>
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i> <?php echo $success_message; ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($error_message)): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle me-2"></i> <?php echo $error_message; ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="post" action="">
                            <div class="form-group">
                                <input type="text" name="name" id="name" class="form-control" required placeholder=" " />
                                <label for="name" class="floating-label">Your Name</label>
                            </div>
                            
                            <div class="form-group">
                                <input type="email" name="email" id="email" class="form-control" required placeholder=" " />
                                <label for="email" class="floating-label">Your Email</label>
                            </div>
                            
                            <div class="form-group">
                                <textarea name="message" id="message" class="form-control" rows="5" required placeholder=" "></textarea>
                                <label for="message" class="floating-label">Your Message</label>
                            </div>
                            
                            <button type="submit" class="submit-btn">
                                <i class="fas fa-paper-plane me-2"></i> Send Message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer Section -->
    <footer class="footer">
        <div class="container">
            <div class="text-center">
                <p style="font-size: 1.6rem; margin-bottom: 25px; letter-spacing: 1.5px; font-weight: 600;">Stay Connected with Us</p>
                <div class="social-links">
                    <a href="https://www.facebook.com/profile.php?id=100091625616068" class="social-link facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-link twitter"><i class="fab fa-twitter"></i></a>
                    <a href="https://www.instagram.com/premguptaa_?utm_source=qr" class="social-link instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-link linkedin"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" class="social-link youtube"><i class="fab fa-youtube"></i></a>
                </div>
                <p style="font-size: 1.1rem; margin-top: 30px; font-weight: 500;">&copy; 2025 All To All Mobile. All Rights Reserved.</p>
                <p style="font-size: 1rem; margin-top: 15px; color: #dcdcdc; font-style: italic; letter-spacing: 1px;">Crafted with creativity & precision by <span style="color: #ff6b81; font-weight: bold;">Prem Gupta</span></p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add animation to form elements on page load
        document.addEventListener('DOMContentLoaded', function() {
            const formElements = document.querySelectorAll('.form-group, .contact-title, .contact-description');
            
            formElements.forEach((element, index) => {
                setTimeout(() => {
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }, 100 * index);
            });
            
            // Add focus effects for form inputs
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.querySelector('.floating-label').style.color = '#ffcc00';
                });
                
                input.addEventListener('blur', function() {
                    if (!this.value) {
                        this.parentElement.querySelector('.floating-label').style.color = 'rgba(255, 255, 255, 0.7)';
                    }
                });
            });
        });
    </script>
</body>
</html>