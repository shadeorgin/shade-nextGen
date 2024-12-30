<?php
require_once '../config/config.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';

// Check if user is logged in and has appropriate permissions
if (!isLoggedIn() || !hasPermission('view_users')) {
    setFlashMessage('error', 'You do not have permission to access this page.');
    header('Location: ../index.php');
    exit;
}

// Handle search and filtering
$search = $_GET['search'] ?? '';
$role_filter = $_GET['role'] ?? '';
$sort = $_GET['sort'] ?? 'id';
$order = $_GET['order'] ?? 'ASC';

try {
    $sql = "SELECT id, first_name, last_name,
        CONCAT(first_name, ' ', last_name) as full_name,
        email, role, created_at
        FROM users 
        WHERE 1=1";
    $params = [];

    // Search filter
    if (!empty($search)) {
        $sql .= " AND (CONCAT(first_name, ' ', last_name) LIKE ? OR email LIKE ?)";
        $params[] = "%{$search}%";
        $params[] = "%{$search}%";
    }

    // Role filter
    if (!empty($role_filter)) {
        $sql .= " AND role = ?";
        $params[] = $role_filter;
    }

    // Add sorting
    $allowed_sorts = ['id', 'full_name', 'email', 'role', 'created_at'];
    $sort = in_array($sort, $allowed_sorts) ? $sort : 'id';
    $order = $order === 'DESC' ? 'DESC' : 'ASC';

    // Special handling for full_name sort
    if ($sort === 'full_name') {
        $sql .= " ORDER BY first_name $order, last_name $order";
    } else {
        $sql .= " ORDER BY $sort $order";
    }

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error = "Error fetching users: " . $e->getMessage();
    error_log($e->getMessage());
}

$page_title = "User Management";
include_once '../includes/header.php';
?>
<div class="container mt-4">
    <div class="row mb-4">
        <div class="col">
            <h1>User Management</h1>
        </div>
        <div class="col text-end">
            <a href="create.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New User
            </a>
        </div>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($error); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php
    $flash_message = getFlashMessage();
    if ($flash_message) {
        $type = 'info';
        $message = '';
        
        if (is_array($flash_message)) {
            $type = $flash_message['type'] ?? 'info';
            $message = $flash_message['message'] ?? '';
        } else {
            $message = (string) $flash_message;
        }
        
        if (!empty($message)): ?>
            <div class="alert alert-<?php echo htmlspecialchars($type); ?> alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif;
    }
    ?>
    <div class="row">
        <div class="col">
            <form class="mb-4" method="GET" action="">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search users..." value="<?php echo htmlspecialchars($search); ?>">
                    <select name="role" class="form-select" style="max-width: 150px;">
                        <option value="">All Roles</option>
                        <option value="admin" <?php echo $role_filter === 'admin' ? 'selected' : ''; ?>>Admin</option>
                        <option value="staff" <?php echo $role_filter === 'staff' ? 'selected' : ''; ?>>Staff</option>
                        <option value="donor" <?php echo $role_filter === 'donor' ? 'selected' : ''; ?>>Donor</option>
                    </select>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['id']); ?></td>
                            <td><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <?php
                                $badge_class = match($user['role']) {
                                    'admin' => 'bg-danger',
                                    'staff' => 'bg-primary',
                                    'donor' => 'bg-success',
                                    default => 'bg-secondary'
                                };
                                ?>
                                <span class="badge <?php echo $badge_class; ?>">
                                    <?php echo htmlspecialchars(ucfirst($user['role'])); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($user['created_at']); ?></td>
                            <td>
                                <a href="view.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="edit.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="delete.php?id=<?php echo $user['id']; ?>"
                                class="btn btn-sm btn-danger"
                                title="Delete"
                                onclick="return confirm('Are you sure you want to delete this user?');">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No users found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    </div>
    <?php include_once '../includes/footer.php'; ?>
