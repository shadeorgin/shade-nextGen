<?php
// Debug flag - set to false to disable debug output
define('DEBUG_MODE', false);

require_once(__DIR__ . '/../includes/init.php');
require_once(__DIR__ . '/../includes/utilities.php');
require_once(__DIR__ . '/includes/report_utilities.php');

// Helper function for debug output
function debug_log($message) {
    if (DEBUG_MODE) {
        echo "<div class='alert alert-info'>Debug: " . htmlspecialchars($message) . "</div>";
    }
}
echo "<div class='container mt-4'>";  // Start container earlier

// SQL Queries
$query1 = "SELECT
    COUNT(DISTINCT CASE WHEN t.appealId = 0 THEN 0 ELSE a.id END) as total_appeals,
    COALESCE(SUM(CASE WHEN t.TxType = 'C' THEN t.amount ELSE 0 END), 0) as total_amount
FROM TblTxDetails t
LEFT JOIN TblAppealInfo a ON t.appealId = a.id
WHERE YEAR(t.DateOfTx) = :year
AND (a.id <> -1 OR t.appealId = 0)";

$query2 = "SELECT
    COALESCE(CASE WHEN t.appealId = 0 THEN 'In-Progress' ELSE a.status END, 'Pending') as status,
    COUNT(DISTINCT CASE WHEN t.appealId = 0 THEN 0 ELSE a.id END) as appeal_count,
    COALESCE(SUM(CASE WHEN t.TxType = 'C' THEN t.amount ELSE 0 END), 0) as total_amount
FROM TblTxDetails t
LEFT JOIN TblAppealInfo a ON t.appealId = a.id
WHERE YEAR(t.DateOfTx) = :year
AND (a.id <> -1 OR t.appealId = 0)
GROUP BY CASE WHEN t.appealId = 0 THEN 'In-Progress' ELSE a.status END";

$query3 = "SELECT
        a.id,
        a.name,
        a.status,
        a.created_date
    FROM TblAppealInfo a
    LEFT JOIN TblTxDetails t ON a.id = t.appealId
    WHERE t.TxId IS NULL
        AND a.id <> -1
        AND YEAR(a.created_date) = :year
    ORDER BY created_date";

// Get selected year or default to previous year
$selectedYear = isset($_GET['year']) ? intval($_GET['year']) : getDefaultYear();

// Initialize error message
$error = '';

try {
    // Initialize database connection
    $db = Database::getInstance();
    debug_log("Database connection successful");

    // Query 1
    debug_log("Executing Query 1 - Total Summary");
    $totalSummary = $db->queryOne($query1, ['year' => $selectedYear]);
    debug_log("Query 1 completed - Results: " . print_r($totalSummary, true));

    // Query 2
    debug_log("Executing Query 2 - Status Summary");
    $statusSummary = $db->queryAll($query2, ['year' => $selectedYear]);
    debug_log("Query 2 completed - Found " . count($statusSummary) . " status records");

    // Query 3
    debug_log("Executing Query 3 - No Transactions");
    $noTransactions = $db->queryAll($query3, ['year' => $selectedYear]);
    debug_log("Query 3 completed - Found " . count($noTransactions) . " records without transactions");

} catch (Exception $e) {
    $error = "Database error occurred";
    if (DEBUG_MODE) {
        $error .= ": " . $e->getMessage();
        $error .= "<br>File: " . htmlspecialchars($e->getFile());
        $error .= "<br>Line: " . $e->getLine();
        if ($e instanceof PDOException) {
            $error .= "<br>SQL State: " . $e->getCode();
        }
    }
}
?>

    <!-- Back Button -->
    <a href="<?php echo getBaseUrl(); ?>reports/" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Back to Reports
    </a>

    <h1 class="mb-4">Appeal Summary Report</h1>

    <!-- Year Selection Form -->
    <form method="GET" class="mb-4">
        <div class="row align-items-end">
            <div class="col-auto">
                <label for="year" class="form-label">Select Year:</label>
                <select name="year" id="year" class="form-select" onchange="this.form.submit()">
                    <?php foreach (getYearRange() as $year): ?>
                        <?php $selected = ($year == $selectedYear) ? 'selected' : ''; ?>
                        <option value="<?php echo $year; ?>" <?php echo $selected; ?>><?php echo $year; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </form>

    <?php if ($error): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php else: ?>

        <!-- Total Appeals Summary -->
        <div class="card mb-4">
            <div class="card-header">
                <h4 class="mb-0">Total Appeals Summary</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Total Appeals</th>
                            <th>Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo number_format(array_get($totalSummary, 'total_appeals', 0)); ?></td>
                            <td>₹<?php echo formatIndianCurrency(floatval(array_get($totalSummary, 'total_amount', 0))); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Status-wise Appeals Summary -->
        <div class="card mb-4">
            <div class="card-header">
                <h4 class="mb-0">Status-wise Appeals Summary
                    <?php if (!empty($statusSummary)): ?>
                        <span class="badge bg-info"><?php echo array_sum(array_column($statusSummary, 'appeal_count')); ?> Appeals</span>
                    <?php endif; ?>
                </h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>Status</th>
                            <th>Number of Appeals</th>
                            <th>Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($statusSummary as $status): ?>
                        <tr>
                            <td><?php echo htmlspecialchars(array_get($status, 'status', '')); ?></td>
                            <td><?php echo number_format(array_get($status, 'appeal_count', 0)); ?></td>
                            <td>₹<?php echo formatIndianCurrency(floatval(array_get($status, 'total_amount', 0))); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Appeals Without Transactions -->
        <div class="card mb-4">
            <div class="card-header">
                <h4 class="mb-0">Appeals Without Transactions
                    <?php if (!empty($noTransactions)): ?>
                        <span class="badge bg-warning"><?php echo count($noTransactions); ?> Appeals</span>
                    <?php endif; ?>
                </h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>Appeal ID</th>
                            <th>Appeal Name</th>
                            <th>Status</th>
                            <th>Created Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($noTransactions) > 0): ?>
                            <?php foreach ($noTransactions as $appeal): ?>
                            <tr>
                                <td><?php echo htmlspecialchars(array_get($appeal, 'id', '')); ?></td>
                                <td><?php echo htmlspecialchars(array_get($appeal, 'name', '')); ?></td>
                                <td><?php echo htmlspecialchars(array_get($appeal, 'status', '')); ?></td>
                                <td><?php echo !empty($appeal['created_date']) ? date('Y-m-d', strtotime($appeal['created_date'])) : ''; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center">No appeals without transactions found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    <?php endif; ?>
</div>

<?php require_once(__DIR__ . '/../includes/footer.php'); ?>
