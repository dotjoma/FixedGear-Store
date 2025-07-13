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

// Sample categories data (replace with database query)
$categories = [
    [
        'id' => 1,
        'name' => 'Fixed Gear',
        'description' => 'Complete fixed gear bicycles',
        'product_count' => 12,
        'status' => 'active',
        'created_at' => '2024-01-15'
    ],
    [
        'id' => 2,
        'name' => 'Frames',
        'description' => 'Bicycle frames and framesets',
        'product_count' => 8,
        'status' => 'active',
        'created_at' => '2024-01-10'
    ],
    [
        'id' => 3,
        'name' => 'Components',
        'description' => 'Bicycle components and parts',
        'product_count' => 25,
        'status' => 'active',
        'created_at' => '2024-01-05'
    ],
    [
        'id' => 4,
        'name' => 'Accessories',
        'description' => 'Bicycle accessories and gear',
        'product_count' => 18,
        'status' => 'active',
        'created_at' => '2024-01-01'
    ],
    [
        'id' => 5,
        'name' => 'Apparel',
        'description' => 'Cycling clothing and apparel',
        'product_count' => 0,
        'status' => 'inactive',
        'created_at' => '2024-01-20'
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories Management | Admin Dashboard</title>
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
        
        .search-box {
            background: #181a20;
            border: 1px solid #333;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            color: #fff;
            margin-bottom: 2rem;
        }
        
        .search-box:focus {
            border-color: #e6ff00;
            box-shadow: 0 0 0 2px #e6ff0033;
            outline: none;
        }
        
        .categories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.5rem;
        }
        
        .category-card {
            background: #181a20;
            border: 1px solid #333;
            border-radius: 12px;
            padding: 1.5rem;
            transition: transform 0.3s, border-color 0.3s;
        }
        
        .category-card:hover {
            transform: translateY(-5px);
            border-color: #e6ff00;
        }
        
        .category-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }
        
        .category-name {
            font-size: 1.3rem;
            font-weight: bold;
            color: #e6ff00;
            margin: 0;
        }
        
        .category-status {
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
        
        .status-inactive {
            background: #6c757d;
            color: #fff;
        }
        
        .category-description {
            color: #bdbdbd;
            margin-bottom: 1rem;
            line-height: 1.5;
        }
        
        .category-stats {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-number {
            font-size: 1.2rem;
            font-weight: 900;
            color: #e6ff00;
            display: block;
        }
        
        .stat-label {
            color: #bdbdbd;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .category-actions {
            display: flex;
            gap: 0.5rem;
        }
        
        .btn-action {
            padding: 0.5rem 1rem;
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
        
        .btn-toggle {
            background: #6c757d;
            color: #fff;
        }
        
        .btn-toggle:hover {
            background: #5a6268;
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
            <h1 class="page-title">CATEGORIES MANAGEMENT</h1>
            <button class="btn btn-add">
                <i class="fas fa-plus me-2"></i>Add Category
            </button>
        </div>

        <!-- Stats Cards -->
        <div class="stats-cards">
            <div class="stat-card">
                <div class="stat-number"><?php echo count($categories); ?></div>
                <div class="stat-label">Total Categories</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo count(array_filter($categories, function($c) { return $c['status'] === 'active'; })); ?></div>
                <div class="stat-label">Active Categories</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo array_sum(array_column($categories, 'product_count')); ?></div>
                <div class="stat-label">Total Products</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo count(array_filter($categories, function($c) { return $c['product_count'] > 0; })); ?></div>
                <div class="stat-label">Categories with Products</div>
            </div>
        </div>

        <!-- Search Box -->
        <div class="mb-3">
            <input type="text" class="form-control search-box" placeholder="Search categories..." id="searchCategories">
        </div>

        <!-- Categories Grid -->
        <div class="categories-grid">
            <?php foreach ($categories as $category): ?>
            <div class="category-card" data-name="<?php echo htmlspecialchars($category['name']); ?>">
                <div class="category-header">
                    <h3 class="category-name"><?php echo htmlspecialchars($category['name']); ?></h3>
                    <span class="category-status status-<?php echo $category['status']; ?>">
                        <?php echo ucfirst($category['status']); ?>
                    </span>
                </div>
                
                <p class="category-description"><?php echo htmlspecialchars($category['description']); ?></p>
                
                <div class="category-stats">
                    <div class="stat-item">
                        <span class="stat-number"><?php echo $category['product_count']; ?></span>
                        <span class="stat-label">Products</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number"><?php echo date('M j', strtotime($category['created_at'])); ?></span>
                        <span class="stat-label">Created</span>
                    </div>
                </div>
                
                <div class="category-actions">
                    <button class="btn btn-action btn-edit" title="Edit Category">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button class="btn btn-action btn-toggle" title="Toggle Status">
                        <i class="fas fa-toggle-on"></i> Toggle
                    </button>
                    <button class="btn btn-action btn-delete" title="Delete Category">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Search functionality
        document.getElementById('searchCategories').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const categories = document.querySelectorAll('.category-card');
            
            categories.forEach(category => {
                const name = category.querySelector('.category-name').textContent.toLowerCase();
                const description = category.querySelector('.category-description').textContent.toLowerCase();
                
                const matchesSearch = name.includes(searchTerm) || description.includes(searchTerm);
                category.style.display = matchesSearch ? '' : 'none';
            });
        });
    </script>
</body>
</html> 