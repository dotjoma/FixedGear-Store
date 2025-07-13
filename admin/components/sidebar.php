<?php
// Get current page for active state
$currentPage = basename($_SERVER['SCRIPT_NAME']);
?>
<!-- Sidebar -->
<div class="sidebar">
    <div class="sidebar-header">
        <h3>ADMIN PANEL</h3>
    </div>
    
    <div class="sidebar-menu">
        <a href="index.php" class="sidebar-item <?php echo ($currentPage === 'index.php') ? 'active' : ''; ?>">
            <i class="fas fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
        <a href="users.php" class="sidebar-item <?php echo ($currentPage === 'users.php') ? 'active' : ''; ?>">
            <i class="fas fa-users"></i>
            <span>Users</span>
        </a>
        <a href="products.php" class="sidebar-item <?php echo ($currentPage === 'products.php') ? 'active' : ''; ?>">
            <i class="fas fa-shopping-bag"></i>
            <span>Products</span>
        </a>
        <a href="categories.php" class="sidebar-item <?php echo ($currentPage === 'categories.php') ? 'active' : ''; ?>">
            <i class="fas fa-tags"></i>
            <span>Categories</span>
        </a>
    </div>
</div> 