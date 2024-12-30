<?php
if (!function_exists('hasPermission')) {
    function hasPermission($permission) {
        // Basic permission check based on user role
        if ($_SESSION['user_role'] === 'admin') {
            return true; // Admins have all permissions
        }
        return false;
    }
}

// Add this if not already defined in functions.php
if (!function_exists('isAdmin')) {
    function isAdmin() {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }
}
