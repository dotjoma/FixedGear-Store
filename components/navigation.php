<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$currentPage = basename($_SERVER['SCRIPT_NAME']);
$currentDir = dirname($_SERVER['SCRIPT_NAME']);
$inPages = (strpos($currentDir, '/pages') !== false);
$inAdmin = (strpos($currentDir, '/admin') !== false);
$homeLink = $inPages ? '../index.php' : ($inAdmin ? '../index.php' : 'index.php');
$shopLink = $inPages ? '../pages/shop.php' : ($inAdmin ? '../pages/shop.php' : 'pages/shop.php');
$loginLink = $inPages ? 'login.php' : ($inAdmin ? '../pages/login.php' : 'pages/login.php');
$registerLink = $inPages ? 'register.php' : ($inAdmin ? '../pages/register.php' : 'pages/register.php');
$adminLink = $inPages ? '../admin/index.php' : ($inAdmin ? 'index.php' : 'admin/index.php');
$logoutLink = $inPages ? '../includes/process_auth.php' : ($inAdmin ? '../includes/process_auth.php' : 'includes/process_auth.php');
?>
<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg" style="background:#14161b; min-height:80px;">
  <div class="container-fluid px-4 d-flex align-items-center justify-content-between">
    <!-- Left: Social Icons -->
    <div class="d-flex align-items-center">
      <a href="#" class="me-4 text-white"><i class="fab fa-instagram fa-lg"></i></a>
      <a href="#" class="me-4 text-white"><i class="fab fa-youtube fa-lg"></i></a>
      <a href="#" class="me-4 text-white"><i class="fab fa-facebook fa-lg"></i></a>
      <a href="#" class="me-4 text-white"><i class="fab fa-tiktok fa-lg"></i></a>
    </div>
    <!-- Center: Logo -->
    <div class="mx-auto text-center" style="position:absolute; left:50%; transform:translateX(-50%);">
      <a href="<?= $homeLink ?>">
        <img src="<?= $inPages ? '../assets/images/fgc-logo-ribbon.png' : ($inAdmin ? '../assets/images/fgc-logo-ribbon.png' : 'assets/images/fgc-logo-ribbon.png') ?>" alt="Fixed Gear Culture Logo" style="max-height:64px; width:auto;">
      </a>
    </div>
    <!-- Right: Shop, Cart, User, Hamburger -->
    <div class="d-flex align-items-center ms-auto">
      <!-- Shop Link -->
      <a href="<?= $shopLink ?>" class="me-4 fw-bold text-uppercase text-white" style="letter-spacing:1px; font-size:1.1rem;">Shop</a>
      <!-- Cart Icon -->
      <a href="#" class="me-4 position-relative text-white" style="font-size:1.3rem;">
        <i class="fas fa-shopping-bag"></i>
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill"
              style="font-size:0.8rem; background:#e6ff00; color:#181818; font-weight:bold; box-shadow:0 0 0 2px #14161b;">0</span>
      </a>
      <!-- User Icon: Login/Register/Logout/Admin -->
      <?php if (empty($_SESSION['user_id'])): ?>
        <!-- Always go to login page when not logged in -->
        <a href="<?= $loginLink ?>" class="me-4 text-white" style="font-size:1.3rem;"><i class="fas fa-user"></i></a>
      <?php else: ?>
        <!-- If logged in, show dropdown -->
        <div class="dropdown me-4">
          <a href="#" class="text-white dropdown-toggle" style="font-size:1.3rem;" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-user"></i>
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
              <li><a class="dropdown-item" href="<?= $adminLink ?>"><i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard</a></li>
              <li><hr class="dropdown-divider"></li>
            <?php endif; ?>
            <li>
              <form id="logoutForm" method="post" action="<?= $logoutLink ?>" style="display:inline;">
                <input type="hidden" name="action" value="logout">
                <input type="hidden" name="current_location" value="<?= $currentDir ?>">
                <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt me-2"></i>Logout</button>
              </form>
            </li>
          </ul>
        </div>
      <?php endif; ?>
      <!-- Hamburger Menu -->
      <a href="#" class="text-white" style="font-size:2rem; margin-top:2px;">
        <!-- Hamburger menu as three lines -->
        <span style="display:inline-block; width:32px;">
          <span style="display:block; height:3px; background:#fff; margin:6px 0; border-radius:2px;"></span>
          <span style="display:block; height:3px; background:#fff; margin:6px 0; border-radius:2px;"></span>
          <span style="display:block; height:3px; background:#fff; margin:6px 0; border-radius:2px;"></span>
        </span>
      </a>
    </div>
  </div>
</nav>
<!-- End Navigation Bar --> 