<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - All To All Mobile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/4c729db828.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        :root {
            --primary-bg: #121212;
            --secondary-bg: #1e1e1e;
            --primary-color: #ffffff;
            --secondary-color: rgba(255, 255, 255, 0.7);
            --highlight-color: #ffcc00;
            --accent-blue: #76e1ff;
            --faq-bg: linear-gradient(135deg, #1a1a2e, #16213e);
            --faq-item-bg: rgba(45, 45, 58, 0.8);
            --faq-answer-bg: rgba(53, 53, 74, 0.9);
            --box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
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
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        /* FAQ Section */
        .faq-section {
            background: var(--faq-bg);
            padding: 5rem 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
            min-height: 100vh;
        }

        .faq-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 20% 30%, rgba(118, 225, 255, 0.1) 0%, transparent 40%),
                        radial-gradient(circle at 80% 70%, rgba(255, 204, 0, 0.1) 0%, transparent 40%);
            z-index: 0;
        }

        .faq-container {
            position: relative;
            z-index: 1;
            max-width: 900px;
            margin: 0 auto;
        }

        .faq-title {
            font-size: 3.2rem;
            margin-bottom: 3rem;
            color: var(--highlight-color);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            position: relative;
            display: inline-block;
            text-shadow: 0 2px 10px rgba(255, 204, 0, 0.3);
        }

        .faq-title::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: var(--accent-blue);
            border-radius: 3px;
        }

        .faq-item {
            background: var(--faq-item-bg);
            border-radius: 12px;
            margin-bottom: 1.5rem;
            overflow: hidden;
            box-shadow: var(--box-shadow);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: var(--transition);
        }

        .faq-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
        }

        .faq-question {
            background: none;
            border: none;
            color: var(--accent-blue);
            font-size: 1.3rem;
            font-weight: 600;
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            padding: 1.5rem 2rem;
            text-align: left;
            cursor: pointer;
            transition: var(--transition);
        }

        .faq-question:hover {
            color: var(--highlight-color);
        }

        .faq-question img {
            width: 28px;
            height: 28px;
            margin-right: 15px;
            transition: var(--transition);
        }

        .faq-question:hover img {
            transform: scale(1.1);
        }

        .faq-icon {
            font-size: 1.5rem;
            font-weight: bold;
            transition: var(--transition);
            color: var(--primary-color);
        }

        .faq-answer {
            max-height: 0;
            overflow: hidden;
            font-size: 1.1rem;
            line-height: 1.6;
            padding: 0 2rem;
            background: var(--faq-answer-bg);
            transition: max-height 0.5s ease-out, padding 0.3s ease;
        }

        .faq-item.active .faq-answer {
            max-height: 500px;
            padding: 1.5rem 2rem;
        }

        .faq-item.active .faq-icon {
            transform: rotate(45deg);
            color: var(--highlight-color);
        }

        /* Footer */
        footer {
            background: rgba(31, 28, 44, 0.9);
            backdrop-filter: blur(10px);
            color: white;
            padding: 3rem 0;
            border-top-left-radius: 40px;
            border-top-right-radius: 40px;
            box-shadow: 0 -10px 30px rgba(0, 0, 0, 0.4);
            font-family: 'Poppins', sans-serif;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.6);
            position: relative;
            z-index: 1;
        }

        .footer-content {
            text-align: center;
            max-width: 1000px;
            margin: auto;
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
            font-size: 1.8rem;
            text-decoration: none;
            transition: all 0.3s ease;
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
        @media (max-width: 768px) {
            .faq-title {
                font-size: 2.5rem;
                margin-bottom: 2rem;
            }
            
            .faq-question {
                font-size: 1.1rem;
                padding: 1.2rem 1.5rem;
            }
            
            .faq-answer {
                font-size: 1rem;
                padding: 0 1.5rem;
            }
            
            .faq-item.active .faq-answer {
                padding: 1rem 1.5rem;
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


   
    <!-- FAQ Section -->
    <section class="faq-section">
        <div class="faq-container">
            <h1 class="faq-title">Frequently Asked Questions</h1>
            
            <div class="faq-items">
                <!-- FAQ Item 1 -->
                <div class="faq-item">
                    <button class="faq-question">
                        <img src="images/icons8-return-parcel-24.png" alt="Return Policy">
                        What is your return policy?
                        <span class="faq-icon">+</span>
                    </button>
                    <div class="faq-answer">
                        We accept returns within 30 days of purchase, provided the product is in its original condition with all accessories and packaging. To initiate a return, please contact our customer service team with your order number. Refunds will be processed within 5-7 business days after we receive the returned item.
                    </div>
                </div>

                <!-- FAQ Item 2 -->
                <div class="faq-item">
                    <button class="faq-question">
                        <img src="images/icons8-shipping-100.png" alt="Shipping">
                        Do you offer international shipping?
                        <span class="faq-icon">+</span>
                    </button>
                    <div class="faq-answer">
                        Currently, we only ship within the United States. We're working to expand our shipping options to include international destinations in the future. Please check back later or subscribe to our newsletter for updates on international shipping availability.
                    </div>
                </div>

                <!-- FAQ Item 3 -->
                <div class="faq-item">
                    <button class="faq-question">
                        <img src="https://img.icons8.com/ios/50/ffffff/delete-sign.png" alt="Cancel Order">
                        Can I cancel my order?
                        <span class="faq-icon">+</span>
                    </button>
                    <div class="faq-answer">
                        You can cancel your order within 24 hours of placing it, provided it hasn't already been processed for shipping. To cancel, go to "My Orders" in your account or contact our customer support team immediately. For orders that have already shipped, you'll need to wait for delivery and then initiate a return if needed.
                    </div>
                </div>

                <!-- FAQ Item 4 -->
                <div class="faq-item">
                    <button class="faq-question">
                        <img src="https://img.icons8.com/ios/50/ffffff/warranty-card.png" alt="Warranty">
                        What warranty do you offer on products?
                        <span class="faq-icon">+</span>
                    </button>
                    <div class="faq-answer">
                        Most of our products come with a 1-year manufacturer's warranty. Some premium products may have extended warranty options available for purchase. Warranty coverage typically includes manufacturing defects but does not cover accidental damage or normal wear and tear. Please check the product description for specific warranty details.
                    </div>
                </div>

                <!-- FAQ Item 5 -->
                <div class="faq-item">
                    <button class="faq-question">
                        <img src="https://img.icons8.com/ios/50/ffffff/credit-card-security.png" alt="Payment Security">
                        Is my payment information secure?
                        <span class="faq-icon">+</span>
                    </button>
                    <div class="faq-answer">
                        Absolutely. We use industry-standard SSL encryption to protect your payment information. We don't store your credit card details on our servers - all payments are processed through PCI-compliant payment gateways. You can also choose to pay via PayPal for an additional layer of security.
                    </div>
                </div>

                <!-- FAQ Item 6 -->
                <div class="faq-item">
                    <button class="faq-question">
                        <img src="https://img.icons8.com/ios/50/ffffff/delivery.png" alt="Delivery">
                        How long does delivery take?
                        <span class="faq-icon">+</span>
                    </button>
                    <div class="faq-answer">
                        Standard delivery typically takes 3-5 business days within the continental US. We also offer expedited shipping options (2-day and overnight) for an additional fee. Delivery times may be slightly longer during peak seasons or for remote locations. You'll receive tracking information as soon as your order ships.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer Section -->
    <footer>
        <div class="footer-content">
            <p style="font-size: 1.6rem; margin-bottom: 25px; letter-spacing: 1.5px; font-weight: 600;">Stay Connected with Us</p>
            <div class="social-links">
                <a href="https://www.facebook.com/profile.php?id=100091625616068" class="social-link facebook"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="#" class="social-link twitter"><i class="fa-brands fa-twitter"></i></a>
                <a href="https://www.instagram.com/premguptaa_?utm_source=qr" class="social-link instagram"><i class="fa-brands fa-instagram"></i></a>
                <a href="#" class="social-link linkedin"><i class="fa-brands fa-linkedin-in"></i></a>
                <a href="#" class="social-link youtube"><i class="fa-brands fa-youtube"></i></a>
            </div>
            <p style="font-size: 1.1rem; margin-top: 30px; font-weight: 500;">&copy; 2025 All To All Mobile. All Rights Reserved.</p>
            <p style="font-size: 1rem; margin-top: 15px; color: #dcdcdc; font-style: italic; letter-spacing: 1px;">Crafted with creativity & precision by <span style="color: #ff6b81; font-weight: bold;">Prem Gupta</span></p>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const faqItems = document.querySelectorAll('.faq-item');
            
            faqItems.forEach(item => {
                const question = item.querySelector('.faq-question');
                
                question.addEventListener('click', () => {
                    // Close all other items
                    faqItems.forEach(otherItem => {
                        if (otherItem !== item && otherItem.classList.contains('active')) {
                            otherItem.classList.remove('active');
                        }
                    });
                    
                    // Toggle current item
                    item.classList.toggle('active');
                    
                    // Smooth scroll to keep item in view
                    if (item.classList.contains('active')) {
                        item.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                });
            });
            
            // Add animation on page load
            setTimeout(() => {
                document.querySelector('.faq-title').style.opacity = '1';
                document.querySelector('.faq-title').style.transform = 'translateY(0)';
                
                const items = document.querySelectorAll('.faq-item');
                items.forEach((item, index) => {
                    setTimeout(() => {
                        item.style.opacity = '1';
                        item.style.transform = 'translateY(0)';
                    }, 100 * index);
                });
            }, 100);
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>