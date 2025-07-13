<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in and has admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../pages/login.php');
    exit;
}

require_once '../../includes/connect.php';

$action = $_POST['action'] ?? '';
$response = ['success' => false, 'message' => 'Invalid request.'];

if ($action === 'create') {
    $name = trim($_POST['name'] ?? '');
    $status = $_POST['status'] ?? 'active';
    
    if (empty($name)) {
        $response = ['success' => false, 'message' => 'Category name is required.'];
    } else {
        // Check if category name already exists
        $stmt = $conn->prepare("SELECT categoryID FROM categories WHERE name = :name LIMIT 1");
        $stmt->execute([':name' => $name]);
        
        if ($stmt->fetch()) {
            $response = ['success' => false, 'message' => 'Category name already exists.'];
        } else {
            $stmt = $conn->prepare("INSERT INTO categories (name, status) VALUES (:name, :status)");
            if ($stmt->execute([':name' => $name, ':status' => $status])) {
                $response = ['success' => true, 'message' => 'Category created successfully.'];
            } else {
                $response = ['success' => false, 'message' => 'Failed to create category.'];
            }
        }
    }
} elseif ($action === 'update') {
    $categoryID = $_POST['categoryID'] ?? '';
    $name = trim($_POST['name'] ?? '');
    $status = $_POST['status'] ?? 'active';
    
    if (empty($categoryID) || empty($name)) {
        $response = ['success' => false, 'message' => 'Category ID and name are required.'];
    } else {
        // Check if category name already exists (excluding current category)
        $stmt = $conn->prepare("SELECT categoryID FROM categories WHERE name = :name AND categoryID != :categoryID LIMIT 1");
        $stmt->execute([':name' => $name, ':categoryID' => $categoryID]);
        
        if ($stmt->fetch()) {
            $response = ['success' => false, 'message' => 'Category name already exists.'];
        } else {
            $stmt = $conn->prepare("UPDATE categories SET name = :name, status = :status WHERE categoryID = :categoryID");
            if ($stmt->execute([':name' => $name, ':status' => $status, ':categoryID' => $categoryID])) {
                $response = ['success' => true, 'message' => 'Category updated successfully.'];
            } else {
                $response = ['success' => false, 'message' => 'Failed to update category.'];
            }
        }
    }
} elseif ($action === 'delete') {
    $categoryID = $_POST['categoryID'] ?? '';
    
    if (empty($categoryID)) {
        $response = ['success' => false, 'message' => 'Category ID is required.'];
    } else {
        // Check if category has products (you might want to add this check later)
        $stmt = $conn->prepare("DELETE FROM categories WHERE categoryID = :categoryID");
        if ($stmt->execute([':categoryID' => $categoryID])) {
            $response = ['success' => true, 'message' => 'Category deleted successfully.'];
        } else {
            $response = ['success' => false, 'message' => 'Failed to delete category.'];
        }
    }
} elseif ($action === 'toggle_status') {
    $categoryID = $_POST['categoryID'] ?? '';
    
    if (empty($categoryID)) {
        $response = ['success' => false, 'message' => 'Category ID is required.'];
    } else {
        // Get current status
        $stmt = $conn->prepare("SELECT status FROM categories WHERE categoryID = :categoryID");
        $stmt->execute([':categoryID' => $categoryID]);
        $category = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($category) {
            $newStatus = $category['status'] === 'active' ? 'inactive' : 'active';
            $stmt = $conn->prepare("UPDATE categories SET status = :status WHERE categoryID = :categoryID");
            if ($stmt->execute([':status' => $newStatus, ':categoryID' => $categoryID])) {
                $response = ['success' => true, 'message' => 'Category status updated successfully.', 'new_status' => $newStatus];
            } else {
                $response = ['success' => false, 'message' => 'Failed to update category status.'];
            }
        } else {
            $response = ['success' => false, 'message' => 'Category not found.'];
        }
    }
} elseif ($action === 'get_categories') {
    // Get all categories for AJAX requests
    $stmt = $conn->prepare("SELECT categoryID, name, status, created_at FROM categories ORDER BY created_at DESC");
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $response = ['success' => true, 'categories' => $categories];
}

// Return JSON response for AJAX requests
header('Content-Type: application/json');
echo json_encode($response); 