<?php
// Database connection with error handling
require 'config.php';

// Initialize variables
$success = $error = '';
$name = $email = $comment = '';
$rating = 0;

// Handle form submission with validation
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate and sanitize inputs
    $name = trim(htmlspecialchars($_POST['name'] ?? ''));
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $rating = (int)($_POST['rating'] ?? 0);
    $comment = trim(htmlspecialchars($_POST['comment'] ?? ''));
    $date = date('Y-m-d H:i:s');
    
    // Validate inputs
    $valid = true;
    
    if (empty($name) || strlen($name) > 100) {
        $valid = false;
        $error = "Please enter a valid name (max 100 characters)";
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 255) {
        $valid = false;
        $error = "Please enter a valid email address";
    }
    
    if ($rating < 1 || $rating > 5) {
        $valid = false;
        $error = "Please select a valid rating";
    }
    
    if (empty($comment)) {
        $valid = false;
        $error = "Please share your experience";
    }
    
    // If valid, insert into database
    if ($valid) {
        try {
            $stmt = $conn->prepare("INSERT INTO feedback (name, email, rating, comment, date) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("ssiss", $name, $email, $rating, $comment, $date);
            
            if ($stmt->execute()) {
                $success = "Thank you for your feedback!";
                // Clear form fields after successful submission
                $name = $email = $comment = '';
                $rating = 0;
            } else {
                throw new Exception("Database error");
            }
        } catch (Exception $e) {
            error_log("Feedback submission error: " . $e->getMessage());
            $error = "Error submitting feedback. Please try again later.";
        }
    }
}

// Get all feedback from database with pagination
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limit = 5;
$offset = ($page - 1) * $limit;

