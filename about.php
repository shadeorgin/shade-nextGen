<?php include 'includes/header.php'; ?>

<!-- Hero Section with Mission & Vision -->
<div class="container-fluid bg-light py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6 mb-4">
                <h2 class="text-success fw-bold">Our Mission</h2>
                <p class="lead">To empower communities through sustainable development and create lasting positive change in the lives of those we serve.</p>
            </div>
            <div class="col-md-6 mb-4">
                <h2 class="text-success fw-bold">Our Vision</h2>
                <p class="lead">A world where every individual has access to opportunities for growth, education, and a better quality of life.</p>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Section -->
<div class="container-fluid bg-success text-white py-5">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3 mb-4">
                <h2 class="counter fw-bold">5000+</h2>
                <p>Lives Impacted</p>
            </div>
            <div class="col-md-3 mb-4">
                <h2 class="counter fw-bold">100+</h2>
                <p>Projects Completed</p>
            </div>
            <div class="col-md-3 mb-4">
                <h2 class="counter fw-bold">25+</h2>
                <p>Countries Reached</p>
            </div>
            <div class="col-md-3 mb-4">
                <h2 class="counter fw-bold">1000+</h2>
                <p>Volunteers</p>
            </div>
        </div>
    </div>
</div>

<!-- Team Section -->
<div class="container py-5">
    <h2 class="text-center text-success mb-5">Our Team</h2>
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <img src="https://via.placeholder.com/300x300" class="card-img-top" alt="Team Member">
                <div class="card-body text-center">
                    <h5 class="card-title">John Doe</h5>
                    <p class="card-text text-success">Executive Director</p>
                    <p class="card-text">Leading our organization with over 15 years of experience in non-profit management.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <img src="https://via.placeholder.com/300x300" class="card-img-top" alt="Team Member">
                <div class="card-body text-center">
                    <h5 class="card-title">Jane Smith</h5>
                    <p class="card-text text-success">Program Director</p>
                    <p class="card-text">Coordinating our initiatives with passion and dedication since 2015.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <img src="https://via.placeholder.com/300x300" class="card-img-top" alt="Team Member">
                <div class="card-body text-center">
                    <h5 class="card-title">Mike Johnson</h5>
                    <p class="card-text text-success">Operations Manager</p>
                    <p class="card-text">Ensuring smooth execution of all our programs and initiatives.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Timeline Section -->
<div class="container-fluid bg-light py-5">
    <div class="container">
        <h2 class="text-center text-success mb-5">Our Journey</h2>
        <div class="timeline">
            <div class="row g-0">
                <div class="col-md-6 offset-md-6">
                    <div class="timeline-item">
                        <h4>2023</h4>
                        <p>Reached milestone of helping 5000+ beneficiaries</p>
                    </div>
                </div>
            </div>
            <div class="row g-0">
                <div class="col-md-6">
                    <div class="timeline-item">
                        <h4>2020</h4>
                        <p>Expanded operations to 25 countries</p>
                    </div>
                </div>
            </div>
            <div class="row g-0">
                <div class="col-md-6 offset-md-6">
                    <div class="timeline-item">
                        <h4>2018</h4>
                        <p>Launched our first international project</p>
                    </div>
                </div>
            </div>
            <div class="row g-0">
                <div class="col-md-6">
                    <div class="timeline-item">
                        <h4>2015</h4>
                        <p>Organization founded</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding: 20px 0;
}

.timeline::before {
    content: '';
    position: absolute;
    width: 2px;
    background: #198754;
    top: 0;
    bottom: 0;
    left: 50%;
    margin-left: -1px;
}

.timeline-item {
    padding: 20px 30px;
    position: relative;
    background: white;
    border-radius: 6px;
    margin: 20px 0;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

.timeline-item h4 {
    color: #198754;
    margin-bottom: 10px;
}

@media (max-width: 767.98px) {
    .timeline::before {
        left: 30px;
    }
    .timeline-item {
        margin-left: 60px;
    }
    .col-md-6.offset-md-6 {
        margin-left: 0;
    }
}
</style>

<script>
// Simple counter animation
const counters = document.querySelectorAll('.counter');
counters.forEach(counter => {
    const target = counter.innerText;
    counter.innerText = '0';
    
    const updateCounter = () => {
        const c = +counter.innerText;
        const inc = target.replace('+', '') / 100;
        
        if (c < target.replace('+', '')) {
            counter.innerText = Math.ceil(c + inc);
            setTimeout(updateCounter, 20);
        } else {
            counter.innerText = target;
        }
    };
    
    updateCounter();
});
</script>

<?php include 'includes/footer.php'; ?>

