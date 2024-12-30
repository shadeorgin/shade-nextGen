# PHP PDO with Environment Variables - Native Implementation

## Table of Contents
1. [Introduction](#introduction)
2. [Native Environment Variables Implementation](#native-implementation)
3. [Security Best Practices](#security-best-practices)
4. [Testing Procedures](#testing-procedures)
5. [Common Pitfalls](#common-pitfalls)

## Introduction

This guide demonstrates how to implement secure database connections using PHP PDO with environment variables without external dependencies.

## Native Implementation

### 1. Environment File Structure
Create two files:
```bash
# .env
DB_HOST=localhost
DB_NAME=myapp_db
DB_USER=dbuser
DB_PASS=secure_password
DB_CHARSET=utf8mb4
DB_SOCKET=/path/to/mysql.sock  # Optional
```

```bash
# .env.example
DB_HOST=localhost
DB_NAME=your_database
DB_USER=your_username
DB_PASS=your_password
DB_CHARSET=utf8mb4
DB_SOCKET=  # Optional
```

### 2. Environment Loader Class
```php
// src/EnvLoader.php
class EnvLoader {
    private static $variables = [];

    public static function load(string $path): void {
        if (!file_exists($path)) {
            throw new RuntimeException("Environment file not found");
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);
            
            putenv(sprintf('%s=%s', $name, $value));
            $_ENV[$name] = $value;
            self::$variables[$name] = $value;
        }
    }

    public static function get(string $key, $default = null) {
        return self::$variables[$key] ?? $default;
    }

    public static function requireVars(array $required): void {
        $missing = [];
        foreach ($required as $var) {
            if (!isset(self::$variables[$var])) {
                $missing[] = $var;
            }
        }
        
        if (!empty($missing)) {
            throw new RuntimeException(
                'Required environment variables not set: ' . implode(', ', $missing)
            );
        }
    }
}
```

### 3. Database Connection Class
```php
// src/Database.php
class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        $dsn = sprintf("mysql:host=%s;dbname=%s;charset=%s",
            EnvLoader::get('DB_HOST'),
            EnvLoader::get('DB_NAME'),
            EnvLoader::get('DB_CHARSET', 'utf8mb4')
        );

        if ($socket = EnvLoader::get('DB_SOCKET')) {
            $dsn .= ";unix_socket=" . $socket;
        }

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->pdo = new PDO(
                $dsn,
                EnvLoader::get('DB_USER'),
                EnvLoader::get('DB_PASS'),
                $options
            );
        } catch (PDOException $e) {
            throw new RuntimeException("Connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance(): self {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): PDO {
        return $this->pdo;
    }
}
```

### 4. Implementation Example
```php
// index.php
require_once 'src/EnvLoader.php';
require_once 'src/Database.php';

// Load environment variables
EnvLoader::load(__DIR__ . '/.env');

// Verify required variables
EnvLoader::requireVars(['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS']);

// Get database connection
try {
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    
    // Your database operations here
    $stmt = $pdo->query("SELECT * FROM users");
    $users = $stmt->fetchAll();
} catch (Exception $e) {
    // Handle error appropriately
    error_log($e->getMessage());
    exit('Database error occurred');
}
```

## Security Best Practices

1. **File Security**
- Set appropriate file permissions:
    ```bash
    chmod 600 .env
    chmod 644 .env.example
    ```
- Add to .gitignore:
    ```
    .env
    .env.local
    .env.*
    !.env.example
    ```

2. **Environment Variables**
- Use different .env files per environment
- Never commit sensitive data
- Regularly rotate credentials
- Use strong, unique passwords

3. **Error Handling**
- Log errors securely
- Never expose database errors to users
- Implement proper exception handling

## Testing Procedures

### 1. Environment Test
```php
// tests/test_env.php
require_once '../src/EnvLoader.php';

try {
    EnvLoader::load(__DIR__ . '/../.env');
    EnvLoader::requireVars(['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS']);
    echo "Environment variables loaded successfully\n";

    // Display loaded variables (development only)
    foreach (['DB_HOST', 'DB_NAME', 'DB_USER'] as $var) {
        echo "$var: " . EnvLoader::get($var) . "\n";
    }
} catch (Exception $e) {
    echo "Environment test failed: " . $e->getMessage() . "\n";
}
```

### 2. Connection Test
```php
// tests/test_connection.php
require_once '../src/EnvLoader.php';
require_once '../src/Database.php';

try {
    EnvLoader::load(__DIR__ . '/../.env');
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    
    $result = $pdo->query("SELECT 1")->fetch();
    echo "Database connection successful!\n";
} catch (Exception $e) {
    echo "Connection test failed: " . $e->getMessage() . "\n";
}
```

## Common Pitfalls

1. **File Permissions**
- Issue: Can't read .env file
- Solution: Check file permissions and ownership

2. **Missing Variables**
- Issue: Undefined environment variables
- Solution: Copy .env.example to .env and set all required values

3. **Character Encoding**
- Issue: Special characters in passwords
- Solution: Use proper string escaping in .env file

4. **Production Security**
- Issue: Development settings in production
- Solution: Use environment-specific .env files

## Additional Resources

- [PHP PDO Documentation](https://www.php.net/manual/en/book.pdo.php)
- [PHP Security Best Practices](https://www.php.net/manual/en/security.php)

