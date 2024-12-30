<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include configuration
require_once __DIR__ . '/config/config.php';

// Verify database connection
try {
    $conn->query('SELECT 1');
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

// If already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error = '';
$email = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        try {
            // Prepared statement to prevent SQL injection
            $sql = "SELECT id, email, password, first_name, role FROM users WHERE email = ?";
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                throw new Exception("Database error: Failed to prepare statement");
            }

            $stmt->bindValue(1, $email, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                // Debug information about the user
                error_log("Login attempt - User found: " . json_encode([
                    'id' => $user['id'],
                    'email' => $user['email'],
                    'role' => $user['role'],
                    'has_password' => !empty($user['password'])
                ]));

                // Verify the password against the stored hash
                // Note: During registration, passwords should be hashed using:
                // password_hash($password, PASSWORD_DEFAULT)
                if (password_verify($password, $user['password'])) {
                    // Set session variables
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_name'] = $user['first_name'];
                    $_SESSION['user_role'] = $user['role'];

                    header("Location: index.php");
                    exit();
                } else {
                    error_log("Login failed: Password verification failed for user {$user['email']}");
                    $error = "Invalid password. Please try again.";
                }
            } else {
                // User not found
                error_log("Login failed: No user found with email $email");
                $error = "No account found with this email address.";
            }

        } catch (Exception $e) {
            $error = "An error occurred. Please try again later.";
        }
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h3 class="text-center mb-0">Login</h3>
                </div>
                <div class="card-body">
                    <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
                        <div class="alert alert-info" role="alert">
                            <h5>Debug Information:</h5>
                            <ul class="mb-0">
                                <li>Email submitted: <?php echo htmlspecialchars($email); ?></li>
                                <li>User found: <?php echo isset($user) ? 'Yes' : 'No'; ?></li>
                                <?php if (isset($user)): ?>
                                    <li>Password verification attempted: Yes</li>
                                    <li>User role: <?php echo htmlspecialchars($user['role']); ?></li>
                                    <li>Password hash exists: <?php echo !empty($user['password']) ? 'Yes' : 'No'; ?></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" class="form-control <?php echo !empty($error) ? 'is-invalid' : ''; ?>"
                                id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="admin@charity.org" required>
                            <div class="invalid-feedback">
                                Please provide a valid email address.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control <?php echo !empty($error) ? 'is-invalid' : ''; ?>"
                                id="password" name="password" placeholder="Admin@123" required>
                            <div class="invalid-feedback">
                                Please provide your password.
                            </div>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <p class="mb-0">Don't have an account? <a href="register.php" class="text-success">Register here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
