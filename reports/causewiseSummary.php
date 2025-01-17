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

// Initialize error message
$error = '';

// Get year range and selected year
$yearRange = getYearRange();
$selectedYear = isset($_GET['year']) ? intval($_GET['year']) : getDefaultYear();

try {
    // Initialize database connection
    $db = Database::getInstance();
    debug_log("Database connection successful");

    // Array to store all results
    $results = [];

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

    <h1 class="mb-4">Causewise Summary Report</h1>

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
                        <?php foreach ($yearRange as $year): ?>
                            <?php $selected = ($year == $selectedYear) ? 'selected' : ''; ?>
                            <option value="<?php echo $year; ?>" <?php echo $selected; ?>><?php echo $year; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </form>

        <!-- Section 1: Total Appeals -->
        <?php
        $sql = "select Year(a.DateOfTx) as Year, count(distinct appealId) as 'Appeal Count'
            from TblTxDetails a
            LEFT OUTER JOIN TblAppealInfo b ON a.AppealId=b.Id
            where Year(a.DateOfTx)=:year and a.appealId<>-1
            group by Year";
        $totalAppeals = $db->queryAll($sql, ['year' => $selectedYear]);
        ?>
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">1. Total Appeals
                    <?php if (!empty($totalAppeals)): ?>
                        <span class="badge bg-info"><?php echo array_sum(array_column($totalAppeals, 'Appeal Count')); ?> Appeals</span>
                    <?php endif; ?>
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($totalAppeals)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <?php foreach (array_keys($totalAppeals[0]) as $header): ?>
                                        <th><?php echo htmlspecialchars($header); ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($totalAppeals as $row): ?>
                                    <tr>
                                        <?php foreach ($row as $value): ?>
                                            <td><?php echo htmlspecialchars($value); ?></td>
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

        <!-- Section 2: Causewise Appeals -->
        <?php
        $sql = "select Year(a.DateOfTx) as Year,
            (case when b.cause IS NULL then 'General' else b.cause end) as cause,
            count(distinct appealId) as 'Total Appeals'
            from TblTxDetails a
            LEFT OUTER JOIN TblAppealInfo b ON a.AppealId=b.Id
            where Year(a.DateOfTx)=:year and a.appealId<>-1
            group by Year, b.cause";
        $causewiseAppeals = $db->queryAll($sql, ['year' => $selectedYear]);
        ?>
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">2. Causewise Appeals
                    <?php if (!empty($causewiseAppeals)): ?>
                        <span class="badge bg-info"><?php echo array_sum(array_column($causewiseAppeals, 'Total Appeals')); ?> Appeals</span>
                        <span class="badge bg-secondary"><?php echo count(array_unique(array_column($causewiseAppeals, 'cause'))); ?> Categories</span>
                    <?php endif; ?>
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($causewiseAppeals)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <?php foreach (array_keys($causewiseAppeals[0]) as $header): ?>
                                        <th><?php echo htmlspecialchars($header); ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($causewiseAppeals as $row): ?>
                                    <tr>
                                        <?php foreach ($row as $value): ?>
                                            <td><?php echo htmlspecialchars($value); ?></td>
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

        <!-- Section 3: Status and Causewise Appeals -->
        <?php
        $sql = "select Year(a.DateOfTx) as Year,
            (case when b.status IS NULL then 'In-Progress' else b.status end) as status,
            (case when b.cause IS NULL then 'General' else b.cause end) as cause,
            count(distinct appealId) as 'Total Appeals'
            from TblTxDetails a
            LEFT OUTER JOIN TblAppealInfo b ON a.AppealId=b.Id
            where Year(a.DateOfTx)=:year and a.appealId<>-1
            group by Year, b.status, b.cause";
        $statusCausewise = $db->queryAll($sql, ['year' => $selectedYear]);
        ?>
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">3. Status and Causewise Appeals
                    <?php if (!empty($statusCausewise)): ?>
                        <span class="badge bg-info"><?php echo array_sum(array_column($statusCausewise, 'Total Appeals')); ?> Appeals</span>
                    <?php endif; ?>
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($statusCausewise)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <?php foreach (array_keys($statusCausewise[0]) as $header): ?>
                                        <th><?php echo htmlspecialchars($header); ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($statusCausewise as $row): ?>
                                    <tr>
                                        <?php foreach ($row as $value): ?>
                                            <td><?php echo htmlspecialchars($value); ?></td>
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

        <!-- Section 4: Appeals without Transactions -->
        <?php
        $sql = "select Year(DateEntered) As Year, Status, Cause, Count(*) as Count
            from TblAppealInfo
            where Year(DateEntered)=:year and Id <> -1
            and Id NOT in (select AppealId from TblTxDetails where Year(DateOfTx)=:year)
            group by Status, Cause";
        $noTransactions = $db->queryAll($sql, ['year' => $selectedYear]);
        ?>
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">4. Appeals without Transactions
                    <?php if (!empty($noTransactions)): ?>
                        <span class="badge bg-warning"><?php echo array_sum(array_column($noTransactions, 'Count')); ?> Appeals</span>
                    <?php endif; ?>
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($noTransactions)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <?php foreach (array_keys($noTransactions[0]) as $header): ?>
                                        <th><?php echo htmlspecialchars($header); ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($noTransactions as $row): ?>
                                    <tr>
                                        <?php foreach ($row as $value): ?>
                                            <td><?php echo htmlspecialchars($value); ?></td>
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

        <!-- Section 5: New COVID Related Appeals -->
        <?php
        $sql = "select Year(DateEntered) as Year, Id, Name, Cause, Status
            from TblAppealInfo
            where Year(DateEntered)=:year and UPPER(Name) like '%COVID%' and Id <> -1
            order by Id asc";
        $covidAppeals = $db->queryAll($sql, ['year' => $selectedYear]);
        ?>
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">5. New COVID Related Appeals
                    <?php if (!empty($covidAppeals)): ?>
                        <span class="badge bg-info"><?php echo count($covidAppeals); ?> Appeals</span>
                    <?php endif; ?>
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($covidAppeals)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <?php foreach (array_keys($covidAppeals[0]) as $header): ?>
                                        <th><?php echo htmlspecialchars($header); ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($covidAppeals as $row): ?>
                                    <tr>
                                        <?php foreach ($row as $value): ?>
                                            <td><?php echo htmlspecialchars($value); ?></td>
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

        <!-- Section 6: Continuing COVID Appeals -->
        <?php
        $sql = "select distinct Year(a.DateOfTx) as Year, Year(b.DateEntered) as Year_Created,
            a.AppealId, b.Name
            from TblTxDetails a
            inner join TblAppealInfo b ON a.AppealId=b.Id and b.Id <> -1
            where a.DateOfTx between :yearStart and :yearEnd
            and UPPER(b.Name) like '%COVID%'
            order by a.AppealId asc";
        $continuingCovid = $db->queryAll($sql, [
            'yearStart' => $selectedYear . '-01-01',
            'yearEnd' => $selectedYear . '-12-31'
        ]);
        ?>
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">6. Continuing COVID Appeals
                    <?php if (!empty($continuingCovid)): ?>
                        <span class="badge bg-info"><?php echo count($continuingCovid); ?> Appeals</span>
                    <?php endif; ?>
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($continuingCovid)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <?php foreach (array_keys($continuingCovid[0]) as $header): ?>
                                        <th><?php echo htmlspecialchars($header); ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($continuingCovid as $row): ?>
                                    <tr>
                                        <?php foreach ($row as $value): ?>
                                            <td><?php echo htmlspecialchars($value); ?></td>
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
