<?php require_once '../includes/init.php'; ?>

<div class="container my-5">
    <?php if ($FEATURE_WARNINGS['enabled'] && $FEATURE_WARNINGS['features']['contact']['enabled']): ?>
        <div class="alert alert-<?php echo $FEATURE_WARNINGS['style']; ?> <?php echo $FEATURE_WARNINGS['dismissible'] ? 'alert-dismissible fade show' : ''; ?> mb-4" role="alert">
            <i class="bi bi-info-circle-fill me-2"></i>
            <?php echo $FEATURE_WARNINGS['features']['contact']['message']; ?>
            <?php if ($FEATURE_WARNINGS['dismissible']): ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <h1 class="text-center mb-5">Contact Us</h1>

    <div class="row">
        <!-- Contact Form -->
        <div class="col-lg-6 mb-4">
            <div class="card border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">Send us a Message</h5>
                </div>
                <div class="card-body">
                    <form id="contactForm" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" required>
                            <div class="invalid-feedback">
                                Please enter your name
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" required>
                            <div class="invalid-feedback">
                                Please enter a valid email address
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" class="form-control" id="subject" required>
                            <div class="invalid-feedback">
                                Please enter a subject
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" rows="5" required></textarea>
                            <div class="invalid-feedback">
                                Please enter your message
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">Send Message</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="col-lg-6">
            <div class="card border-success mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">Contact Information</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex mb-3">
                        <i class="bi bi-geo-alt-fill text-success me-3 fs-4"></i>
                        <div>
                            <h6 class="mb-1">Address</h6>
                            <p class="mb-0">Chennai, Tamilnadu, India</p>
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <i class="bi bi-telephone-fill text-success me-3 fs-4"></i>
                        <div>
                            <h6 class="mb-1">Phone</h6>
                            <p class="mb-0">+91 80952 61616</p>
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <i class="bi bi-envelope-fill text-success me-3 fs-4"></i>
                        <div>
                            <h6 class="mb-1">Email</h6>
                            <p class="mb-0">shadedotteam@gmail.com</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Map Section -->
            <div class="card border-success mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">Location</h5>
                </div>
                <div class="card-body">
                    <div class="map-placeholder bg-light text-center p-5">
                        <i class="bi bi-map fs-1 text-success"></i>
                        <p class="mt-2">Map Integration Coming Soon</p>
                    </div>
                </div>
            </div>

            <!-- Social Media Links -->
            <div class="card border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">Connect With Us</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-around">
                        <a href="https://www.facebook.com/profile.php?id=100080343721975" class="text-success fs-3" target="_blank" rel="noopener noreferrer"><i class="bi bi-facebook"></i></a>
                        <a href="https://x.com/shadegroup" class="text-success fs-3" target="_blank" rel="noopener noreferrer"><i class="bi bi-twitter"></i></a>
                        <a href="https://www.linkedin.com/company/shadedotorgdotin/" class="text-success fs-3" target="_blank" rel="noopener noreferrer"><i class="bi bi-linkedin"></i></a>
                        <a href="#" class="text-success fs-3" target="_blank" rel="noopener noreferrer"><i class="bi bi-instagram"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Form Validation Script -->
<script>
    (function () {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
    })()
</script>

<?php require_once '../includes/footer.php'; ?>
