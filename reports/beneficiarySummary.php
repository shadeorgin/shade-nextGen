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

        <!-- Report sections will be added here -->
        <div class="alert alert-info">
            Report sections are under development. Please check back later.
        </div>

    <?php endif; ?>
</div>

<?php require_once(__DIR__ . '/../includes/footer.php'); ?>

