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
        group by Year, a.status
        order by status ASC";

    $query2 = "SELECT DISTINCT substr(a.appealId,1,5) as Id,
        b.Name as AppealName,
        substr(c.Name, 1, 30) as Beneficiary,
        (CASE WHEN b.status IS NULL THEN 'In-Progress' ELSE b.status END) as status,
        (CASE WHEN b.cause IS NULL THEN 'General' ELSE b.cause END) as cause,
        c.Category, c.Type
    FROM TblTxDetails a
    LEFT OUTER JOIN TblAppealInfo b ON a.AppealId=b.Id
    INNER JOIN TblBeneficiary c ON b.BeneficiaryId=c.Id
    WHERE Year(a.DateOfTx)=:year AND a.appealId <> -1
    ORDER BY status ASC, b.Name ASC";

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
                                    <th colspan="3">
                                        Status-wise Beneficiaries 
                                        <span class="badge bg-info">
                                            <?php 
                                            $totalBeneficiaries = array_sum(array_column($statuswiseBeneficiaries, 'Total Beneficiaries'));
                                            echo $totalBeneficiaries . ' ' . ($totalBeneficiaries == 1 ? 'Beneficiary' : 'Beneficiaries');
                                            ?>
                                        </span>
                                    </th>
                                </tr>
                                <tr>
                                    <?php foreach (array_keys($statuswiseBeneficiaries[0]) as $header): ?>
                                        <th><?php echo ucfirst(strtolower($header)); ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($statuswiseBeneficiaries as $row): ?>
                                    <tr>
                                        <?php foreach ($row as $key => $value): ?>
                                            <td>
                                                <?php
                                                if ($key === 'Total Beneficiaries') {
                                                    echo "<span class='badge bg-info'>" . htmlspecialchars($value) . "</span>";
                                                } else {
                                                    echo htmlspecialchars($value);
                                                }
                                                ?>
                                            </td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                                <tr class="table-info">
                                    <td colspan="2"><strong>Total</strong></td>
                                    <td>
                                        <span class="badge bg-info">
                                            <?php
                                            $total = array_sum(array_column($statuswiseBeneficiaries, 'Total Beneficiaries'));
                                            echo $total;
                                            ?>
                                        </span>
                                    </td>
                                </tr>
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
                                    <th colspan="7">
                                        Detailed Beneficiary List
                                        <span class="badge bg-info">
                                            <?php 
                                            $totalCount = count($beneficiaryList);
                                            echo $totalCount . ' ' . ($totalCount == 1 ? 'Beneficiary' : 'Beneficiaries');
                                            ?>
                                        </span>
                                    </th>
                                </tr>
                                <tr>
                                    <?php foreach (array_keys($beneficiaryList[0]) as $header): ?>
                                        <th><?php echo ucfirst(strtolower($header)); ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Pre-count records per status
                                $statusCounts = [];
                                foreach ($beneficiaryList as $row) {
                                    $status = $row['status'];
                                    if (!isset($statusCounts[$status])) {
                                        $statusCounts[$status] = 0;
                                    }
                                    $statusCounts[$status]++;
                                }

                                $currentStatus = null;
                                foreach ($beneficiaryList as $row):
                                    if ($currentStatus !== $row['status']) {
                                        $currentStatus = $row['status'];
                                        echo "<tr class='table-secondary'>
                                                <td colspan='7'>
                                                    <strong>Status: " . htmlspecialchars($currentStatus) . "</strong>
                                                    <span class='badge bg-info'>" . 
                                                        $statusCounts[$currentStatus] . " in " . htmlspecialchars($currentStatus) . 
                                                    "</span>
                                                </td>
                                            </tr>";
                                    }
                                    echo "<tr>";
                                    foreach ($row as $value) {
                                        echo "<td>" . htmlspecialchars($value) . "</td>";
                                    }
                                    echo "</tr>";
                                endforeach;
                                ?>
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
