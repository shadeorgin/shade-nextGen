<?php require_once '../includes/init.php'; ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">Create Account</h4>
                </div>
                <div class="card-body p-4">
                    <form id="registrationForm" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="fullName" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="fullName" name="fullName" required>
                            <div class="invalid-feedback">
                                Please enter your full name.
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                            <div class="invalid-feedback">
                                Please enter a valid email address.
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                            <div class="invalid-feedback">
                                Please choose a username.
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" 
                                required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                                oninput="updatePasswordStrength(this.value)">
                            <div class="progress mt-2" style="height: 5px;">
                                <div id="passwordStrength" class="progress-bar bg-danger" role="progressbar"></div>
                            </div>
                            <div class="invalid-feedback">
                                Password must be at least 8 characters long and include uppercase, lowercase, and numbers.
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                            <div class="invalid-feedback">
                                Passwords do not match.
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    I agree to the Terms and Conditions
                                </label>
                                <div class="invalid-feedback">
                                    You must agree to the terms and conditions.
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-success w-100 mb-3">Register</button>
                        
                        <div class="text-center">
                            Already have an account? <a href="login.php" class="text-success">Login here</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Password strength indicator
function updatePasswordStrength(password) {
    const strength = {
        length: password.length >= 8,
        hasUpper: /[A-Z]/.test(password),
        hasLower: /[a-z]/.test(password),
        hasNumber: /\d/.test(password)
    };
    
    const strengthBar = document.getElementById('passwordStrength');
    const strengthCount = Object.values(strength).filter(Boolean).length;
    const strengthPercent = (strengthCount / 4) * 100;
    
    strengthBar.style.width = strengthPercent + '%';
    
    if (strengthPercent <= 25) {
        strengthBar.className = 'progress-bar bg-danger';
    } else if (strengthPercent <= 50) {
        strengthBar.className = 'progress-bar bg-warning';
    } else if (strengthPercent <= 75) {
        strengthBar.className = 'progress-bar bg-info';
    } else {
        strengthBar.className = 'progress-bar bg-success';
    }
}

// Form validation
document.getElementById('registrationForm').addEventListener('submit', function(event) {
    event.preventDefault();
    
    if (!this.checkValidity()) {
        event.stopPropagation();
    } else if (document.getElementById('password').value !== document.getElementById('confirmPassword').value) {
        document.getElementById('confirmPassword').setCustomValidity('Passwords do not match');
    } else {
        // Form is valid, you can submit it here
        alert('Registration form is valid! Ready to submit.');
    }
    
    this.classList.add('was-validated');
});

// Reset custom validity on input
document.getElementById('confirmPassword').addEventListener('input', function() {
    this.setCustomValidity('');
});
</script>

<?php require_once('../includes/footer.php'); ?>

