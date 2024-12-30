<?php
declare(strict_types=1);

class Database {
    private static ?Database $instance = null;
    private ?PDO $connection = null;
    private array $queryLog = [];
    private bool $inTransaction = false;
    
    private const CHARSET = 'utf8mb4';
    private const COLLATION = 'utf8mb4_unicode_ci';
    private const TIMEOUT = 30; // seconds
    private const SOCKET = '/tmp/mysql.sock'; // Default MySQL socket location on Mac
    
    private function __construct() {
        try {
            // Determine whether to use socket or host
            if (PHP_OS === 'Darwin' && file_exists(self::SOCKET)) {
                $dsn = sprintf("mysql:unix_socket=%s;dbname=%s;charset=%s",
                    self::SOCKET,
                    DB_NAME,
                    self::CHARSET
                );
            } else {
                $dsn = sprintf("mysql:host=%s;dbname=%s;charset=%s",
                    DB_HOST,
                    DB_NAME,
                    self::CHARSET
                );
            }
            
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_TIMEOUT => self::TIMEOUT,
                PDO::ATTR_PERSISTENT => true, // Enable connection pooling
                PDO::MYSQL_ATTR_INIT_COMMAND => 
                    "SET NAMES " . self::CHARSET . " COLLATE " . self::COLLATION,
                PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
            ];
            
            $this->connection = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage());
            throw new Exception("Database connection failed. Please try again later.");
        }
    }
    
    public static function getInstance(): self {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function prepare(string $query): PDOStatement {
        $stmt = $this->connection->prepare($query);
        if (defined('DEVELOPMENT') && DEVELOPMENT) {
            $this->logQuery($query);
        }
        return $stmt;
    }
    
    public function execute(string $query, array $params = []): PDOStatement {
        $stmt = $this->prepare($query);
        $stmt->execute($params);
        return $stmt;
    }
    
    public function fetchAll(string $query, array $params = []): array {
        return $this->execute($query, $params)->fetchAll();
    }
    
    public function fetch(string $query, array $params = []): array|false {
        return $this->execute($query, $params)->fetch();
    }
    
    public function beginTransaction(): bool {
        if (!$this->inTransaction) {
            $this->inTransaction = true;
            return $this->connection->beginTransaction();
        }
        return false;
    }
    
    public function commit(): bool {
        if ($this->inTransaction) {
            $this->inTransaction = false;
            return $this->connection->commit();
        }
        return false;
    }
    
    public function rollback(): bool {
        if ($this->inTransaction) {
            $this->inTransaction = false;
            return $this->connection->rollBack();
        }
        return false;
    }
    
    private function logQuery(string $query): void {
        $this->queryLog[] = [
            'query' => $query,
            'time' => microtime(true),
            'trace' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)
        ];
    }
    
    public function getQueryLog(): array {
        return $this->queryLog;
    }
    
    public function getLastInsertId(): string|false {
        return $this->connection->lastInsertId();
    }
    
    // Prevent cloning of singleton
    private function __clone() {}
    
    // Prevent unserialization
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}

// Example usage:
/*
try {
    $db = Database::getInstance();
    
    // Simple query
    $users = $db->fetchAll("SELECT * FROM users WHERE active = ?", [1]);
    
    // Transaction example
    $db->beginTransaction();
    try {
        $db->execute("INSERT INTO users (name, email) VALUES (?, ?)", 
            ['John', 'john@example.com']);
        $userId = $db->getLastInsertId();
        $db->execute("INSERT INTO user_profiles (user_id, bio) VALUES (?, ?)", 
            [$userId, 'Bio text']);
        $db->commit();
    } catch (Exception $e) {
        $db->rollback();
        throw $e;
    }
} catch (Exception $e) {
    // Handle errors
    error_log($e->getMessage());
}
*/