try {
    // Get total count for pagination
    $count_result = $conn->query("SELECT COUNT(*) as total FROM feedback");
    $total_feedback = $count_result->fetch_assoc()['total'];
    $total_pages = ceil($total_feedback / $limit);
    
    // Get paginated feedback
    $feedback_query = $conn->prepare("SELECT * FROM feedback ORDER BY date DESC LIMIT ? OFFSET ?");
    $feedback_query->bind_param("ii", $limit, $offset);
    $feedback_query->execute();
    $feedback_result = $feedback_query->get_result();
} catch (Exception $e) {
    error_log("Feedback query error: " . $e->getMessage());
    $error = "Error loading feedback. Please try again later.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback - Infinity Hair Salon</title>
    <meta name="description" content="Share your experience at Infinity Hair Salon. We value your feedback to improve our services.">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #8a63ff;
            --primary-light: #b18aff;
            --secondary: #00d2d3;
            --accent: #ff7675;
            --dark: #121212;
            --darker: #0a0a0a;
            --light: #e0e0e0;
            --lighter: #f5f5f5;
            --gray: #757575;
            --border: #333333;
            --star-filled: #ffc107;
            --star-empty: #444444;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--dark);
            color: var(--light);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        header {
            background: linear-gradient(135deg, var(--primary), var(--darker));
            color: white;
            text-align: center;
            padding: 4rem 1rem;
            clip-path: polygon(0 0, 100% 0, 100% 90%, 0 100%);
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }
        
        header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 20% 50%, rgba(138, 99, 255, 0.3), transparent 70%);
            z-index: 0;
        }
        
        header h1 {
            font-size: 2.8rem;
            margin-bottom: 0.5rem;
            font-weight: 700;
            position: relative;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }
        
        header p {
            font-size: 1.2rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
            position: relative;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
            flex: 1;
        }
        
        .feedback-form {
            background: var(--darker);
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            padding: 2.5rem;
            margin-bottom: 3rem;
            border: 1px solid var(--border);
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        
        .feedback-form:hover {
            transform: translateY(-5px);
        }
        
        .feedback-form::after {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 200%;
            background: radial-gradient(circle, rgba(138, 99, 255, 0.1), transparent 70%);
            z-index: 0;
        }
        
        .form-title {
            text-align: center;
            margin-bottom: 2.5rem;
            color: var(--primary-light);
            font-size: 2rem;
            position: relative;
            z-index: 1;
        }
        
        .form-group {
            margin-bottom: 2rem;
            position: relative;
            z-index: 1;
        }
        
        label {
            display: block;
            margin-bottom: 0.8rem;
            font-weight: 500;
            color: var(--lighter);
        }
        
        input, textarea, select {
            width: 100%;
            padding: 1rem 1.2rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border);
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            color: var(--light);
        }
        
        input:focus, textarea:focus, select:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(138, 99, 255, 0.2);
            background: rgba(255, 255, 255, 0.08);
        }
        
        textarea {
            min-height: 180px;
            resize: vertical;
        }
        
        .rating-container {
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
        }
        
        .rating-stars {
            display: flex;
            gap: 0.8rem;
            direction: rtl; /* Right to left for better star selection */
        }
        
        .rating-stars input {
            display: none;
        }
        
        .rating-stars label {
            font-size: 2rem;
            color: var(--star-empty);
            cursor: pointer;
            transition: all 0.2s ease;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .rating-stars input:checked ~ label,
        .rating-stars label:hover,
        .rating-stars label:hover ~ label {
            color: var(--star-filled);
            text-shadow: 0 0 10px rgba(255, 193, 7, 0.5);
        }
        
        .rating-stars input:checked + label {
            color: var(--star-filled);
            transform: scale(1.1);
        }
        
        .submit-btn {
            background: linear-gradient(to right, var(--primary), var(--primary-light));
            color: white;
            border: none;
            padding: 1.2rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 1rem;
            position: relative;
            overflow: hidden;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .submit-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to right, var(--primary-light), var(--primary));
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: -1;
        }
        
        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(138, 99, 255, 0.4);
        }
        
        .submit-btn:hover::before {
            opacity: 1;
        }
        
        .submit-btn:active {
            transform: translateY(0);
        }
        
        .feedback-list {
            background: var(--darker);
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            padding: 2.5rem;
            margin-bottom: 3rem;
            border: 1px solid var(--border);
        }
        
        .feedback-list h2 {
            text-align: center;
            margin-bottom: 2.5rem;
            color: var(--primary-light);
            font-size: 2rem;
        }
        
        .feedback-item {
            padding: 2rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            gap: 1rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 10px;
            margin-bottom: 1.5rem;
            position: relative;
        }
        
        .feedback-item:hover {
            background: rgba(255, 255, 255, 0.06);
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .feedback-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        
        .feedback-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        
        .feedback-name {
            font-weight: 600;
            color: var(--primary-light);
            font-size: 1.2rem;
        }
        
        .feedback-date {
            color: var(--gray);
            font-size: 0.9rem;
        }
        
        .feedback-rating {
            color: var(--star-filled);
            font-size: 1.2rem;
            letter-spacing: 2px;
        }
        
        .feedback-comment {
            color: var(--light);
            line-height: 1.7;
        }
        
        .feedback-email {
            font-size: 0.8rem;
            color: var(--gray);
            margin-top: 0.5rem;
        }
        
        .alert {
            padding: 1.2rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
            border: 1px solid transparent;
            animation: fadeIn 0.5s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .alert::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: currentColor;
            opacity: 0.1;
            z-index: 0;
        }
        
        .alert-success {
            color: var(--secondary);
        }
        
        .alert-error {
            color: var(--accent);
        }
        
        .alert i {
            margin-right: 0.8rem;
        }
        
        .alert-content {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .empty-feedback {
            text-align: center;
            color: var(--gray);
            padding: 2rem;
            font-style: italic;
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 2rem;
        }
        
        .pagination a, .pagination span {
            display: inline-block;
            padding: 0.5rem 1rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 5px;
            color: var(--light);
            text-decoration: none;
            transition: all 0.3s ease;
            border: 1px solid var(--border);
        }
        
        .pagination a:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
        }
        
        .pagination .current {
            background: var(--primary-light);
            color: white;
            font-weight: bold;
        }
        
        .pagination .disabled {
            opacity: 0.5;
            pointer-events: none;
        }
        
        footer {
            background: var(--darker);
            color: var(--gray);
            text-align: center;
            padding: 2rem;
            margin-top: auto;
            border-top: 1px solid var(--border);
        }
        
        .footer-links {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .footer-links a {
            color: var(--light);
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .footer-links a:hover {
            color: var(--primary-light);
        }
        
        .social-links {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .social-links a {
            color: var(--light);
            font-size: 1.5rem;
            transition: color 0.3s ease, transform 0.3s ease;
        }
        
        .social-links a:hover {
            color: var(--primary-light);
            transform: translateY(-3px);
        }
        
        @media (max-width: 768px) {
            header {
                padding: 3rem 1rem;
                clip-path: polygon(0 0, 100% 0, 100% 95%, 0 100%);
            }
            
            header h1 {
                font-size: 2.2rem;
            }
            
            .feedback-form,
            .feedback-list {
                padding: 1.8rem;
            }
            
            .rating-stars label {
                font-size: 1.8rem;
            }
            
            .feedback-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
            
            .feedback-date {
                align-self: flex-end;
            }
        }
        
        /* Animation for new feedback submission */
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.02); }
            100% { transform: scale(1); }
        }
        
        .new-feedback {
            animation: pulse 2s ease-in-out;
            border-left: 4px solid var(--primary);
        }
    </style>
</head>
<body>
    <header>
        <h1>Share Your Feedback</h1>
        <p>We value your opinion! Let us know about your experience at Infinity Hair Salon.</p>
    </header>
    
    <div class="container">
        <?php if (isset($success)): ?>
            <div class="alert alert-success">
                <div class="alert-content">
                    <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-error">
                <div class="alert-content">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="feedback-form">
            <h2 class="form-title">Your Feedback Matters</h2>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>#feedback-form" id="feedback-form">
                <div class="form-group">
                    <label for="name">Your Name</label>
                    <input type="text" id="name" name="name" placeholder="Enter your name" required value="<?php echo htmlspecialchars($name); ?>">
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required value="<?php echo htmlspecialchars($email); ?>">
                </div>
                
                <div class="form-group">
                    <label>Your Rating</label>
                    <div class="rating-container">
                        <div class="rating-stars">
                            <input type="radio" id="star5" name="rating" value="5" required <?php echo $rating === 5 ? 'checked' : ''; ?>>
                            <label for="star5" title="Excellent">★</label>
                            <input type="radio" id="star4" name="rating" value="4" <?php echo $rating === 4 ? 'checked' : ''; ?>>
                            <label for="star4" title="Good">★</label>
                            <input type="radio" id="star3" name="rating" value="3" <?php echo $rating === 3 ? 'checked' : ''; ?>>
                            <label for="star3" title="Average">★</label>
                            <input type="radio" id="star2" name="rating" value="2" <?php echo $rating === 2 ? 'checked' : ''; ?>>
                            <label for="star2" title="Poor">★</label>
                            <input type="radio" id="star1" name="rating" value="1" <?php echo $rating === 1 ? 'checked' : ''; ?>>
                            <label for="star1" title="Terrible">★</label>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="comment">Your Experience</label>
                    <textarea id="comment" name="comment" placeholder="Tell us about your experience..." required><?php echo htmlspecialchars($comment); ?></textarea>
                </div>
                
                <button type="submit" class="submit-btn">
                    <i class="fas fa-paper-plane"></i> Submit Feedback
                </button>
            </form>
        </div>
        
        <div class="feedback-list">
            <h2>What Our Clients Say</h2>
            
            <?php if (isset($feedback_result) && $feedback_result->num_rows > 0): ?>
                <?php while ($feedback = $feedback_result->fetch_assoc()): ?>
                    <div class="feedback-item <?php echo (isset($_POST['name']) && $_POST['name'] === $feedback['name'] && isset($success)) ? 'new-feedback' : ''; ?>">
                        <div class="feedback-header">
                            <span class="feedback-name"><?php echo htmlspecialchars($feedback['name']); ?></span>
                            <span class="feedback-date"><?php echo date('M j, Y \a\t g:i a', strtotime($feedback['date'])); ?></span>
                        </div>
                        <div class="feedback-rating" aria-label="Rating: <?php echo $feedback['rating']; ?> out of 5 stars">
                            <?php echo str_repeat('★', $feedback['rating']) . str_repeat('☆', 5 - $feedback['rating']); ?>
                        </div>
                        <p class="feedback-comment"><?php echo nl2br(htmlspecialchars($feedback['comment'])); ?></p>
                        <?php if (isset($_SESSION['admin'])): ?>
                            <div class="feedback-email"><?php echo htmlspecialchars($feedback['email']); ?></div>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
                
                <?php if ($total_pages > 1): ?>
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="?page=<?php echo $page - 1; ?>"><i class="fas fa-chevron-left"></i> Previous</a>
                        <?php else: ?>
                            <span class="disabled"><i class="fas fa-chevron-left"></i> Previous</span>
                        <?php endif; ?>
                        
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <a href="?page=<?php echo $i; ?>" <?php echo $i == $page ? 'class="current"' : ''; ?>><?php echo $i; ?></a>
                        <?php endfor; ?>
                        
                        <?php if ($page < $total_pages): ?>
                            <a href="?page=<?php echo $page + 1; ?>">Next <i class="fas fa-chevron-right"></i></a>
                        <?php else: ?>
                            <span class="disabled">Next <i class="fas fa-chevron-right"></i></span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="empty-feedback">
                    <i class="far fa-comment-dots"></i> No feedback yet. Be the first to share!
                </div>
            <?php endif; ?>
        </div>
    </div>
    
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
    
    <script>
        // Enhance form submission with AJAX
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('feedback-form');
            
            if (form) {
                form.addEventListener('submit', function(e) {
                    // You could add AJAX submission here for a smoother experience
                    // For now, we'll just let it submit normally
                });
            }
            
            // Highlight newly submitted feedback
            if (window.location.hash === '#feedback-form' && document.querySelector('.new-feedback')) {
                setTimeout(() => {
                    const newFeedback = document.querySelector('.new-feedback');
                    newFeedback.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }, 500);
            }
            
            // Add animation to stars on hover
            const stars = document.querySelectorAll('.rating-stars label');
            stars.forEach(star => {
                star.addEventListener('mouseover', function() {
                    this.style.transform = 'scale(1.3)';
                    this.style.transition = 'transform 0.2s ease';
                });
                
                star.addEventListener('mouseout', function() {
                    this.style.transform = 'scale(1)';
                });
            });
        });
    </script>
</body>
</html>