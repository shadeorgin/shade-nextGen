<?php
require_once __DIR__ . '/config/config.php';
include 'includes/header.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Handle date range filter
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d', strtotime('-30 days'));
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');

// Fetch report data
try {
    $stmt = $conn->prepare("
        SELECT 
            DATE(donation_date) as date,
            SUM(amount) as daily_total,
            COUNT(*) as donation_count
        FROM donations 
        WHERE donation_date BETWEEN ? AND ?
        GROUP BY DATE(donation_date)
    ");
    $stmt->execute([$start_date, $end_date]);
    $donation_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $stmt = $conn->prepare("
        SELECT 
            title,
            goal_amount,
            current_amount
        FROM appeals
        WHERE start_date <= NOW() AND end_date >= NOW()
    ");
    $stmt->execute();
    $campaign_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}
?>

<!-- Date Range Filter -->
<div class="container mt-4">
    <div class="card">
        <div class="card-body">
            <form class="row g-3" method="GET">
                <div class="col-md-4">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" 
                        value="<?php echo $start_date; ?>">
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" 
                        value="<?php echo $end_date; ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-success d-block">Apply Filter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reports Tabs -->
<div class="container mt-4">
    <ul class="nav nav-tabs" id="reportTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="donations-tab" data-bs-toggle="tab" href="#donations" role="tab">
                Donation Reports
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="campaigns-tab" data-bs-toggle="tab" href="#campaigns" role="tab">
                Campaign Reports
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="impact-tab" data-bs-toggle="tab" href="#impact" role="tab">
                Impact Reports
            </a>
        </li>
    </ul>
    
    <div class="tab-content" id="reportTabsContent">
        <!-- Donations Tab -->
        <div class="tab-pane fade show active" id="donations" role="tabpanel">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title">Donation Trends</h5>
                        <button class="btn btn-outline-success" onclick="exportReport('donations')">
                            <i class="fas fa-download"></i> Export
                        </button>
                    </div>
                    <canvas id="donationChart"></canvas>
                    
                    <div class="table-responsive mt-4">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Total Donations</th>
                                    <th>Number of Donations</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($donation_data as $row): ?>
                                <tr>
                                    <td><?php echo $row['date']; ?></td>
                                    <td>$<?php echo number_format($row['daily_total'], 2); ?></td>
                                    <td><?php echo $row['donation_count']; ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Campaigns Tab -->
        <div class="tab-pane fade" id="campaigns" role="tabpanel">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title">Campaign Progress</h5>
                        <button class="btn btn-outline-success" onclick="exportReport('campaigns')">
                            <i class="fas fa-download"></i> Export
                        </button>
                    </div>
                    <canvas id="campaignChart"></canvas>
                    
                    <div class="table-responsive mt-4">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Campaign</th>
                                    <th>Target Amount</th>
                                    <th>Current Amount</th>
                                    <th>Progress</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($campaign_data as $campaign): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($campaign['title'] ?? ''); ?></td>
                                    <td>$<?php echo number_format($campaign['goal_amount'] ?? 0, 2); ?>
                                    <td>$<?php echo number_format($campaign['current_amount'], 2); ?></td>
                                    <td>
                                        <div class="progress">
                                            <?php 
                                            $current = $campaign['current_amount'] ?? 0;
                                            $goal = $campaign['goal_amount'] ?? 1;
                                            $percentage = ($current / $goal) * 100;
                                            ?>
                                            <div class="progress-bar bg-success" role="progressbar" 
                                                style="width: <?php echo $percentage; ?>%">
                                                <?php echo round($percentage); ?>%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Impact Tab -->
        <div class="tab-pane fade" id="impact" role="tabpanel">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title">Impact Statistics</h5>
                        <button class="btn btn-outline-success" onclick="exportReport('impact')">
                            <i class="fas fa-download"></i> Export
                        </button>
                    </div>
                    <div class="alert alert-info">
                        Impact reporting functionality coming soon...
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Integration -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Donation Chart
    const donationCtx = document.getElementById('donationChart').getContext('2d');
    new Chart(donationCtx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode(array_column($donation_data, 'date')); ?>,
            datasets: [{
                label: 'Daily Donations ($)',
                data: <?php echo json_encode(array_column($donation_data, 'daily_total')); ?>,
                borderColor: '#198754',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    
    // Campaign Chart
    const campaignCtx = document.getElementById('campaignChart').getContext('2d');
    new Chart(campaignCtx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode(array_column($campaign_data, 'title')); ?>,
            datasets: [{
                label: 'Goal Amount', 
                data: <?php echo json_encode(array_column($campaign_data, 'goal_amount')); ?>,
                backgroundColor: '#198754'
            }, {
                label: 'Current Amount',
                data: <?php echo json_encode(array_column($campaign_data, 'current_amount')); ?>,
                backgroundColor: '#75b798'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});

function exportReport(type) {
    alert('Export functionality will be implemented soon for ' + type + ' reports.');
}
</script>

<?php include 'includes/footer.php'; ?>

