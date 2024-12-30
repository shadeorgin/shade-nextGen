<?php
include 'includes/header.php';
require_once __DIR__ . '/config/config.php';

// Get filter parameters
$category = isset($_GET['category']) ? $_GET['category'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'latest';

// Prepare the base query
$query = "SELECT * FROM appeals WHERE status = 'active'";
if ($category) {
    $query .= " AND category = ?";
}

// Add sorting
switch($sort) {
    case 'amount':
        $query .= " ORDER BY goal_amount DESC";
        break;
    case 'progress':
        $query .= " ORDER BY current_amount/goal_amount DESC";
        break;
    default:
        $query .= " ORDER BY created_at DESC";
}

$stmt = $conn->prepare($query);
if ($category) {
    $stmt->bindValue(1, $category, PDO::PARAM_STR);
}
$stmt->execute();
?>

<div class="container mt-4">
    <h1 class="text-center mb-4" style="color: #2E7D32">Current Appeals</h1>
    
    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-md-6">
            <form class="d-flex" method="GET">
                <select name="category" class="form-select me-2">
                    <option value="">All Categories</option>
                    <option value="education" <?php echo $category == 'education' ? 'selected' : ''; ?>>Education</option>
                    <option value="healthcare" <?php echo $category == 'healthcare' ? 'selected' : ''; ?>>Healthcare</option>
                    <option value="disaster" <?php echo $category == 'disaster' ? 'selected' : ''; ?>>Disaster Relief</option>
                    <option value="community" <?php echo $category == 'community' ? 'selected' : ''; ?>>Community</option>
                </select>
                <select name="sort" class="form-select me-2">
                    <option value="latest" <?php echo $sort == 'latest' ? 'selected' : ''; ?>>Latest</option>
                    <option value="amount" <?php echo $sort == 'amount' ? 'selected' : ''; ?>>Target Amount</option>
                    <option value="progress" <?php echo $sort == 'progress' ? 'selected' : ''; ?>>Progress</option>
                </select>
                <button type="submit" class="btn btn-success">Filter</button>
            </form>
        </div>
    </div>

    <!-- Appeals Grid -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php while($appeal = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <img src="<?php echo htmlspecialchars($appeal['image_url'] ?? ''); ?>" 
                        class="card-img-top" alt="Appeal Image" 
                        style="height: 200px; object-fit: cover;">
                    
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($appeal['title'] ?? ''); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($appeal['description'] ?? ''); ?></p>
                        
                        <?php
                        $current_amount = $appeal['current_amount'] ?? 0;
                        $goal_amount = $appeal['goal_amount'] ?? 1; // Default to 1 to avoid division by zero
                        $progress = $goal_amount > 0 ? ($current_amount / $goal_amount) * 100 : 0;
                        $progress = min($progress, 100); // Cap at 100%
                        ?>
                        
                        <div class="progress mb-3">
                            <div class="progress-bar bg-success" 
                                role="progressbar" 
                                style="width: <?php echo $progress; ?>%"
                                aria-valuenow="<?php echo $progress; ?>" 
                                aria-valuemin="0" 
                                aria-valuemax="100">
                                <?php echo number_format($progress, 1); ?>%
                            </div>
                        </div>
                        
                        <p class="card-text">
                            <small class="text-muted">
                                Raised: $<?php echo number_format($appeal['current_amount'] ?? 0, 2); ?> of
                                $<?php echo number_format($appeal['goal_amount'] ?? 0, 2); ?>
                            </small>
                        </p>
                        
                        <div class="d-grid gap-2">
                            <button type="button" 
                                    class="btn btn-success" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#donateModal<?php echo $appeal['id']; ?>">
                                Donate Now
                            </button>
                        </div>
                    </div>
                    
                    <div class="card-footer text-muted">
                        Category: <?php echo ucfirst(htmlspecialchars($appeal['category'] ?? '')); ?>
                    </div>
                </div>

                <!-- Donation Modal -->
                <div class="modal fade" id="donateModal<?php echo $appeal['id']; ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Donate to: <?php echo htmlspecialchars($appeal['title'] ?? ''); ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <form id="donationForm<?php echo $appeal['id']; ?>">
                                    <div class="mb-3">
                                        <label for="amount" class="form-label">Donation Amount ($)</label>
                                        <input type="number" class="form-control" id="amount" min="1" step="0.01" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="message" class="form-label">Message (Optional)</label>
                                        <textarea class="form-control" id="message" rows="3"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-success w-100">Proceed to Payment</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <?php if($stmt->rowCount() === 0): ?>
        <div class="alert alert-info text-center">
            No appeals found matching your criteria.
        </div>
    <?php endif; ?>
</div>

<?php
include 'includes/footer.php';
?>

