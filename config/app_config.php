<?php
// Dynamic base URL detection
$isDevServer = (php_sapi_name() === 'cli-server');
define('BASE_URL', $isDevServer ? '' : '/charity_org');

// Asset paths - remove the charity_org prefix for dev server
define('CSS_PATH', BASE_URL . '/css');
define('JS_PATH', BASE_URL . '/js');
define('IMAGES_PATH', BASE_URL . '/images');

// Other application constants
define('APP_NAME', 'Charity Organization');
define('APP_VERSION', '1.0.0');

// Directory paths
define('APP_ROOT', dirname(__DIR__));
define('CONFIG_PATH', APP_ROOT . '/config');
define('INCLUDES_PATH', APP_ROOT . '/includes');
