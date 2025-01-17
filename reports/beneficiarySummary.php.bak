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

// Get selected year (default to current year if not set)
$selectedYear = isset($_GET['year']) ? intval($_GET['year']) : getDefaultYear();

try {
    // Initialize database connection
    $db = Database::getInstance();
    debug_log("Database connection successful");

    // SQL Queries
    $query1 = "select Year(a.DateEntered) as Year, 
        (case when a.status IS NULL then 'In-Progress' else a.status end) as status, 
        count(distinct beneficiaryId) as 'Total Beneficiaries' 
        from TblAppealInfo a 
        LEFT OUTER JOIN TblBeneficiary b ON a.BeneficiaryId=b.Id 
        where Year(a.DateEntered)=:year and a.Id <> -1
        group by Year, a.status";

    $query2 = "select distinct substr(a.appealId,1,5) as Id, 
        b.Name as AppealName, 
        substr(c.Name, 1, 30) as Beneficiary, 
        (case when b.status IS NULL then 'In-Progress' else b.status end) as status, 
        (case when b.cause IS NULL then 'General' else b.cause end) as cause, 
        c.Category, c.Type 
        from TblTxDetails a 
        LEFT OUTER JOIN TblAppealInfo b ON a.AppealId=b.Id 
        INNER JOIN TblBeneficiary c ON b.BeneficiaryId=c.Id 
        where Year(a.DateOfTx)=:year and a.appealId <> -1
        order by a.appealId";

    // Execute Query 1 - Status-wise Beneficiaries
    debug_log("Executing Query 1 - Status-wise Beneficiaries");
    $statuswiseBeneficiaries = $db->queryAll($query1, ['year' => $selectedYear]);
    debug_log("Query 1 completed - Found " . count($statuswiseBeneficiaries) . " status records");

    // Execute Query 2 - Detailed Beneficiary List
    debug_log("Executing Query 2 - Detailed Beneficiary List");
    $beneficiaryList = $db->queryAll($query2, ['year' => $selectedYear]);
    debug_log("Query 2 completed - Found " . count($beneficiaryList) . " beneficiary records");

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

    <h1 class="mb-4">Beneficiary Summary Report</h1>

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

        <!-- Status-wise Beneficiaries -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">1. Status-wise Beneficiaries</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($statuswiseBeneficiaries)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <?php foreach (array_keys($statuswiseBeneficiaries[0]) as $header): ?>
                                        <th><?php echo htmlspecialchars($header); ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($statuswiseBeneficiaries as $row): ?>
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

        <!-- Detailed Beneficiary List -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">2. Detailed Beneficiary List</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($beneficiaryList)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <?php foreach (array_keys($beneficiaryList[0]) as $header): ?>
                                        <th><?php echo htmlspecialchars($header); ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($beneficiaryList as $row): ?>
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

