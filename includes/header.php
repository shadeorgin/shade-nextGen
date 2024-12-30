<?php
// Start session before anything else
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include required files
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/functions.php';

// Check for admin access
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo APP_NAME; ?></title>
    <link href="<?php echo CSS_PATH; ?>/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo CSS_PATH; ?>/fontawesome/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-green: #2E7D32;
            --secondary-green: #4CAF50;
            --light-green: #81C784;
        }
        .navbar {
            background-color: var(--primary-green) !important;
        }
        .navbar-dark .navbar-nav .nav-link {
            color: white;
        }
        .btn-custom {
            background-color: var(--secondary-green);
            color: white;
        }
        .btn-custom:hover {
            background-color: var(--light-green);
            color: white;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="<?php echo BASE_URL; ?>/index.php">Charity Org</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>/about.php">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>/contact.php">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>/appeals.php">Appeals</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>/beneficiaries.php">Beneficiaries</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>/reports.php">Reports</a>
                    </li>
                    <?php if(isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'admin'): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Admin
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="adminDropdown">
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/users/list.php">Manage Users</a></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/users/create.php">Add User</a></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/ReadMe-Guides/">ReadMe Guides</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/admin/settings.php">Settings</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <span class="nav-link">Welcome, <?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?> (<?php echo ucfirst(htmlspecialchars($_SESSION['user_role'] ?? '')); ?>)</span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>/logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>/login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>/register.php">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
