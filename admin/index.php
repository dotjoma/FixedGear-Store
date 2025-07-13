<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in and has admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../pages/login.php');
    exit;
}

require_once '../includes/connect.php';

// Get total users
try {
    $stmt = $conn->prepare('SELECT COUNT(*) FROM users');
    $stmt->execute();
    $totalUsers = $stmt->fetchColumn();
} catch (PDOException $e) {
    $totalUsers = 0;
}
// Get total products
try {
    $stmt = $conn->prepare('SELECT COUNT(*) FROM products');
    $stmt->execute();
    $totalProducts = $stmt->fetchColumn();
} catch (PDOException $e) {
    $totalProducts = 0;
}
// Get total categories
try {
    $stmt = $conn->prepare('SELECT COUNT(*) FROM categories');
    $stmt->execute();
    $totalCategories = $stmt->fetchColumn();
} catch (PDOException $e) {
    $totalCategories = 0;
}
// Get active products
try {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM products WHERE status = 'active'");
    $stmt->execute();
    $activeProducts = $stmt->fetchColumn();
} catch (PDOException $e) {
    $activeProducts = 0;
}

// Get recent users
try {
    $stmt = $conn->prepare('SELECT username, created_at FROM users ORDER BY created_at DESC LIMIT 3');
    $stmt->execute();
    $recentUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $recentUsers = [];
}
// Get recent products
try {
    $stmt = $conn->prepare('SELECT product_name, created_at FROM products ORDER BY created_at DESC LIMIT 3');
    $stmt->execute();
    $recentProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $recentProducts = [];
}
// Get recent categories
try {
    $stmt = $conn->prepare('SELECT name, created_at FROM categories ORDER BY created_at DESC LIMIT 3');
    $stmt->execute();
    $recentCategories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $recentCategories = [];
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
    <?php include('components/notifications.php'); ?>

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
                <div class="stat-number"><?php echo $totalUsers; ?></div>
                <div class="stat-label">Total Users</div>
            </div>
            <div class="stat-card">
                <i class="fas fa-shopping-bag"></i>
                <div class="stat-number"><?php echo $totalProducts; ?></div>
                <div class="stat-label">Products</div>
            </div>
            <div class="stat-card">
                <i class="fas fa-tags"></i>
                <div class="stat-number"><?php echo $totalCategories; ?></div>
                <div class="stat-label">Categories</div>
            </div>
            <div class="stat-card">
                <i class="fas fa-check-circle"></i>
                <div class="stat-number"><?php echo $activeProducts; ?></div>
                <div class="stat-label">Active Products</div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="activity-section">
            <h3 class="section-title">Recent Activity</h3>
            <?php foreach ($recentUsers as $user): ?>
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div class="activity-content">
                    <h6>New User Registration</h6>
                    <p><?php echo htmlspecialchars($user['username']); ?> joined on <?php echo date('M j, Y', strtotime($user['created_at'])); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
            <?php foreach ($recentProducts as $product): ?>
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-box"></i>
                </div>
                <div class="activity-content">
                    <h6>Product Added</h6>
                    <p><?php echo htmlspecialchars($product['product_name']); ?> added on <?php echo date('M j, Y', strtotime($product['created_at'])); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
            <?php foreach ($recentCategories as $cat): ?>
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-tags"></i>
                </div>
                <div class="activity-content">
                    <h6>Category Added</h6>
                    <p><?php echo htmlspecialchars($cat['name']); ?> created on <?php echo date('M j, Y', strtotime($cat['created_at'])); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/scripts.js"></script>
</body>
</html>
