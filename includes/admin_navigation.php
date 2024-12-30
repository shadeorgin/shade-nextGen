<?php if (!function_exists('isAdmin')) { require_once 'auth.php'; } ?>
<nav class="admin-nav">
    <ul>
        <li><a href="index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <?php if (isAdmin()): ?>
        <li class="dropdown">
            <a href="#" class="dropdown-toggle"><i class="fas fa-users-cog"></i> User Management</a>
            <ul class="dropdown-menu">
                <li><a href="users/list.php"><i class="fas fa-list"></i> List Users</a></li>
                <li><a href="users/create.php"><i class="fas fa-user-plus"></i> Add User</a></li>
            </ul>
        </li>
        <?php endif; ?>
        <li><a href="appeals.php"><i class="fas fa-hands-helping"></i> Manage Appeals</a></li>
        <li><a href="beneficiaries.php"><i class="fas fa-users"></i> Manage Beneficiaries</a></li>
        <li><a href="donors.php"><i class="fas fa-hand-holding-heart"></i> Donors</a></li>
        <li><a href="donations.php"><i class="fas fa-gift"></i> Donations</a></li>
        <li><a href="guides.php"><i class="fas fa-book"></i> Documentation Guides</a></li>
        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</nav>

<style>
.admin-nav .dropdown-toggle { cursor: pointer; }
.admin-nav .dropdown-menu {
    display: none;
    position: absolute;
    background: #fff;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    padding: 10px 0;
    z-index: 1000;
}
.admin-nav .dropdown:hover .dropdown-menu { display: block; }
.admin-nav .dropdown-menu li { padding: 5px 15px; }
.admin-nav .fas { margin-right: 8px; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropdowns = document.querySelectorAll('.dropdown-toggle');
    dropdowns.forEach(dropdown => {
        dropdown.addEventListener('click', (e) => {
            e.preventDefault();
            const menu = dropdown.nextElementSibling;
            menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
        });
    });
});
</script>

