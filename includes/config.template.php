<?php
/**
* Database Configuration Template
* Copy this file to config.php and update with actual credentials
* Keep config.php secure and never commit to version control
*/

// Environment detection
define('IS_PRODUCTION', false);  // Set to true in production

// Base URL Configuration
define('BASE_URL', IS_PRODUCTION ? '/reports/' : '/');

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
