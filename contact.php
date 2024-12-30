<?php
require_once __DIR__ . '/config/config.php';
include 'includes/header.php';

$success_message = '';
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    // Validate input
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error_message = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
    } else {
        // Prepare and execute SQL statement
        $sql = "INSERT INTO contact_messages (name, email, subject, message, created_at) 
            VALUES (?, ?, ?, ?, NOW())";
        
        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute([$name, $email, $subject, $message]);
            $success_message = "Thank you for your message. We will get back to you soon!";
            
            // Clear form data after successful submission
            $name = $email = $subject = $message = '';
        } catch (PDOException $e) {
            $error_message = "Sorry, there was an error sending your message. Please try again later.";
        }
    }
}
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-lg-8">
            <h2 class="text-success mb-4">Contact Us</h2>
            
            <?php if ($success_message): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
            <?php endif; ?>
            
            <?php if ($error_message): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>
            
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="bg-light p-4 rounded shadow-sm">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" 
                        value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" 
                        value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="subject" class="form-label">Subject</label>
                    <input type="text" class="form-control" id="subject" name="subject" 
                        value="<?php echo htmlspecialchars($subject ?? ''); ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="message" class="form-label">Message</label>
                    <textarea class="form-control" id="message" name="message" rows="5" 
                            required><?php echo htmlspecialchars($message ?? ''); ?></textarea>
                </div>
                
                <button type="submit" class="btn btn-success">Send Message</button>
            </form>
        </div>
        
        <div class="col-lg-4">
            <div class="bg-light p-4 rounded shadow-sm mb-4">
                <h4 class="text-success mb-3">Contact Information</h4>
                <p><i class="fas fa-map-marker-alt text-success"></i> 123 Charity Street, City</p>
                <p><i class="fas fa-phone text-success"></i> +1 234 567 8900</p>
                <p><i class="fas fa-envelope text-success"></i> info@charity.org</p>
            </div>
            
            <!-- Google Maps Placeholder -->
            <div class="bg-light p-2 rounded shadow-sm" style="height: 300px;">
                <div class="bg-secondary h-100 d-flex align-items-center justify-content-center text-white">
                    <p class="mb-0">Google Maps Here</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

