<?php
require_once '../../includes/connect.php';

function is_ajax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $response = ["success" => false, "message" => "Unknown error."];

    if ($action === 'add') {
        $product_name = $_POST['product_name'] ?? '';
        $categoryID = $_POST['categoryID'] ?? '';
        $price = $_POST['price'] ?? 0;
        $stock_quantity = $_POST['stock_quantity'] ?? 0;
        $status = $_POST['status'] ?? 'active';
        $imageData = null;
        if (isset($_FILES['image']) && $_FILES['image']['tmp_name']) {
            $imageData = file_get_contents($_FILES['image']['tmp_name']);
        }
        try {
            $stmt = $conn->prepare("INSERT INTO products (product_name, categoryID, price, image, status, stock_quantity) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $product_name,
                $categoryID,
                $price,
                $imageData,
                $status,
                $stock_quantity
            ]);
            $response = ["success" => true, "message" => "Product added successfully!"];
        } catch (PDOException $e) {
            $response = ["success" => false, "message" => "Failed to add product."];
        }
        if (is_ajax()) {
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        } else {
            // fallback for non-AJAX
            header('Location: ../products.php');
            exit;
        }
    }
    if ($action === 'edit') {
        $productID = $_POST['productID'] ?? '';
        $product_name = $_POST['product_name'] ?? '';
        $categoryID = $_POST['categoryID'] ?? '';
        $price = $_POST['price'] ?? 0;
        $stock_quantity = $_POST['stock_quantity'] ?? 0;
        $status = $_POST['status'] ?? 'active';
        $imageData = null;
        $updateImage = false;
        if (isset($_FILES['image']) && $_FILES['image']['tmp_name']) {
            $imageData = file_get_contents($_FILES['image']['tmp_name']);
            $updateImage = true;
        }
        try {
            if ($updateImage) {
                $stmt = $conn->prepare("UPDATE products SET product_name=?, categoryID=?, price=?, image=?, status=?, stock_quantity=? WHERE productID=?");
                $stmt->execute([
                    $product_name,
                    $categoryID,
                    $price,
                    $imageData,
                    $status,
                    $stock_quantity,
                    $productID
                ]);
            } else {
                $stmt = $conn->prepare("UPDATE products SET product_name=?, categoryID=?, price=?, status=?, stock_quantity=? WHERE productID=?");
                $stmt->execute([
                    $product_name,
                    $categoryID,
                    $price,
                    $status,
                    $stock_quantity,
                    $productID
                ]);
            }
            $response = ["success" => true, "message" => "Product updated successfully!"];
        } catch (PDOException $e) {
            $response = ["success" => false, "message" => "Failed to update product."];
        }
        if (is_ajax()) {
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        } else {
            header('Location: ../products.php');
            exit;
        }
    }
    if ($action === 'delete') {
        $productID = $_POST['productID'] ?? '';
        try {
            $stmt = $conn->prepare("DELETE FROM products WHERE productID=?");
            $stmt->execute([$productID]);
            $response = ["success" => true, "message" => "Product deleted successfully!"];
        } catch (PDOException $e) {
            $response = ["success" => false, "message" => "Failed to delete product."];
        }
        if (is_ajax()) {
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        } else {
            header('Location: ../products.php');
            exit;
        }
    }
}
// If not POST, fallback
header('Location: ../products.php');
exit; 