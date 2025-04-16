<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

 class Cart {
    public function __construct() {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    // Check if the user is logged in before adding a product
    public function addProduct($productId, $price, $quantity = 1) {
        if (!isset($_SESSION['user_id'])) {
            // Redirect to login if not logged in
            header("Location: login.php?message=Please log in to add items to your cart.");
            exit();
        }

        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$productId] = ['quantity' => $quantity, 'price' => $price];
        }
    }

    public function removeProduct($productId) {
        if (isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
        }
    }

    public function getCartItems() {
        return $_SESSION['cart'];
    }

    public function clearCart() {
        $_SESSION['cart'] = [];
    }
}


?>
