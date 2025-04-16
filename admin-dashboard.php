<?php
session_start();
include 'config.php';

// Enhanced security check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin-login.php");
    exit();
}

// CSRF token generation
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Fetch all products with pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;
$product_result = $conn->query("SELECT * FROM products LIMIT $limit OFFSET $offset");
$total_products = $conn->query("SELECT COUNT(*) as total FROM products")->fetch_assoc()['total'];
$total_pages = ceil($total_products / $limit);

// Fetch all pending payments with pagination
$payment_page = isset($_GET['payment_page']) ? (int)$_GET['payment_page'] : 1;
$payment_offset = ($payment_page - 1) * $limit;
$payment_result = $conn->query("SELECT * FROM orders WHERE payment_proof IS NOT NULL AND status = 'Pending Payment' LIMIT $limit OFFSET $payment_offset");
$total_payments = $conn->query("SELECT COUNT(*) as total FROM orders WHERE payment_proof IS NOT NULL AND status = 'Pending Payment'")->fetch_assoc()['total'];
$total_payment_pages = ceil($total_payments / $limit);

// Handle product addition with validation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_product'])) {
    // CSRF token validation
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error_message = "Invalid CSRF token. Please try again.";
    } else {
        $product_name = trim($_POST['product_name']);
        $product_description = trim($_POST['product_description']);
        $product_price = (float)$_POST['product_price'];
        
        // Validate inputs
        if (empty($product_name) || empty($product_description) || $product_price <= 0) {
            $error_message = "Please fill all fields with valid data.";
        } else {
            // Handle file upload securely
            if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
                $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'model/gltf-binary'];
                $file_info = finfo_open(FILEINFO_MIME_TYPE);
                $mime_type = finfo_file($file_info, $_FILES['product_image']['tmp_name']);
                finfo_close($file_info);
                
                if (in_array($mime_type, $allowed_types)) {
                    $file_ext = pathinfo($_FILES['product_image']['name'], PATHINFO_EXTENSION);
                    $new_filename = uniqid('product_', true) . '.' . $file_ext;
                    $target_dir = "uploads/products/";
                    
                    if (!is_dir($target_dir)) {
                        mkdir($target_dir, 0755, true);
                    }
                    
                    $target_file = $target_dir . $new_filename;
                    
                    if (move_uploaded_file($_FILES['product_image']['tmp_name'], $target_file)) {
                        // Insert product into the database with prepared statement
                        $stmt = $conn->prepare("INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)");
                        $stmt->bind_param("ssds", $product_name, $product_description, $product_price, $target_file);
                        
                        if ($stmt->execute()) {
                            $success_message = "Product added successfully.";
                            // Refresh the page to show the new product
                            header("Location: admin-dashboard.php?success=Product+added+successfully");
                            exit();
                        } else {
                            $error_message = "Error: " . $stmt->error;
                            // Clean up the uploaded file if DB insert failed
                            unlink($target_file);
                        }
                        $stmt->close();
                    } else {
                        $error_message = "Sorry, there was an error uploading your file.";
                    }
                } else {
                    $error_message = "Invalid file type. Only JPG, PNG, GIF, or GLB files are allowed.";
                }
            } else {
                $error_message = "Please upload a product image.";
            }
        }
    }
}

