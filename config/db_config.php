<?php
// Database configuration for Charity Organization application
$db_config = [
    'host'        => 'localhost',
    'port'        => '3306',
    'dbname'      => 'charity_db',
    'username'    => 'raghs',
    'password'    => 'RaghsMySQL12#',
    'charset'     => 'utf8mb4',
    'unix_socket' => '/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock'
];

try {
    // PDO connection options
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false
    ];

    // Construct DSN using config array
    $dsn = sprintf(
        "mysql:host=%s;port=%s;dbname=%s;charset=%s;unix_socket=%s",
        $db_config['host'],
        $db_config['port'],
        $db_config['dbname'],
        $db_config['charset'],
        $db_config['unix_socket']
    );

    // Create PDO instance
    $conn = new PDO($dsn, $db_config['username'], $db_config['password'], $options);

} catch (PDOException $e) {
    error_log("Database Connection Error: " . $e->getMessage());
    die("Sorry, there was a problem connecting to the database. Please try again later.");
}
?>
