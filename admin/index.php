<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in and has admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../pages/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Fixed Gear Culture</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts: Orbitron for bold headings -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700;900&display=swap" rel="stylesheet">
    <!-- Admin Styles -->
    <link href="components/admin-styles.css" rel="stylesheet">
    <style>
        body {
            background: #14161b;
            color: #fff;
            font-family: 'Orbitron', Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>
    <?php include('components/navigation.php'); ?>
    <?php include('components/sidebar.php'); ?>

    <!-- Main Content -->
    <div class="main-content">
        <div class="dashboard-header">
            <h1 class="dashboard-title">DASHBOARD</h1>
            <p class="dashboard-subtitle">Welcome back, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?>! Here's what's happening with your store.</p>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <i class="fas fa-users"></i>
                <div class="stat-number">1,247</div>
                <div class="stat-label">Total Users</div>
            </div>
            <div class="stat-card">
                <i class="fas fa-shopping-bag"></i>
                <div class="stat-number">89</div>
                <div class="stat-label">Products</div>
            </div>
            <div class="stat-card">
                <i class="fas fa-shopping-cart"></i>
                <div class="stat-number">342</div>
                <div class="stat-label">Orders</div>
            </div>
            <div class="stat-card">
                <i class="fas fa-dollar-sign"></i>
                <div class="stat-number">$12,847</div>
                <div class="stat-label">Revenue</div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="activity-section">
            <h3 class="section-title">Recent Activity</h3>
            
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div class="activity-content">
                    <h6>New User Registration</h6>
                    <p>John Doe registered a new account</p>
                </div>
            </div>
            
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="activity-content">
                    <h6>New Order Received</h6>
                    <p>Order #1234 placed by Jane Smith</p>
                </div>
            </div>
            
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-box"></i>
                </div>
                <div class="activity-content">
                    <h6>Product Added</h6>
                    <p>New fixed gear bike added to inventory</p>
                </div>
            </div>
            
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-star"></i>
                </div>
                <div class="activity-content">
                    <h6>Review Posted</h6>
                    <p>5-star review for Track Bike Pro</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
