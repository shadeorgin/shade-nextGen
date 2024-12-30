# PHP Database Initialization Guide

## Overview
This guide covers best practices and implementation patterns for database initialization scripts in PHP applications, with a focus on security and maintainability.

## Table of Contents
1. [Purpose and Usage](#purpose-and-usage)
2. [Security Best Practices](#security-best-practices)
3. [Implementation Patterns](#implementation-patterns)
4. [Sample Implementation](#sample-implementation)
5. [Common Pitfalls](#common-pitfalls)
6. [CLI vs Web Considerations](#cli-vs-web-considerations)

## Purpose and Usage
Database initialization scripts (`init_db.php`) serve several key purposes:
- Initial database schema creation
- Sample data population for development
- Database migration execution
- Testing environment setup
- Development environment reset

### When to Use
- During initial application setup
- Development environment reset
- Testing environment preparation
- Continuous Integration (CI) pipeline setup

## Security Best Practices

### 1. Execution Control
```php
// At the start of init_db.php
if (php_sapi_name() !== 'cli') {
    die('This script can only be run from the command line.');
}

// Environment check
if (!in_array(getenv('APP_ENV'), ['development', 'testing'])) {
    die('This script can only be run in development or testing environments.');
}
```

### 2. Configuration Security
```php
// Use environment variables for sensitive data
$db_config = [
    'host' => getenv('DB_HOST'),
    'user' => getenv('DB_USER'),
    'pass' => getenv('DB_PASS'),
    'name' => getenv('DB_NAME')
];

// Verify all required configuration exists
foreach (['host', 'user', 'pass', 'name'] as $required) {
    if (empty($db_config[$required])) {
        die("Missing required configuration: {$required}");
    }
}
```

### 3. File Location
Place initialization scripts outside web root:
```
/project_root
├── public/           # Web root
│   └── index.php
├── scripts/          # CLI scripts location
│   └── init_db.php
└── config/
    └── database.php
```

## Implementation Patterns

### 1. Basic Structure
```php
#!/usr/bin/env php
<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/database.php';

class DatabaseInitializer {
    private $pdo;
    private $schemaFile;
    private $sampleDataFile;

    public function __construct(PDO $pdo, string $schemaFile, string $sampleDataFile = null) {
        $this->pdo = $pdo;
        $this->schemaFile = $schemaFile;
        $this->sampleDataFile = $sampleDataFile;
    }

    public function initialize(): void {
        try {
            $this->createSchema();
            if ($this->sampleDataFile) {
                $this->loadSampleData();
            }
        } catch (PDOException $e) {
            die("Initialization failed: " . $e->getMessage());
        }
    }

    private function createSchema(): void {
        $sql = file_get_contents($this->schemaFile);
        $this->pdo->exec($sql);
    }

    private function loadSampleData(): void {
        $sql = file_get_contents($this->sampleDataFile);
        $this->pdo->exec($sql);
    }
}
```

### 2. Progress Tracking
```php
public function initialize(): void {
    try {
        echo "Starting database initialization...\n";
        
        echo "Creating schema... ";
        $this->createSchema();
        echo "Done.\n";
        
        if ($this->sampleDataFile) {
            echo "Loading sample data... ";
            $this->loadSampleData();
            echo "Done.\n";
        }
        
        echo "Database initialization completed successfully.\n";
    } catch (PDOException $e) {
        echo "Error!\n";
        die("Initialization failed: " . $e->getMessage());
    }
}
```

## Sample Implementation

### Complete Example
```php
#!/usr/bin/env php
<?php

// Execution control
if (php_sapi_name() !== 'cli') {
    die('This script can only be run from the command line.');
}

// Load configuration
require_once __DIR__ . '/../config/database.php';

try {
    // Initialize PDO connection
    $dsn = sprintf(
        "mysql:host=%s;port=%d;charset=utf8mb4",
        $db_config['host'],
        $db_config['port'] ?? 3306
    );
    
    $pdo = new PDO($dsn, $db_config['user'], $db_config['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    // Create database if not exists
    $pdo->exec(sprintf(
        "CREATE DATABASE IF NOT EXISTS `%s` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci",
        $db_config['name']
    ));
    
    $pdo->exec("USE `{$db_config['name']}`");

    // Initialize database
    $initializer = new DatabaseInitializer(
        $pdo,
        __DIR__ . '/../database/schema.sql',
        __DIR__ . '/../database/sample_data.sql'
    );
    
    $initializer->initialize();

} catch (PDOException $e) {
    die("Database initialization failed: " . $e->getMessage());
}
```

## Common Pitfalls

### 1. Character Set Issues
```php
// Always specify character set in DSN and database creation
$dsn = "mysql:host=localhost;charset=utf8mb4";
$sql = "CREATE DATABASE mydb CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
```

### 2. Transaction Handling
```php
public function loadSampleData(): void {
    $this->pdo->beginTransaction();
    try {
        $sql = file_get_contents($this->sampleDataFile);
        $this->pdo->exec($sql);
        $this->pdo->commit();
    } catch (PDOException $e) {
        $this->pdo->rollBack();
        throw $e;
    }
}
```

### 3. Large File Handling
```php
public function executeLargeFile(string $filename): void {
    $handle = fopen($filename, 'r');
    $sql = '';
    
    while (!feof($handle)) {
        $sql .= fgets($handle);
        
        if (substr(trim($sql), -1) === ';') {
            $this->pdo->exec($sql);
            $sql = '';
        }
    }
    
    fclose($handle);
}
```

## CLI vs Web Considerations

### Command Line Usage
```bash
# Basic usage
php scripts/init_db.php

# With environment specification
APP_ENV=development php scripts/init_db.php

# With additional options
php scripts/init_db.php --no-sample-data
```

### Web Access Prevention
```php
// In init_db.php
if (php_sapi_name() !== 'cli') {
    header('HTTP/1.0 403 Forbidden');
    die('Access Denied');
}

// In .htaccess
<Files "init_db.php">
    Order Allow,Deny
    Deny from all
</Files>
```

### Progress Reporting
```php
class ConsoleProgressReporter implements ProgressReporter {
    public function start(string $message): void {
        echo $message . "... ";
    }
    
    public function finish(): void {
        echo "Done!\n";
    }
    
    public function error(string $message): void {
        echo "Error: " . $message . "\n";
    }
}
```

## Best Practices Summary

1. **Security**
- Run only from CLI
- Check environment
- Use configuration files
- Protect sensitive data

2. **Implementation**
- Use OOP approach
- Implement progress reporting
- Handle errors gracefully
- Use transactions where appropriate

3. **Maintenance**
- Keep SQL files separate
- Version control schemas
- Document requirements
- Include cleanup scripts

4. **Testing**
- Test in isolated environment
- Verify data integrity
- Check error conditions
- Validate permissions

