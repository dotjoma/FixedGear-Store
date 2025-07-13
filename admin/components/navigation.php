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
<!-- Admin Navigation -->
<nav class="navbar navbar-expand-lg admin-nav">
    <div class="container-fluid">
        <a class="navbar-brand" href="../index.php">
            <i class="fas fa-cog me-2"></i>ADMIN DASHBOARD
        </a>
        
        <div class="navbar-nav ms-auto">
            <a class="nav-link" href="../index.php">
                <i class="fas fa-home me-1"></i>View Site
            </a>
            <span class="nav-link user-info">
                <i class="fas fa-user me-1"></i><?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?>
            </span>
            <form method="post" action="../includes/process_auth.php" style="display:inline;">
                <input type="hidden" name="action" value="logout">
                <input type="hidden" name="current_location" value="/admin">
                <button type="submit" class="nav-link btn btn-link">
                    <i class="fas fa-sign-out-alt me-1"></i>Logout
                </button>
            </form>
        </div>
    </div>
</nav> 