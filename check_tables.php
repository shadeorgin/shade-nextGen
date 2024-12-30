<?php
require_once __DIR__ . '/config/config.php';

try {
    // Create PDO connection using config from db_config.php
    $dsn = "mysql:unix_socket={$db_config['unix_socket']};dbname={$db_config['dbname']}";
    $pdo = new PDO($dsn, $db_config['username'], $db_config['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get list of tables
    $stmt = $pdo->query('SHOW TABLES');
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (empty($tables)) {
        echo "<h3>No tables found in database '{$db_config['dbname']}'</h3>";
    } else {
        echo "<h3>Tables in database '{$db_config['dbname']}':</h3>";
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>Table Name</th><th>Row Count</th></tr>";

        // Get row count for each table
        foreach ($tables as $table) {
            $stmt = $pdo->query("SELECT COUNT(*) FROM `$table`");
            $count = $stmt->fetchColumn();
            echo "<tr><td>$table</td><td>$count</td></tr>";
        }
        echo "</table>";
    }

} catch (PDOException $e) {
    echo "<h3>Database Connection Error:</h3>";
    echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
}

