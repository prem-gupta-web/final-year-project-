<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'config.php';
include 'cart.php';

$cart = new Cart();
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

// Fetch products
if ($search_query) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE ? OR description LIKE ?");
    $search_term = '%' . $search_query . '%';
    $stmt->bind_param("ss", $search_term, $search_term);
} else {
    $stmt = $conn->prepare("SELECT * FROM products");
}

$stmt->execute();
$result = $stmt->get_result();

// Handle Add to Cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart'])) {
    $productId = $_POST['product_id'];
    $quantity = 1;
    $stmt = $conn->prepare("SELECT price FROM products WHERE id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $stmt->bind_result($price);
    $stmt->fetch();
    $stmt->close();
    $cart->addProduct($productId, $price, $quantity);
    header("Location: cart-view.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All To All Mobile | Premium Smartphones & Accessories</title>
    <meta name="description" content="Discover the latest smartphones and mobile accessories at All To All Mobile. Premium quality with competitive prices.">
    
    <!-- Preload Critical Assets -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" as="style">
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" as="style">
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@400;500;600;700&display=swap" as="font" crossorigin>
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script type="module" src="https://unpkg.com/@google/model-viewer@latest/dist/model-viewer.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    
    <style>
        :root {
            --primary: #2563eb;
            --primary-light: #3b82f6;
            --primary-dark: #1e40af;
            --secondary: #f59e0b;
            --secondary-light: #fbbf24;
            --dark: #0f172a;
            --darker: #020617;
            --light: #f8fafc;
            --gray: #94a3b8;
            --gradient: linear-gradient(135deg, var(--primary), var(--primary-light));
            --gradient-secondary: linear-gradient(135deg, var(--secondary), var(--secondary-light));
            --glass: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-md: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            --shadow-xl: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: var(--darker);
            color: var(--light);
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
            line-height: 1.6;
            scroll-behavior: smooth;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            line-height: 1.2;
        }

        /* Glassmorphism Effect */
        .glass-card {
            background: var(--glass);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--glass-border);
            box-shadow: var(--shadow-lg);
            border-radius: 16px;
            transition: var(--transition);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: var(--dark);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--gradient);
            border-radius: 5px;
        }

        /* Navbar - Ultra Premium */
        .navbar {
            background: rgba(2, 6, 23, 0.98);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            padding: 1.2rem 2rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            transition: var(--transition);
            box-shadow: var(--shadow-xl);
        }

        .navbar.scrolled {
            padding: 0.8rem 2rem;
            background: rgba(2, 6, 23, 0.95);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.6rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            letter-spacing: 0.5px;
        }

        .navbar-brand i {
            color: var(--secondary);
            font-size: 1.8rem;
            transition: transform 0.5s cubic-bezier(0.68, -0.55, 0.27, 1.55);
        }

        .navbar-brand:hover i {
            transform: rotate(360deg);
        }

        .nav-link {
            color: var(--light) !important;
            font-weight: 500;
            padding: 0.5rem 1.2rem !important;
            position: relative;
            transition: var(--transition);
            letter-spacing: 0.5px;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 2px;
            background: var(--secondary);
            transition: var(--transition);
        }

        .nav-link:hover::before {
            width: calc(100% - 2.4rem);
        }

        .nav-link i {
            margin-right: 0.5rem;
            transition: var(--transition);
        }

        .nav-link:hover i {
            color: var(--secondary);
            transform: scale(1.2);
        }

        /* Hero Section - Ultra Premium */
        .hero-section {
            min-height: 100vh;
            background: linear-gradient(rgba(2, 6, 23, 0.9), rgba(2, 6, 23, 0.9)), url('https://images.unsplash.com/photo-1601784551446-20c9e07cdbdb?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            padding-top: 80px;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 20% 50%, rgba(59, 130, 246, 0.15) 0%, transparent 50%);
            pointer-events: none;
        }

        .hero-content {
            z-index: 2;
            position: relative;
        }

        .hero-heading {
            font-size: 4rem;
            font-weight: 700;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            background: linear-gradient(to right, var(--light), var(--gray));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            letter-spacing: -0.5px;
        }

        .hero-heading span {
            background: linear-gradient(to right, var(--secondary), var(--secondary-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-block;
        }

        .hero-text {
            font-size: 1.2rem;
            color: var(--gray);
            max-width: 500px;
            margin-bottom: 2.5rem;
            line-height: 1.7;
        }

        /* Button Styles */
        .btn-3d {
            position: relative;
            overflow: hidden;
            transform-style: preserve-3d;
            transition: var(--transition);
            transform: translateZ(0);
            will-change: transform;
            backface-visibility: hidden;
        }

        .btn-3d::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(rgba(255, 255, 255, 0.1), transparent);
            transform: translateZ(-1px);
            transition: var(--transition);
        }

        .btn-3d:hover {
            transform: translateY(-3px) translateZ(5px);
        }

        .btn-3d:hover::before {
            background: linear-gradient(rgba(255, 255, 255, 0.2), transparent);
        }

        .btn-primary-custom {
            background: var(--gradient);
            color: white;
            border: none;
            padding: 1rem 2.5rem;
            border-radius: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.3), 0 5px 5px rgba(0, 0, 0, 0.2);
            transition: var(--transition);
        }

        .btn-primary-custom:hover {
            box-shadow: 0 15px 30px rgba(37, 99, 235, 0.4), 0 10px 10px rgba(0, 0, 0, 0.2);
            color: white;
        }

        .btn-secondary-custom {
            background: linear-gradient(135deg, #ef4444, #f97316);
            color: white;
            border: none;
            padding: 1rem 2.5rem;
            border-radius: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
            box-shadow: 0 10px 20px rgba(239, 68, 68, 0.3), 0 5px 5px rgba(0, 0, 0, 0.2);
            transition: var(--transition);
        }

        .btn-secondary-custom:hover {
            box-shadow: 0 15px 30px rgba(239, 68, 68, 0.4), 0 10px 10px rgba(0, 0, 0, 0.2);
            color: white;
        }

        /* 3D Model Container */
        .model-container {
            width: 100%;
            height: 600px;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: var(--shadow-xl);
            position: relative;
            transform-style: preserve-3d;
            perspective: 1000px;
        }

        .model-container::after {
            content: '';
            position: absolute;
            bottom: -20px;
            left: 20%;
            width: 60%;
            height: 40px;
            background: rgba(0, 0, 0, 0.3);
            filter: blur(20px);
            border-radius: 50%;
            z-index: -1;
        }

        model-viewer {
            --progress-bar-color: var(--secondary);
            --progress-bar-height: 3px;
            --poster-color: transparent;
        }

        /* Floating Animation */
        @keyframes float {
            0%, 100% {
                transform: translateY(0) translateZ(0);
            }
            50% {
                transform: translateY(-20px) translateZ(10px);
            }
        }

        .floating {
            animation: float 6s ease-in-out infinite;
            transform-style: preserve-3d;
        }

        /* Search Bar - Premium */
        .search-container {
            max-width: 800px;
            margin: 3rem auto;
            position: relative;
            z-index: 2;
        }

        .search-input {
            width: 100%;
            padding: 1.2rem 2rem;
            border: none;
            border-radius: 50px;
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            color: var(--light);
            font-size: 1.1rem;
            box-shadow: var(--shadow-lg);
            transition: var(--transition);
            border: 1px solid rgba(255, 255, 255, 0.1);
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        .search-input::placeholder {
            color: var(--gray);
            opacity: 0.7;
        }

        .search-input:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.5), var(--shadow-lg);
            background: rgba(15, 23, 42, 0.9);
            border-color: rgba(59, 130, 246, 0.3);
        }

        .search-btn {
            position: absolute;
            right: 6px;
            top: 6px;
            background: var(--gradient);
            color: white;
            border: none;
            padding: 0.8rem 1.8rem;
            border-radius: 50px;
            font-weight: 600;
            transition: var(--transition);
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .search-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        /* Products Section - Ultra Premium */
        .products-section {
            padding: 6rem 0;
            background: var(--darker);
            position: relative;
            overflow: hidden;
        }

        .products-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at center, rgba(59, 130, 246, 0.05) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        .section-title {
            text-align: center;
            margin-bottom: 4rem;
            position: relative;
            z-index: 1;
        }

        .section-title h2 {
            font-size: 3rem;
            font-weight: 700;
            display: inline-block;
            background: linear-gradient(to right, var(--light), var(--gray));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            position: relative;
            padding-bottom: 1rem;
        }

        .section-title h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: var(--gradient);
            border-radius: 2px;
        }

        .product-card {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 20px;
            overflow: hidden;
            transition: var(--transition);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: var(--shadow-lg);
            height: 100%;
            display: flex;
            flex-direction: column;
            position: relative;
            z-index: 1;
            transform-style: preserve-3d;
        }

        .product-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), transparent);
            opacity: 0;
            transition: var(--transition);
            z-index: -1;
        }

        .product-card:hover {
            transform: translateY(-10px) translateZ(10px);
            box-shadow: var(--shadow-xl);
            border-color: rgba(59, 130, 246, 0.3);
        }

        .product-card:hover::before {
            opacity: 1;
        }

        .product-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: var(--gradient-secondary);
            color: var(--darker);
            padding: 0.3rem 1.2rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 700;
            box-shadow: var(--shadow);
            z-index: 2;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .product-img-container {
            height: 280px;
            position: relative;
            overflow: hidden;
            background: rgba(0, 0, 0, 0.1);
        }

        .product-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .product-card:hover .product-img {
            transform: scale(1.05);
        }

        .product-body {
            padding: 1.8rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .product-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 0.8rem;
            color: var(--light);
        }

        .product-description {
            color: var(--gray);
            font-size: 0.95rem;
            margin-bottom: 1.2rem;
            flex-grow: 1;
            line-height: 1.6;
        }

        .product-price {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--secondary);
            margin-bottom: 1.8rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .product-price::before {
            content: '₹';
            font-size: 1rem;
            opacity: 0.8;
        }

        .add-to-cart-btn {
            width: 100%;
            background: var(--gradient);
            color: white;
            border: none;
            padding: 0.9rem;
            border-radius: 12px;
            font-weight: 600;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.8rem;
            box-shadow: var(--shadow);
            letter-spacing: 0.5px;
        }

        .add-to-cart-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }

        /* Reviews Section - Ultra Premium */
        .reviews-section {
            padding: 6rem 0;
            background: linear-gradient(rgba(2, 6, 23, 0.9), rgba(2, 6, 23, 0.9)), url('https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?ixlib=rb-1.2.1&auto=format&fit=crossection');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            position: relative;
            overflow: hidden;
        }

        .reviews-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 80% 20%, rgba(245, 158, 11, 0.1) 0%, transparent 50%);
            pointer-events: none;
        }

        .review-card {
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2.5rem;
            transition: var(--transition);
            box-shadow: var(--shadow-lg);
            height: 100%;
            border: 1px solid rgba(255, 255, 255, 0.08);
            transform-style: preserve-3d;
        }

        .review-card:hover {
            transform: translateY(-10px) translateZ(10px);
            box-shadow: var(--shadow-xl);
            border-color: rgba(245, 158, 11, 0.3);
        }

        .review-img {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--secondary);
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow);
        }

        .review-name {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 0.8rem;
            color: var(--light);
        }

        .review-text {
            color: var(--gray);
            font-style: italic;
            margin-bottom: 1.5rem;
            line-height: 1.7;
        }

        .review-stars {
            color: var(--secondary);
            font-size: 1.3rem;
            letter-spacing: 2px;
        }

        /* Policies Section - Ultra Premium */
        .policies-section {
            padding: 6rem 0;
            background: var(--darker);
            position: relative;
            overflow: hidden;
        }

        .policies-section::before {
            content: '';
            position: absolute;
            bottom: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at center, rgba(59, 130, 246, 0.05) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        .policy-card {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2.5rem;
            margin-bottom: 2rem;
            transition: var(--transition);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: var(--shadow-lg);
            transform-style: preserve-3d;
        }

        .policy-card:hover {
            transform: translateY(-5px) translateZ(5px);
            box-shadow: var(--shadow-xl);
            border-color: rgba(59, 130, 246, 0.3);
        }

        .policy-title {
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 1.2rem;
            color: var(--secondary);
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .policy-title i {
            font-size: 1.2rem;
        }

        .policy-text {
            color: var(--gray);
            line-height: 1.7;
        }

        /* Footer - Ultra Premium */
        .footer {
            background: linear-gradient(to bottom, rgba(2, 6, 23, 0.98), rgba(2, 6, 23, 1));
            padding: 5rem 0 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            position: relative;
            overflow: hidden;
        }

        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 1px;
            background: linear-gradient(to right, transparent, var(--primary), transparent);
        }

        .footer-title {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 2rem;
            background: linear-gradient(to right, var(--light), var(--gray));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: 0.5px;
        }

        .social-links {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            margin-bottom: 3rem;
        }

        .social-link {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.08);
            color: var(--light);
            font-size: 1.5rem;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        .social-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
        }

        .social-link:hover::before {
            left: 100%;
        }

        .facebook {
            color: #3b5998;
        }

        .twitter {
            color: #1da1f2;
        }

        .instagram {
            background: linear-gradient(45deg, #405de6, #5851db, #833ab4, #c13584, #e1306c, #fd1d1d);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .linkedin {
            color: #0077b5;
        }

        .youtube {
            color: #ff0000;
        }

        .social-link:hover {
            transform: translateY(-5px) scale(1.1);
            box-shadow: var(--shadow-lg);
        }

        .footer-text {
            color: var(--gray);
            text-align: center;
            margin-top: 3rem;
            line-height: 1.7;
        }

        .footer-text a {
            color: var(--secondary);
            text-decoration: none;
            transition: var(--transition);
            font-weight: 600;
        }

        .footer-text a:hover {
            color: var(--secondary-light);
            text-decoration: underline;
        }

        /* Back to Top Button */
        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            background: var(--gradient);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            box-shadow: var(--shadow-xl);
            opacity: 0;
            visibility: hidden;
            transition: var(--transition);
            z-index: 999;
            cursor: pointer;
            border: none;
        }

        .back-to-top.active {
            opacity: 1;
            visibility: visible;
        }

        .back-to-top:hover {
            transform: translateY(-5px) scale(1.1);
            box-shadow: 0 15px 30px rgba(37, 99, 235, 0.5);
        }

        /* Responsive Styles */
        @media (max-width: 1199.98px) {
            .hero-heading {
                font-size: 3.5rem;
            }
            
            .model-container {
                height: 500px;
            }
        }

        @media (max-width: 991.98px) {
            .hero-heading {
                font-size: 3rem;
            }
            
            .section-title h2 {
                font-size: 2.5rem;
            }
            
            .product-img-container {
                height: 240px;
            }
        }

        @media (max-width: 767.98px) {
            .navbar {
                padding: 1rem;
            }
            
            .hero-section {
                text-align: center;
                padding-top: 6rem;
                background-attachment: scroll;
            }
            
            .hero-heading {
                font-size: 2.5rem;
            }
            
            .hero-text {
                margin-left: auto;
                margin-right: auto;
            }
            
            .model-container {
                height: 400px;
                margin-top: 3rem;
            }
            
            .section-title h2 {
                font-size: 2.2rem;
            }
            
            .product-card, .review-card, .policy-card {
                padding: 1.8rem;
            }
        }

        @media (max-width: 575.98px) {
            .hero-heading {
                font-size: 2rem;
            }
            
            .hero-text {
                font-size: 1rem;
            }
            
            .btn-primary-custom, .btn-secondary-custom {
                padding: 0.8rem 1.8rem;
                font-size: 0.9rem;
            }
            
            .search-input {
                padding: 1rem 1.5rem;
                font-size: 1rem;
            }
            
            .search-btn {
                padding: 0.7rem 1.5rem;
                font-size: 0.9rem;
            }
            
            .section-title h2 {
                font-size: 1.8rem;
            }
            
            .product-title {
                font-size: 1.2rem;
            }
            
            .product-price {
                font-size: 1.3rem;
            }
            
            .social-link {
                width: 50px;
                height: 50px;
                font-size: 1.3rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNavbar">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-mobile-alt"></i>
                <span>All To All Mobile</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php"><i class="fas fa-home"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php"><i class="fas fa-info-circle"></i> About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="faq.php"><i class="fas fa-question-circle"></i> FAQ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="feedback.php"><i class="fas fa-phone-alt"></i> Feedback</a>
                    </li>
                   
                    <li class="nav-item">
                        <a class="nav-link" href="location.php"><i class="fas fa-map-marker-alt"></i> Location</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cart-view.php"><i class="fas fa-shopping-cart"></i> Cart</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="my_order.php"><i class="fas fa-box"></i> My Orders</a>
                    </li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                            <a class="nav-link btn-3d btn-secondary-custom" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                            <a class="nav-link btn-3d btn-primary-custom" href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 order-lg-1 order-2">
                    <div class="hero-content" data-aos="fade-right" data-aos-delay="100">
                        <h1 class="hero-heading">Designed for <span>Best Audio </span>Experience</h1>
                        <p class="hero-text">
                        With advanced features, sleek designs, and user-friendly interfaces, our gadgets are perfect for anyone looking to upgrade their tech.                        </p>
                        <div class="d-flex flex-wrap gap-3 btn-container">
                            <a href="#products" class="btn btn-3d btn-primary-custom">Explore Products</a>
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <a href="logout.php" class="btn btn-3d btn-secondary-custom">Logout</a>
                            <?php else: ?>
                                <a href="login.php" class="btn btn-3d btn-secondary-custom">Register Now</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 order-lg-2 order-1" data-aos="fade-left" data-aos-delay="200">
                    <div class="model-container floating">
                        <model-viewer 
                            src="images/a4tech_headphone.glb" 
                            alt="Premium Smartphone 3D Model" 
                            auto-rotate 
                            camera-controls 
                            shadow-intensity="1"
                            exposure="1.2"
                            environment-image="neutral"
                            style="width: 100%; height: 100%;">
                        </model-viewer>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Search Bar -->
    <div class="container">
        <div class="search-container" data-aos="fade-up" data-aos-delay="300">
            <form method="GET" action="index.php" class="position-relative">
                <input 
                    id="searchInput"
                    class="search-input" 
                    type="search" 
                    name="search"
                    placeholder="Search for products..."
                    aria-label="Search"
                    value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                >
                <button class="search-btn" type="submit">
                    <i class="fas fa-search"></i> Search
                </button>
            </form>
        </div>
    </div>

    <!-- Products Section -->
    <section class="products-section" id="products">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Featured Products</h2>
            </div>
            <div class="row g-4">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="<?php echo rand(100, 300); ?>">
                            <div class="product-card glass-card">
                                <div class="product-badge">
                                    <i class="fas fa-bolt"></i> Hot Deal
                                </div>
                                <div class="product-img-container">
                                    <?php if (pathinfo($row['image'], PATHINFO_EXTENSION) === 'glb'): ?>
                                        <model-viewer 
                                            src="<?php echo htmlspecialchars($row['image']); ?>"
                                            alt="<?php echo htmlspecialchars($row['name']); ?>" 
                                            auto-rotate 
                                            camera-controls 
                                            shadow-intensity="1"
                                            style="width: 100%; height: 100%;"> 
                                        </model-viewer>
                                    <?php else: ?>
                                        <img 
                                            src="<?php echo htmlspecialchars($row['image']); ?>" 
                                            class="product-img" 
                                            alt="<?php echo htmlspecialchars($row['name']); ?>"
                                            loading="lazy"
                                        >
                                    <?php endif; ?>
                                </div>
                                <div class="product-body">
                                    <h5 class="product-title"><?php echo htmlspecialchars($row['name']); ?></h5>
                                    <p class="product-description"><?php echo htmlspecialchars(substr($row['description'], 0, 100)) . '...'; ?></p>
                                    <div class="product-price"><?php echo number_format($row['price'], 2); ?></div>
                                    <form method="POST" action="">
                                        <input type="hidden" name="product_id" value="<?php echo (int) $row['id']; ?>">
                                        <button type="submit" name="add_to_cart" class="add-to-cart-btn btn-3d">
                                            <i class="fas fa-cart-plus"></i> Add to Cart
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5" data-aos="fade-up">
                        <h4 class="text-muted">No products found</h4>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Reviews Section -->
    <section class="reviews-section">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Customer Testimonials</h2>
            </div>
            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="review-card glass-card">
                        <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Rahul Sharma" class="review-img">
                        <h5 class="review-name">Rahul Sharma</h5>
                        <p class="review-text">"The service at All To All Mobile is exceptional. My new phone arrived faster than expected, and the quality is outstanding. Highly recommended!"</p>
                        <div class="review-stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="review-card glass-card">
                        <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Anjali Verma" class="review-img">
                        <h5 class="review-name">Anjali Verma</h5>
                        <p class="review-text">"I'm extremely satisfied with my purchase. The website is user-friendly, and the product descriptions are accurate. Will definitely shop here again!"</p>
                        <div class="review-stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="review-card glass-card">
                        <img src="https://randomuser.me/api/portraits/men/65.jpg" alt="Rohit Singh" class="review-img">
                        <h5 class="review-name">Rohit Singh</h5>
                        <p class="review-text">"Excellent customer support! They helped me choose the perfect phone for my needs. The delivery was prompt and the packaging was secure."</p>
                        <div class="review-stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Policies Section -->
    <section class="policies-section">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Our Policies</h2>
            </div>
            <div class="row">
                <?php 
                $policies = [
                    "Introduction" => "Welcome to All To All Mobile! By using our website, you agree to abide by the terms and conditions set forth in this policy. Please read them carefully.",
                    "Privacy Policy" => "Your privacy is important to us. We collect personal information only for the purpose of improving your shopping experience. We do not share your data with third parties without your consent.",
                    "Product Information" => "All product details, including descriptions, images, and prices, are accurate to the best of our knowledge. However, we reserve the right to modify any product details or pricing at any time.",
                    "Shipping & Delivery" => "We strive to process and deliver your orders as quickly as possible. Standard shipping takes 3-5 business days. Express shipping options are available at checkout.",
                    "Returns & Exchanges" => "If you are unsatisfied with your purchase, we offer returns and exchanges within 7 days of delivery. Products must be in new, unused condition with original packaging.",
                    "Warranty" => "All products come with a 1-year manufacturer warranty. Please contact our support team for any warranty claims or issues with your product.",
                    "Payment Security" => "We use industry-standard encryption to protect your payment information. All transactions are processed through secure payment gateways.",
                    "Customer Support" => "Our support team is available 24/7 to assist with any questions or concerns. Contact us via phone, email, or live chat for prompt assistance."
                ]; 

                foreach ($policies as $title => $description):
                ?>
                    <div class="col-md-6" data-aos="fade-up" data-aos-delay="<?php echo rand(100, 300); ?>">
                        <div class="policy-card glass-card">
                            <h4 class="policy-title">
                                <i class="fas fa-<?php 
                                    switch($title) {
                                        case 'Introduction': echo 'info-circle'; break;
                                        case 'Privacy Policy': echo 'lock'; break;
                                        case 'Product Information': echo 'mobile-alt'; break;
                                        case 'Shipping & Delivery': echo 'shipping-fast'; break;
                                        case 'Returns & Exchanges': echo 'exchange-alt'; break;
                                        case 'Warranty': echo 'shield-alt'; break;
                                        case 'Payment Security': echo 'credit-card'; break;
                                        case 'Customer Support': echo 'headset'; break;
                                        default: echo 'circle';
                                    }
                                ?>"></i>
                                <?php echo $title; ?>
                            </h4>
                            <p class="policy-text"><?php echo $description; ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h3 class="footer-title">All To All Mobile</h3>
                    <div class="social-links">
                        <a href="https://www.facebook.com/profile.php?id=100091625616068" class="social-link facebook" aria-label="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-link twitter" aria-label="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="https://www.instagram.com/premguptaa_?utm_source=qr" class="social-link instagram" aria-label="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="social-link linkedin" aria-label="LinkedIn">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="#" class="social-link youtube" aria-label="YouTube">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                    <p class="footer-text">
                        &copy; 2025 All To All Mobile. All Rights Reserved.<br>
                        Designed with <i class="fas fa-heart" style="color: #ef4444;"></i> by <a href="#" target="_blank" rel="noopener">Prem Gupta</a>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button class="back-to-top" id="backToTop" aria-label="Back to top">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        // Initialize AOS animation
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            mirror: false
        });

        // Navbar scroll effect
        const navbar = document.getElementById('mainNavbar');
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Back to top button
        const backToTopButton = document.getElementById('backToTop');
        window.addEventListener('scroll', function() {
            if (window.scrollY > 300) {
                backToTopButton.classList.add('active');
            } else {
                backToTopButton.classList.remove('active');
            }
        });

        backToTopButton.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Play click sound
        function playClickSound() {
            const audio = new Audio('https://www.soundjay.com/buttons/sounds/button-09.mp3');
            audio.volume = 0.3;
            audio.play().catch(e => console.log('Audio play failed:', e));
        }

        // Add click sound to all buttons and links
        document.querySelectorAll('button, a').forEach(element => {
            if (!element.classList.contains('no-sound')) {
                element.addEventListener('click', playClickSound);
            }
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    window.scrollTo({
                        top: target.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Lazy load images
        if ('loading' in HTMLImageElement.prototype) {
            const lazyImages = document.querySelectorAll('img[loading="lazy"]');
            lazyImages.forEach(img => {
                img.src = img.dataset.src;
            });
        } else {
            // Fallback for browsers that don't support lazy loading
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/vanilla-lazyload@17.6.1/dist/lazyload.min.js';
            document.body.appendChild(script);
            script.onload = () => {
                new LazyLoad();
            };
        }
    </script>
</body>
</html>