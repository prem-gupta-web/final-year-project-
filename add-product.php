<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_product'])) {
    $product_name = $_POST['product_name'];
    $product_description = $_POST['product_description'];
    $product_price = $_POST['product_price'];

    // Handle image upload
    $target_dir = "images/";
    $target_file = $target_dir . basename($_FILES["product_image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["product_image"]["tmp_name"]);
    if($check !== false) {
        if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
            // Insert product into the database
            $stmt = $conn->prepare("INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssds", $product_name, $product_description, $product_price, $target_file);

            if ($stmt->execute()) {
                $success_message = "Product added successfully.";
            } else {
                $error_message = "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $error_message = "Sorry, there was an error uploading your file.";
        }
    } else {
        $error_message = "File is not an image.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <main class="container py-5">
        <section>
            <h2 class="text-center mb-4">Admin Dashboard</h2>
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
            <form method="POST" action="add-product.php" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="product_name" class="form-label">Product Name:</label>
                    <input type="text" class="form-control" id="product_name" name="product_name" required>
                </div>
                <div class="mb-3">
                    <label for="product_description" class="form-label">Product Description:</label>
                    <textarea class="form-control" id="product_description" name="product_description" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="product_price" class="form-label">Product Price:</label>
                    <input type="number" step="0.01" class="form-control" id="product_price" name="product_price" required>
                </div>
                <div class="mb-3">
                    <label for="product_image" class="form-label">Product Image:</label>
                    <input type="file" class="form-control" id="product_image" name="product_image" required>
                </div>
                <button type="submit" class="btn btn-primary" name="add_product">Add Product</button>
            </form>
        </section>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>