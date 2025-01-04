<?php
require_once('../includes/init.php');
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Analytics Dashboard</h2>
        <a href="<?php echo getBaseUrl(); ?>reports/" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Reports
        </a>
    </div>

    <!-- Summary Cards Row -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Appeals</h5>
                    <h2 class="card-text">1,234</h2>
                    <p class="text-muted">+15% from last month</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Processing Time</h5>
                    <h2 class="card-text">4.5 days</h2>
                    <p class="text-muted">-2 days from average</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Success Rate</h5>
                    <h2 class="card-text">78%</h2>
                    <p class="text-muted">+5% from last month</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Active Cases</h5>
                    <h2 class="card-text">456</h2>
                    <p class="text-muted">Currently in progress</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Monthly Appeals Trend</h5>
                    <div class="bg-light p-5 text-center">
                        <p class="text-muted">Chart Placeholder</p>
                        <p class="text-muted"><i class="fas fa-chart-line fa-3x"></i></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Appeal Types Distribution</h5>
                    <div class="bg-light p-5 text-center">
                        <p class="text-muted">Chart Placeholder</p>
                        <p class="text-muted"><i class="fas fa-chart-pie fa-3x"></i></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Stats Table -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Detailed Statistics</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Metric</th>
                            <th>Current</th>
                            <th>Previous</th>
                            <th>Change</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>New Appeals</td>
                            <td>245</td>
                            <td>212</td>
                            <td class="text-success">+15.6%</td>
                        </tr>
                        <tr>
                            <td>Resolved Cases</td>
                            <td>198</td>
                            <td>187</td>
                            <td class="text-success">+5.9%</td>
                        </tr>
                        <tr>
                            <td>Average Resolution Time</td>
                            <td>4.5 days</td>
                            <td>6.2 days</td>
                            <td class="text-success">-27.4%</td>
                        </tr>
                        <tr>
                            <td>Customer Satisfaction</td>
                            <td>4.2/5.0</td>
                            <td>3.8/5.0</td>
                            <td class="text-success">+10.5%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

