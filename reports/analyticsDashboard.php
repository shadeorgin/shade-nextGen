<?php
require_once(__DIR__ . '/../includes/init.php');

// Chart configuration with default values
$chartConfig = [
    'legendFontSize' => 16,
    'axisLabelFontSize' => 16,
    'titleFontSize' => 18
];
require_once(__DIR__ . '/../includes/utilities.php');
require_once(__DIR__ . '/includes/report_utilities.php');

$error = '';
$appealsData = [];
$geoData = [];
$categoryData = [];
$simpleCategoryData = [];
$monthlyTxData = [];
$monthlyBalanceData = [];
try {
    // Initialize database connection
    $db = Database::getInstance();
    $selectedYear = isset($_GET['year']) ? intval($_GET['year']) : getDefaultYear();

    // Debug queries
    $debugQueries = [
        "Sample Transactions" => "SELECT t.* FROM TblTxDetails t JOIN TblAppealInfo a ON t.appealId = a.Id WHERE YEAR(t.DateOfTx) = :year AND a.Id <> -1 LIMIT 5",
        "Transaction Types" => "SELECT DISTINCT t.TxType, COUNT(*) as count FROM TblTxDetails t JOIN TblAppealInfo a ON t.appealId = a.Id WHERE YEAR(t.DateOfTx) = :year AND a.Id <> -1 GROUP BY t.TxType",
        "Monthly Data" => "SELECT DATE_FORMAT(t.DateOfTx, '%Y-%m') as month, t.TxType, COUNT(*) as count, SUM(t.amount) as total FROM TblTxDetails t JOIN TblAppealInfo a ON t.appealId = a.Id WHERE YEAR(t.DateOfTx) = :year AND a.Id <> -1 GROUP BY month, t.TxType ORDER BY month"
    ];
    $debugData = [];
    foreach ($debugQueries as $label => $query) {
        $debugData[$label] = $db->queryAll($query, ['year' => $selectedYear]);
    }

    // Appeals status distribution query
    // Only count credit (C) transactions for total amount to avoid double counting from debits
    $appealsQuery = "SELECT 
        COALESCE(a.status, 'Pending') as status,
        COUNT(DISTINCT a.Id) as total_appeals,
        COALESCE(SUM(CASE WHEN t.TxType = 'C' THEN t.amount ELSE 0 END), 0) as total_amount
    FROM TblAppealInfo a
    LEFT JOIN TblTxDetails t ON a.Id = t.appealid
    WHERE YEAR(t.DateOfTx) = :year
    AND a.Id <> -1
    GROUP BY a.status";
    $appealsData = $db->queryAll($appealsQuery, ['year' => $selectedYear]);

    // Geographic distribution by State
    $geoQuery = "SELECT
        COALESCE(b.State, 'Unknown') as region,
        COUNT(DISTINCT b.Id) as beneficiary_count,
        COUNT(DISTINCT a.Id) as appeal_count
        FROM TblBeneficiary b
        LEFT JOIN TblAppealInfo a ON b.Id = a.BeneficiaryId
        LEFT JOIN TblTxDetails t ON a.Id = t.appealid
        WHERE YEAR(t.DateOfTx) = :year
        AND a.Id <> -1
        GROUP BY b.State";
    $geoData = $db->queryAll($geoQuery, ['year' => $selectedYear]);

    // Category and Type distribution (detailed view)
    $categoryQuery = "SELECT
        COALESCE(b.Category, 'General') as category,
        b.Type,
        COUNT(DISTINCT b.Id) as beneficiary_count
        FROM TblBeneficiary b
        LEFT JOIN TblAppealInfo a ON b.Id = a.BeneficiaryId
        LEFT JOIN TblTxDetails t ON a.Id = t.appealid
        WHERE YEAR(t.DateOfTx) = :year
        AND a.Id <> -1
        GROUP BY b.Category, b.Type";
    $categoryData = $db->queryAll($categoryQuery, ['year' => $selectedYear]);

    // Simplified category distribution
    $simpleCategoryQuery = "SELECT
        COALESCE(b.Category, 'General') as category,
        COUNT(DISTINCT b.Id) as beneficiary_count
        FROM TblBeneficiary b
        LEFT JOIN TblAppealInfo a ON b.Id = a.BeneficiaryId
        LEFT JOIN TblTxDetails t ON a.Id = t.appealid
        WHERE YEAR(t.DateOfTx) = :year
        AND a.Id <> -1
        GROUP BY b.Category";
        $simpleCategoryData = $db->queryAll($simpleCategoryQuery, ['year' => $selectedYear]);

        // Monthly transaction count
        // Monthly transaction count
        $monthlyTxQuery = "SELECT
            DATE_FORMAT(t.DateOfTx, '%Y-%m') as month,
            COUNT(*) as tx_count
            FROM TblTxDetails t
            JOIN TblAppealInfo a ON t.appealId = a.Id
            WHERE YEAR(t.DateOfTx) = :year
            AND a.Id <> -1
            GROUP BY DATE_FORMAT(t.DateOfTx, '%Y-%m')
            ORDER BY month";
        $monthlyTxData = $db->queryAll($monthlyTxQuery, ['year' => $selectedYear]);

        // Monthly transaction count by account
        // Monthly transaction count by account

        // Monthly transaction count by TargetAcct
        $monthlyTxDetailedQuery = "SELECT
            DATE_FORMAT(t.DateOfTx, '%Y-%m') as month,
            t.TargetAcct,
            COUNT(*) as tx_count
            FROM TblTxDetails t
            JOIN TblAppealInfo a ON t.appealId = a.Id
            WHERE YEAR(t.DateOfTx) = :year
            AND a.Id <> -1
            GROUP BY DATE_FORMAT(t.DateOfTx, '%Y-%m'), t.TargetAcct
            ORDER BY month, t.TargetAcct";
        $monthlyTxDetailedData = $db->queryAll($monthlyTxDetailedQuery, ['year' => $selectedYear]);
        // Monthly CR vs DR
        // Monthly CR vs DR

        // Add debug query
        $debugQuery = "SELECT
            DATE_FORMAT(t.DateOfTx, '%Y-%m') as month,
            t.TxType,
            COUNT(*) as count,
            SUM(t.amount) as total
        FROM TblTxDetails t
        WHERE YEAR(t.DateOfTx) = :year
        GROUP BY month, t.TxType
        ORDER BY month, t.TxType";
        $debugTxData = $db->queryAll($debugQuery, ['year' => $selectedYear]);

        $monthlyBalanceQuery = "
        SELECT
            DATE_FORMAT(t.DateOfTx, '%Y-%m') as month,
            COALESCE(SUM(CASE WHEN t.TxType = 'C' THEN t.amount ELSE 0 END), 0) as credit_amount,
            COALESCE(SUM(CASE WHEN t.TxType = 'D' THEN t.amount ELSE 0 END), 0) as debit_amount,
            COALESCE(SUM(CASE
                WHEN t.TxType = 'C' THEN t.amount
                WHEN t.TxType = 'D' THEN -t.amount
            END), 0) as net_balance
        FROM TblTxDetails t
            JOIN TblAppealInfo a ON t.appealId = a.Id
            WHERE YEAR(t.DateOfTx) = :year
            AND a.Id <> -1 
            GROUP BY DATE_FORMAT(t.DateOfTx, '%Y-%m')
            ORDER BY month";
        $monthlyBalanceData = $db->queryAll($monthlyBalanceQuery, ['year' => $selectedYear]);
} catch (Exception $e) {
    $error = "Failed to fetch dashboard data";
    if (!IS_PRODUCTION) {
        $error .= ": " . $e->getMessage();
    }
}
?>
<!-- Include Chart.js before header -->
<?php if (USE_LOCAL_CHARTJS): ?>
<script src="<?php echo getBaseUrl(); ?>assets/js/chart.min.js"></script>
<?php else: ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<?php endif; ?>
<style>
    .toggle-button {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 1;
    }
    .chart-container {
        position: relative;
        height: 400px;
        margin-bottom: 20px;
    }
