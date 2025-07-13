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
        <a href="orders.php" class="sidebar-item <?php echo ($currentPage === 'orders.php') ? 'active' : ''; ?>">
            <i class="fas fa-shopping-cart"></i>
            <span>Orders</span>
        </a>
        <a href="analytics.php" class="sidebar-item <?php echo ($currentPage === 'analytics.php') ? 'active' : ''; ?>">
            <i class="fas fa-chart-bar"></i>
            <span>Analytics</span>
        </a>
        <a href="settings.php" class="sidebar-item <?php echo ($currentPage === 'settings.php') ? 'active' : ''; ?>">
            <i class="fas fa-cog"></i>
            <span>Settings</span>
        </a>
        <a href="reports.php" class="sidebar-item <?php echo ($currentPage === 'reports.php') ? 'active' : ''; ?>">
            <i class="fas fa-file-alt"></i>
            <span>Reports</span>
        </a>
    </div>
</div> 