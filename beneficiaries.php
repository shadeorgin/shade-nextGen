<?php
require_once __DIR__ . '/config/config.php';
include 'includes/header.php';

// Initialize pagination variables
$limit = 12; // items per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Initialize search variables
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';

// Prepare base query
$query = "SELECT * FROM beneficiaries WHERE 1=1";
$count_query = "SELECT COUNT(*) as total FROM beneficiaries WHERE 1=1";

// Add search conditions
if (!empty($search)) {
    $search_term = "%$search%";
    $query .= " AND (name LIKE ? OR story LIKE ?)";
    $count_query .= " AND (name LIKE ? OR story LIKE ?)";
}

// Add category filter
if (!empty($category)) {
    $query .= " AND category = ?";
    $count_query .= " AND category = ?";
}

// Add pagination
$query .= " LIMIT ?, ?";

// Prepare and execute count query
$count_stmt = $conn->prepare($count_query);
$params = [];
if (!empty($search) && !empty($category)) {
    $params[] = $search_term;
    $params[] = $search_term;
    $params[] = $category;
} elseif (!empty($search)) {
    $params[] = $search_term;
    $params[] = $search_term;
} elseif (!empty($category)) {
    $params[] = $category;
}
foreach ($params as $i => $param) {
    $count_stmt->bindValue($i + 1, $param, PDO::PARAM_STR);
}
$count_stmt->execute();
$total_results = $count_stmt->fetch(PDO::FETCH_ASSOC)['total'];
$total_pages = ceil($total_results / $limit);

// Prepare and execute main query
$stmt = $conn->prepare($query);
$params = [];
if (!empty($search) && !empty($category)) {
    $params = [$search_term, $search_term, $category, $start, $limit];
} elseif (!empty($search)) {
    $params = [$search_term, $search_term, $start, $limit];
} elseif (!empty($category)) {
    $params = [$category, $start, $limit];
} else {
    $params = [$start, $limit];
}
foreach ($params as $i => $param) {
    $stmt->bindValue($i + 1, $param, 
        is_int($param) ? PDO::PARAM_INT : PDO::PARAM_STR);
}
$stmt->execute();
?>

<!-- Impact Statistics Section -->
<div class="container mt-5">
    <div class="row text-center mb-5">
        <h2 class="text-success mb-4">Our Impact</h2>
        <div class="col-md-3">
            <div class="card border-success h-100">
                <div class="card-body">
                    <h3 class="text-success">1,234</h3>
                    <p>Lives Impacted</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-success h-100">
                <div class="card-body">
                    <h3 class="text-success">56</h3>
                    <p>Communities Served</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-success h-100">
                <div class="card-body">
                    <h3 class="text-success">$2.5M</h3>
                    <p>Aid Distributed</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-success h-100">
                <div class="card-body">
                    <h3 class="text-success">89%</h3>
                    <p>Success Rate</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filter Section -->
<div class="container mb-4">
    <form method="GET" class="row g-3">
        <div class="col-md-6">
            <input type="text" class="form-control" name="search" placeholder="Search beneficiaries..." value="<?php echo htmlspecialchars($search); ?>">
        </div>
        <div class="col-md-4">
            <select class="form-select" name="category">
                <option value="">All Categories</option>
                <option value="education" <?php echo $category === 'education' ? 'selected' : ''; ?>>Education</option>
                <option value="healthcare" <?php echo $category === 'healthcare' ? 'selected' : ''; ?>>Healthcare</option>
                <option value="housing" <?php echo $category === 'housing' ? 'selected' : ''; ?>>Housing</option>
                <option value="employment" <?php echo $category === 'employment' ? 'selected' : ''; ?>>Employment</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-success w-100">Search</button>
        </div>
    </form>
</div>

<!-- Success Stories Section -->
<div class="container mb-5">
    <h2 class="text-success mb-4">Success Stories</h2>
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title text-success">Sarah's Educational Journey</h5>
                    <p class="card-text">Through our scholarship program, Sarah was able to complete her degree in Engineering and now works at a leading tech company.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title text-success">The Thompson Family</h5>
                    <p class="card-text">After receiving housing assistance, the Thompson family was able to secure stable accommodation and focus on their children's education.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Beneficiaries Grid -->
<div class="container">
    <h2 class="text-success mb-4">Our Beneficiaries</h2>
    <div class="row">
        <?php while ($beneficiary = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="<?php echo htmlspecialchars($beneficiary['image_url'] ?? ''); ?>" class="card-img-top" alt="Beneficiary Image">
                    <div class="card-body">
                        <h5 class="card-title text-success"><?php echo htmlspecialchars($beneficiary['name'] ?? ''); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($beneficiary['story'] ?? ''); ?></p>
                        <p class="text-muted">Category: <?php echo htmlspecialchars($beneficiary['category'] ?? ''); ?></p>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- Pagination -->
<?php if ($total_pages > 1): ?>
<div class="container mb-5">
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $page-1; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo urlencode($category); ?>">Previous</a>
            </li>
            <?php for($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?php echo $page == $i ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo urlencode($category); ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
            <li class="page-item <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $page+1; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo urlencode($category); ?>">Next</a>
            </li>
        </ul>
    </nav>
</div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>