</style>

<?php require_once(__DIR__ . '/../includes/header.php'); ?>
    <div class="container mt-4">
        <a href="<?php echo getBaseUrl(); ?>reports/" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left"></i> Back to Reports
        </a>
        <h2>Analytics Dashboard</h2>

        <form method="GET" class="mb-4">
            <div class="row align-items-end">
                <div class="col-auto">
                    <label for="year" class="form-label">Select Year:</label>
                    <select name="year" id="year" class="form-select" onchange="this.form.submit()">
                        <?php foreach (getYearRange() as $year): ?>
                            <?php $selected = ($year == $selectedYear) ? 'selected' : ''; ?>
                            <option value="<?php echo $year; ?>" <?php echo $selected; ?>><?php echo $year; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </form>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php else: ?>
            <div class="row">
                <div class="col-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Appeals Distribution</h5>
                            <div class="chart-container">
                                <canvas id="appealsChart"></canvas>
                            </div>
                            <div class="accordion mt-3 bg-light" id="appealsAccordion">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#appealsCollapse" aria-expanded="false">
                                            How to Use Appeals Chart
                                        </button>
                                    </h2>
                                    <div id="appealsCollapse" class="accordion-collapse collapse">
                                        <div class="accordion-body">
                                            <p><strong>Purpose:</strong></p>
                                            <ul>
                                                <li>Shows distribution of appeals by their status</li>
                                                <li>Displays both appeal counts and monetary amounts</li>
                                                <li>Helps track appeal processing effectiveness</li>
                                            </ul>
                                            <p><strong>Interactive Features:</strong></p>
                                            <ul>
                                                <li>Click any legend item to hide/show that data series</li>
                                                <li>Mouse over bars to see exact values</li>
                                                <li>Compare appeal counts against total amounts</li>
                                            </ul>
                                            <p><strong>Available Controls:</strong></p>
                                            <ul>
                                                <li>Year selector at top of dashboard</li>
                                                <li>Toggleable legends for Appeal Count and Total Amount</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Geographic Distribution</h5>
                            <div class="chart-container">
                                <canvas id="geoChart"></canvas>
                            </div>
                            <div class="accordion mt-3 bg-light" id="geoAccordion">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#geoCollapse" aria-expanded="false">
                                            How to Use Geographic Chart
                                        </button>
                                    </h2>
                                    <div id="geoCollapse" class="accordion-collapse collapse">
                                        <div class="accordion-body">
                                            <p><strong>Purpose:</strong></p>
                                            <ul>
                                                <li>Displays regional distribution by state</li>
                                                <li>Compares beneficiary and appeal counts geographically</li>
                                                <li>Identifies high-activity regions</li>
                                            </ul>
                                            <p><strong>Interactive Features:</strong></p>
                                            <ul>
                                                <li>Click legend items to hide/show specific metrics</li>
                                                <li>Hover over bars for detailed state-wise counts</li>
                                                <li>Horizontal layout for better readability</li>
                                            </ul>
                                            <p><strong>Available Controls:</strong></p>
                                            <ul>
                                                <li>Year selector at top of dashboard</li>
                                                <li>Toggleable legends for Beneficiaries and Appeals</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Category Distribution</h5>
                                                    <button class="btn btn-outline-secondary btn-sm toggle-button" onclick="toggleCategoryView()">
                                                        <i class="fas fa-list"></i> Show Details
                                                    </button>
                                                    <div class="chart-container">
                                                        <canvas id="categoryChart"></canvas>
                            </div>
                            <div class="accordion mt-3 bg-light" id="categoryAccordion">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#categoryCollapse" aria-expanded="false">
                                            How to Use Category Chart
                                        </button>
                                    </h2>
                                    <div id="categoryCollapse" class="accordion-collapse collapse">
                                        <div class="accordion-body">
                                            <p><strong>Purpose:</strong></p>
                                            <ul>
                                                <li>Shows beneficiary distribution across categories</li>
                                                <li>Provides insights into beneficiary types</li>
                                                <li>Supports both overview and detailed analysis</li>
                                            </ul>
                                            <p><strong>Interactive Features:</strong></p>
                                            <ul>
                                                <li>Click legend items to hide/show specific categories</li>
                                                <li>Hover over segments for exact counts and percentages</li>
                                                <li>Switch between pie and bar chart views</li>
                                            </ul>
                                            <p><strong>Available Controls:</strong></p>
                                            <ul>
                                                <li>Year selector at top of dashboard</li>
                                                <li>"Show Details" toggle button for view switching</li>
                                                <li>Toggleable category legends</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Monthly Transaction Count</h5>
                            <button class="btn btn-outline-secondary btn-sm toggle-button" onclick="toggleTxView()">
                                <i class="fas fa-list"></i> Show Details
                            </button>
                            <div class="chart-container">
                                <canvas id="monthlyTxChart"></canvas>
                            </div>
                            <div class="accordion mt-3 bg-light" id="monthlyTxAccordion">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#monthlyTxCollapse" aria-expanded="false">
                                            How to Use Transaction Chart
                                        </button>
                                    </h2>
                                    <div id="monthlyTxCollapse" class="accordion-collapse collapse">
                                        <div class="accordion-body">
                                            <p><strong>Purpose:</strong></p>
                                            <ul>
                                                <li>Visualizes monthly transaction trends</li>
                                                <li>Shows transaction patterns over time</li>
                                                <li>Tracks account-wise transaction distribution</li>
                                            </ul>
                                            <p><strong>Interactive Features:</strong></p>
                                            <ul>
                                                <li>Click legend items to hide/show specific accounts</li>
                                                <li>Hover over points to see exact transaction counts</li>
                                                <li>View overall trends or account-specific patterns</li>
                                            </ul>
                                            <p><strong>Available Controls:</strong></p>
                                            <ul>
                                                <li>Year selector at top of dashboard</li>
                                                <li>"Show Details" toggle for account-wise breakdown</li>
                                                <li>Toggleable account legends in detailed view</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Monthly CR vs DR</h5>
                            <div class="chart-container">
                                <canvas id="monthlyBalanceChart"></canvas>
                            </div>
                            <div class="accordion mt-3 bg-light" id="monthlyBalanceAccordion">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#monthlyBalanceCollapse" aria-expanded="false">
                                            How to Use Balance Chart
                                        </button>
                                    </h2>
                                    <div id="monthlyBalanceCollapse" class="accordion-collapse collapse">
                                        <div class="accordion-body">
                                            <p><strong>Purpose:</strong></p>
                                            <ul>
                                                <li>Compares monthly credit and debit transactions</li>
                                                <li>Shows financial balance trends</li>
                                                <li>Tracks monetary flow patterns</li>
                                            </ul>
                                            <p><strong>Interactive Features:</strong></p>
                                            <ul>
                                                <li>Click legend items to hide/show CR or DR data</li>
                                                <li>Hover over bars for exact monetary values</li>
                                                <li>Compare credit vs debit amounts</li>
                                            </ul>
                                            <p><strong>Available Controls:</strong></p>
                                            <ul>
                                                <li>Year selector at top of dashboard</li>
                                                <li>Toggleable legends for CR and DR amounts</li>
                                                <li>All amounts shown in ₹ (INR)</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if (!IS_PRODUCTION): ?>
                    <div class="accordion mt-2" id="debugAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed bg-danger text-white" type="button" data-bs-toggle="collapse" data-bs-target="#debugCollapse" aria-expanded="false">
                                    Debug Information
                                </button>
                            </h2>
                            <div id="debugCollapse" class="accordion-collapse collapse">
                                <div class="accordion-body">
                                    <h6>Raw Transaction Data:</h6>
                                    <pre class="bg-light p-2"><?php echo htmlspecialchars(print_r($debugTxData, true)); ?></pre>
                                    <h6>Monthly Balance Data:</h6>
                                    <pre class="bg-light p-2"><?php echo htmlspecialchars(print_r($monthlyBalanceData, true)); ?></pre>
                                </div>
                                    <h6 class="mt-3">Balance Query:</h6>
                                    <pre class="bg-light p-2"><?php echo htmlspecialchars($monthlyBalanceQuery); ?></pre>
                                    <h6 class="mt-3">Balance Data:</h6>
                                    <pre class="bg-light p-2"><?php echo htmlspecialchars(print_r($monthlyBalanceData, true)); ?></pre>
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
// Initialize chart configuration
const chartColors = [
    'rgba(255, 99, 132, 0.6)',   // Pink
    'rgba(54, 162, 235, 0.6)',   // Blue
    'rgba(255, 206, 86, 0.6)',   // Yellow
    'rgba(75, 192, 192, 0.6)',   // Teal
    'rgba(153, 102, 255, 0.6)',  // Purple
    'rgba(255, 159, 64, 0.6)',   // Orange
    'rgba(46, 204, 113, 0.6)',   // Green
    'rgba(142, 68, 173, 0.6)',   // Deep Purple
    'rgba(52, 152, 219, 0.6)',   // Light Blue
    'rgba(231, 76, 60, 0.6)',    // Red
    'rgba(26, 188, 156, 0.6)',   // Turquoise
    'rgba(243, 156, 18, 0.6)'    // Dark Yellow
];
const chartConfig = <?php echo json_encode($chartConfig); ?>;
const monthlyTxData = <?php echo json_encode($monthlyTxData); ?>;
const monthlyTxDetailedData = <?php echo json_encode($monthlyTxDetailedData); ?>;
const appealsData = <?php echo json_encode($appealsData); ?>;
const geoData = <?php echo json_encode($geoData); ?>;
const categoryData = <?php echo json_encode($categoryData); ?>;
const simpleCategoryData = <?php echo json_encode($simpleCategoryData); ?>;
const monthlyBalanceData = <?php echo json_encode($monthlyBalanceData); ?>;
let isDetailedView = false;
let categoryChart = null;
let txChart = null;
let isTxDetailedView = false;
    function initializeCategoryChart(isDetailed = false) {
        const ctx = document.getElementById('categoryChart').getContext('2d');
        const data = isDetailed ? categoryData : simpleCategoryData;

        if (categoryChart) {
            categoryChart.destroy();
        }

        if (isDetailed) {
            // Group data by category
            const groupedData = {};
            data.forEach(item => {
                if (!groupedData[item.category]) {
                    groupedData[item.category] = [];
                }
                groupedData[item.category].push({
                    type: item.Type,
                    count: parseInt(item.beneficiary_count)
                });
            });

            categoryChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: Object.keys(groupedData),
                    datasets: [{
                        label: 'Beneficiaries by Category and Type',
                        data: Object.values(groupedData).map(types =>
                            types.reduce((sum, item) => sum + item.count, 0)
                        ),
                        backgroundColor: chartColors
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: {
                                font: {
                                    size: chartConfig.legendFontSize
                                }
                            }
                        }
                    }
                }
            });
        } else {
            categoryChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: data.map(item => item.category),
                    datasets: [{
                        data: data.map(item => parseInt(item.beneficiary_count)),
                        backgroundColor: chartColors
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                font: {
                                    size: chartConfig.legendFontSize
                                }
                            }
                        }
                    }
                }
            });
        }
    }

    function toggleCategoryView() {
        isDetailedView = !isDetailedView;
        const button = document.querySelector('.toggle-button');
        button.innerHTML = isDetailedView ?
            '<i class="fas fa-chart-pie"></i> Show Simple View' :
            '<i class="fas fa-list"></i> Show Details';
        initializeCategoryChart(isDetailedView);
    }

    function initializeTransactionChart(isDetailed = false) {
        const ctx = document.getElementById('monthlyTxChart').getContext('2d');

        if (txChart) {
            txChart.destroy();
        }

        if (!isDetailed) {
            // Simple view - single line chart
            txChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: monthlyTxData.map(item => {
                        const [year, month] = item.month.split('-');
                        return new Date(year, month - 1).toLocaleDateString('default', { month: 'short' });
                    }),
                    datasets: [{
                        label: 'Total Transactions',
                        data: monthlyTxData.map(item => parseInt(item.tx_count)),
                        borderColor: chartColors[0],
                        backgroundColor: chartColors[0].replace('0.6', '0.1'),
                        borderWidth: 2,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: {
                                font: {
                                    size: chartConfig.legendFontSize
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                font: {
                                    size: chartConfig.axisLabelFontSize
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                font: {
                                    size: chartConfig.axisLabelFontSize
                                }
                            }
                        }
                    }
                }
            });
        } else {
            // Detailed view - multiple line chart
            const months = [...new Set(monthlyTxDetailedData.map(item => item.month))].sort();
            const targetAccts = [...new Set(monthlyTxDetailedData.map(item => item.TargetAcct))].sort();

            const datasets = targetAccts.map((acct, index) => {
                const data = months.map(month => {
                    const record = monthlyTxDetailedData.find(item =>
                        item.month === month && item.TargetAcct === acct
                    );
                    return record ? parseInt(record.tx_count) : 0;
                });

                return {
                    label: acct,
                    data: data,
                    borderColor: chartColors[index],
                    backgroundColor: chartColors[index].replace('0.6', '0.1'),
                    borderWidth: 2,
                    fill: true
                };
            });

            txChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: months.map(month => {
                        const [year, m] = month.split('-');
                        return new Date(year, m - 1).toLocaleDateString('default', { month: 'short' });
                    }),
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: {
                                    size: chartConfig.legendFontSize
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                font: {
                                    size: chartConfig.axisLabelFontSize
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                font: {
                                    size: chartConfig.axisLabelFontSize
                                }
                            }
                        }
                    }
                }
            });
        }
    }

    function toggleTxView() {
        isTxDetailedView = !isTxDetailedView;
        const button = document.querySelector('#monthlyTxChart').closest('.card-body').querySelector('.toggle-button');
        button.innerHTML = isTxDetailedView ?
            '<i class="fas fa-chart-line"></i> Show Simple View' :
            '<i class="fas fa-list"></i> Show Details';
        initializeTransactionChart(isTxDetailedView);
    }

    document.addEventListener('DOMContentLoaded', function() {

        // Appeals Chart
        if (appealsData && appealsData.length > 0) {
            new Chart(document.getElementById('appealsChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: appealsData.map(item => item.status),
                    datasets: [{
                        label: 'Number of Appeals',
                        data: appealsData.map(item => parseInt(item.total_appeals)),
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }, {
                        label: 'Total Amount (₹)',
                        data: appealsData.map(item => parseFloat(item.total_amount)),
                        backgroundColor: 'rgba(255, 99, 132, 0.6)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: {
                                font: {
                                    size: chartConfig.legendFontSize
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                font: {
                                    size: chartConfig.axisLabelFontSize
                                }
                            },
                            title: {
                                display: true,
                                font: {
                                    size: chartConfig.titleFontSize
                                }
                            }
                        },
                        y: {
                            ticks: {
                                font: {
                                    size: chartConfig.axisLabelFontSize
                                }
                            },
                            title: {
                                display: true,
                                font: {
                                    size: chartConfig.titleFontSize
                                }
                            }
                        }
                    }
                }
            });
        }

        // Geographic Chart
        if (geoData && geoData.length > 0) {
            new Chart(document.getElementById('geoChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: geoData.map(item => item.region),
                    datasets: [{
                        label: 'Beneficiaries',
                        data: geoData.map(item => parseInt(item.beneficiary_count)),
                        backgroundColor: 'rgba(75, 192, 192, 0.6)'
                    }, {
                        label: 'Appeals',
                        data: geoData.map(item => parseInt(item.appeal_count)),
                        backgroundColor: 'rgba(153, 102, 255, 0.6)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y',
                    plugins: {
                        legend: {
                            labels: {
                                font: {
                                    size: chartConfig.legendFontSize
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                font: {
                                    size: chartConfig.axisLabelFontSize
                                }
                            },
                            title: {
                                display: true,
                                font: {
                                    size: chartConfig.titleFontSize
                                }
                            }
                        },
                        y: {
                            ticks: {
                                font: {
                                    size: chartConfig.axisLabelFontSize
                                }
                            },
                            title: {
                                display: true,
                                font: {
                                    size: chartConfig.titleFontSize
                                }
                            }
                        }
                    }
                }
            });
        }

        // Initialize all charts
        initializeCategoryChart(false); // Start with simple view

        initializeTransactionChart(false);

        // Monthly CR vs DR Chart
        if (monthlyBalanceData && monthlyBalanceData.length > 0) {
            new Chart(document.getElementById('monthlyBalanceChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: monthlyBalanceData.map(item => {
                        const [year, month] = item.month.split('-');
                        return new Date(year, month - 1).toLocaleDateString('default', { month: 'short' });
                    }),
                    datasets: [{
                        label: 'Credit (C)',
                        data: monthlyBalanceData.map(item => parseFloat(item.credit_amount)),
                        backgroundColor: 'rgba(40, 167, 69, 0.6)',
                        borderColor: 'rgba(40, 167, 69, 1)',
                        borderWidth: 1
                    }, {
                        label: 'Debit (D)',
                        data: monthlyBalanceData.map(item => -parseFloat(item.debit_amount)),
                        backgroundColor: 'rgba(220, 53, 69, 0.6)',
                        borderColor: 'rgba(220, 53, 69, 1)',
                        borderWidth: 1
                    }, {
                        label: 'Net Balance',
                        data: monthlyBalanceData.map(item => parseFloat(item.net_balance)),
                        type: 'line',
                        backgroundColor: 'rgba(0, 123, 255, 0.2)',
                        borderColor: 'rgba(0, 123, 255, 1)',
                        borderWidth: 2,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index'
                    },
                    plugins: {
                        legend: {
                            labels: {
                                font: {
                                    size: chartConfig.legendFontSize
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                font: {
                                    size: chartConfig.axisLabelFontSize
                                },
                                callback: function(value) {
                                    return '₹' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>
<?php require_once(__DIR__ . '/../includes/footer.php'); ?>
</body>
</html>
