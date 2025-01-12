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
try {
    // Initialize database connection
    $db = Database::getInstance();
    $selectedYear = isset($_GET['year']) ? intval($_GET['year']) : getDefaultYear();

    // Appeals status distribution
    $appealsQuery = "SELECT
        COALESCE(a.status, 'Pending') as status,
        COUNT(DISTINCT a.Id) as total_appeals,
        COALESCE(SUM(t.amount), 0) as total_amount
        FROM TblAppealInfo a
        LEFT JOIN TblTxDetails t ON a.Id = t.appealid
        WHERE YEAR(t.DateOfTx) = :year
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
        GROUP BY b.Category";
    $simpleCategoryData = $db->queryAll($simpleCategoryQuery, ['year' => $selectedYear]);
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
    const appealsData = <?php echo json_encode($appealsData); ?>;
    const geoData = <?php echo json_encode($geoData); ?>;
    const categoryData = <?php echo json_encode($categoryData); ?>;
    const simpleCategoryData = <?php echo json_encode($simpleCategoryData); ?>;
    let isDetailedView = false;
    let categoryChart = null;

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
    });
</script>
<?php require_once(__DIR__ . '/../includes/footer.php'); ?>
</body>
</html>
