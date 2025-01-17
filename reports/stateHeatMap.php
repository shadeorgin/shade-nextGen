<?php
require_once(__DIR__ . '/../includes/init.php');
require_once(__DIR__ . '/../includes/utilities.php');
require_once(__DIR__ . '/includes/report_utilities.php');

$error = '';
$stateData = [];

try {
    $db = Database::getInstance();
    
    $stateQuery = "SELECT 
        b.State as state,
        COUNT(DISTINCT b.Id) as beneficiary_count,
        COUNT(DISTINCT a.Id) as appeal_count
    FROM TblBeneficiary b
    LEFT JOIN TblAppealInfo a ON b.Id = a.BeneficiaryId
    LEFT JOIN TblTxDetails t ON t.AppealId = a.Id
    WHERE b.State IS NOT NULL 
        AND t.AppealId <> -1
    GROUP BY b.State
    ORDER BY beneficiary_count DESC";
    
    $stateData = $db->queryAll($stateQuery);
    
} catch (Exception $e) {
    $error = "Failed to fetch state coverage data";
    if (!IS_PRODUCTION) {
        $error .= ": " . $e->getMessage();
    }
}

require_once(__DIR__ . '/../includes/header.php');
?>

<!-- Interactive map assets coming soon -->
<style>
    #staticImageContainer {
        width: 100%;
        height: 600px;
        margin: 20px 0;
        border-radius: 8px;
        overflow: hidden;
        border: 2px solid #dee2e6;
    }
    #staticImageContainer img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
</style>

<div class="container mt-4">
    <a href="<?php echo getBaseUrl(); ?>reports/" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Back to Reports
    </a>
    
    <h2>State Coverage Heat Map</h2>
    <p class="text-muted">2024 Coverage Visualization</p>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php else: ?>
        <div class="card" id="staticCard">
            <div class="card-body">
                <div id="staticImageContainer">
                    <img src="<?php echo getBaseUrl(); ?>assets/images/SHaDE-HeatMap-2024-India.jpeg" alt="SHaDE Coverage Map 2024" class="img-fluid">
                </div>
            </div>
        </div>

        <div class="alert alert-info text-center mt-3">
            <i class="fas fa-map-marked-alt me-2"></i>
            Interactive coverage map coming soon!
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
                            <h6>State Data:</h6>
                            <pre><?php echo htmlspecialchars(print_r($stateData, true)); ?></pre>
                            <h6>GeoJSON Path:</h6>
                            <pre><?php echo htmlspecialchars(getBaseUrl() . 'reports/assets/data/india-states.geojson'); ?></pre>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<!-- Interactive map code coming soon
<script>
/*document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('toggleMapBtn');
    const mapContainer = document.getElementById('mapContainer');
    const staticCard = document.getElementById('staticCard');

    toggleBtn.addEventListener('click', function() {
        const isShowingStatic = staticCard.style.display !== 'none';
        if (isShowingStatic) {
            staticCard.style.display = 'none';
            mapContainer.style.display = 'block';
            toggleBtn.textContent = 'Switch to Static Image';
        } else {
            staticCard.style.display = 'block';
            mapContainer.style.display = 'none';
            toggleBtn.textContent = 'Switch to Interactive Map';
        }
    });
    const debug = document.getElementById('debug');
    try {
        debug.innerHTML = 'Initializing map...';
        const map = L.map('mapContainer').setView([20.5937, 78.9629], 5);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        const geoJsonUrl = '<?php echo getBaseUrl(); ?>reports/assets/data/india-states.geojson';
        debug.innerHTML = 'Loading GeoJSON from: ' + geoJsonUrl;

        fetch(geoJsonUrl)
            .then(response => response.json())
            .then(geojson => {
                const stateData = <?php echo json_encode($stateData); ?>;
                L.geoJSON(geojson, {
                    style: function(feature) {
                        return {
                            fillColor: '#0868ac',
                            weight: 2,
                            opacity: 1,
                            color: 'white',
                            fillOpacity: 0.7
                        };
                    }
                }).addTo(map);
                debug.innerHTML = 'Map rendered successfully';
                setTimeout(() => debug.style.display = 'none', 3000);
            })
            .catch(error => {
                debug.innerHTML = 'Error loading map: ' + error.message;
                debug.classList.remove('alert-info');
                debug.classList.add('alert-danger');
            });
    } catch (error) {
        debug.innerHTML = 'Error initializing map: ' + error.message;
        debug.classList.remove('alert-info');
        debug.classList.add('alert-danger');
    }
});*/
</script>
-->
</script>

<?php require_once(__DIR__ . '/../includes/footer.php'); ?>

