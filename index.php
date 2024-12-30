<?php require_once 'includes/header.php'; ?>

<!-- Hero Section -->
<div class="container-fluid bg-success bg-gradient text-white py-5">
    <div class="container py-5">
        <h1 class="display-4 fw-bold">Welcome to SHaDE-nextGen</h1>
        <p class="col-md-8 fs-4">Empowering communities through sustainable development and environmental conservation.</p>
        <a class="btn btn-light btn-lg" href="#learn-more">Learn More</a>
    </div>
</div>

<!-- Carousel Section -->
<div id="mainCarousel" class="carousel slide my-5" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="0" class="active"></button>
        <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="1"></button>
        <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="2"></button>
    </div>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="assets/images/slide1.jpg" class="d-block w-100" alt="Community Projects">
            <div class="carousel-caption d-none d-md-block">
                <h5>Community Projects</h5>
                <p>Supporting local initiatives for sustainable development.</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="assets/images/slide2.jpg" class="d-block w-100" alt="Environmental Conservation">
            <div class="carousel-caption d-none d-md-block">
                <h5>Environmental Conservation</h5>
                <p>Protecting our planet for future generations.</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="assets/images/slide3.jpg" class="d-block w-100" alt="Education Programs">
            <div class="carousel-caption d-none d-md-block">
                <h5>Education Programs</h5>
                <p>Empowering through knowledge and skills.</p>
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

<!-- Mission Statement Section -->
<div class="container my-5" id="learn-more">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h2 class="text-center mb-4">Our Mission</h2>
            <p class="lead text-center">To promote sustainable development and environmental conservation through community engagement, education, and innovative solutions.</p>
        </div>
    </div>
</div>

<!-- Featured Content -->
<div class="container my-5">
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card h-100 border-success">
                <div class="card-body">
                    <h5 class="card-title">Sustainable Development</h5>
                    <p class="card-text">Supporting initiatives that promote environmental, social, and economic sustainability in communities.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-success">
                <div class="card-body">
                    <h5 class="card-title">Community Engagement</h5>
                    <p class="card-text">Building strong partnerships with local communities to create lasting positive impact.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-success">
                <div class="card-body">
                    <h5 class="card-title">Innovation Hub</h5>
                    <p class="card-text">Developing creative solutions to address environmental and social challenges.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>

