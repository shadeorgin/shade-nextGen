<?php
/**
* Database Configuration Template
* Copy this file to config.php and update with actual values
*/

// Database connection parameters
define('DB_HOST', 'localhost');
define('DB_NAME', 'your_database');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_CHARSET', 'utf8mb4');

// Connection options
define('DB_PERSISTENT', true);  // Enable connection pooling
define('DB_TIMEOUT', 5);        // Connection timeout in seconds
define('DB_ERRMODE', PDO::ERRMODE_EXCEPTION);

// Add this file to .gitignore to prevent committing sensitive data
// cp config.template.php config.php

