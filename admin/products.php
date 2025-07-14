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
try {
    $stmt = $conn->prepare("SELECT p.productID, p.product_name, p.categoryID, c.name AS category, p.price, p.stock_quantity, p.status, p.image FROM products p JOIN categories c ON p.categoryID = c.categoryID");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $products = [];
}

// Fetch categories from database for filter dropdown
try {
    $catFilterStmt = $conn->prepare("SELECT DISTINCT name FROM categories WHERE status = 'active' ORDER BY name");
    $catFilterStmt->execute();
    $categories = $catFilterStmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    $categories = [];
}
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
        
        .btn-outline-secondary {
            border-color: #333;
            color: #fff;
            background: transparent;
        }
        
        .btn-outline-secondary:hover {
            background: #333;
            border-color: #e6ff00;
            color: #e6ff00;
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
    <?php include('components/notifications.php'); ?>
    <?php
    // Show notification if set in session
    if (isset($_SESSION['notif_message'])) {
        $notifType = $_SESSION['notif_type'] ?? 'success';
        $notifMsg = addslashes($_SESSION['notif_message']);
        echo "<script>document.addEventListener('DOMContentLoaded', function() { showNotification('{$notifMsg}', '{$notifType}', 5000); });</script>";
        unset($_SESSION['notif_message'], $_SESSION['notif_type']);
    }
    ?>

    <!-- Main Content -->
    <div class="main-content">
        <div class="page-header">
            <h1 class="page-title">PRODUCTS MANAGEMENT</h1>
            <button class="btn btn-add" data-bs-toggle="modal" data-bs-target="#addProductModal">
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
                <div class="stat-number"><?php echo count(array_filter($products, function($p) { return $p['stock_quantity'] > 0; })); ?></div>
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
                <option value="inactive">Inactive</option>
            </select>
            
            <input type="text" class="search-box" placeholder="Search products..." id="searchProducts">
            <button type="button" class="btn btn-outline-secondary" id="clearFilters">
                <i class="fas fa-times me-1"></i>Clear Filters
            </button>
        </div>

        <!-- Products Table -->
        <div class="table-responsive">
          <table class="table table-dark table-striped align-middle">
            <thead>
              <tr>
                <th scope="col">Image</th>
                <th scope="col">Name</th>
                <th scope="col">Category</th>
                <th scope="col">Price</th>
                <th scope="col">Stock</th>
                <th scope="col">Status</th>
                <th scope="col">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($products as $product): ?>
              <tr data-category="<?php echo htmlspecialchars($product['category']); ?>" data-status="<?php echo htmlspecialchars($product['status']); ?>">
                <td style="width: 80px;"><img src="<?php echo 'includes/product_image.php?id=' . $product['productID']; ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>" style="width: 60px; height: 60px; object-fit: cover; border-radius: 6px;"></td>
                <td class="fw-bold"><?php echo htmlspecialchars($product['product_name']); ?></td>
                <td><?php echo htmlspecialchars($product['category']); ?></td>
                <td>$<?php echo number_format($product['price'], 2); ?></td>
                <td><?php echo $product['stock_quantity']; ?></td>
                <td><span class="stock-status status-<?php echo $product['status']; ?>">
                  <?php echo ucfirst(str_replace('_', ' ', $product['status'])); ?>
                </span></td>
                <td>
                  <button class="btn btn-action btn-edit btn-sm me-1" title="Edit Product"
                    data-bs-toggle="modal" data-bs-target="#editProductModal"
                    data-id="<?php echo $product['productID']; ?>"
                    data-name="<?php echo htmlspecialchars($product['product_name']); ?>"
                    data-categoryid="<?php echo $product['categoryID']; ?>"
                    data-price="<?php echo $product['price']; ?>"
                    data-stock="<?php echo $product['stock_quantity']; ?>"
                    data-status="<?php echo $product['status']; ?>">
                    <i class="fas fa-edit"></i>
                  </button>
                  <button class="btn btn-action btn-delete btn-sm" title="Delete Product"
                    data-bs-toggle="modal" data-bs-target="#deleteProductModal"
                    data-id="<?php echo $product['productID']; ?>"
                    data-name="<?php echo htmlspecialchars($product['product_name']); ?>">
                    <i class="fas fa-trash"></i>
                  </button>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <div id="noResults" class="text-center py-4" style="display: none;">
            <i class="fas fa-search fa-2x text-muted mb-3"></i>
            <h5 class="text-muted">No products found</h5>
            <p class="text-muted">Try adjusting your search criteria or filters.</p>
          </div>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content bg-dark text-light">
          <form action="includes/process_products.php" method="POST" enctype="multipart/form-data">
            <div class="modal-header">
              <h5 class="modal-title" id="addProductModalLabel">Add Product</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label for="product_name" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="product_name" name="product_name" required>
              </div>
              <div class="mb-3">
                <label for="categoryID" class="form-label">Category</label>
                <select class="form-select" id="categoryID" name="categoryID" required>
                  <option value="">Select Category</option>
                  <?php
                  // Fetch categories from DB for the dropdown
                  try {
                    $catStmt = $conn->prepare("SELECT categoryID, name FROM categories WHERE status = 'active'");
                    $catStmt->execute();
                    $catList = $catStmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($catList as $cat) {
                      echo '<option value="' . $cat['categoryID'] . '">' . htmlspecialchars($cat['name']) . '</option>';
                    }
                  } catch (PDOException $e) {}
                  ?>
                </select>
              </div>
              <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="number" step="0.01" class="form-control" id="price" name="price" required>
              </div>
              <div class="mb-3">
                <label for="stock_quantity" class="form-label">Stock Quantity</label>
                <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" required>
              </div>
              <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status" required>
                  <option value="active">Active</option>
                  <option value="inactive">Inactive</option>
                </select>
              </div>
              <div class="mb-3">
                <label for="image" class="form-label">Product Image</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*">
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary" name="action" value="add">Add Product</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Edit Product Modal -->
    <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content bg-dark text-light">
          <form action="includes/process_products.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="productID" id="edit_productID">
            <div class="modal-header">
              <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label for="edit_product_name" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="edit_product_name" name="product_name" required>
              </div>
              <div class="mb-3">
                <label for="edit_categoryID" class="form-label">Category</label>
                <select class="form-select" id="edit_categoryID" name="categoryID" required>
                  <option value="">Select Category</option>
                  <?php
                  try {
                    $catStmt = $conn->prepare("SELECT categoryID, name FROM categories WHERE status = 'active'");
                    $catStmt->execute();
                    $catList = $catStmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($catList as $cat) {
                      echo '<option value="' . $cat['categoryID'] . '">' . htmlspecialchars($cat['name']) . '</option>';
                    }
                  } catch (PDOException $e) {}
                  ?>
                </select>
              </div>
              <div class="mb-3">
                <label for="edit_price" class="form-label">Price</label>
                <input type="number" step="0.01" class="form-control" id="edit_price" name="price" required>
              </div>
              <div class="mb-3">
                <label for="edit_stock_quantity" class="form-label">Stock Quantity</label>
                <input type="number" class="form-control" id="edit_stock_quantity" name="stock_quantity" required>
              </div>
              <div class="mb-3">
                <label for="edit_status" class="form-label">Status</label>
                <select class="form-select" id="edit_status" name="status" required>
                  <option value="active">Active</option>
                  <option value="inactive">Inactive</option>
                </select>
              </div>
              <div class="mb-3">
                <label for="edit_image" class="form-label">Product Image (leave blank to keep current)</label>
                <input type="file" class="form-control" id="edit_image" name="image" accept="image/*">
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary" name="action" value="edit">Save Changes</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Delete Product Modal -->
    <div class="modal fade" id="deleteProductModal" tabindex="-1" aria-labelledby="deleteProductModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content bg-dark text-light">
          <form action="includes/process_products.php" method="POST">
            <input type="hidden" name="productID" id="delete_productID">
            <div class="modal-header">
              <h5 class="modal-title" id="deleteProductModalLabel">Delete Product</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <p>Are you sure you want to delete <span id="delete_product_name" class="fw-bold"></span>?</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-danger" name="action" value="delete">Delete</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/scripts.js"></script>
    
    <script>
        // Search and filter functionality
        function filterProducts() {
            const searchTerm = document.getElementById('searchProducts').value.toLowerCase();
            const categoryFilter = document.getElementById('categoryFilter').value;
            const statusFilter = document.getElementById('statusFilter').value;
            
            const tableRows = document.querySelectorAll('tbody tr');
            const noResultsDiv = document.getElementById('noResults');
            let visibleCount = 0;
            
            tableRows.forEach(row => {
                const name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const category = row.dataset.category;
                const status = row.dataset.status;
                
                const matchesSearch = name.includes(searchTerm);
                const matchesCategory = !categoryFilter || category === categoryFilter;
                const matchesStatus = !statusFilter || status === statusFilter;
                
                const isVisible = (matchesSearch && matchesCategory && matchesStatus);
                row.style.display = isVisible ? '' : 'none';
                if (isVisible) visibleCount++;
            });
            
            // Show/hide no results message
            if (visibleCount === 0) {
                noResultsDiv.style.display = 'block';
            } else {
                noResultsDiv.style.display = 'none';
            }
        }
        
        document.getElementById('searchProducts').addEventListener('input', filterProducts);
        document.getElementById('categoryFilter').addEventListener('change', filterProducts);
        document.getElementById('statusFilter').addEventListener('change', filterProducts);
        
        // Clear filters functionality
        document.getElementById('clearFilters').addEventListener('click', function() {
            document.getElementById('searchProducts').value = '';
            document.getElementById('categoryFilter').value = '';
            document.getElementById('statusFilter').value = '';
            filterProducts();
        });

        // Edit Product Modal: fill fields with product data
        const editProductModal = document.getElementById('editProductModal');
        if (editProductModal) {
          editProductModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            document.getElementById('edit_productID').value = button.getAttribute('data-id');
            document.getElementById('edit_product_name').value = button.getAttribute('data-name');
            document.getElementById('edit_categoryID').value = button.getAttribute('data-categoryid');
            document.getElementById('edit_price').value = button.getAttribute('data-price');
            document.getElementById('edit_stock_quantity').value = button.getAttribute('data-stock');
            document.getElementById('edit_status').value = button.getAttribute('data-status');
          });
        }
        // Delete Product Modal: fill fields with product data
        const deleteProductModal = document.getElementById('deleteProductModal');
        if (deleteProductModal) {
          deleteProductModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            document.getElementById('delete_productID').value = button.getAttribute('data-id');
            document.getElementById('delete_product_name').textContent = button.getAttribute('data-name');
          });
        }

        // AJAX for Add Product
        const addProductForm = document.querySelector('#addProductModal form');
        if (addProductForm) {
          addProductForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            formData.append('action', 'add');
            fetch('includes/process_products.php', {
              method: 'POST',
              body: formData,
              headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
              if (data.success) {
                const modal = bootstrap.Modal.getInstance(document.getElementById('addProductModal'));
                if (modal) modal.hide();
                setTimeout(() => {
                  showSuccess(data.message);
                  setTimeout(() => location.reload(), 1500);
                }, 400);
              } else {
                showError(data.message);
              }
            })
            .catch(() => showError('An error occurred while adding the product.'));
          });
        }
        // AJAX for Edit Product
        const editProductForm = document.querySelector('#editProductModal form');
        if (editProductForm) {
          editProductForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            formData.append('action', 'edit');
            fetch('includes/process_products.php', {
              method: 'POST',
              body: formData,
              headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
              if (data.success) {
                const modal = bootstrap.Modal.getInstance(document.getElementById('editProductModal'));
                if (modal) modal.hide();
                setTimeout(() => {
                  showSuccess(data.message);
                  setTimeout(() => location.reload(), 1500);
                }, 400);
              } else {
                showError(data.message);
              }
            })
            .catch(() => showError('An error occurred while updating the product.'));
          });
        }
        // AJAX for Delete Product
        const deleteProductForm = document.querySelector('#deleteProductModal form');
        if (deleteProductForm) {
          deleteProductForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            formData.append('action', 'delete');
            fetch('includes/process_products.php', {
              method: 'POST',
              body: formData,
              headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
              if (data.success) {
                const modal = bootstrap.Modal.getInstance(document.getElementById('deleteProductModal'));
                if (modal) modal.hide();
                setTimeout(() => {
                  showSuccess(data.message);
                  setTimeout(() => location.reload(), 1500);
                }, 400);
              } else {
                showError(data.message);
              }
            })
            .catch(() => showError('An error occurred while deleting the product.'));
          });
        }
    </script>
</body>
</html> 