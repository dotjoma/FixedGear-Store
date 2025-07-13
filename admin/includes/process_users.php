<?php
require_once '../../includes/connect.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized.']);
    exit;
}

$action = $_POST['action'] ?? '';

if ($action === 'add') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $fname = trim($_POST['fname'] ?? '');
    $lname = trim($_POST['lname'] ?? '');
    $role = $_POST['role'] ?? 'user';
    $password = $_POST['password'] ?? '';
    if (!$username || !$email || !$password) {
        echo json_encode(['success' => false, 'message' => 'Username, email, and password are required.']);
        exit;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email address.']);
        exit;
    }
    $hash = password_hash($password, PASSWORD_DEFAULT);
    try {
        $stmt = $conn->prepare('INSERT INTO users (username, email, fname, lname, role, pword, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())');
        $stmt->execute([$username, $email, $fname, $lname, $role, $hash]);
        echo json_encode(['success' => true, 'message' => 'User added successfully!']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Failed to add user. Username or email may already exist.']);
    }
    exit;
}

if ($action === 'edit') {
    $userID = $_POST['userID'] ?? '';
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $fname = trim($_POST['fname'] ?? '');
    $lname = trim($_POST['lname'] ?? '');
    $role = $_POST['role'] ?? 'user';
    $password = $_POST['password'] ?? '';
    if (!$userID || !$username || !$email) {
        echo json_encode(['success' => false, 'message' => 'User ID, username, and email are required.']);
        exit;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email address.']);
        exit;
    }
    try {
        if ($password) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare('UPDATE users SET username=?, email=?, fname=?, lname=?, role=?, pword=? WHERE userID=?');
            $stmt->execute([$username, $email, $fname, $lname, $role, $hash, $userID]);
        } else {
            $stmt = $conn->prepare('UPDATE users SET username=?, email=?, fname=?, lname=?, role=? WHERE userID=?');
            $stmt->execute([$username, $email, $fname, $lname, $role, $userID]);
        }
        echo json_encode(['success' => true, 'message' => 'User updated successfully!']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Failed to update user. Username or email may already exist.']);
    }
    exit;
}

if ($action === 'delete') {
    $userID = $_POST['userID'] ?? '';
    if (!$userID) {
        echo json_encode(['success' => false, 'message' => 'User ID is required.']);
        exit;
    }
    try {
        $stmt = $conn->prepare('DELETE FROM users WHERE userID=?');
        $stmt->execute([$userID]);
        echo json_encode(['success' => true, 'message' => 'User deleted successfully!']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Failed to delete user.']);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid action.']); 