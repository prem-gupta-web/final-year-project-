<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = $_POST['order_id'];

    if (isset($_POST['confirm_payment'])) {
        $status = 'Confirmed';
    } elseif (isset($_POST['reject_payment'])) {
        $status = 'Rejected';
    } else {
        die("Invalid action.");
    }

    // Prepare the SQL statement
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    // Bind parameters
    $stmt->bind_param("si", $status, $order_id);

    // Execute and redirect
    if ($stmt->execute()) {
        // Clear output buffer to ensure header works
        ob_clean();
        header('Location: admin-dashboard.php'); // Fixed the file name to match your form
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
