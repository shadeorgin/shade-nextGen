<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHaDE</title>
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/bootstrap-icons.css">
    <style>
        @font-face {
            font-family: "bootstrap-icons";
            src: url("/assets/fonts/bootstrap-icons.woff2") format("woff2");
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
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    <div class="toast-container">
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
    </div>
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="/">SHaDE</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/pages/about.php">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/pages/contact.php" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Coming Soon">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/pages/reports.php">Reports</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/pages/login.php" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Coming Soon">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/pages/register.php" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Coming Soon">Register</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <script src="/assets/js/bootstrap.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Show toast on page load
            var toastEl = document.querySelector('.toast');
            var toast = new bootstrap.Toast(toastEl, {
                autohide: true,
                delay: 5000
            });
            toast.show();
        });
    </script>
