<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Flash Messages
function setFlashMessage($message, $type = 'success') {
    $_SESSION['flash_message'] = [
        'message' => $message,
        'type' => $type
    ];
}

function getFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $message;
    }
    return null;
}

// CSRF Protection
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCSRFToken($token) {
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        return false;
    }
    return true;
}

// Input Validation & Sanitization
function sanitizeInput($input) {
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validatePassword($password) {
    // At least 8 characters, 1 uppercase, 1 lowercase, 1 number
    return strlen($password) >= 8 &&
        preg_match('/[A-Z]/', $password) &&
        preg_match('/[a-z]/', $password) &&
        preg_match('/[0-9]/', $password);
}

// User Management Utilities
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

function getUserById($pdo, $userId) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Error Logging
function logError($message, $severity = 'ERROR') {
    $logFile = __DIR__ . '/../logs/error.log';
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] [$severity] $message" . PHP_EOL;
    error_log($logMessage, 3, $logFile);
}

// Date Formatting
function formatDate($date, $format = 'Y-m-d H:i:s') {
    return date($format, strtotime($date));
}

function timeAgo($datetime) {
    $time = strtotime($datetime);
    $now = time();
    $diff = $now - $time;

    if ($diff < 60) {
        return "just now";
    } elseif ($diff < 3600) {
        return floor($diff / 60) . " minutes ago";
    } elseif ($diff < 86400) {
        return floor($diff / 3600) . " hours ago";
    } else {
        return floor($diff / 86400) . " days ago";
    }
}

// String Manipulation
function slugify($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    return $text;
}

function truncate($string, $length = 100, $append = "...") {
    if (strlen($string) > $length) {
        $string = substr($string, 0, $length) . $append;
    }
    return $string;
}

// Security Functions
function generateRandomToken($length = 32) {
    return bin2hex(random_bytes($length));
}

function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

function validateRequiredFields($data, $required) {
    $errors = [];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            $errors[] = ucfirst($field) . " is required.";
        }
    }
    return $errors;
}

// XSS Prevention
function escapeHTML($string) {
    return htmlspecialchars($string, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

// File Upload Helpers
function isAllowedFileType($filename, $allowed = ['jpg', 'jpeg', 'png', 'gif']) {
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    return in_array($ext, $allowed);
}

function generateUniqueFilename($filename) {
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    return uniqid() . '.' . $ext;
}

// Pagination Helper
function getPaginationLinks($total, $perPage, $currentPage, $urlPattern) {
    $totalPages = ceil($total / $perPage);
    $links = [];

    for ($i = 1; $i <= $totalPages; $i++) {
        $links[] = [
            'page' => $i,
            'url' => str_replace('{page}', $i, $urlPattern),
            'active' => $i === $currentPage
        ];
    }

    return $links;
}
