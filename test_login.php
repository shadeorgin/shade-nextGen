<?php
require_once 'config/db_config.php';

try {
    // Test the connection
    echo "Testing database connection...<br>";
    
    // Query a single user
    $stmt = $conn->query("SELECT * FROM users LIMIT 1");
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        echo "Successfully retrieved user:<br>";
        echo "<pre>";
        print_r($user);
        echo "</pre>";
    } else {
        echo "No users found in the database.";
    }
    
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

