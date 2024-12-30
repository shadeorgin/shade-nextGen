# PHP PDO with Environment Variables - Composer Implementation

## Table of Contents
1. [Introduction](#introduction)
2. [Composer Setup](#composer-setup)
3. [Implementation Guide](#implementation-guide)
4. [Security Best Practices](#security-best-practices)
5. [Testing Procedures](#testing-procedures)
6. [Common Pitfalls](#common-pitfalls)

## Introduction

This guide demonstrates how to implement secure database connections using PHP PDO with environment variables using Composer and the `vlucas/phpdotenv` package.

## Composer Setup

### 1. Initialize Project
```bash
composer init
composer require vlucas/phpdotenv
```

### 2. Project Structure
```
project/
├── .env
├── .env.example
├── composer.json
├── composer.lock
├── src/
│   ├── Database.php
│   └── config.php
└── tests/
    ├── test_env.php
    └── test_connection.php
```

## Implementation Guide

### 1. Environment Files
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

### 2. Configuration Setup
```php
// src/config.php
<?php

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Required variables
$dotenv->required(['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS']);

// Optional variables with defaults
$db_charset = $_ENV['DB_CHARSET'] ?? 'utf8mb4';
```

### 3. Database Connection Class
```php
// src/Database.php
<?php

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        $dsn = sprintf("mysql:host=%s;dbname=%s;charset=%s",
            $_ENV['DB_HOST'],
            $_ENV['DB_NAME'],
            $_ENV['DB_CHARSET'] ?? 'utf8mb4'
        );

        if (isset($_ENV['DB_SOCKET'])) {
            $dsn .= ";unix_socket=" . $_ENV['DB_SOCKET'];
        }

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->pdo = new PDO(
                $dsn,
                $_ENV['DB_USER'],
                $_ENV['DB_PASS'],
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

### 4. Usage Example
```php
// index.php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/config.php';
require_once __DIR__ . '/src/Database.php';

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

1. **Composer Security**
- Keep dependencies updated:
    ```bash
    composer update --no-dev
    ```
- Use security scanning:
    ```bash
    composer audit
    ```

2. **Environment Security**
- Add to .gitignore:
    ```
    /vendor/
    .env
    .env.local
    .env.*
    !.env.example
    ```
- Set file permissions:
    ```bash
    chmod 600 .env
    chmod 644 .env.example
    ```

3. **Production Configuration**
```php
$dotenv->required([
    'DB_HOST',
    'DB_NAME',
    'DB_USER',
    'DB_PASS'
])->notEmpty();
```

## Testing Procedures

### 1. Environment Test
```php
// tests/test_env.php
<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/config.php';

try {
    // Test environment variables
    foreach (['DB_HOST', 'DB_NAME', 'DB_USER'] as $var) {
        if (empty($_ENV[$var])) {
            throw new RuntimeException("Missing environment variable: $var");
        }
        echo "$var: " . $_ENV[$var] . "\n";
    }
    echo "Environment test passed!\n";
} catch (Exception $e) {
    echo "Environment test failed: " . $e->getMessage() . "\n";
}
```

### 2. Connection Test
```php
// tests/test_connection.php
<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/config.php';
require_once __DIR__ . '/../src/Database.php';

try {
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    
    $result = $pdo->query("SELECT 1")->fetch();
    echo "Database connection successful!\n";
} catch (Exception $e) {
    echo "Connection test failed: " . $e->getMessage() . "\n";
}
```

## Common Pitfalls

1. **Composer Issues**
- Issue: Autoloader not found
- Solution: Run `composer dump-autoload`

2. **Package Conflicts**
- Issue: Version conflicts
- Solution: Use `composer why` and `composer why-not`

3. **Production Deployment**
- Issue: Missing vendor directory
- Solution: Run `composer install --no-dev`

4. **Environment Loading**
- Issue: Multiple .env loading
- Solution: Use singleton pattern for env loading

## Additional Resources

- [PHP dotenv Documentation](https://github.com/vlucas/phpdotenv)
- [Composer Documentation](https://getcomposer.org/doc/)
- [PHP PDO Documentation](https://www.php.net/manual/en/book.pdo.php)
- [PHP Security Best Practices](https://www.php.net/manual/en/security.php)

