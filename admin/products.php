<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in and has admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../pages/login.php');
    exit;
}

// Include database connection
require_once '../includes/connect.php';

// Get products from database (assuming you have a products table)
// For now, we'll create sample data
$products = [
    [
        'id' => 1,
        'name' => 'Track Bike Pro',
        'category' => 'Fixed Gear',
        'price' => 899.99,
        'stock' => 15,
        'status' => 'active',
        'image' => '../assets/images/fgc_shop_kv.png'
    ],
    [
        'id' => 2,
        'name' => 'Urban Commuter',
        'category' => 'Fixed Gear',
        'price' => 649.99,
        'stock' => 8,
        'status' => 'active',
        'image' => '../assets/images/fgc_shop_kv.png'
    ],
    [
        'id' => 3,
        'name' => 'Racing Frame',
        'category' => 'Frames',
        'price' => 299.99,
        'stock' => 0,
        'status' => 'out_of_stock',
        'image' => '../assets/images/fgc_shop_kv.png'
    ]
];

$categories = ['Fixed Gear', 'Frames', 'Components', 'Accessories'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Management | Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts: Orbitron for bold headings -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700;900&display=swap" rel="stylesheet">
    <!-- Admin Styles -->
    <link href="components/admin-styles.css" rel="stylesheet">
    <style>
        body {
            background: #14161b;
            color: #fff;
            font-family: 'Orbitron', Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        
        .page-title {
            font-family: 'Orbitron', Arial, sans-serif;
            font-size: 2.5rem;
            font-weight: 900;
            color: #e6ff00;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin: 0;
        }
        
        .btn-add {
            background: #e6ff00;
            color: #181818;
            font-weight: bold;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: background 0.3s;
        }
        
        .btn-add:hover {
            background: #d4e600;
            color: #181818;
        }
        
        .filters {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }
        
        .filter-select {
            background: #181a20;
            border: 1px solid #333;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            color: #fff;
            min-width: 150px;
        }
        
        .filter-select:focus {
            border-color: #e6ff00;
            outline: none;
        }
        
        .search-box {
            background: #181a20;
            border: 1px solid #333;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            color: #fff;
            flex: 1;
            min-width: 200px;
        }
        
        .search-box:focus {
            border-color: #e6ff00;
            box-shadow: 0 0 0 2px #e6ff0033;
            outline: none;
        }
        
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }
        
        .product-card {
            background: #181a20;
            border: 1px solid #333;
            border-radius: 12px;
            overflow: hidden;
            transition: transform 0.3s, border-color 0.3s;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            border-color: #e6ff00;
        }
        
        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            background: #14161b;
        }
        
        .product-info {
            padding: 1.5rem;
        }
        
        .product-name {
            font-size: 1.2rem;
            font-weight: bold;
            color: #fff;
            margin-bottom: 0.5rem;
        }
        
        .product-category {
            color: #e6ff00;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0.5rem;
        }
        
        .product-price {
            font-size: 1.5rem;
            font-weight: 900;
            color: #e6ff00;
            margin-bottom: 1rem;
        }
        
        .product-stock {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .stock-status {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-active {
            background: #28a745;
            color: #fff;
        }
        
        .status-out_of_stock {
            background: #dc3545;
            color: #fff;
        }
        
        .product-actions {
            display: flex;
            gap: 0.5rem;
        }
        
        .btn-action {
            padding: 0.5rem;
            border: none;
            border-radius: 4px;
            font-size: 0.9rem;
            transition: background 0.3s;
            flex: 1;
        }
        
        .btn-edit {
            background: #007bff;
            color: #fff;
        }
        
        .btn-edit:hover {
            background: #0056b3;
            color: #fff;
        }
        
        .btn-delete {
            background: #dc3545;
            color: #fff;
        }
        
        .btn-delete:hover {
            background: #c82333;
            color: #fff;
        }
        
        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: #181a20;
            border: 1px solid #333;
            border-radius: 8px;
            padding: 1rem;
            text-align: center;
        }
        
        .stat-number {
            font-size: 1.5rem;
            font-weight: 900;
            color: #e6ff00;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            color: #bdbdbd;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>
    <?php include('components/navigation.php'); ?>
    <?php include('components/sidebar.php'); ?>

    <!-- Main Content -->
    <div class="main-content">
        <div class="page-header">
            <h1 class="page-title">PRODUCTS MANAGEMENT</h1>
            <button class="btn btn-add">
                <i class="fas fa-plus me-2"></i>Add Product
            </button>
        </div>

        <!-- Stats Cards -->
        <div class="stats-cards">
            <div class="stat-card">
                <div class="stat-number"><?php echo count($products); ?></div>
                <div class="stat-label">Total Products</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo count(array_filter($products, function($p) { return $p['status'] === 'active'; })); ?></div>
                <div class="stat-label">Active Products</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo count(array_filter($products, function($p) { return $p['stock'] > 0; })); ?></div>
                <div class="stat-label">In Stock</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">$<?php echo number_format(array_sum(array_column($products, 'price')), 2); ?></div>
                <div class="stat-label">Total Value</div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="filters">
            <select class="filter-select" id="categoryFilter">
                <option value="">All Categories</option>
                <?php foreach ($categories as $category): ?>
                <option value="<?php echo htmlspecialchars($category); ?>"><?php echo htmlspecialchars($category); ?></option>
                <?php endforeach; ?>
            </select>
            
            <select class="filter-select" id="statusFilter">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="out_of_stock">Out of Stock</option>
            </select>
            
            <input type="text" class="search-box" placeholder="Search products..." id="searchProducts">
        </div>

        <!-- Products Grid -->
        <div class="products-grid">
            <?php foreach ($products as $product): ?>
            <div class="product-card" data-category="<?php echo htmlspecialchars($product['category']); ?>" data-status="<?php echo htmlspecialchars($product['status']); ?>">
                <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image">
                <div class="product-info">
                    <div class="product-name"><?php echo htmlspecialchars($product['name']); ?></div>
                    <div class="product-category"><?php echo htmlspecialchars($product['category']); ?></div>
                    <div class="product-price">$<?php echo number_format($product['price'], 2); ?></div>
                    <div class="product-stock">
                        <span>Stock: <?php echo $product['stock']; ?></span>
                        <span class="stock-status status-<?php echo $product['status']; ?>">
                            <?php echo ucfirst(str_replace('_', ' ', $product['status'])); ?>
                        </span>
                    </div>
                    <div class="product-actions">
                        <button class="btn btn-action btn-edit" title="Edit Product">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn btn-action btn-delete" title="Delete Product">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Search and filter functionality
        function filterProducts() {
            const searchTerm = document.getElementById('searchProducts').value.toLowerCase();
            const categoryFilter = document.getElementById('categoryFilter').value;
            const statusFilter = document.getElementById('statusFilter').value;
            
            const products = document.querySelectorAll('.product-card');
            
            products.forEach(product => {
                const name = product.querySelector('.product-name').textContent.toLowerCase();
                const category = product.dataset.category;
                const status = product.dataset.status;
                
                const matchesSearch = name.includes(searchTerm);
                const matchesCategory = !categoryFilter || category === categoryFilter;
                const matchesStatus = !statusFilter || status === statusFilter;
                
                product.style.display = (matchesSearch && matchesCategory && matchesStatus) ? '' : 'none';
            });
        }
        
        document.getElementById('searchProducts').addEventListener('input', filterProducts);
        document.getElementById('categoryFilter').addEventListener('change', filterProducts);
        document.getElementById('statusFilter').addEventListener('change', filterProducts);
    </script>
</body>
</html> 