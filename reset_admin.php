<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'config/db_config.php';

echo "<pre>Testing database connection...\n";
if ($conn) {
    echo "Database connection successful!\n";
}

// First, delete existing admin user if exists
$delete_sql = "DELETE FROM users WHERE email = 'admin@charity.org'";
$conn->exec($delete_sql);
echo "Cleared existing admin user\n";

// Create new admin user
$password_hash = password_hash('Admin@123', PASSWORD_BCRYPT);
$insert_sql = "INSERT INTO users (email, password, role, first_name, last_name, status) 
            VALUES ('admin@charity.org', :password, 'admin', 'System', 'Admin', 'active')";

$stmt = $conn->prepare($insert_sql);
$stmt->bindParam(':password', $password_hash);

if ($stmt->execute()) {
    echo "Created new admin user successfully!\n";
} else {
    echo "Failed to create admin user\n";
    print_r($stmt->errorInfo());
}

// Test authentication
echo "\nTesting authentication...\n";
$email = 'admin@charity.org';
$password = 'Admin@123';

// Fetch user
$auth_sql = "SELECT * FROM users WHERE email = :email AND status = 'active'";
$stmt = $conn->prepare($auth_sql);
$stmt->bindParam(':email', $email);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

echo "User lookup results:\n";
print_r($user);

if ($user) {
    echo "\nAttempting password verification...\n";
    if (password_verify($password, $user['password'])) {
        echo "SUCCESS: Password verification passed!\n";
        echo "\nLogin should work with:\n";
        echo "Email: admin@charity.org\n";
        echo "Password: Admin@123\n";
    } else {
        echo "FAILED: Password verification failed!\n";
    }
} else {
    echo "FAILED: User not found!\n";
}

