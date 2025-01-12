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
        <!-- Appeal Summary Report Card -->
        <div class="col-md-6 mb-4">
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

        <!-- Causewise Summary Report Card -->
        <div class="col-md-6 mb-4">
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

        <!-- Beneficiary Summary Report Card -->
        <div class="col-md-6 mb-4">
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

        <!-- Transaction Summary Report Card -->
        <div class="col-md-6 mb-4">
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

        <!-- Analytics Dashboard Card -->
        <div class="col-md-6 mb-4">
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
