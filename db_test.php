<?php
try {
    // Include database configuration
    require_once __DIR__ . '/config/config.php';
    
    echo "<h2>Database Connection Test</h2>";
    
    // Attempt to connect to database
    $dsn = sprintf(
        "mysql:dbname=%s;unix_socket=%s;charset=utf8mb4",
        $db_config['dbname'],
        $db_config['unix_socket']
    );

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
    ];

    $conn = new PDO($dsn, $db_config['username'], $db_config['password'], $options);
    echo "<p style='color: green;'>✓ Successfully connected to the database</p>";
    
    // Get all tables
    $stmt = $conn->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (count($tables) > 0) {
        echo "<h3>Tables in database:</h3>";
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>Table Name</th><th>Number of Records</th></tr>";
        
        foreach ($tables as $table) {
            $count = $conn->query("SELECT COUNT(*) FROM `$table`")->fetchColumn();
            echo "<tr>";
            echo "<td>$table</td>";
            echo "<td>$count</td>";
            echo "</tr>";
        }
        
        echo "</table>";
    } else {
        echo "<p>No tables found in the database.</p>";
    }
    
} catch(PDOException $e) {
    echo "<p style='color: red;'>Database Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>DSN used: " . preg_replace('/password=([^;]*)/', 'password=***', $dsn) . "</p>";
    error_log("Database Error: " . $e->getMessage());
} catch(Exception $e) {
    echo "<p style='color: red;'>General Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    error_log("General Error: " . $e->getMessage());
}
?>

