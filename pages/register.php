<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('../components/navigation.php');

require_once '../includes/connect.php';
$register_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    define('INCLUDED_FROM_FORM', true);
    require_once '../includes/process_auth.php';
    $action = $_POST['action'] ?? '';
    if ($action === 'register') {
        $register_message = $response['message'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register | Fixed Gear Culture</title>
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
    .auth-info {
      color: #fff;
      font-size: 1.05rem;
      margin-bottom: 1.5rem;
    }
    .privacy-link {
      color: #e6ff00;
      text-decoration: underline;
    }
    .privacy-link:hover {
      color: #fff;
    }
    .have-account-text {
      text-align: center;
      margin-top: 1.5rem;
      color: #bdbdbd;
      font-size: 1rem;
    }
    .login-link {
      color: #e6ff00;
      text-decoration: underline;
      font-weight: bold;
    }
    .login-link:hover {
      color: #fff;
    }
  </style>
</head>
<body>
  <div class="container-fluid auth-section">
    <div class="row w-100 justify-content-center align-items-center">
      <!-- Register Card -->
      <div class="col-12 col-lg-6 d-flex justify-content-center">
        <form class="auth-card" method="post" action="">
          <div class="auth-title">REGISTER</div>
          <?php if ($register_message): ?>
            <div class="alert alert-info"><?php echo htmlspecialchars($register_message); ?></div>
          <?php endif; ?>
          <input type="hidden" name="action" value="register">
          <div class="mb-3">
            <label for="register-email" class="form-label">Email address <span class="text-danger">*</span></label>
            <input type="email" class="form-control" id="register-email" name="email" required>
          </div>
          <div class="mb-3">
            <label for="register-username" class="form-label">Username <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="register-username" name="username" required>
          </div>
          <div class="mb-3">
            <label for="register-password" class="form-label">Password <span class="text-danger">*</span></label>
            <input type="password" class="form-control" id="register-password" name="password" required>
          </div>
          <div class="mb-3">
            <label for="register-fname" class="form-label">First Name</label>
            <input type="text" class="form-control" id="register-fname" name="fname">
          </div>
          <div class="mb-3">
            <label for="register-lname" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="register-lname" name="lname">
          </div>
          <div class="mb-3" style="font-size:0.98rem; color:#bdbdbd;">
            We use your personal data to provide the best possible user experience on this website, to manage access to your account and for other purposes described in our <a href="#" class="privacy-link">privacy policy</a>.
          </div>
          <button type="submit" class="btn btn-neon fw-bold px-4 py-2">REGISTER</button>
          <div class="have-account-text">
            Already have an account? <a href="login.php" class="login-link">Login here</a>
          </div>
        </form>
      </div>
    </div>
  </div>
  <?php include('../components/footer.php'); ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../js/scripts.js"></script>
</body>
</html> 