<?php
require_once(__DIR__ . '/../includes/init.php');
require_once(__DIR__ . '/../includes/utilities.php');
require_once(__DIR__ . '/includes/report_utilities.php');

$error = '';
$stateData = [];
$debugData = [];

try {
    $db = Database::getInstance();

    // Simple debug queries
    $debugData['States in DB'] = $db->queryAll("
        SELECT DISTINCT State, COUNT(*) as count
        FROM TblBeneficiary
        WHERE State IS NOT NULL
        GROUP BY State
        ORDER BY count DESC
    ");

    $debugData['Sample Records'] = $db->queryAll("
        SELECT Id, State, City
        FROM TblBeneficiary
        WHERE State IS NOT NULL
        LIMIT 5
    ");

    // Main query with simpler JOIN
    $stateQuery = "
        SELECT
            b.State as state,
            COUNT(*) as beneficiary_count
        FROM TblBeneficiary b
        WHERE b.State IS NOT NULL
        GROUP BY b.State
        ORDER BY beneficiary_count DESC
    ";

    $stateData = $db->queryAll($stateQuery);
    $debugData['Heat Map Data'] = $stateData;
} catch (Exception $e) {
    $error = "Failed to fetch state coverage data";
    if (!IS_PRODUCTION) {
        $error .= ": " . $e->getMessage();
    }
}
?>

<?php require_once(__DIR__ . '/../includes/header.php'); ?>
<!-- Include map visualization library -->
<link rel="stylesheet" href="assets/css/heatmap.css">
<script src="assets/js/indiaMap.js"></script>

<div class="container mt-4">
    <a href="<?php echo getBaseUrl(); ?>reports/" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Back to Reports
    </a>

    <h2>State Coverage Heat Map</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php else: ?>
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Coverage Intensity by State</h5>
                        <div id="mapContainer" class="position-relative">
                            <?php include(__DIR__ . '/templates/indiaMapSvg.php'); ?>
                            <div id="tooltipContainer" class="map-tooltip d-none"></div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h6>Legend</h6>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span>Low Coverage</span>
                                            <div class="legend-gradient"></div>
                                            <span>High Coverage</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if (!IS_PRODUCTION): ?>
                            <div class="accordion mt-3">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#debugData">
                                            Debug Information
                                        </button>
                                    </h2>
                                    <div id="debugData" class="accordion-collapse collapse">
                                        <div class="accordion-body">
                                            <pre><?php echo htmlspecialchars(print_r($stateData, true)); ?></pre>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
    // Initialize heat map with state data
    document.addEventListener('DOMContentLoaded', function() {
        const stateData = <?php echo json_encode($stateData); ?>;
        indiaMap.init(stateData);
    });
</script>

<?php require_once(__DIR__ . '/../includes/footer.php'); ?>
