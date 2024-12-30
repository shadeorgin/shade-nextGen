# MySQL Unix Socket Connection Guide

## Overview
A Unix socket (or Unix domain socket) is an inter-process communication mechanism that allows bidirectional data exchange between processes on the same host operating system.

## Unix Sockets vs TCP/IP

### Unix Sockets
- Communication mechanism for processes on the same machine
- Uses file system for communication
- Lower overhead than TCP/IP
- No network protocol overhead
- Cannot be used for remote connections

### TCP/IP
- Network protocol for communication
- Works across different machines
- Higher overhead due to network stack
- Requires port binding
- Can be used for both local and remote connections

## Advantages of Unix Sockets
1. **Performance**
- Faster than TCP/IP for local connections
- Lower latency
- Less system overhead
- No network protocol overhead

2. **Security**
- Limited to local machine access
- Uses filesystem permissions
- No exposure to network attacks
- Cannot be accessed remotely

3. **Resource Usage**
- No network port required
- Lower memory footprint
- Reduced CPU usage
- Less system overhead

## Disadvantages
1. Limited to local connections only
2. Not available on all operating systems
3. Requires file system permissions management
4. Cannot be used in distributed systems
5. Less flexible than TCP/IP

## Common Socket Locations

### Linux
- `/var/run/mysqld/mysqld.sock`
- `/tmp/mysql.sock`
- `/var/lib/mysql/mysql.sock`

### macOS
- `/tmp/mysql.sock`
- `/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock` (XAMPP)
- `/Applications/MAMP/tmp/mysql/mysql.sock` (MAMP)

### Windows
- Not applicable (Windows uses named pipes instead)

## Permission Requirements
1. **Socket File Permissions**
```bash
# Should be owned by mysql user
ls -l /var/run/mysqld/mysqld.sock
srwxrwxrwx 1 mysql mysql 0 Jan 1 00:00 /var/run/mysqld/mysqld.sock
```

2. **Directory Permissions**
- Socket directory should be accessible by both MySQL and web server
- Typical permissions: `755` or `750`
- Owner: mysql:mysql

3. **User Permissions**
- Web server user (www-data, apache, etc.) needs read/write access
- MySQL user needs full access
- Consider using groups for permission management

## Connection Configuration

### PHP PDO
```php
$pdo = new PDO(
    "mysql:unix_socket=/path/to/mysql.sock;dbname=database",
    $username,
    $password
);
```

### PHP MySQLi
```php
$mysqli = new mysqli(
    null,
    $username,
    $password,
    $database,
    null,
    '/path/to/mysql.sock'
);
```

## Troubleshooting Steps

1. **Verify Socket Existence**
```bash
ls -l /path/to/mysql.sock
```

2. **Check Socket Permissions**
```bash
namei -l /path/to/mysql.sock
```

3. **Verify MySQL Configuration**
```bash
mysql --print-defaults
grep socket /etc/my.cnf
```

4. **Test Socket Connection**
```bash
mysql --socket=/path/to/mysql.sock -u user -p
```

5. **Common Issues**
- Socket file missing
- Incorrect permissions
- Wrong socket path
- MySQL not running
- SELinux restrictions

## Best Practices

1. **Configuration**
- Use consistent socket path across configurations
- Document socket location in configuration files
- Consider environment-specific socket locations

2. **Security**
- Implement proper file permissions
- Use specific MySQL users for different applications
- Regular security audits
- Monitor socket file permissions

3. **Maintenance**
- Regular permission checks
- Monitor socket file existence
- Implement error logging
- Document socket location changes

4. **Development**
- Use environment variables for socket path
- Implement connection fallback mechanisms
- Add connection timeout handling
- Proper error handling for socket issues

## When to Use Unix Sockets

### Recommended for:
- Single-server setups
- Local development
- High-performance requirements
- Security-sensitive applications

### Not Recommended for:
- Distributed systems
- Cloud environments
- Container-based deployments
- Remote database connections

## Complete Code Examples

### 1. Configuration File (db_config.php)
```php
<?php
$db_config = [
    'host'        => null,           // null when using unix_socket
    'dbname'      => 'your_database',
    'username'    => 'your_username',
    'password'    => 'your_password',
    'unix_socket' => '/var/run/mysqld/mysqld.sock',  // Adjust path as needed
    'charset'     => 'utf8mb4',
    'options'     => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]
];
```

