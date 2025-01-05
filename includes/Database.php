<?php
// Attempt to load configuration
if (file_exists(__DIR__ . '/config.php')) {
    require_once __DIR__ . '/config.php';
}

// Define default values if not set in config
defined('DB_HOST') or define('DB_HOST', 'localhost');
defined('DB_PORT') or define('DB_PORT', '3306');
defined('DB_NAME') or define('DB_NAME', '');
defined('DB_USER') or define('DB_USER', '');
defined('DB_PASS') or define('DB_PASS', '');
defined('DB_CHARSET') or define('DB_CHARSET', 'utf8mb4');
defined('DB_PERSISTENT') or define('DB_PERSISTENT', true);
defined('DB_ERRMODE') or define('DB_ERRMODE', PDO::ERRMODE_EXCEPTION);

/**
* Database Connection Class
* Handles PDO database connections with connection pooling and prepared statements
*/
class Database {
    private static $instance = null;
    private $conn;
    private $options;

    /**
    * Private constructor to prevent direct instantiation
    * Initializes database connection options
    */
    private function __construct() {
        // Validate required configuration
        if (empty(DB_NAME) || empty(DB_USER)) {
            throw new RuntimeException('Database configuration is incomplete. Please check config.php');
        }

        $this->options = [
            PDO::ATTR_PERSISTENT => DB_PERSISTENT,
            PDO::ATTR_ERRMODE => DB_ERRMODE
        ];
    }

    /**
    * Get database instance (Singleton pattern)
    * @return Database
    */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
    * Get database connection
    * @return PDO
    * @throws PDOException
    */
    public function getConnection() {
        if ($this->conn === null) {
            try {
                $dsn = sprintf(
                    "mysql:host=%s;port=%s;dbname=%s;charset=%s",
                    DB_HOST,
                    DB_PORT,
                    DB_NAME,
                    DB_CHARSET
                );
                $this->conn = new PDO($dsn, DB_USER, DB_PASS, $this->options);
            } catch (PDOException $e) {
                throw new PDOException(sprintf(
                    "Connection failed: %s (Host: %s, Port: %s, Database: %s)",
                    $e->getMessage(),
                    DB_HOST,
                    DB_PORT,
                    DB_NAME
                ));
            }
        }
        return $this->conn;
    }

    /**
    * Prepare and execute a query with parameters
    * @param string $sql SQL query with placeholders
    * @param array $params Parameters to bind
    * @return PDOStatement
    * @throws PDOException
    */
    public function query($sql, array $params = []) {
        try {
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            throw new PDOException("Query failed: " . $e->getMessage());
        }
    }

    /**
    * Fetch a single row from the database
    * @param string $sql SQL query with placeholders
    * @param array $params Parameters to bind
    * @return array|null Single row as associative array or null if no results
    * @throws PDOException
    */
    public function queryOne($sql, array $params = []) {
        try {
            $stmt = $this->query($sql, $params);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result !== false ? $result : null;
        } catch (PDOException $e) {
            throw new PDOException("QueryOne failed: " . $e->getMessage());
        }
    }

    /**
    * Fetch all rows from the database
    * @param string $sql SQL query with placeholders
    * @param array $params Parameters to bind
    * @return array Array of rows as associative arrays
    * @throws PDOException
    */
    public function queryAll($sql, array $params = []) {
        try {
            $stmt = $this->query($sql, $params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new PDOException("QueryAll failed: " . $e->getMessage());
        }
    }

    /**
    * Prevent cloning of the instance (Singleton pattern)
    */
    private function __clone() {}

    /**
    * Prevent unserializing of the instance (Singleton pattern)
    */
    public function __wakeup() {
        throw new RuntimeException('Cannot unserialize singleton');
    }
}

