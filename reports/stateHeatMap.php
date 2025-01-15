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
    WHERE b.State IS NOT NULL
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

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
    #mapContainer {
        width: 100%;
        height: 600px;
        margin: 20px 0;
        border-radius: 8px;
        overflow: hidden;
        border: 2px solid #dee2e6;
    }
    .info {
        padding: 6px 8px;
        font: 14px/16px Arial, sans-serif;
        background: white;
        box-shadow: 0 0 15px rgba(0,0,0,0.2);
        border-radius: 5px;
    }
</style>

<div class="container mt-4">
    <a href="<?php echo getBaseUrl(); ?>reports/" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Back to Reports
    </a>
    
    <h2>State Coverage Heat Map</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php else: ?>
        <div class="card">
            <div class="card-body">
                <div id="debug" class="alert alert-info mb-3">Map loading...</div>
                <div id="mapContainer"></div>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
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
});
</script>

<?php require_once(__DIR__ . '/../includes/footer.php'); ?>

