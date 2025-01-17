<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHaDE</title>
    <link rel="stylesheet" href="<?php echo getBaseUrl(); ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo getBaseUrl(); ?>assets/css/bootstrap-icons.css">
    <style>
    html, body {
        height: 100%;
    }
    body {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }
    main {
        flex: 1 0 auto;
    }
    @font-face {
            font-family: "bootstrap-icons";
            src: url("<?php echo getBaseUrl(); ?>assets/fonts/bootstrap-icons.woff2") format("woff2");
            font-weight: normal;
            font-style: normal;
        }
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }
        .custom-toast {
            background-color: var(--shade-green-light);
            color: white;
        }
        .custom-toast .toast-header {
            background-color: var(--shade-green);
            color: white;
        }
        .custom-toast .btn-close {
            filter: brightness(0) invert(1);
        }
        :root {
            --shade-green: #28a745;
            --shade-green-dark: #218838;
            --shade-green-light: #34ce57;
        }
        .navbar-custom {
            background-color: var(--shade-green);
        }
        .navbar-custom .navbar-brand,
        .navbar-custom .nav-link {
            color: white;
        }
        .navbar-custom .nav-link:hover {
            color: rgba(255,255,255,0.8);
        }
    .social-icon {
        color: white;
        font-size: 1.5rem;
        margin: 0 10px;
        transition: all 0.3s ease;
    }
    .social-icon:hover {
        transform: scale(1.2);
    }
    .social-icon.facebook:hover {
        color: #1877f2;
    }
    .social-icon.twitter:hover {
        color: #1da1f2;
    }
    .social-icon.instagram:hover {
        color: #e4405f;
    }
</style>
</head>
<body class="d-flex flex-column min-vh-100">
    <?php
    // Start session if not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Determine if toast should be shown
    $show_toast = false;
    if ($TOAST_CONFIG['enabled']) {
        switch ($TOAST_CONFIG['display_frequency']) {
            case 'always':
                $show_toast = true;
                break;
            case 'once':
                if (!isset($_SESSION['toast_shown'])) {
                    $show_toast = true;
                    $_SESSION['toast_shown'] = true;
                }
                break;
            case 'never':
                $show_toast = false;
                break;
        }
    }
    ?>
    <div class="toast-container">
        <?php if ($show_toast): ?>
        <div class="toast custom-toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">SHaDE Status</strong>
                <small>Just now</small>
                <button type="button" class="btn-close ms-2" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                🚧 This site is currently under development. Features like Login, Register and Contact are not yet functional.
            </div>
        </div>
    <?php endif; ?>
</div>
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="<?php echo getBaseUrl(); ?>">Home</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo getBaseUrl(); ?>pages/about.php">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo getBaseUrl(); ?>pages/versionHistory.php"><i class="bi bi-clock-history"></i> Version History</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo getBaseUrl(); ?>pages/contact.php" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Coming Soon">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo getBaseUrl(); ?>reports/">Reports</a>
                </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo getBaseUrl(); ?>pages/login.php" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Coming Soon">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo getBaseUrl(); ?>pages/register.php" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Coming Soon">Register</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <script src="<?php echo getBaseUrl(); ?>assets/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Show toast on page load if element exists
            var toastEl = document.querySelector('.toast');
            if (toastEl) {
                var toast = new bootstrap.Toast(toastEl, {
                    autohide: <?php echo $TOAST_CONFIG['timing']['autohide'] ? 'true' : 'false'; ?>,
                    delay: <?php echo $TOAST_CONFIG['timing']['delay']; ?>
                });
                toast.show();
            }
        });
    </script>
