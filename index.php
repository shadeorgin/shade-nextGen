<?php require_once 'includes/init.php'; ?>

<main>
<!-- Hero Section -->
<div class="container-fluid bg-success bg-gradient text-white py-5">
    <div class="container py-5">
        <h1 class="display-4 fw-bold">Welcome to SHaDE <small class="fs-6 d-block text-light">(Share, Help and ADorE)</small></h1>
        <p class="col-md-8 fs-4">Empowering lives since 2008 through charitable initiatives, educational support, and disaster relief. Based in Tamil Nadu, serving humanity across India.</p>
        <a class="btn btn-light btn-lg" href="#learn-more">Learn More</a>
    </div>
</div>

<!-- Carousel Section -->
<div class="container">
    <div id="mainCarousel" class="carousel slide my-5" data-bs-ride="carousel" style="max-height: 500px;">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="0" class="active"></button>
        <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="1"></button>
        <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="2"></button>
    </div>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="assets/images/food-aid.jpg" class="d-block w-100" alt="SHaDE volunteers providing food and medical assistance to those in need" style="object-fit: cover; height: 500px;">
            <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 p-2 rounded">
                <h5>Food & Medical Aid</h5>
                <p>Providing essential support to individuals and organizations across Tamil Nadu since 2008.</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="assets/images/education.jpg" class="d-block w-100" alt="SHaDE Sponsored Students program empowering education" style="object-fit: cover; height: 500px;">
            <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 p-2 rounded">
                <h5>SHaDE Sponsored Students (SSS)</h5>
                <p>Transforming lives through education since 2011, supporting students across multiple states.</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="assets/images/disaster-relief.jpg" class="d-block w-100" alt="SHaDE emergency response team providing disaster relief" style="object-fit: cover; height: 500px;">
            <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 p-2 rounded">
                <h5>Emergency Relief</h5>
                <p>Swift response to floods, COVID-19, and other emergencies, working with local NGO partners.</p>
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
</div>

<!-- Mission Statement Section -->
<div class="container my-5" id="learn-more">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h2 class="text-center mb-4">Our Mission</h2>
            <p class="lead text-center">To uplift lives through charitable initiatives, with focus on food & medical aid, education support, and disaster relief. Operating from Tamil Nadu, we collaborate with local NGOs to create lasting positive impact across India.</p>
        </div>
    </div>
</div>

<!-- Featured Content -->
<div class="container my-5">
    <div class="row g-4">
        <div class="col-md-3">
            <div class="card h-100 border-success">
                <div class="card-body">
                    <h5 class="card-title">Food & Medical Aid</h5>
                    <p class="card-text">Supporting individuals and organizations with essential needs since our inception in 2008.</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100 border-success">
                <div class="card-body">
                    <h5 class="card-title">Education Support (SSS)</h5>
                    <p class="card-text">Our SHaDE Sponsored Students program has been transforming lives through education since 2011.</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100 border-success">
                <div class="card-body">
                    <h5 class="card-title">Disaster Relief</h5>
                    <p class="card-text">Providing emergency support during floods, COVID-19, and other natural calamities.</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100 border-success">
                <div class="card-body">
                    <h5 class="card-title">NGO Collaborations</h5>
                    <p class="card-text">Working hand in hand with Indian NGOs to amplify our impact and reach.</p>
                </div>
            </div>
        </div>
    </div>
</div>
</main>

<?php require_once 'includes/footer.php'; ?>

