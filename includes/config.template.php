<?php
/**
* Database Configuration Template
* Copy this file to config.php and update with actual credentials
* Keep config.php secure and never commit to version control
*/

// Environment detection and paths
define('IS_PRODUCTION', false);  // Set to true in production
define('PROD_BASE_PATH', '/SHaDE-nextGen');  // Production base path
define('LOCAL_BASE_PATH', '');   // Local base path (empty for root)

// Base URL Configuration
define('BASE_URL', IS_PRODUCTION ? PROD_BASE_PATH : LOCAL_BASE_PATH);

// Function to get base URL
function getBaseUrl() {
    return BASE_URL;
}

// Database connection parameters
define('DB_HOST', '127.0.0.1');     // Database host
define('DB_PORT', '3306');          // Database port
define('DB_NAME', 'database_name'); // Your database name
define('DB_USER', 'username');      // Your database username
define('DB_PASS', 'password');      // Your database password
define('DB_CHARSET', 'utf8mb4');

// Connection options
define('DB_PERSISTENT', true);  // Enable connection pooling
define('DB_TIMEOUT', 5);        // Connection timeout in seconds
define('DB_ERRMODE', PDO::ERRMODE_EXCEPTION);

// Asset Configuration
define('USE_LOCAL_CHARTJS', true);  // Set false to use CDN version of Chart.js

// Chart Font Size Configuration
define('CHART_LEGEND_FONT_SIZE', 16);     // Font size for chart legends
define('CHART_AXIS_LABEL_FONT_SIZE', 16); // Font size for chart axis labels
define('CHART_TITLE_FONT_SIZE', 18);      // Font size for chart titles
