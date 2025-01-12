<?php
require_once(__DIR__ . '/../includes/init.php');
require_once(__DIR__ . '/../includes/utilities.php');
require_once(__DIR__ . '/includes/report_utilities.php');

$error = '';
$stateData = [];

try {
    // Initialize database connection
    $db = Database::getInstance();
    
    // Query state coverage data
    $stateQuery = "SELECT 
        COALESCE(b.State, 'Unknown') as state,
        COUNT(DISTINCT b.Id) as beneficiary_count,
        COUNT(DISTINCT a.Id) as appeal_count,
        MAX(b.City) as sample_city
    FROM TblBeneficiary b
    LEFT JOIN TblAppealInfo a ON b.Id = a.BeneficiaryId
    GROUP BY b.State
    ORDER BY b.State";
    
    $stateData = $db->queryAll($stateQuery);
    
} catch (Exception $e) {
    $error = "Failed to fetch state coverage data";
    if (!IS_PRODUCTION) {
        $error .= ": " . $e->getMessage();
    }
}
?>

<?php require_once(__DIR__ . '/../includes/header.php'); ?>
<div class="container mt-4">
    <a href="<?php echo getBaseUrl(); ?>reports/" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Back to Reports
    </a>
    
    <h2>State Coverage Map</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php else: ?>
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Coverage by State</h5>
                        <div id="indiaMap" style="height: 600px;"></div>
                        
                        <div class="accordion mt-3 bg-light">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#mapDataCollapse">
                                        Coverage Data Details
                                    </button>
                                </h2>
                                <div id="mapDataCollapse" class="accordion-collapse collapse">
                                    <div class="accordion-body">
                                        <pre class="bg-light p-2"><?php echo htmlspecialchars(print_r($stateData, true)); ?></pre>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once(__DIR__ . '/../includes/footer.php'); ?>
</body>
</html>

