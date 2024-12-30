<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Basic session security check
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}
$_SESSION['last_activity'] = time();

// Check if user is logged in
$is_logged_in = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
$is_admin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;

include 'includes/header.php';

if (!$is_logged_in) {
    // Optional: Redirect to login page if you want to restrict access
    // header("Location: login.php");
    // exit();
}

// Debug session state
error_log("=== Session Debug on index.php ===");
error_log("Session Status: " . session_status());
error_log("Session ID: " . session_id());
error_log("Session Data: " . print_r($_SESSION, true));
error_log("================================");
?>

<!-- Carousel Section -->
<div id="mainCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="0" class="active"></button>
        <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="1"></button>
        <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="2"></button>
    </div>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="https://placehold.co/1200x400/198754/FFF" class="d-block w-100" alt="Charity Work">
            <div class="carousel-caption">
                <h2>Making a Difference</h2>
                <p>Together we can create positive change in our communities.</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="https://placehold.co/1200x400/2E7D32/FFF" class="d-block w-100" alt="Community Support">
            <div class="carousel-caption">
                <h2>Supporting Communities</h2>
                <p>Building stronger communities through sustainable initiatives.</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="https://placehold.co/1200x400/1B5E20/FFF" class="d-block w-100" alt="Volunteer Work">
            <div class="carousel-caption">
                <h2>Join Our Cause</h2>
                <p>Become part of our mission to help those in need.</p>
            </div>
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

<!-- Jumbotron Section -->
<div class="container py-5">
    <div class="p-5 mb-4 bg-light rounded-3">
        <div class="container-fluid py-5">
            <h1 class="display-5 fw-bold text-success">Our Mission</h1>
            <p class="col-md-8 fs-4">We are dedicated to creating lasting change in communities worldwide. Through sustainable development, education, and healthcare initiatives, we work to improve lives and build a better future for all.</p>
            <button class="btn btn-success btn-lg" type="button">Learn More About Us</button>
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="container px-4 py-5" id="features">
    <h2 class="pb-2 border-bottom text-success">Our Impact Areas</h2>
    <div class="row g-4 py-5 row-cols-1 row-cols-lg-3">
        <div class="col d-flex align-items-start">
            <div class="icon-square text-bg-success d-inline-flex align-items-center justify-content-center fs-4 flex-shrink-0 me-3 p-3 rounded">
                <i class="bi bi-heart-fill"></i>
            </div>
            <div>
                <h3 class="fs-2 text-success">Healthcare</h3>
                <p>Providing essential medical services and healthcare support to underserved communities.</p>
                <a href="#" class="btn btn-success">Learn More</a>
            </div>
        </div>
        <div class="col d-flex align-items-start">
            <div class="icon-square text-bg-success d-inline-flex align-items-center justify-content-center fs-4 flex-shrink-0 me-3 p-3 rounded">
                <i class="bi bi-book-fill"></i>
            </div>
            <div>
                <h3 class="fs-2 text-success">Education</h3>
                <p>Supporting educational initiatives and creating opportunities for lifelong learning.</p>
                <a href="#" class="btn btn-success">Learn More</a>
            </div>
        </div>
        <div class="col d-flex align-items-start">
            <div class="icon-square text-bg-success d-inline-flex align-items-center justify-content-center fs-4 flex-shrink-0 me-3 p-3 rounded">
                <i class="bi bi-house-heart-fill"></i>
            </div>
            <div>
                <h3 class="fs-2 text-success">Community Development</h3>
                <p>Building sustainable infrastructure and empowering local communities.</p>
                <a href="#" class="btn btn-success">Learn More</a>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

