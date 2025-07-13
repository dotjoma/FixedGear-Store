<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../includes/connect.php';

// Fetch categories
try {
    $stmt = $conn->prepare("SELECT categoryID, name FROM categories WHERE status = 'active' ORDER BY name ASC");
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $categories = [];
}

// Fetch products (all, for now)
try {
    $stmt = $conn->prepare("SELECT p.productID, p.product_name, p.price, p.stock_quantity, p.status, c.name AS category FROM products p JOIN categories c ON p.categoryID = c.categoryID WHERE p.status = 'active' ORDER BY p.created_at DESC");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $products = [];
}

// Get selected category from query string
$selectedCategory = $_GET['category'] ?? '';
$filteredProducts = $selectedCategory ? array_filter($products, function($p) use ($selectedCategory) {
    return $p['category'] === $selectedCategory;
}) : $products;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop | Fixed Gear Culture</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="../admin/components/admin-styles.css" rel="stylesheet">
    <style>
        body { background: #181a20; color: #fff; }
        .shop-header { margin-top: 2rem; text-align: center; }
        .category-menu { display: flex; gap: 1.5rem; justify-content: center; margin: 2rem 0 1rem 0; flex-wrap: wrap; }
        .category-link { color: #fff; font-weight: bold; text-decoration: none; padding: 0.5rem 1.2rem; border-radius: 20px; transition: background 0.2s; }
        .category-link.active, .category-link:hover { background: #e6ff00; color: #181818; }
        .product-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 2rem; }
        .product-card { background: #22242a; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px #0002; display: flex; flex-direction: column; }
        .product-image { width: 100%; height: 220px; object-fit: cover; background: #14161b; }
        .product-info { padding: 1.2rem; flex: 1; display: flex; flex-direction: column; }
        .product-name { font-size: 1.1rem; font-weight: bold; color: #e6ff00; margin-bottom: 0.5rem; }
        .product-category { color: #bdbdbd; font-size: 0.9rem; margin-bottom: 0.5rem; }
        .product-price { font-size: 1.3rem; font-weight: 900; color: #fff; margin-bottom: 1rem; }
        .add-cart-btn { background: #e6ff00; color: #181818; font-weight: bold; border: none; border-radius: 8px; padding: 0.5rem 1.2rem; text-transform: uppercase; letter-spacing: 1px; transition: background 0.2s; }
        .add-cart-btn:hover { background: #d4e600; color: #181818; }
        .out-of-stock { color: #dc3545; font-weight: bold; font-size: 0.95rem; }
        .sort-select { background: #22242a; color: #fff; border: 1px solid #333; border-radius: 8px; padding: 0.5rem 1rem; min-width: 160px; }
        .sort-select:focus { border-color: #e6ff00; outline: none; }
    </style>
</head>
<body>
    <?php include('../components/navigation.php'); ?>
    <div class="container">
        <!-- Banner Section -->
        <div class="row mt-4 mb-4">
            <div class="col-md-6 mb-2 mb-md-0">
                <img src="../assets/images/courage_collab_banenr_fgc.jpg" alt="Collab Banner" class="img-fluid w-100 rounded-3" style="object-fit:cover; height:220px;">
            </div>
            <div class="col-md-6">
                <img src="../assets/images/fls_web-banner_tiny.jpg" alt="Final Lap Sale Banner" class="img-fluid w-100 rounded-3" style="object-fit:cover; height:220px;">
            </div>
        </div>
        <!-- Shop Header -->
        <div class="shop-header">
            <h1 class="display-5 fw-bold">Shop</h1>
        </div>
        <!-- Category Menu -->
        <div class="category-menu">
            <a href="shop.php" class="category-link<?php if (!$selectedCategory) echo ' active'; ?>">All products</a>
            <?php foreach ($categories as $cat): ?>
                <a href="shop.php?category=<?php echo urlencode($cat['name']); ?>" class="category-link<?php if ($selectedCategory === $cat['name']) echo ' active'; ?>"><?php echo htmlspecialchars($cat['name']); ?></a>
            <?php endforeach; ?>
        </div>
        <!-- Sorting Dropdown -->
        <div class="d-flex justify-content-end mb-3">
            <select class="sort-select" id="sortProducts">
                <option value="default">Default sorting</option>
                <option value="price_asc">Price: Low to High</option>
                <option value="price_desc">Price: High to Low</option>
                <option value="name_asc">Name: A-Z</option>
                <option value="name_desc">Name: Z-A</option>
            </select>
        </div>
        <!-- Product Grid -->
        <div class="product-grid" id="productGrid">
            <?php foreach ($filteredProducts as $product): ?>
            <div class="product-card">
                <img src="../admin/includes/product_image.php?id=<?php echo $product['productID']; ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>" class="product-image">
                <div class="product-info">
                    <div class="product-name"><?php echo htmlspecialchars($product['product_name']); ?></div>
                    <div class="product-category"><?php echo htmlspecialchars($product['category']); ?></div>
                    <div class="product-price">₱<?php echo number_format($product['price'], 2); ?></div>
                    <?php if ($product['stock_quantity'] > 0): ?>
                        <button class="add-cart-btn" disabled>Add to Cart</button>
                    <?php else: ?>
                        <div class="out-of-stock">Out of Stock</div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php include('../components/footer.php'); ?>
    <script>
    // Sorting functionality
    document.getElementById('sortProducts').addEventListener('change', function() {
        const value = this.value;
        const grid = document.getElementById('productGrid');
        const cards = Array.from(grid.children);
        cards.sort((a, b) => {
            const nameA = a.querySelector('.product-name').textContent.trim().toLowerCase();
            const nameB = b.querySelector('.product-name').textContent.trim().toLowerCase();
            const priceA = parseFloat(a.querySelector('.product-price').textContent.replace('$',''));
            const priceB = parseFloat(b.querySelector('.product-price').textContent.replace('$',''));
            if (value === 'price_asc') return priceA - priceB;
            if (value === 'price_desc') return priceB - priceA;
            if (value === 'name_asc') return nameA.localeCompare(nameB);
            if (value === 'name_desc') return nameB.localeCompare(nameA);
            return 0;
        });
        grid.innerHTML = '';
        cards.forEach(card => grid.appendChild(card));
    });
    </script>
</body>
</html> 