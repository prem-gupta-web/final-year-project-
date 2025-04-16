<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = $_SESSION['order_id'];
    $payment_proof = $_FILES['payment_proof']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["payment_proof"]["name"]);

    // Create the uploads directory if it doesn't exist
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    if (move_uploaded_file($_FILES["payment_proof"]["tmp_name"], $target_file)) {
        $stmt = $conn->prepare("UPDATE orders SET payment_proof = ? WHERE id = ?");
        $stmt->bind_param("si", $payment_proof, $order_id);

        if ($stmt->execute()) {
            $_SESSION['upload_success'] = "The file " . basename($_FILES["payment_proof"]["name"]) . " has been uploaded.";
            header('Location: confirmation.php');
            exit();
        } else {
            $error_message = "Error updating record: " . $stmt->error;
        }
    } else {
        $error_message = "Sorry, there was an error uploading your file.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Payment Proof | All To All Mobile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <script src="https://kit.fontawesome.com/4c729db828.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    
    <style>
    :root {
        --primary-bg: #121212;
        --secondary-bg: #1e1e1e;
        --primary-color: #ffffff;
        --secondary-color: rgba(255, 255, 255, 0.7);
        --highlight-color: #ffcc00;
        --accent-color: #00e0ff;
        --navbar-bg-gradient: linear-gradient(to right, #212121, #0d0d0d);
        --button-gradient: linear-gradient(135deg, #00e0ff, #007bff);
        --button-hover-gradient: linear-gradient(135deg, #007bff, #00e0ff);
        --error-color: #ff4c4c;
        --box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        --glow-effect: 0 0 15px rgba(0, 224, 255, 0.5);
    }
    
    body {
        background-color: var(--primary-bg);
        color: var(--primary-color);
        font-family: 'Poppins', sans-serif;
        overflow-x: hidden;
    }
    
    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }
    
    ::-webkit-scrollbar-track {
        background: var(--primary-bg);
    }
    
    ::-webkit-scrollbar-thumb {
        background: var(--accent-color);
        border-radius: 10px;
    }
    
    /* Navbar styles */
    .navbar {
        background: linear-gradient(to right, #212121, rgb(3, 39, 166)) !important;
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.5);
    }
    
    .navbar-brand {
        font-weight: 700;
        letter-spacing: 1px;
    }
    
    .navbar-brand span {
        color: var(--highlight-color);
    }
    
    .nav-link {
        position: relative;
        padding: 8px 15px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .nav-link:hover {
        color: var(--highlight-color) !important;
        transform: translateY(-2px);
    }
    
    .nav-link i {
        transition: transform 0.3s ease;
    }
    
    .nav-link:hover i {
        transform: rotate(15deg);
    }
    
    /* Upload container */
    .upload-container {
        background: rgba(30, 30, 30, 0.95);
        backdrop-filter: blur(12px);
        border-radius: 20px;
        box-shadow: var(--box-shadow);
        border: 1px solid rgba(255, 255, 255, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        max-width: 600px;
        margin: 2rem auto;
        padding: 2.5rem;
    }
    
    .upload-container:hover {
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.8);
        transform: translateY(-5px);
    }
    
    .section-title {
        font-weight: 700;
        text-shadow: var(--glow-effect);
        position: relative;
        display: inline-block;
    }
    
    .section-title::after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 0;
        width: 100%;
        height: 3px;
        background: var(--accent-color);
        transform: scaleX(0.7);
        border-radius: 3px;
    }
    
    /* File upload styling */
    .file-upload {
        position: relative;
        margin-bottom: 1.5rem;
    }
    
    .file-upload input[type="file"] {
        position: absolute;
        left: 0;
        top: 0;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }
    
    .file-upload-label {
        display: block;
        padding: 1.5rem;
        background: rgba(26, 26, 26, 0.8);
        border: 2px dashed rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        text-align: center;
        transition: all 0.3s ease;
    }
    
    .file-upload-label:hover {
        border-color: var(--accent-color);
        background: rgba(35, 35, 35, 0.9);
    }
    
    .file-upload-label i {
        font-size: 2.5rem;
        color: var(--accent-color);
        margin-bottom: 1rem;
        display: block;
    }
    
    .file-name {
        margin-top: 1rem;
        font-size: 0.9rem;
        color: var(--secondary-color);
    }
    
    /* Button styles */
    .btn-upload {
        background: var(--button-gradient);
        border: none;
        border-radius: 15px;
        padding: 15px;
        font-weight: 700;
        font-size: 18px;
        letter-spacing: 1px;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.6);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        width: 100%;
    }
    
    .btn-upload:hover {
        background: var(--button-hover-gradient);
        transform: translateY(-3px) scale(1.03);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.8);
    }
    
    .btn-upload::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: 0.5s;
    }
    
    .btn-upload:hover::before {
        left: 100%;
    }
    
    /* Error message */
    .alert-error {
        background: var(--error-color);
        color: white;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(255, 0, 0, 0.4);
        border: none;
    }
    
    /* Footer styles */
    footer {
        background: linear-gradient(to right, #212121, #0d0d0d);
        padding: 2rem 0;
        margin-top: 3rem;
    }
    
    .social-description {
        color: var(--secondary-color);
        margin-bottom: 1.5rem;
    }
    
    .social-icons {
        display: flex;
        justify-content: center;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .social-icon {
        color: white;
        font-size: 1.5rem;
        transition: all 0.3s ease;
    }
    
    .social-icon:hover {
        color: var(--accent-color);
        transform: translateY(-5px) scale(1.2);
    }
    
    /* Animation classes */
    .fade-in {
        animation: fadeIn 0.8s ease-in-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold fs-4" href="index.php">
                <i class="fa fa-mobile-alt me-2"></i>
                <span>All To All Mobile</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php"><i class="fa fa-home"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cart-view.php"><i class="fa fa-shopping-cart"></i> Cart</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="my_order.php"><i class="fa fa-box"></i> My Orders</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container py-5">
        <!-- Upload Form -->
        <div class="upload-container fade-in">
            <h2 class="text-center mb-4 section-title">
                <i class="fas fa-file-upload me-2"></i>Upload Payment Proof
            </h2>
            
            <?php if (isset($error_message)): ?>
                <div class="alert alert-error mb-4 animate__animated animate__shakeX">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data">
                <div class="file-upload mb-4">
                    <label for="payment_proof" class="file-upload-label">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <span>Click to browse or drag & drop your payment screenshot</span>
                        <div class="file-name" id="file-name">No file selected</div>
                    </label>
                    <input type="file" class="form-control" id="payment_proof" name="payment_proof" required>
                </div>
                
                <div class="d-grid">
                    <button type="submit" class="btn btn-upload">
                        <i class="fas fa-paper-plane me-2"></i>Upload Proof
                    </button>
                </div>
            </form>
            
            <div class="mt-4 text-center">
                <p class="text-muted">Accepted formats: JPG, PNG, PDF (Max 5MB)</p>
            </div>
        </div>
    </main>

    <!-- Footer Section -->
    <footer>
        <div class="container text-center">
            <p class="social-description">Stay connected with us on social media:</p>
            <div class="social-icons">
                <a href="https://www.facebook.com/profile.php?id=100091625616068" class="social-icon"><i class="fab fa-facebook"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                <a href="https://www.instagram.com/premguptaa_?utm_source=qr" class="social-icon"><i class="fab fa-instagram"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-linkedin"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-youtube"></i></a>
            </div>
            <p>&copy; 2025 All To All Mobile. All Rights Reserved.</p>
        </div>
    </footer>

    <script>
    // Show selected file name
    document.getElementById('payment_proof').addEventListener('change', function(e) {
        const fileName = e.target.files[0] ? e.target.files[0].name : 'No file selected';
        document.getElementById('file-name').textContent = fileName;
        
        // Add animation to the file upload area
        const label = document.querySelector('.file-upload-label');
        label.classList.add('animate__animated', 'animate__pulse');
        setTimeout(() => {
            label.classList.remove('animate__animated', 'animate__pulse');
        }, 1000);
    });
    
    // Drag and drop functionality
    const fileUploadLabel = document.querySelector('.file-upload-label');
    
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        fileUploadLabel.addEventListener(eventName, preventDefaults, false);
    });
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    ['dragenter', 'dragover'].forEach(eventName => {
        fileUploadLabel.addEventListener(eventName, highlight, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        fileUploadLabel.addEventListener(eventName, unhighlight, false);
    });
    
    function highlight() {
        fileUploadLabel.style.borderColor = 'var(--accent-color)';
        fileUploadLabel.style.backgroundColor = 'rgba(0, 224, 255, 0.1)';
    }
    
    function unhighlight() {
        fileUploadLabel.style.borderColor = 'rgba(255, 255, 255, 0.2)';
        fileUploadLabel.style.backgroundColor = 'rgba(26, 26, 26, 0.8)';
    }
    
    fileUploadLabel.addEventListener('drop', handleDrop, false);
    
    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        document.getElementById('payment_proof').files = files;
        
        // Trigger change event
        const event = new Event('change');
        document.getElementById('payment_proof').dispatchEvent(event);
    }
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>