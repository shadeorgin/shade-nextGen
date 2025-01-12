<?php
require_once('../includes/init.php');
?>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col">
            <h1 class="display-4">SHaDE Reports</h1>
            <hr class="my-4">
        </div>
    </div>

    <div class="row">
        <!-- First row -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Appeal Summary Report</h5>
                    <p class="card-text">
                        Comprehensive summary of appeals data, including statistics on appeal status,
                        types, and outcomes across different time periods.
                    </p>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="<?php echo getBaseUrl(); ?>reports/appealSummary.php" class="btn btn-primary">View Report</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Causewise Summary Report</h5>
                    <p class="card-text">
                        Detailed analysis of donations by cause, showing contribution patterns,
                        cause-specific trends, and impact distribution across categories.
                    </p>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="<?php echo getBaseUrl(); ?>reports/causewiseSummary.php" class="btn btn-primary">View Report</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Beneficiary Summary Report</h5>
                    <p class="card-text">
                        Comprehensive overview of beneficiary data, including demographics,
                        assistance types, and impact metrics across different periods.
                    </p>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="<?php echo getBaseUrl(); ?>reports/beneficiarySummary.php" class="btn btn-primary">View Report</a>
                </div>
            </div>
        </div>

        <!-- Second row -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Transaction Summary Report</h5>
                    <p class="card-text">
                        Detailed analysis of transactions including donation patterns,
                        frequency statistics, and temporal distribution of contributions.
                    </p>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="<?php echo getBaseUrl(); ?>reports/transactionSummary.php" class="btn btn-primary">View Report</a>
                </div>
            </div>
        </div>

        <!-- State Coverage Heat Map Card -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">State Coverage Heat Map</h5>
                    <p class="card-text">Visual representation of beneficiary distribution across India using an interactive heat map. Shows coverage intensity and state-wise statistics.</p>
                    <div class="d-grid">
                        <a href="<?php echo getBaseUrl(); ?>reports/stateHeatMap.php" class="btn btn-primary">
                            <i class="fas fa-map-marked-alt me-2"></i>View Heat Map
                        </a>
                    </div>
                </div>
                <div class="card-footer text-muted">
                    <i class="fas fa-chart-area"></i> Geographic Visualization
                </div>
            </div>
        </div>
        <!-- Analytics Dashboard Card -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Analytics Dashboard</h5>
                    <p class="card-text">
                        Interactive dashboard displaying key metrics, trends, and performance indicators
                        through visual charts and analytics. View appeals, beneficiaries, and geographical
                        distributions in comprehensive 3D and 2D visualizations.
                    </p>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="<?php echo getBaseUrl(); ?>reports/analyticsDashboard.php" class="btn btn-primary">View Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once('../includes/footer.php'); ?>
