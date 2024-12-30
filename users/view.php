<?php
require_once '../config/config.php';
require_once '../includes/functions.php';

// Initialize variables
$user = null;
$error = null;

// Validate user ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $error = "Invalid user ID provided.";
} else {
    $userId = (int)$_GET['id'];

    try {
        // Prepare and execute the query
        $stmt = $conn->prepare("
            SELECT
                id,
                first_name, 
                last_name,
                CONCAT(first_name, ' ', last_name) as full_name,
                email,
                role,
                created_at,
                updated_at
            FROM users 
            WHERE id = ?
        ");

        // Execute with the user ID parameter
        $stmt->execute([$userId]);

        // Fetch the user
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            $error = "User not found.";
        }
    } catch (PDOException $e) {
        $error = "Database error: Unable to fetch user details.";
        error_log("Error fetching user $userId: " . $e->getMessage());
    }
}
?>

<?php include '../includes/header.php'; ?>
    <div class="container py-4">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <!-- Navigation buttons -->
                <div class="mb-4">
                    <a href="list.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php else: ?>
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">User Details</h4>
                            <div>
                                <a href="edit.php?id=<?php echo htmlspecialchars($user['id']); ?>"
                                class="btn btn-primary btn-sm">
                                    <i class="fas fa-pencil-alt"></i> Edit User
                                </a>
                                <button type="button"
                                        class="btn btn-danger btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteModal">
                                    <i class="fas fa-trash-alt"></i> Delete User
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-3 fw-bold">ID:</div>
                                <div class="col-md-9"><?php echo htmlspecialchars($user['id']); ?></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-3 fw-bold">Name:</div>
                                <div class="col-md-9"><?php echo htmlspecialchars($user['full_name']); ?></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-3 fw-bold">Email:</div>
                                <div class="col-md-9"><?php echo htmlspecialchars($user['email']); ?></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-3 fw-bold">Role:</div>
                                <div class="col-md-9"><?php echo htmlspecialchars($user['role']); ?></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-3 fw-bold">Created At:</div>
                                <div class="col-md-9"><?php echo htmlspecialchars($user['created_at']); ?></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-3 fw-bold">Updated At:</div>
                                <div class="col-md-9"><?php echo htmlspecialchars($user['updated_at']); ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- Delete Confirmation Modal -->
                    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Are you sure you want to delete this user? This action cannot be undone.
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <a href="delete.php?id=<?php echo htmlspecialchars($user['id']); ?>"
                                    class="btn btn-danger">Delete User</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
