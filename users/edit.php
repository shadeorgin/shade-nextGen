<?php
require_once '../config/config.php';
require_once '../includes/header.php';

// Initialize variables
$errors = [];
$success = false;
$user = null;

// Validate user ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $errors[] = "Invalid user ID specified";
} else {
    $userId = (int)$_GET['id'];

    // Fetch existing user data
    $stmt = $conn->prepare("SELECT id, first_name, last_name, email, role FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $errors[] = "User not found";
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $user) {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $errors[] = "Invalid form submission";
    } else {
        // Validate input
        $first_name = trim($_POST['first_name'] ?? '');
        $last_name = trim($_POST['last_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $role = trim($_POST['role'] ?? '');

        if (empty($first_name)) {
            $errors[] = "First name is required";
        }
        if (empty($last_name)) {
            $errors[] = "Last name is required";
        }
        if (empty($email)) {
            $errors[] = "Email is required";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format";
        }
        if (!in_array($role, ['admin', 'staff', 'donor'])) {
            $errors[] = "Invalid role selected";
        }

        // Check email uniqueness (excluding current user)
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$email, $userId]);
        if (!$stmt->fetch()) {
            try {
                if (!empty($password)) {
                    // Update with password
                    $stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, password = ?, role = ? WHERE id = ?");
                    $stmt->execute([$first_name, $last_name, $email, password_hash($password, PASSWORD_DEFAULT), $role, $userId]);
                } else {
                    // Update without password
                    $stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, role = ? WHERE id = ?");
                    $stmt->execute([$first_name, $last_name, $email, $role, $userId]);
                }

                error_log("User updated successfully: ID=$userId, Email=$email");
                $_SESSION['success_message'] = "User updated successfully";
                header("Location: list.php");
                exit;

            } catch (PDOException $e) {
                error_log("Database error during user update: " . $e->getMessage());
                $errors[] = "Database error: " . $e->getMessage();
            }
        } else {
            $errors[] = "Email address already in use";
        }
        try {
            if (!empty($password)) {
                // Update with password
                $stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, password = ?, role = ? WHERE id = ?");
                $stmt->execute([$first_name, $last_name, $email, password_hash($password, PASSWORD_DEFAULT), $role, $userId]);
            } else {
                // Update without password
                $stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, role = ? WHERE id = ?");
                $stmt->execute([$first_name, $last_name, $email, $role, $userId]);
            }

            $_SESSION['success'] = "User updated successfully";
            header("Location: list.php");
            exit;

        } catch (PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
}

// Generate CSRF token
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
?>

<div class="container mt-4">
    <h2>Edit User</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($user): ?>
        <form method="POST" class="needs-validation" novalidate>
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

            <div class="mb-3">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name"
                    value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name"
                    value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email"
                    value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password (leave empty to keep current)</label>
                <input type="password" class="form-control" id="password" name="password">
                <div class="form-text">Only fill this if you want to change the password</div>
            </div>

            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select class="form-select" id="role" name="role" required>
                    <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                    <option value="staff" <?php echo $user['role'] === 'staff' ? 'selected' : ''; ?>>Staff</option>
                    <option value="donor" <?php echo $user['role'] === 'donor' ? 'selected' : ''; ?>>Donor</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Update User</button>
            <a href="list.php" class="btn btn-secondary">Cancel</a>
        </form>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>
