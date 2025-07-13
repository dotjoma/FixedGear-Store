<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/connect.php';

$action = $_POST['action'] ?? '';

if ($action === 'login') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($username && $password) {
        $stmt = $conn->prepare("SELECT userID, username, pword, role FROM users WHERE username = :username OR email = :username LIMIT 1");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['pword'])) {
            $_SESSION['user_id'] = $user['userID'];
            $_SESSION['role'] = $user['role'];
            $response = ['success' => true, 'message' => 'Login successful.'];
        } else {
            $response = ['success' => false, 'message' => 'Invalid username/email or password.'];
        }
    } else {
        $response = ['success' => false, 'message' => 'Please fill in all fields.'];
    }
} elseif ($action === 'register') {
    $email = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $fname = trim($_POST['fname'] ?? '');
    $lname = trim($_POST['lname'] ?? '');
    if ($email && $username && $password) {
        $stmt = $conn->prepare("SELECT userID FROM users WHERE username = :username OR email = :email LIMIT 1");
        $stmt->execute([':username' => $username, ':email' => $email]);
        if ($stmt->fetch()) {
            $response = ['success' => false, 'message' => 'Username or email already exists.'];
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (fname, lname, username, email, pword, role) VALUES (:fname, :lname, :username, :email, :pword, 'user')");
            $stmt->execute([
                ':fname' => $fname,
                ':lname' => $lname,
                ':username' => $username,
                ':email' => $email,
                ':pword' => $hash
            ]);
            $response = ['success' => true, 'message' => 'Registration successful. You can now log in.'];
        }
    } else {
        $response = ['success' => false, 'message' => 'Please fill in all required fields.'];
    }
} elseif ($action === 'logout') {
    // Clear all session variables
    $_SESSION = array();
    
    // Destroy the session cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    // Destroy the session
    session_destroy();
    
    // Always redirect to the project root index.php using absolute path
    $projectRoot = '/finalprojectmayao/';
    header('Location: ' . $projectRoot . 'index.php');
    exit;
}

// Return the response array for form processing
return $response ?? ['success' => false, 'message' => 'Invalid request.']; 