<?php
require_once '../../includes/connect.php';
$productID = $_GET['id'] ?? '';
if ($productID) {
    $stmt = $conn->prepare('SELECT image FROM products WHERE productID = ?');
    $stmt->execute([$productID]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row && $row['image']) {
        header('Content-Type: image/jpeg');
        echo $row['image'];
        exit;
    }
}
// If no image, serve default
header('Content-Type: image/png');
readfile('../../assets/images/fgc_shop_kv.png'); 