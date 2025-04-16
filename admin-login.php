<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // For simplicity, we're using hardcoded credentials
    $admin_username = 'admin';
    $admin_password = 'admin123';

    if ($username === $admin_username && $password === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin-dashboard.php");
        exit();
    } else {
        $error_message = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/4c729db828.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body style="background: linear-gradient(135deg, #1c1c1c, #2c3e50); font-family: Arial, sans-serif; color: #ecf0f1; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0;">
    <main class="container py-5" style="background: rgba(44, 62, 80, 0.9); border-radius: 20px; padding: 40px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5); max-width: 400px; width: 100%;">
        <section>
            <h2 class="text-center mb-4" style="color: #ecf0f1; text-shadow: 0 0 10px rgba(236, 240, 241, 0.8); letter-spacing: 1px;">Admin Login</h2>
            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger" role="alert" style="background: #e74c3c; color: white; border: none; border-radius: 5px; padding: 10px; margin-bottom: 20px; text-align: center; box-shadow: 0 5px 15px rgba(231, 76, 60, 0.4);">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="username" class="form-label" style="color: #bdc3c7;">Username:</label>
                    <input type="text" class="form-control" id="username" name="username" required style="background: rgba(236, 240, 241, 0.1); border: 1px solid #34495e; color: #ecf0f1;">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label" style="color: #bdc3c7;">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" required style="background: rgba(236, 240, 241, 0.1); border: 1px solid #34495e; color: #ecf0f1;">
                </div>
                <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, #00bcd4, #2196f3); border: none; padding: 10px 20px; border-radius: 50px; color: white; font-weight: 700; letter-spacing: 1px; transition: all 0.3s ease; width: 100%; cursor: pointer; outline: none; text-transform: uppercase; margin-top: 20px;">Login</button>
            </form>
        </section>
    </main>
</body>

</html>