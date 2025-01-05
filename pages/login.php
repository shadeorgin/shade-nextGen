<?php require_once '../includes/init.php'; ?>

<div class="container py-5">
    <?php if ($FEATURE_WARNINGS['enabled'] && $FEATURE_WARNINGS['features']['login']['enabled']): ?>
        <div class="alert alert-<?php echo $FEATURE_WARNINGS['style']; ?> <?php echo $FEATURE_WARNINGS['dismissible'] ? 'alert-dismissible fade show' : ''; ?> mb-4" role="alert">
            <i class="bi bi-info-circle-fill me-2"></i>
            <?php echo $FEATURE_WARNINGS['features']['login']['message']; ?>
            <?php if ($FEATURE_WARNINGS['dismissible']): ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg">
                <div class="card-header bg-success text-white">
                    <h4 class="card-title mb-0 text-center">Login</h4>
                </div>
                <div class="card-body p-4">
                    <form id="loginForm" action="process_login.php" method="POST" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="username" class="form-label">Email/Username</label>
                            <input type="text" 
                                class="form-control" 
                                id="username" 
                                name="username" 
                                required 
                                autocomplete="username"
                                autofocus>
                            <div class="invalid-feedback">
                                Please enter your username or email
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" 
                                class="form-control" 
                                id="password" 
                                name="password" 
                                required
                                autocomplete="current-password"
                                minlength="8">
                            <div class="invalid-feedback">
                                Please enter your password
                            </div>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" 
                                class="form-check-input" 
                                id="rememberMe" 
                                name="remember">
                            <label class="form-check-label" for="rememberMe">
                                Remember me
                            </label>
                        </div>
                        
                        <button type="submit" class="btn btn-success w-100 mb-3">
                            Login
                        </button>
                        
                        <div class="d-flex justify-content-between mb-0">
                            <a href="forgot_password.php" class="text-success">Forgot Password?</a>
                            <a href="register.php" class="text-success">Register</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Form validation
(function() {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            form.classList.add('was-validated')
        }, false)
    })
})()
</script>

<?php require_once('../includes/footer.php'); ?>

