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

// Get selected year (default to current year if not set)
$selectedYear = isset($_GET['year']) ? intval($_GET['year']) : getDefaultYear();

// Initialize error message
$error = '';

try {
    // Initialize database connection
    $db = Database::getInstance();
    debug_log("Database connection successful");

    // SQL Queries
    $query1 = "select Year(DateOfTx) as Year, TxType, sum(Amount) as Total 
        from TblTxDetails where Year(DateOfTx)=:year and AppealId >= 0
        group by Year, TxType";

    $query2 = "select Year(DateOfTx) as Year, TxType, sum(Amount) as Total 
        from TblTxDetails where Year(DateOfTx)=:year and AppealId >= 0
        AND Remarks NOT LIKE '%SHaDE%Funds%' 
        group by Year, TxType";

    $query3 = "select
        Year(DateOfTx) as Year,
        AppealId,
        CASE WHEN a.AppealId = 0 THEN 'Generic SHaDE Appeal' ELSE COALESCE(b.Name, 'Unknown Appeal') END as AppealName,
        CASE WHEN a.AppealId = 0 THEN 'Active' ELSE COALESCE(b.Status, 'Unknown') END as AppealStatus,
        sum(CASE WHEN TxType='C' then a.Amount else 0 end) as CREDIT,
        sum(CASE WHEN TxType='D' then a.Amount else 0 end) as DEBIT,
        sum(CASE WHEN TxType='C' then a.Amount else 0 end)-sum(CASE WHEN TxType='D' then a.Amount else 0 end) as EffectiveTotal
        from TblTxDetails a
        LEFT OUTER JOIN TblAppealInfo b ON a.AppealId=b.ID
        where Year(DateOfTx)=:year and AppealId >= 0
        group by Year, AppealId";

    $query4 = "select
        Year(DateOfTx) as Year,
        AppealId,
        CASE WHEN a.AppealId = 0 THEN 'Generic SHaDE Appeal' ELSE COALESCE(b.Name, 'Unknown Appeal') END as AppealName,
        CASE WHEN a.AppealId = 0 THEN 'Active' ELSE COALESCE(b.Status, 'Unknown') END as AppealStatus,
        sum(CASE WHEN TxType='C' then a.Amount else 0 end) as CREDIT,
        sum(CASE WHEN TxType='D' then a.Amount else 0 end) as DEBIT,
        sum(CASE WHEN TxType='C' then a.Amount else 0 end)-sum(CASE WHEN TxType='D' then a.Amount else 0 end) as EffectiveTotal
        from TblTxDetails a
        LEFT OUTER JOIN TblAppealInfo b ON a.AppealId=b.ID
        where Year(DateOfTx)=:year and AppealId >= 0
        and a.Remarks NOT LIKE '%SHaDE%Funds%'
        group by Year, AppealId";

    $query5 = "select
        Year(DateOfTx) as Year,
        AppealId,
        CASE WHEN a.AppealId = 0 THEN 'Generic SHaDE Appeal' ELSE COALESCE(b.Name, 'Unknown Appeal') END as AppealName,
        CASE WHEN a.AppealId = 0 THEN 'Active' ELSE COALESCE(b.Status, 'Unknown') END as AppealStatus,
        sum(CASE WHEN TxType='C' then a.Amount else 0 end) as CREDIT,
        sum(CASE WHEN TxType='D' then a.Amount else 0 end) as DEBIT,
        sum(CASE WHEN TxType='C' then a.Amount else 0 end)-sum(CASE WHEN TxType='D' then a.Amount else 0 end) as EffectiveTotal
        from TblTxDetails a
        LEFT OUTER JOIN TblAppealInfo b ON a.AppealId=b.ID
        where Year(DateOfTx)=:year and AppealId >= 0
        and a.Remarks NOT LIKE '%SHaDE%Funds%'
        and UPPER(b.Name) like '%COVID%'
        group by Year, AppealId";

    // Execute queries
    $allTransactions = $db->queryAll($query1, ['year' => $selectedYear]);
    $nonInternalTransactions = $db->queryAll($query2, ['year' => $selectedYear]);
    $appealwiseTransactions = $db->queryAll($query3, ['year' => $selectedYear]);
    $appealwiseNonInternal = $db->queryAll($query4, ['year' => $selectedYear]);
    $covidTransactions = $db->queryAll($query5, ['year' => $selectedYear]);

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

<div class="container mt-4">
    <!-- Back Button -->
    <a href="<?php echo getBaseUrl(); ?>reports/" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Back to Reports
    </a>

    <h1 class="mb-4">Transaction Summary Report</h1>

    <?php if ($error): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php else: ?>
        <!-- Year Selection Form -->
        <form method="GET" class="mb-4">
            <div class="row align-items-end">
                <div class="col-auto">
                    <label for="year" class="form-label">Select Year:</label>
                    <select name="year" id="year" class="form-select" onchange="this.form.submit()">
                        <?php foreach (getYearRange() as $year): ?>
                            <?php $selected = ($year == $selectedYear) ? 'selected' : ''; ?>
                            <option value="<?php echo $year; ?>" <?php echo $selected; ?>>
                                <?php echo $year; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </form>

        <!-- 1. Summary of all Transactions -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">1. Summary of all Transactions</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($allTransactions)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <?php foreach (array_keys($allTransactions[0]) as $header): ?>
                                        <th><?php echo htmlspecialchars($header); ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($allTransactions as $row): ?>
                                    <tr>
                                        <?php foreach ($row as $key => $value): ?>
                                            <td><?php echo $key === 'Total' ? '₹' . formatIndianCurrency($value) : htmlspecialchars($value); ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted">No data available</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- 2. Summary excluding Internal Funds -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">2. Summary excluding Internal Funds</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($nonInternalTransactions)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <?php foreach (array_keys($nonInternalTransactions[0]) as $header): ?>
                                        <th><?php echo htmlspecialchars($header); ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($nonInternalTransactions as $row): ?>
                                    <tr>
                                        <?php foreach ($row as $key => $value): ?>
                                            <td><?php echo $key === 'Total' ? '₹' . formatIndianCurrency($value) : htmlspecialchars($value); ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted">No data available</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- 3. Appeal wise Transaction Summary -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">3. Appeal wise Transaction Summary</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($appealwiseTransactions)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <?php foreach (array_keys($appealwiseTransactions[0]) as $header): ?>
                                        <th><?php echo htmlspecialchars($header); ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($appealwiseTransactions as $row): ?>
                                    <tr>
                                        <?php foreach ($row as $key => $value): ?>
                                            <td><?php echo in_array($key, ['CREDIT', 'DEBIT', 'EffectiveTotal']) ? '₹' . formatIndianCurrency($value) : ($value === null ? '' : ($key === 'AppealName' && $row['AppealId'] === '0' ? 'Generic SHaDE Appeal' : htmlspecialchars((string)$value))); ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted">No data available</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- 4. Appeal wise Transaction Summary - Excluding Internal Funds -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">4. Appeal wise Transaction Summary - Excluding Internal Funds</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($appealwiseNonInternal)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <?php foreach (array_keys($appealwiseNonInternal[0]) as $header): ?>
                                        <th><?php echo htmlspecialchars($header); ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($appealwiseNonInternal as $row): ?>
                                    <tr>
                                        <?php foreach ($row as $key => $value): ?>
                                            <td><?php echo in_array($key, ['CREDIT', 'DEBIT', 'EffectiveTotal']) ? '₹' . formatIndianCurrency($value) : ($value === null ? '' : ($key === 'AppealName' && $row['AppealId'] === '0' ? 'Generic SHaDE Appeal' : htmlspecialchars((string)$value))); ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted">No data available</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- 5. COVID Specific Transaction Summary -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">5. COVID Specific Transaction Summary</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($covidTransactions)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <?php foreach (array_keys($covidTransactions[0]) as $header): ?>
                                        <th><?php echo htmlspecialchars($header); ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($covidTransactions as $row): ?>
                                    <tr>
                                        <?php foreach ($row as $key => $value): ?>
                                            <td><?php echo in_array($key, ['CREDIT', 'DEBIT', 'EffectiveTotal']) ? '₹' . formatIndianCurrency($value) : ($value === null ? '' : ($key === 'AppealName' && $row['AppealId'] === '0' ? 'Generic SHaDE Appeal' : htmlspecialchars((string)$value))); ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted">No data available</p>
                <?php endif; ?>
            </div>
        </div>

    <?php endif; ?>
</div>

<?php require_once(__DIR__ . '/../includes/footer.php'); ?>

