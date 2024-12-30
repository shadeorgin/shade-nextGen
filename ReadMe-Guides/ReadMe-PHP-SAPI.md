# PHP SAPI (Server API) Guide

## What is SAPI?

SAPI (Server Application Programming Interface) in PHP defines how PHP interfaces with the web server or command-line environment. It's the layer that connects PHP with the server environment it's running in.

## Why SAPI is Important

1. Determines how PHP processes requests
2. Affects available features and functions
3. Impacts security considerations
4. Influences performance characteristics
5. Controls PHP's execution environment

## Common SAPI Types

### 1. apache2handler
- Used with Apache mod_php
- Direct integration with Apache
- Persistent PHP process
```php
if (php_sapi_name() === 'apache2handler') {
    // Apache-specific code
}
```

### 2. cgi-fcgi
- FastCGI implementation
- Better process management
- Improved security isolation
```php
if (php_sapi_name() === 'cgi-fcgi') {
    // FastCGI-specific optimizations
}
```

### 3. cli
- Command Line Interface
- Used for scripts, cron jobs, and CLI applications
- Different output handling
```php
if (php_sapi_name() === 'cli') {
    // CLI-specific functionality
    echo "Running from command line\n";
}
```

### 4. fpm-fcgi
- PHP-FPM (FastCGI Process Manager)
- Modern, efficient process management
- Better resource control
```php
if (php_sapi_name() === 'fpm-fcgi') {
    // FPM-specific configurations
}
```

## Detecting SAPI

There are multiple ways to detect the current SAPI:

```php
// Method 1: Using php_sapi_name()
$sapi_type = php_sapi_name();

// Method 2: Using PHP_SAPI constant
$sapi_type = PHP_SAPI;

// Method 3: Using getenv()
$is_cli = (getenv('SHELL') !== false);
```

## Security Considerations

1. CLI vs. Web Access
```php
if (php_sapi_name() !== 'cli') {
    die('This script can only be run from the command line');
}
```

2. Environment-Specific Code
```php
if (php_sapi_name() === 'apache2handler') {
    // Use Apache-specific security headers
    header('X-Frame-Options: DENY');
}
```

3. Resource Limits
```php
if (php_sapi_name() === 'fpm-fcgi') {
    // Set FPM-specific limits
    ini_set('max_execution_time', 30);
}
```

## Best Practices

### 1. SAPI-Specific Configuration
```php
switch (php_sapi_name()) {
    case 'cli':
        ini_set('memory_limit', '1G');
        break;
    case 'fpm-fcgi':
        ini_set('memory_limit', '128M');
        break;
    default:
        ini_set('memory_limit', '64M');
}
```

### 2. Output Handling
```php
function showMessage($message) {
    if (php_sapi_name() === 'cli') {
        echo $message . PHP_EOL;
    } else {
        echo "<p>" . htmlspecialchars($message) . "</p>\n";
    }
}
```

### 3. Error Handling
```php
if (php_sapi_name() === 'cli') {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(E_ALL & ~E_NOTICE);
}
```

### 4. File Operations
```php
function getFilePath($filename) {
    if (php_sapi_name() === 'cli') {
        return __DIR__ . '/cli_files/' . $filename;
    }
    return __DIR__ . '/web_files/' . $filename;
}
```

## Common Use Cases

### 1. Maintenance Scripts
```php
if (php_sapi_name() !== 'cli') {
    header('HTTP/1.1 403 Forbidden');
    die('Access Denied');
}

// Maintenance task code here
```

### 2. Web Application Bootstrap
```php
if (php_sapi_name() !== 'cli') {
    session_start();
    header('Content-Type: text/html; charset=utf-8');
}
```

### 3. Testing Environment
```php
function isTestEnvironment() {
    return php_sapi_name() === 'cli' && 
        defined('PHPUNIT_RUNNING') && 
        PHPUNIT_RUNNING === true;
}
```

## Troubleshooting

1. Check current SAPI:
```php
echo "Current SAPI: " . php_sapi_name();
```

2. Debug SAPI-specific issues:
```php
function debugSapiInfo() {
    return [
        'sapi_name' => php_sapi_name(),
        'php_version' => PHP_VERSION,
        'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'N/A',
        'is_cli' => PHP_SAPI === 'cli'
    ];
}
```

## Additional Resources

1. [PHP Manual: php_sapi_name](https://www.php.net/manual/en/function.php-sapi-name.php)
2. [PHP-FPM Documentation](https://www.php.net/manual/en/install.fpm.php)
3. [Command Line Usage](https://www.php.net/manual/en/features.commandline.php)

