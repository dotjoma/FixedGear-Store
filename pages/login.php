<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('../components/navigation.php');

// Handle login POST
require_once '../includes/connect.php';
$login_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    define('INCLUDED_FROM_FORM', true);
    require_once '../includes/process_auth.php';
    $action = $_POST['action'] ?? '';
    if ($action === 'login') {
        if ($response['success']) {
            header('Location: ../index.php');
            exit;
        } else {
            $login_message = $response['message'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | Fixed Gear Culture</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <!-- Google Fonts: Orbitron for bold headings -->
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700;900&display=swap" rel="stylesheet">
  <style>
    body {
      background: #14161b;
      color: #fff;
      font-family: 'Orbitron', Arial, sans-serif;
    }
    .auth-section {
      min-height: 80vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .auth-card {
      background: #181a20;
      border-radius: 16px;
      box-shadow: 0 4px 32px rgba(0,0,0,0.15);
      padding: 2.5rem 2rem;
      margin: 1rem;
      width: 100%;
      max-width: 420px;
    }
    .auth-title {
      font-family: 'Orbitron', Arial, sans-serif;
      font-size: 2.2rem;
      font-weight: 900;
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-bottom: 2rem;
    }
    .form-label {
      font-weight: bold;
      text-transform: uppercase;
      letter-spacing: 1px;
      font-size: 1rem;
    }
    .form-control {
      background: #14161b;
      border: 1px solid #333;
      color: #fff;
      border-radius: 8px;
      font-size: 1rem;
    }
    .form-control:focus {
      background: #181a20;
      color: #fff;
      border-color: #e6ff00;
      box-shadow: 0 0 0 2px #e6ff0033;
    }
    .btn-neon {
      background: #e6ff00;
      color: #181818;
      font-weight: bold;
      border-radius: 8px;
      padding: 0.6rem 2.2rem;
      text-transform: uppercase;
      letter-spacing: 1px;
      transition: background 0.2s, color 0.2s;
    }
    .btn-neon:hover {
      background: #d4e600;
      color: #181818;
    }
    .auth-link {
      color: #e6ff00;
      text-decoration: underline;
      font-size: 1rem;
    }
    .auth-link:hover {
      color: #fff;
    }
    .form-check-label {
      font-size: 1rem;
      font-weight: normal;
      text-transform: none;
    }
    .no-account-text {
      text-align: center;
      margin-top: 1.5rem;
      color: #bdbdbd;
      font-size: 1rem;
    }
    .register-link {
      color: #e6ff00;
      text-decoration: underline;
      font-weight: bold;
    }
    .register-link:hover {
      color: #fff;
    }
  </style>
</head>
<body>
  <div class="container-fluid auth-section">
    <div class="row w-100 justify-content-center align-items-center">
      <!-- Login Card -->
      <div class="col-12 col-lg-5 d-flex justify-content-center">
        <form class="auth-card" method="post" action="">
          <div class="auth-title">LOGIN</div>
          <?php if ($login_message): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($login_message); ?></div>
          <?php endif; ?>
          <input type="hidden" name="action" value="login">
          <div class="mb-3">
            <label for="login-username" class="form-label">Username or email address <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="login-username" name="username" required>
          </div>
          <div class="mb-3">
            <label for="login-password" class="form-label">Password <span class="text-danger">*</span></label>
            <div class="input-group">
              <input type="password" class="form-control" id="login-password" name="password" required>
              <span class="input-group-text bg-transparent border-0"><i class="fas fa-eye text-secondary"></i></span>
            </div>
          </div>
          <div class="d-flex align-items-center mb-3">
            <button type="submit" class="btn btn-neon me-3">LOG IN</button>
            <div class="form-check ms-2">
              <input class="form-check-input" type="checkbox" id="rememberMe">
              <label class="form-check-label" for="rememberMe">Remember me</label>
            </div>
          </div>
          <div class="mb-2">
            <a href="#" class="auth-link">Lost your password?</a>
          </div>
          <div class="no-account-text">
            Don't have an account yet? <a href="register.php" class="register-link">Register here</a>
          </div>
        </form>
      </div>
    </div>
  </div>
  <?php include('../components/footer.php'); ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 