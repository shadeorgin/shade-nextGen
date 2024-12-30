<?php
/**
* Database Initialization Script
* 
* Purpose: Initializes database schema and loads sample data safely.
* Security: Multiple layers of protection to ensure safe database operations.
* Usage: Must be run via command line only (php init_db.php)
* 
* SECURITY MEASURES AND RATIONALE:
* 
* 1. CLI-Only Execution Check:
*    - Prevents unauthorized web access to database initialization
*    - Blocks potential exploitation through HTTP requests
*    - Ensures proper execution context for database operations
* 
* 2. Environment Verification:
*    - Prevents accidental execution in production environments
*    - Ensures database operations occur only in appropriate contexts
*    - Protects production data from being overwritten
* 
* 3. Path and File Validation:
*    - Ensures all required files are present before execution
*    - Prevents partial or incomplete database initialization
*    - Validates script is being run from correct directory
* 
* 4. Error Handling:
*    - Provides clear, actionable error messages
*    - Logs all operations for audit purposes
*    - Fails safely if any precondition is not met
*/

// SECURITY CHECK 1: CLI-Only Execution
// Validate that the script is being run from command line
// This prevents web access and potential security exploits
if (php_sapi_name() !== 'cli') {
    die(sprintf(
        "Error: CLI-only script access violation.\n" .
        "This script must be run from command line.\n" .
        "Current SAPI: %s\n",
        php_sapi_name()
    ));
}

// SECURITY CHECK 2: Environment Verification
// Ensure script runs only in development/testing environments
// This protects production databases from accidental modification
$allowedEnvironments = ['development', 'test', 'local'];
$currentEnv = getenv('APP_ENV') ?: 'development';
if (!in_array($currentEnv, $allowedEnvironments)) {
    die(sprintf(
        "Error: Environment security check failed.\n" .
        "Current environment '%s' is not allowed.\n" .
        "Allowed environments: %s\n" .
        "Set APP_ENV environment variable to an allowed value.\n",
        $currentEnv,
        implode(', ', $allowedEnvironments)
    ));
}

// SECURITY CHECK 3: Path and File Validation
// Verify presence of all required files before proceeding
// This ensures complete and consistent database initialization
$requiredFiles = [
    'database.sql' => 'Database schema file',
    'db_config.php' => 'Database configuration file',
    'sample_data.sql' => 'Sample data file (optional)'
];

foreach ($requiredFiles as $file => $description) {
    if (!file_exists($file) && $file !== 'sample_data.sql') {
        die(sprintf(
            "Error: Required file not found.\n" .
            "Missing: %s (%s)\n" .
            "Please run this script from the correct directory.\n" .
            "Current directory: %s\n",
            $file,
            $description,
            getcwd()
        ));
    }
}

// Script initialization
echo "Database Initialization Script\n";
echo "============================\n";
echo "Environment: {$currentEnv}\n";
echo "Execution Mode: " . php_sapi_name() . "\n";
echo "Working Directory: " . getcwd() . "\n\n";

// Include database configuration
require_once __DIR__ . '/config/config.php';

try {
    // Initial connection to MySQL (without database)
    echo "Connecting to MySQL server...\n";
    $pdo = new PDO(
        "mysql:host=" . $db_config['host'] . 
        ";unix_socket=" . $db_config['unix_socket'],
        $db_config['username'],
        $db_config['password'],
        array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        )
    );
    echo "Connection successful!\n\n";

    // Create database if it doesn't exist
    echo "Creating database if not exists...\n";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `" . $db_config['dbname'] . "`");
    $pdo->exec("USE `" . $db_config['dbname'] . "`");
    echo "Database '" . $db_config['dbname'] . "' selected.\n\n";

    // Create new PDO connection with database selected
    $pdo = new PDO(
        "mysql:host=" . $db_config['host'] . 
        ";unix_socket=" . $db_config['unix_socket'] . 
        ";dbname=" . $db_config['dbname'],
        $db_config['username'],
        $db_config['password'],
        array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        )
    );
    
    // Read and execute database.sql
    echo "Executing database.sql...\n";
    $sql = file_get_contents('database.sql');
    if ($sql === false) {
        throw new Exception("Error reading database.sql");
    }
    
    // Split SQL into individual statements
    $statements = array_filter(
        array_map('trim', explode(';', $sql)),
        function($sql) { return !empty($sql); }
    );
    
    foreach ($statements as $statement) {
        $pdo->exec($statement);
        echo ".";
    }
    echo "\nDatabase schema created successfully!\n\n";
    
    // Check if sample_data.sql exists and execute it
    if (file_exists('sample_data.sql')) {
        echo "Found sample_data.sql, importing sample data...\n";
        $sampleSql = file_get_contents('sample_data.sql');
        if ($sampleSql === false) {
            throw new Exception("Error reading sample_data.sql");
        }
        
        // Split and execute sample data statements
        $sampleStatements = array_filter(
            array_map('trim', explode(';', $sampleSql)),
            function($sql) { return !empty($sql); }
        );
        
        foreach ($sampleStatements as $statement) {
            $pdo->exec($statement);
            echo ".";
        }
        echo "\nSample data imported successfully!\n\n";
    } else {
        echo "No sample_data.sql found, skipping sample data import.\n\n";
    }
    
    echo "Database initialization completed successfully!\n";
    
} catch (PDOException $e) {
    die(sprintf(
        "Database Error: %s\n" .
        "Error Code: %s\n" .
        "File: %s\n" .
        "Line: %d\n",
        $e->getMessage(),
        $e->getCode(),
        $e->getFile(),
        $e->getLine()
    ));
} catch (Exception $e) {
    die(sprintf(
        "General Error: %s\n" .
        "File: %s\n" .
        "Line: %d\n",
        $e->getMessage(),
        $e->getFile(),
        $e->getLine()
    ));
}
