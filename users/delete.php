<?php
require_once '../includes/header.php';
require_once '../includes/auth.php';
require_once '../config/config.php';

// Check if user is logged in and has admin privileges
if (!isLoggedIn() || !isAdmin()) {
    setFlashMessage('You do not have permission to delete users.', 'error');
    header('Location: list.php');
    exit();
}

// Verify CSRF token
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !validateCSRFToken($_POST['csrf_token'])) {
        setFlashMessage(['message' => 'Invalid request. Please try again.', 'type' => 'error']);
        header('Location: list.php');
        exit();
    }

    $userId = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
} else {
    $userId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
}

// Validate user ID
if (!$userId) {
    setFlashMessage(['message' => 'Invalid user ID.', 'type' => 'error']);
    header('Location: list.php');
    exit();
}

try {
    // Check if user exists
    $stmt = $conn->prepare("SELECT id, email FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();

    if (!$user) {
        setFlashMessage(['message' => 'User not found.', 'type' => 'error']);
        header('Location: list.php');
        exit();
    }

    // Prevent self-deletion
    if ($userId === $_SESSION['user_id']) {
        setFlashMessage(['message' => 'You cannot delete your own account.', 'type' => 'error']);
        header('Location: list.php');
        exit();
    }

    // If this is a GET request, show confirmation page
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        ?>
        <div class="container mt-4">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="card">
                        <div class="card-header">
                            <h4>Delete User</h4>
                        </div>
                        <div class="card-body">
                            <p>Are you sure you want to delete user: <strong><?php echo htmlspecialchars($user['email']); ?></strong>?</p>
                            <p class="text-danger">This action cannot be undone!</p>
                            
                            <form action="delete.php" method="POST">
                                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                <input type="hidden" name="user_id" value="<?php echo $userId; ?>">
                                
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-danger">Delete User</button>
                                    <a href="list.php" class="btn btn-secondary">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        require_once '../includes/footer.php';
        exit();
    }

    // Process deletion (POST request)
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$userId]);

    if ($stmt->rowCount() > 0) {
        setFlashMessage(['message' => 'User deleted successfully.', 'type' => 'success']);
    } else {
        setFlashMessage(['message' => 'Failed to delete user.', 'type' => 'error']);
    }

} catch (PDOException $e) {
    error_log($e->getMessage());
    setFlashMessage(['message' => 'An error occurred while deleting the user.', 'type' => 'error']);
}

header('Location: list.php');
exit();
?>