// Display success message from redirect
if (isset($_GET['success'])) {
    $success_message = urldecode($_GET['success']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | All To All Mobile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css">
    <script type="module" src="https://unpkg.com/@google/model-viewer@latest/dist/model-viewer.min.js"></script>
    <style>
        :root {
            --primary-color: #bb86fc;
            --secondary-color: #03dac6;
            --accent-color: #ff0266;
            --background-color: #121212;
            --surface-color: #1e1e1e;
            --text-color: #e0e0e0;
            --navbar-bg: #1f1f1f;
            --sidebar-bg: #1f1f1f;
            --transition-speed: 0.3s;
            --error-color: #ff4444;
            --success-color: #00C851;
        }
        
        body {
            background: var(--background-color);
            color: var(--text-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            transition: all var(--transition-speed) ease;
            line-height: 1.6;
        }
        
        /* Top Navbar */
        .top-navbar {
            background: var(--navbar-bg);
            padding: 15px 20px;
            color: var(--text-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #333;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5em;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .user-info a {
            color: var(--secondary-color);
            text-decoration: none;
            transition: color var(--transition-speed);
        }
        
        .user-info a:hover {
            color: var(--accent-color);
            text-decoration: underline;
        }
        
        .toggle-theme {
            cursor: pointer;
            border: none;
            background: none;
            color: var(--text-color);
            font-size: 1.2em;
            transition: transform 0.2s;
        }
        
        .toggle-theme:hover {
            transform: rotate(30deg);
        }
        
        /* Sidebar */
        .sidebar {
            background: var(--sidebar-bg);
            min-height: calc(100vh - 60px);
            padding: 20px 0;
            color: var(--text-color);
            position: sticky;
            top: 60px;
            box-shadow: 2px 0 10px rgba(0,0,0,0.2);
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .sidebar-item {
            margin-bottom: 5px;
        }
        
        .sidebar-link {
            display: flex;
            align-items: center;
            color: var(--text-color);
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 0 30px 30px 0;
            transition: all var(--transition-speed);
            gap: 10px;
        }
        
        .sidebar-link:hover, .sidebar-link.active {
            background: var(--accent-color);
            color: white;
            padding-left: 25px;
        }
        
        .sidebar-link i {
            width: 20px;
            text-align: center;
        }
        
        /* Main Content */
        .main-content {
            background: var(--surface-color);
            border-radius: 10px;
            padding: 30px;
            margin: 20px 0;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
            transition: all var(--transition-speed);
        }
        
        .section-title {
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--accent-color);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        /* Scrollable Table */
        .scrollable-table {
            max-height: 500px;
            overflow-y: auto;
            border-radius: 8px;
            border: 1px solid #444;
        }
        
        .scrollable-table::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        .scrollable-table::-webkit-scrollbar-thumb {
            background: var(--accent-color);
            border-radius: 4px;
        }
        
        .scrollable-table::-webkit-scrollbar-track {
            background: #2a2a2a;
        }
        
        /* Table Styles */
        .table {
            color: var(--text-color);
            margin-bottom: 0;
        }
        
        .table thead {
            background: #2a2a2a;
            position: sticky;
            top: 0;
        }
        
        .table th, .table td {
            border-color: #444;
            padding: 12px 15px;
            vertical-align: middle;
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }
        
        /* Form Controls */
        .form-control, .form-select {
            background: #2a2a2a;
            color: var(--text-color);
            border: 1px solid #444;
            transition: all var(--transition-speed);
            padding: 10px 15px;
        }
        
        .form-control:focus, .form-select:focus {
            background: #2a2a2a;
            color: var(--text-color);
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(187, 134, 252, 0.25);
        }
        
        .form-label {
            font-weight: 500;
            margin-bottom: 8px;
        }
        
        /* Buttons */
        .btn {
            border-radius: 5px;
            transition: all var(--transition-speed);
            padding: 10px 20px;
            font-weight: 500;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-sm {
            padding: 5px 10px;
            font-size: 0.875rem;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        .btn:active {
            transform: translateY(0);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: #9b59b6;
            border-color: #9b59b6;
        }
        
        .btn-secondary {
            background-color: #555;
            border-color: #555;
        }
        
        .btn-secondary:hover {
            background-color: #666;
            border-color: #666;
        }
        
        .btn-success {
            background-color: var(--success-color);
            border-color: var(--success-color);
        }
        
        .btn-success:hover {
            background-color: #007E33;
            border-color: #007E33;
        }
        
        .btn-info {
            background-color: #17a2b8;
            border-color: #17a2b8;
        }
        
        .btn-info:hover {
            background-color: #138496;
            border-color: #117a8b;
        }
        
        .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #000;
        }
        
        .btn-warning:hover {
            background-color: #e0a800;
            border-color: #d39e00;
        }
        
        .btn-danger {
            background-color: var(--error-color);
            border-color: var(--error-color);
        }
        
        .btn-danger:hover {
            background-color: #CC0000;
            border-color: #CC0000;
        }
        
        /* Navigation Buttons */
        .nav-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        
        /* Alerts */
        .alert {
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            border: none;
        }
        
        .alert-success {
            background-color: rgba(0, 200, 81, 0.2);
            color: var(--success-color);
            border-left: 4px solid var(--success-color);
        }
        
        .alert-danger {
            background-color: rgba(255, 68, 68, 0.2);
            color: var(--error-color);
            border-left: 4px solid var(--error-color);
        }
        
        /* Pagination */
        .pagination {
            justify-content: center;
            margin-top: 20px;
        }
        
        .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .page-link {
            background-color: var(--surface-color);
            border-color: #444;
            color: var(--text-color);
        }
        
        .page-link:hover {
            background-color: #2a2a2a;
            color: var(--primary-color);
        }
        
        /* Model Viewer */
        model-viewer {
            width: 100px;
            height: 100px;
            border-radius: 8px;
            background-color: #2a2a2a;
        }
        
        /* Image Preview */
        .image-preview {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #444;
            display: none;
        }
        
        /* Responsive Layout */
        @media (max-width: 992px) {
            .sidebar {
                min-height: auto;
                position: static;
                margin-bottom: 20px;
            }
            
            .sidebar-menu {
                display: flex;
                flex-wrap: wrap;
                gap: 5px;
            }
            
            .sidebar-item {
                margin-bottom: 0;
            }
            
            .sidebar-link {
                border-radius: 30px;
                padding: 8px 15px;
                font-size: 0.9em;
            }
            
            .sidebar-link:hover, .sidebar-link.active {
                padding-left: 15px;
            }
        }
        
        @media (max-width: 768px) {
            .top-navbar {
                flex-direction: column;
                text-align: center;
                gap: 10px;
                padding: 10px;
            }
            
            .user-info {
                flex-direction: column;
                gap: 5px;
            }
            
            .main-content {
                padding: 20px;
            }
            
            .nav-buttons {
                flex-direction: column;
            }
            
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
        }
        
        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }
    </style>
</head>
<body>
    <!-- TOP NAVBAR -->
    <nav class="top-navbar">
        <div class="navbar-brand">
            <i class="fas fa-mobile-alt"></i>
            <span>All To All Mobile Admin</span>
        </div>
        <div class="user-info">
            <span><i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?></span>
            <button class="toggle-theme" id="themeToggle" title="Toggle Dark/Light Mode">
                <i class="fas fa-adjust"></i>
            </button>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </nav>
    
    <div class="container-fluid">
        <div class="row">
            <!-- SIDEBAR -->
            <aside class="col-md-2 sidebar">
                <ul class="sidebar-menu">
                    <li class="sidebar-item">
                        <a href="#dashboard" class="sidebar-link active">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#addProductSection" class="sidebar-link">
                            <i class="fas fa-plus-circle"></i>
                            <span>Add Product</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#manageProductsSection" class="sidebar-link">
                            <i class="fas fa-boxes"></i>
                            <span>Manage Products</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#verifyPaymentsSection" class="sidebar-link">
                            <i class="fas fa-money-check-alt"></i>
                            <span>Verify Payments</span>
                        </a>
                    </li>
                    
                </ul>
            </aside>
            
            <!-- MAIN CONTENT -->
            <main class="col-md-10">
                <div class="main-content fade-in">
                    <!-- Success/Error Messages -->
                    <?php if(isset($success_message)): ?>
                        <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
                    <?php endif; ?>
                    <?php if(isset($error_message)): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
                    <?php endif; ?>
                    
                    <!-- Quick Stats -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card bg-dark text-white">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="fas fa-box"></i> Total Products</h5>
                                    <p class="card-text display-6"><?php echo $total_products; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-dark text-white">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="fas fa-money-bill-wave"></i> Pending Payments</h5>
                                    <p class="card-text display-6"><?php echo $total_payments; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-dark text-white">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="fas fa-chart-line"></i> Today's Orders</h5>
                                    <?php 
                                        $today = date('Y-m-d');
                                        $today_orders = $conn->query("SELECT COUNT(*) as count FROM orders WHERE DATE(order_date) = '$today'")->fetch_assoc()['count'];
                                    ?>
                                    <p class="card-text display-6"><?php echo $today_orders; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- ADD PRODUCT SECTION -->
                    <section id="addProductSection" class="mb-5">
                        <h3 class="section-title">
                            <i class="fas fa-plus-circle"></i>
                            <span>Add New Product</span>
                        </h3>
                        <form method="POST" action="" enctype="multipart/form-data" class="needs-validation" novalidate>
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="product_name" class="form-label">Product Name:</label>
                                        <input type="text" class="form-control" id="product_name" name="product_name" required>
                                        <div class="invalid-feedback">
                                            Please provide a product name.
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="product_description" class="form-label">Product Description:</label>
                                        <textarea class="form-control" id="product_description" name="product_description" rows="3" required></textarea>
                                        <div class="invalid-feedback">
                                            Please provide a product description.
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="product_price" class="form-label">Product Price (₹):</label>
                                        <div class="input-group">
                                            <span class="input-group-text">₹</span>
                                            <input type="number" step="0.01" min="0.01" class="form-control" id="product_price" name="product_price" required>
                                            <div class="invalid-feedback">
                                                Please provide a valid price.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="product_image" class="form-label">Product Image:</label>
                                        <input type="file" class="form-control" id="product_image" name="product_image" accept="image/*,.glb" required
                                               onchange="previewImage(this)">
                                        <div class="invalid-feedback">
                                            Please upload a product image.
                                        </div>
                                        <div class="mt-2">
                                            <img id="imagePreview" class="image-preview" alt="Image preview">
                                            <model-viewer id="modelPreview" class="image-preview" alt="Model preview" camera-controls></model-viewer>
                                        </div>
                                        <small class="text-muted">Supported formats: JPG, PNG, GIF, GLB (3D models)</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-end gap-2">
                                <button type="reset" class="btn btn-secondary">Reset</button>
                                <button type="submit" class="btn btn-primary" name="add_product">
                                    <i class="fas fa-plus"></i> Add Product
                                </button>
                            </div>
                        </form>
                    </section>
                    
                    <hr class="my-4">
                    
                    <!-- MANAGE PRODUCTS SECTION -->
                    <section id="manageProductsSection" class="mb-5">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="section-title mb-0">
                                <i class="fas fa-boxes"></i>
                                <span>Manage Products</span>
                            </h3>
                            <div class="d-flex gap-2">
                                <input type="text" id="productSearch" class="form-control" placeholder="Search products..." style="max-width: 250px;">
                                <button class="btn btn-outline-secondary" onclick="resetSearch()">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="scrollable-table">
                            <table class="table table-bordered table-hover" id="productsTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Price</th>
                                        <th>Image</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($row = $product_result->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                                            <td><?php echo htmlspecialchars(substr($row['description'], 0, 50) . (strlen($row['description']) > 50 ? '...' : '')); ?></td>
                                            <td>₹<?php echo number_format($row['price'], 2); ?></td>
                                            <td>
                                                <?php if(pathinfo($row['image'], PATHINFO_EXTENSION) === 'glb'): ?>
                                                    <model-viewer src="<?php echo htmlspecialchars($row['image']); ?>" alt="Product Image" camera-controls autoplay ar style="width: 100px; height: 100px; border-radius: 8px;"></model-viewer>
                                                <?php else: ?>
                                                    <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="Product Image" width="100" style="border-radius: 8px; object-fit: cover;">
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="edit-product.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?php echo $row['id']; ?>)">
                                                        <i class="fas fa-trash-alt"></i> Delete
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <?php if($total_pages > 1): ?>
                            <nav aria-label="Products pagination">
                                <ul class="pagination">
                                    <?php if($page > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?page=<?php echo $page - 1; ?>" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    
                                    <?php for($i = 1; $i <= $total_pages; $i++): ?>
                                        <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                        </li>
                                    <?php endfor; ?>
                                    
                                    <?php if($page < $total_pages): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?page=<?php echo $page + 1; ?>" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                        <?php endif; ?>
                    </section>
                    
                    <hr class="my-4">
                    
                    <!-- VERIFY PAYMENTS SECTION -->
                    <section id="verifyPaymentsSection">
                        <h3 class="section-title">
                            <i class="fas fa-money-check-alt"></i>
                            <span>Verify Payments</span>
                        </h3>
                        
                        <?php if($payment_result->num_rows > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Total Amount</th>
                                            <th>Date</th>
                                            <th>Payment Proof</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while($row = $payment_result->fetch_assoc()): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                                <td>₹<?php echo number_format($row['total'], 2); ?></td>
                                                <td><?php echo date('d M Y', strtotime($row['order_date'])); ?></td>
                                                <td>
                                                    <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#proofModal" 
                                                            data-proof="<?php echo htmlspecialchars('uploads/' . $row['payment_proof']); ?>">
                                                        <i class="fas fa-eye"></i> View Proof
                                                    </button>
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <form method="POST" action="verify_payment.php" class="d-inline">
                                                            <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                                                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                                            <button type="submit" class="btn btn-success btn-sm" name="confirm_payment">
                                                                <i class="fas fa-check"></i> Confirm
                                                            </button>
                                                        </form>
                                                        <form method="POST" action="verify_payment.php" class="d-inline">
                                                            <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                                                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                                            <button type="submit" class="btn btn-danger btn-sm" name="reject_payment">
                                                                <i class="fas fa-times"></i> Reject
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Pagination -->
                            <?php if($total_payment_pages > 1): ?>
                                <nav aria-label="Payments pagination">
                                    <ul class="pagination">
                                        <?php if($payment_page > 1): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="?payment_page=<?php echo $payment_page - 1; ?>" aria-label="Previous">
                                                    <span aria-hidden="true">&laquo;</span>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        
                                        <?php for($i = 1; $i <= $total_payment_pages; $i++): ?>
                                            <li class="page-item <?php echo $i == $payment_page ? 'active' : ''; ?>">
                                                <a class="page-link" href="?payment_page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                            </li>
                                        <?php endfor; ?>
                                        
                                        <?php if($payment_page < $total_payment_pages): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="?payment_page=<?php echo $payment_page + 1; ?>" aria-label="Next">
                                                    <span aria-hidden="true">&raquo;</span>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </nav>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> No pending payments to verify.
                            </div>
                        <?php endif; ?>
                    </section>
                </div>
            </main>
        </div>
    </div>
    
    <!-- Payment Proof Modal -->
    <div class="modal fade" id="proofModal" tabindex="-1" aria-labelledby="proofModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title" id="proofModalLabel">Payment Proof</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="proofImage" src="" alt="Payment proof" class="img-fluid" style="max-height: 70vh;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a id="downloadProof" href="#" class="btn btn-primary" download>
                        <i class="fas fa-download"></i> Download
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Dark/Light mode toggle functionality
        const themeToggle = document.getElementById('themeToggle');
        themeToggle.addEventListener('click', () => {
            document.body.classList.toggle('light-mode');
            
            if(document.body.classList.contains('light-mode')){
                document.documentElement.style.setProperty('--background-color', '#f4f4f4');
                document.documentElement.style.setProperty('--surface-color', '#fff');
                document.documentElement.style.setProperty('--text-color', '#333');
                document.documentElement.style.setProperty('--navbar-bg', '#e9ecef');
                document.documentElement.style.setProperty('--sidebar-bg', '#e9ecef');
                document.documentElement.style.setProperty('--primary-color', '#6f42c1');
                document.documentElement.style.setProperty('--secondary-color', '#0d6efd');
                document.documentElement.style.setProperty('--accent-color', '#fd7e14');
            } else {
                document.documentElement.style.setProperty('--background-color', '#121212');
                document.documentElement.style.setProperty('--surface-color', '#1e1e1e');
                document.documentElement.style.setProperty('--text-color', '#e0e0e0');
                document.documentElement.style.setProperty('--navbar-bg', '#1f1f1f');
                document.documentElement.style.setProperty('--sidebar-bg', '#1f1f1f');
                document.documentElement.style.setProperty('--primary-color', '#bb86fc');
                document.documentElement.style.setProperty('--secondary-color', '#03dac6');
                document.documentElement.style.setProperty('--accent-color', '#ff0266');
            }
            
            // Save preference to localStorage
            localStorage.setItem('themePreference', document.body.classList.contains('light-mode') ? 'light' : 'dark');
        });
        
        // Check for saved theme preference
        if(localStorage.getItem('themePreference') === 'light') {
            document.body.classList.add('light-mode');
            document.documentElement.style.setProperty('--background-color', '#f4f4f4');
            document.documentElement.style.setProperty('--surface-color', '#fff');
            document.documentElement.style.setProperty('--text-color', '#333');
            document.documentElement.style.setProperty('--navbar-bg', '#e9ecef');
            document.documentElement.style.setProperty('--sidebar-bg', '#e9ecef');
            document.documentElement.style.setProperty('--primary-color', '#6f42c1');
            document.documentElement.style.setProperty('--secondary-color', '#0d6efd');
            document.documentElement.style.setProperty('--accent-color', '#fd7e14');
        }
        
        // Form validation
        (function () {
            'use strict'
            
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            const forms = document.querySelectorAll('.needs-validation')
            
            // Loop over them and prevent submission
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    
                    form.classList.add('was-validated')
                }, false)
            })
        })()
        
        // Image preview for product upload
        function previewImage(input) {
            const imagePreview = document.getElementById('imagePreview');
            const modelPreview = document.getElementById('modelPreview');
            
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const fileType = file.type;
                
                // Hide both previews initially
                imagePreview.style.display = 'none';
                modelPreview.style.display = 'none';
                
                if (fileType.startsWith('image/')) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        imagePreview.style.display = 'block';
                    }
                    
                    reader.readAsDataURL(file);
                } else if (file.name.endsWith('.glb')) {
                    modelPreview.src = URL.createObjectURL(file);
                    modelPreview.style.display = 'block';
                }
            }
        }
        
        // Product search functionality
        document.getElementById('productSearch').addEventListener('input', function() {
            const searchValue = this.value.toLowerCase();
            const rows = document.querySelectorAll('#productsTable tbody tr');
            
            rows.forEach(row => {
                const name = row.cells[1].textContent.toLowerCase();
                const description = row.cells[2].textContent.toLowerCase();
                
                if (name.includes(searchValue) || description.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
        
        function resetSearch() {
            document.getElementById('productSearch').value = '';
            const rows = document.querySelectorAll('#productsTable tbody tr');
            rows.forEach(row => row.style.display = '');
        }
        
        // Confirm delete dialog
        function confirmDelete(productId) {
            if (confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
                window.location.href = `delete-product.php?id=${productId}`;
            }
        }
        
        // Payment proof modal
        const proofModal = document.getElementById('proofModal');
        if (proofModal) {
            proofModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const proofUrl = button.getAttribute('data-proof');
                const modalImage = document.getElementById('proofImage');
                const downloadLink = document.getElementById('downloadProof');
                
                modalImage.src = proofUrl;
                downloadLink.href = proofUrl;
                
                // Check if the file is an image
                const img = new Image();
                img.onload = function() {
                    modalImage.style.display = 'block';
                };
                img.onerror = function() {
                    modalImage.style.display = 'none';
                    modalImage.parentElement.innerHTML = `
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> 
                            This file type cannot be previewed. Please download to view.
                        </div>
                    `;
                };
                img.src = proofUrl;
            });
        }
        
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: 'smooth'
                    });
                    
                    // Update URL without page jump
                    history.pushState(null, null, targetId);
                }
            });
        });
        
        // Highlight active section in sidebar
        window.addEventListener('scroll', function() {
            const sections = document.querySelectorAll('section');
            const scrollPosition = window.scrollY + 100;
            
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.offsetHeight;
                const sectionId = section.getAttribute('id');
                
                if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                    document.querySelectorAll('.sidebar-link').forEach(link => {
                        link.classList.remove('active');
                        if (link.getAttribute('href') === `#${sectionId}`) {
                            link.classList.add('active');
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>