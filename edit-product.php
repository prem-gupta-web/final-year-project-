<?php
session_start();
include 'config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-login.php");
    exit();
}

// Fetch product details
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();
}

// Handle product update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_product'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_description = $_POST['product_description'];
    $product_price = $_POST['product_price'];

    // Handle image upload
    if ($_FILES['product_image']['name']) {
        $target_dir = "images/";
        $target_file = $target_dir . basename($_FILES['product_image']['name']);
        move_uploaded_file($_FILES['product_image']['tmp_name'], $target_file);
        $product_image = $target_file;
    } else {
        $product_image = $_POST['existing_image'];
    }

    // Update product in the database
    $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, image = ? WHERE id = ?");
    $stmt->bind_param("ssdsi", $product_name, $product_description, $product_price, $product_image, $product_id);

    if ($stmt->execute()) {
        $success_message = "Product updated successfully.";
        header("Location: admin-dashboard.php");
        exit();
    } else {
        $error_message = "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <main class="container py-5">
        <section>
            <h2 class="text-center mb-4">Edit Product</h2>
            <?php if (isset($success_message)): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>
            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            <form method="POST" action="" enctype="multipart/form-data">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <input type="hidden" name="existing_image" value="<?php echo $product['image']; ?>">
                <div class="mb-3">
                    <label for="product_name" class="form-label">Product Name:</label>
                    <input type="text" class="form-control" id="product_name" name="product_name" value="<?php echo $product['name']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="product_description" class="form-label">Product Description:</label>
                    <textarea class="form-control" id="product_description" name="product_description" required><?php echo $product['description']; ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="product_price" class="form-label">Product Price:</label>
                    <input type="number" step="0.01" class="form-control" id="product_price" name="product_price" value="<?php echo $product['price']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="product_image" class="form-label">Product Image:</label>
                    <input type="file" class="form-control" id="product_image" name="product_image">
                    <img src="<?php echo $product['image']; ?>" alt="Product Image" width="100">
                </div>
                <button type="submit" class="btn btn-primary" name="update_product">Update Product</button>
            </form>
        </section>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>