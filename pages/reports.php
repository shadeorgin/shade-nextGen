<?php require_once('../includes/init.php'); ?>

<div class="container py-5">
    <!-- Page Title -->
    <h1 class="mb-4">Reports & Analytics Dashboard</h1>
    
    <!-- Filters Section -->
    <div class="card mb-4 border-success">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Filter Reports</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <select class="form-select">
                        <option>All Categories</option>
                        <option>Environmental Impact</option>
                        <option>Financial Reports</option>
                        <option>Project Updates</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select">
                        <option>All Years</option>
                        <option>2023</option>
                        <option>2022</option>
                        <option>2021</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select">
                        <option>All Regions</option>
                        <option>North</option>
                        <option>South</option>
                        <option>East</option>
                        <option>West</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-success w-100">Apply Filters</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Dashboard -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center border-success h-100">
                <div class="card-body">
                    <h3 class="text-success">1,234</h3>
                    <p class="text-muted">Trees Planted</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-success h-100">
                <div class="card-body">
                    <h3 class="text-success">567</h3>
                    <p class="text-muted">Volunteers</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-success h-100">
                <div class="card-body">
                    <h3 class="text-success">89</h3>
                    <p class="text-muted">Active Projects</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-success h-100">
                <div class="card-body">
                    <h3 class="text-success">$123K</h3>
                    <p class="text-muted">Funds Raised</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Impact Over Time</h5>
                </div>
                <div class="card-body">
                    <div class="text-center py-5 bg-light">
                        <p class="text-muted">Chart Placeholder</p>
                        <p class="text-muted">Monthly Impact Metrics</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Project Distribution</h5>
                </div>
                <div class="card-body">
                    <div class="text-center py-5 bg-light">
                        <p class="text-muted">Chart Placeholder</p>
                        <p class="text-muted">Project Category Distribution</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Downloadable Reports -->
    <div class="card border-success mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Available Reports</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Report Name</th>
                            <th>Category</th>
                            <th>Date</th>
                            <th>Size</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Annual Impact Report 2023</td>
                            <td>Impact Analysis</td>
                            <td>2023-12-01</td>
                            <td>2.3 MB</td>
                            <td><a href="#" class="btn btn-sm btn-success">Download</a></td>
                        </tr>
                        <tr>
                            <td>Q3 Financial Summary</td>
                            <td>Financial</td>
                            <td>2023-10-01</td>
                            <td>1.5 MB</td>
                            <td><a href="#" class="btn btn-sm btn-success">Download</a></td>
                        </tr>
                        <tr>
                            <td>Environmental Assessment 2023</td>
                            <td>Environmental</td>
                            <td>2023-09-15</td>
                            <td>3.7 MB</td>
                            <td><a href="#" class="btn btn-sm btn-success">Download</a></td>
                        </tr>
                        <tr>
                            <td>Volunteer Impact Report</td>
                            <td>Community</td>
                            <td>2023-08-30</td>
                            <td>1.8 MB</td>
                            <td><a href="#" class="btn btn-sm btn-success">Download</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Impact Metrics -->
    <div class="card border-success">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Key Impact Metrics</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="border rounded p-3">
                        <h6 class="text-success">Carbon Footprint Reduction</h6>
                        <div class="progress">
                            <div class="progress-bar bg-success" style="width: 75%">75%</div>
                        </div>
                        <small class="text-muted">Target: 80% by 2024</small>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="border rounded p-3">
                        <h6 class="text-success">Community Engagement</h6>
                        <div class="progress">
                            <div class="progress-bar bg-success" style="width: 60%">60%</div>
                        </div>
                        <small class="text-muted">Target: 75% by 2024</small>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="border rounded p-3">
                        <h6 class="text-success">Resource Conservation</h6>
                        <div class="progress">
                            <div class="progress-bar bg-success" style="width: 85%">85%</div>
                        </div>
                        <small class="text-muted">Target: 90% by 2024</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once('../includes/footer.php'); ?>
