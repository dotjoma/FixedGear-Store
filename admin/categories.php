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

// Get categories from database
$stmt = $conn->prepare("SELECT categoryID, name, status, created_at FROM categories ORDER BY created_at DESC");
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        
        .categories-table {
            background: #181a20;
            border: 1px solid #333;
            border-radius: 12px;
            overflow: hidden;
        }
        
        .table {
            margin: 0;
            color: #fff;
        }
        
        .table th {
            background: #14161b;
            color: #e6ff00;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: none;
            padding: 1rem;
        }
        
        .table td {
            border: none;
            border-bottom: 1px solid #333;
            padding: 1rem;
            vertical-align: middle;
        }
        
        .table tbody tr:hover {
            background: #14161b;
        }
        
        .category-name {
            font-weight: bold;
            color: #e6ff00;
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
        
        .btn-action {
            padding: 0.25rem 0.5rem;
            margin: 0 0.25rem;
            border: none;
            border-radius: 4px;
            font-size: 0.8rem;
            transition: background 0.3s;
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
        
        /* Modal Styles */
        .modal-content {
            background: #181a20;
            border: 1px solid #333;
            color: #fff;
        }
        
        .modal-header {
            border-bottom: 1px solid #333;
        }
        
        .modal-title {
            color: #e6ff00;
            font-weight: bold;
        }
        
        .modal-body {
            padding: 1.5rem;
        }
        
        .form-control {
            background: #14161b;
            border: 1px solid #333;
            color: #fff;
            border-radius: 8px;
        }
        
        .form-control:focus {
            background: #181a20;
            border-color: #e6ff00;
            box-shadow: 0 0 0 2px #e6ff0033;
            color: #fff;
        }
        
        .form-label {
            color: #e6ff00;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .btn-close {
            filter: invert(1);
        }
        
        .alert {
            border-radius: 8px;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <?php include('components/navigation.php'); ?>
    <?php include('components/sidebar.php'); ?>
    <?php include('components/notifications.php'); ?>

    <!-- Main Content -->
    <div class="main-content">
        <div class="page-header">
            <h1 class="page-title">CATEGORIES MANAGEMENT</h1>
            <button class="btn btn-add" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
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
                <div class="stat-number"><?php echo count(array_filter($categories, function($c) { return $c['status'] === 'inactive'; })); ?></div>
                <div class="stat-label">Inactive Categories</div>
            </div>
        </div>

        <!-- Search Box -->
        <div class="mb-3">
            <input type="text" class="form-control search-box" placeholder="Search categories..." id="searchCategories">
        </div>

        <!-- Categories Table -->
        <div class="categories-table">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Category Name</th>
                        <th>Status</th>
                        <th>Created Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="categoriesTableBody">
                    <?php foreach ($categories as $category): ?>
                    <tr data-name="<?php echo htmlspecialchars($category['name']); ?>">
                        <td><?php echo $category['categoryID']; ?></td>
                        <td class="category-name"><?php echo htmlspecialchars($category['name']); ?></td>
                        <td>
                            <span class="category-status status-<?php echo $category['status']; ?>">
                                <?php echo ucfirst($category['status']); ?>
                            </span>
                        </td>
                        <td><?php echo date('M j, Y', strtotime($category['created_at'])); ?></td>
                        <td>
                            <button class="btn btn-action btn-edit" title="Edit Category" onclick="editCategory(<?php echo $category['categoryID']; ?>, '<?php echo htmlspecialchars($category['name']); ?>', '<?php echo $category['status']; ?>')">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-action btn-toggle" title="Toggle Status" onclick="toggleStatus(<?php echo $category['categoryID']; ?>)">
                                <i class="fas fa-toggle-on"></i>
                            </button>
                            <button class="btn btn-action btn-delete" title="Delete Category" onclick="deleteCategory(<?php echo $category['categoryID']; ?>, '<?php echo htmlspecialchars($category['name']); ?>')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ADD NEW CATEGORY</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addCategoryForm">
                        <div class="mb-3">
                            <label for="categoryName" class="form-label">Category Name</label>
                            <input type="text" class="form-control" id="categoryName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="categoryStatus" class="form-label">Status</label>
                            <select class="form-control" id="categoryStatus" name="status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-add flex-grow-1">Create Category</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">EDIT CATEGORY</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editCategoryForm">
                        <input type="hidden" id="editCategoryID" name="categoryID">
                        <div class="mb-3">
                            <label for="editCategoryName" class="form-label">Category Name</label>
                            <input type="text" class="form-control" id="editCategoryName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="editCategoryStatus" class="form-label">Status</label>
                            <select class="form-control" id="editCategoryStatus" name="status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-add flex-grow-1">Update Category</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Search functionality
        document.getElementById('searchCategories').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('#categoriesTableBody tr');
            
            rows.forEach(row => {
                const name = row.querySelector('.category-name').textContent.toLowerCase();
                const matchesSearch = name.includes(searchTerm);
                row.style.display = matchesSearch ? '' : 'none';
            });
        });

        // Add Category Form
        document.getElementById('addCategoryForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            formData.append('action', 'create');
            
            fetch('includes/process_categories.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('addCategoryModal'));
                    if (modal) modal.hide();
                    setTimeout(() => {
                        showSuccess(data.message);
                        setTimeout(() => location.reload(), 1500);
                    }, 400);
                } else {
                    showError(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('An error occurred while creating the category.');
            });
        });

        // Edit Category
        function editCategory(categoryID, name, status) {
            document.getElementById('editCategoryID').value = categoryID;
            document.getElementById('editCategoryName').value = name;
            document.getElementById('editCategoryStatus').value = status;
            
            new bootstrap.Modal(document.getElementById('editCategoryModal')).show();
        }

        // Edit Category Form
        document.getElementById('editCategoryForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            formData.append('action', 'update');
            
            fetch('includes/process_categories.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editCategoryModal'));
                    if (modal) modal.hide();
                    setTimeout(() => {
                        showSuccess(data.message);
                        setTimeout(() => location.reload(), 1500);
                    }, 400);
                } else {
                    showError(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('An error occurred while updating the category.');
            });
        });

        // Toggle Status
        function toggleStatus(categoryID) {
            if (confirm('Are you sure you want to toggle this category status?')) {
                const formData = new FormData();
                formData.append('action', 'toggle_status');
                formData.append('categoryID', categoryID);
                
                fetch('includes/process_categories.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showSuccess(data.message);
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showError(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showError('An error occurred while toggling the status.');
                });
            }
        }

        // Delete Category
        function deleteCategory(categoryID, name) {
            if (confirm(`Are you sure you want to delete the category "${name}"?`)) {
                const formData = new FormData();
                formData.append('action', 'delete');
                formData.append('categoryID', categoryID);
                
                fetch('includes/process_categories.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showSuccess(data.message);
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showError(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showError('An error occurred while deleting the category.');
                });
            }
        }
    </script>
</body>
</html> 