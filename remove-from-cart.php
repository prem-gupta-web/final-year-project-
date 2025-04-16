<?php
session_start();
include 'cart.php';

$cart = new Cart();

if (isset($_GET['product_id'])) {
    $productId = $_GET['product_id'];
    $cart->removeProduct($productId);
}

header("Location: cart-view.php");
exit();
?>