**Configuration Explanation:**
- `host`: Set to null because we're using Unix socket instead of TCP/IP
- `unix_socket`: Path to MySQL socket file (varies by environment)
- `charset`: utf8mb4 supports full Unicode, including emojis
- PDO Options:
    - `ATTR_ERRMODE`: Throws exceptions for better error handling
    - `ATTR_DEFAULT_FETCH_MODE`: Returns associative arrays by default
    - `ATTR_EMULATE_PREPARES`: Disabled for proper type handling

### 2. Database Connection Class
```php
<?php
class Database {
    private $connection = null;

    public function __construct($config) {
        try {
            $dsn = sprintf(
                "mysql:unix_socket=%s;dbname=%s;charset=%s",
                $config['unix_socket'],
                $config['dbname'],
                $config['charset']
            );

            $this->connection = new PDO(
                $dsn,
                $config['username'],
                $config['password'],
                $config['options']
            );
        } catch (PDOException $e) {
            throw new Exception("Connection failed: " . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->connection;
    }
}
```

**Class Components Explained:**
- `DSN (Data Source Name)`: Connection string that specifies database details
- `sprintf()`: Used for safe string formatting and avoiding concatenation issues
- `PDO`: PHP Data Objects provides a consistent interface for database access
- Why use this approach:
    1. Encapsulates connection logic
    2. Prevents multiple connection instances
    3. Centralizes error handling
    4. Makes testing and maintenance easier

### 3. Implementation Example
```php
<?php
require_once 'db_config.php';

try {
    $db = new Database($db_config);
    $conn = $db->getConnection();
    
    // Example query with error handling
    $stmt = $conn->prepare("SELECT * FROM users WHERE status = ?");
    $stmt->execute(['active']);
    $users = $stmt->fetchAll();
} catch (Exception $e) {
    error_log($e->getMessage());
    // Handle error appropriately
    die('Database error occurred');
}
```

**Implementation Details:**
- Uses prepared statements to prevent SQL injection
- Implements proper error handling and logging
- Separates configuration from implementation
- Follows single responsibility principle

### 4. Recommended .gitignore Entries
```
# Database configuration
db_config.php
**/db_config.php

# Environment files that might contain DB credentials
.env
.env.local
.env.*.local

# IDE and Editor files
.idea/
.vscode/
*.swp
*.swo

# Logs and debugging
*.log
debug.log
error_log
mysql_error.log

# OS generated files
.DS_Store
Thumbs.db
```

**Security Considerations:**
- Prevents sensitive configuration from being committed
- Excludes environment-specific files
- Ignores logs that might contain sensitive data
- Keeps repository clean from system/IDE files

### 5. Environment-Specific Configuration
```php
<?php
// config.php
$env = getenv('ENVIRONMENT') ?: 'development';

$socket_paths = [
    'development' => '/tmp/mysql.sock',
    'production'  => '/var/run/mysqld/mysqld.sock',
    'xampp'       => '/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock',
    'mamp'        => '/Applications/MAMP/tmp/mysql/mysql.sock'
];

$db_config['unix_socket'] = $socket_paths[$env];
```

**Environment Configuration Benefits:**
- Supports multiple environments (dev, prod, staging)
- Easy to switch between different MySQL installations
- Centralizes socket path management
- Makes deployment more reliable

### 6. Connection Testing Script
```php
<?php
require_once 'db_config.php';

function testConnection($config) {
    try {
        $dsn = sprintf(
            "mysql:unix_socket=%s;dbname=%s",
            $config['unix_socket'],
            $config['dbname']
        );
        
        $pdo = new PDO($dsn, $config['username'], $config['password']);
        echo "Connection successful!\n";
        echo "Socket path: " . $config['unix_socket'] . "\n";
        echo "MySQL version: " . $pdo->getAttribute(PDO::ATTR_SERVER_VERSION) . "\n";
        return true;
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage() . "\n";
        return false;
    }
}

testConnection($db_config);
```

**Testing Script Features:**
- Verifies socket accessibility
- Confirms database credentials
- Displays MySQL version information
- Provides immediate feedback on connection issues

### Common Troubleshooting Steps
1. Socket File Permissions:
```bash
ls -l /var/run/mysqld/mysqld.sock
# Should show: srwxrwxrwx 1 mysql mysql
```

2. Socket Directory Permissions:
```bash
ls -ld /var/run/mysqld
# Should show: drwxr-xr-x 2 mysql mysql
```

3. PHP Configuration Check:
```php
<?php
phpinfo();
// Look for pdo_mysql and mysql sections
```

**Troubleshooting Guidelines:**
- Always check file permissions first
- Verify PHP MySQL extensions are enabled
- Confirm MySQL service is running
- Check error logs for specific issues

