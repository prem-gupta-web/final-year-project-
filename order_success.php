<?php
session_start();
require_once 'Cart.php'; // Ensure the correct path

$cart = new Cart();

// Check if the "Continue Shopping" button is clicked
if (isset($_GET['clear_cart']) && $_GET['clear_cart'] == 1) {
    $cart->clearCart();
    header("Location: index.php"); // Redirect to homepage or product page
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success! | Your Store Name</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #333;
        }
        .success-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 40px;
            text-align: center;
            max-width: 600px;
            width: 90%;
            animation: fadeIn 0.5s ease-in-out;
        }
        h2 {
            color: #28a745;
            margin-bottom: 20px;
            font-size: 2rem;
        }
        p {
            font-size: 1.1rem;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        .success-icon {
            font-size: 4rem;
            margin-bottom: 20px;
            color: #28a745;
            animation: bounce 1s;
        }
        .btn-continue {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 12px 30px;
            font-size: 1rem;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        }
        .btn-continue:hover {
            background-color: #218838;
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
        }
        .confetti {
            position: absolute;
            width: 10px;
            height: 10px;
            background-color: #f00;
            opacity: 0;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {transform: translateY(0);}
            40% {transform: translateY(-30px);}
            60% {transform: translateY(-15px);}
        }
    </style>
</head>
<body>
    <div class="success-container">
        <div class="success-icon">✓</div>
        <h2>🎉 Order Placed Successfully! 🎉</h2>
        <p>Thank you for your purchase! Your order has been received and is being processed. You'll receive a confirmation email shortly with all the details.</p>
        <p>We've cleared your cart so you can start fresh on your next shopping adventure!</p>
        
        <!-- Continue Shopping Button -->
        <a href="order_success.php?clear_cart=1" style="text-decoration: none;">
            <button class="btn-continue">Continue Shopping →</button>
        </a>
    </div>

    <script>
        // Simple confetti effect
        document.addEventListener('DOMContentLoaded', function() {
            const colors = ['#28a745', '#dc3545', '#fd7e14', '#ffc107', '#17a2b8', '#6f42c1'];
            
            for (let i = 0; i < 50; i++) {
                const confetti = document.createElement('div');
                confetti.className = 'confetti';
                confetti.style.left = Math.random() * 100 + 'vw';
                confetti.style.top = -10 + 'px';
                confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.width = Math.random() * 8 + 5 + 'px';
                confetti.style.height = confetti.style.width;
                document.body.appendChild(confetti);
                
                const animationDuration = Math.random() * 3 + 2;
                
                confetti.animate([
                    { top: '-10px', opacity: 0, transform: 'rotate(0deg)' },
                    { top: Math.random() * 50 + 50 + 'vh', opacity: 1 },
                    { top: '100vh', opacity: 0, transform: 'rotate(' + Math.random() * 360 + 'deg)' }
                ], {
                    duration: animationDuration * 1000,
                    delay: Math.random() * 2000,
                    easing: 'cubic-bezier(0.1, 0.8, 0.9, 1)'
                });
            }
        });
    </script>
</body>
</html>