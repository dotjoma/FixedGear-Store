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

// Get users from database
$stmt = $conn->prepare("SELECT userID, username, email, fname, lname, role, created_at FROM users ORDER BY created_at DESC");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Management | Admin Dashboard</title>
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
            justify-content: between;
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
        
        .users-table {
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
        
        .user-role {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .role-admin {
            background: #e6ff00;
            color: #181818;
        }
        
        .role-user {
            background: #333;
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

    <!-- Main Content -->
    <div class="main-content">
        <div class="page-header">
            <h1 class="page-title">USERS MANAGEMENT</h1>
            <button class="btn btn-add">
                <i class="fas fa-plus me-2"></i>Add User
            </button>
        </div>

        <!-- Stats Cards -->
        <div class="stats-cards">
            <div class="stat-card">
                <div class="stat-number"><?php echo count($users); ?></div>
                <div class="stat-label">Total Users</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo count(array_filter($users, function($user) { return $user['role'] === 'admin'; })); ?></div>
                <div class="stat-label">Admins</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo count(array_filter($users, function($user) { return $user['role'] === 'user'; })); ?></div>
                <div class="stat-label">Regular Users</div>
            </div>
        </div>

        <!-- Search Box -->
        <div class="mb-3">
            <input type="text" class="form-control search-box" placeholder="Search users..." id="searchUsers">
        </div>

        <!-- Users Table -->
        <div class="users-table">
            <table class="table">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td>#<?php echo htmlspecialchars($user['userID']); ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td>
                            <?php 
                            $fullName = trim($user['fname'] . ' ' . $user['lname']);
                            echo $fullName ? htmlspecialchars($fullName) : 'N/A';
                            ?>
                        </td>
                        <td>
                            <span class="user-role role-<?php echo $user['role']; ?>">
                                <?php echo htmlspecialchars($user['role']); ?>
                            </span>
                        </td>
                        <td><?php echo date('M j, Y', strtotime($user['created_at'] ?? 'now')); ?></td>
                        <td>
                            <button class="btn btn-action btn-edit" title="Edit User"
                                data-userid="<?php echo htmlspecialchars($user['userID']); ?>"
                                data-username="<?php echo htmlspecialchars($user['username']); ?>"
                                data-email="<?php echo htmlspecialchars($user['email']); ?>"
                                data-fname="<?php echo htmlspecialchars($user['fname']); ?>"
                                data-lname="<?php echo htmlspecialchars($user['lname']); ?>"
                                data-role="<?php echo htmlspecialchars($user['role']); ?>">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-action btn-delete" title="Delete User">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content bg-dark text-light">
          <form id="addUserForm">
            <div class="modal-header">
              <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label for="add_username" class="form-label">Username</label>
                <input type="text" class="form-control" id="add_username" name="username" required>
              </div>
              <div class="mb-3">
                <label for="add_email" class="form-label">Email</label>
                <input type="email" class="form-control" id="add_email" name="email" required>
              </div>
              <div class="mb-3">
                <label for="add_fname" class="form-label">First Name</label>
                <input type="text" class="form-control" id="add_fname" name="fname">
              </div>
              <div class="mb-3">
                <label for="add_lname" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="add_lname" name="lname">
              </div>
              <div class="mb-3">
                <label for="add_role" class="form-label">Role</label>
                <select class="form-select" id="add_role" name="role" required>
                  <option value="user">User</option>
                  <option value="admin">Admin</option>
                </select>
              </div>
              <div class="mb-3">
                <label for="add_password" class="form-label">Password</label>
                <input type="password" class="form-control" id="add_password" name="password" required>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary">Add User</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content bg-dark text-light">
          <form id="editUserForm">
            <input type="hidden" id="edit_userID" name="userID">
            <div class="modal-header">
              <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label for="edit_username" class="form-label">Username</label>
                <input type="text" class="form-control" id="edit_username" name="username" required>
              </div>
              <div class="mb-3">
                <label for="edit_email" class="form-label">Email</label>
                <input type="email" class="form-control" id="edit_email" name="email" required>
              </div>
              <div class="mb-3">
                <label for="edit_fname" class="form-label">First Name</label>
                <input type="text" class="form-control" id="edit_fname" name="fname">
              </div>
              <div class="mb-3">
                <label for="edit_lname" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="edit_lname" name="lname">
              </div>
              <div class="mb-3">
                <label for="edit_role" class="form-label">Role</label>
                <select class="form-select" id="edit_role" name="role" required>
                  <option value="user">User</option>
                  <option value="admin">Admin</option>
                </select>
              </div>
              <div class="mb-3">
                <label for="edit_password" class="form-label">Password (leave blank to keep current)</label>
                <input type="password" class="form-control" id="edit_password" name="password">
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Delete User Modal -->
    <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content bg-dark text-light">
          <form id="deleteUserForm">
            <input type="hidden" id="delete_userID" name="userID">
            <div class="modal-header">
              <h5 class="modal-title" id="deleteUserModalLabel">Delete User</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <p>Are you sure you want to delete <span id="delete_username" class="fw-bold"></span>?</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-danger">Delete</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/scripts.js"></script>
    
    <script>
        // Search functionality
        document.getElementById('searchUsers').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    </script>

    <script>
document.querySelectorAll('.btn-add').forEach(btn => {
  btn.addEventListener('click', () => {
    new bootstrap.Modal(document.getElementById('addUserModal')).show();
  });
});
document.querySelectorAll('.btn-edit').forEach((btn, i) => {
  btn.addEventListener('click', function() {
    document.getElementById('edit_userID').value = this.getAttribute('data-userid');
    document.getElementById('edit_username').value = this.getAttribute('data-username');
    document.getElementById('edit_email').value = this.getAttribute('data-email');
    document.getElementById('edit_fname').value = this.getAttribute('data-fname');
    document.getElementById('edit_lname').value = this.getAttribute('data-lname');
    document.getElementById('edit_role').value = this.getAttribute('data-role');
    document.getElementById('edit_password').value = '';
    new bootstrap.Modal(document.getElementById('editUserModal')).show();
  });
});
document.querySelectorAll('.btn-delete').forEach((btn, i) => {
  btn.addEventListener('click', function() {
    const row = this.closest('tr');
    document.getElementById('delete_userID').value = row.children[0].textContent.replace('#','').trim();
    document.getElementById('delete_username').textContent = row.children[1].textContent.trim();
    new bootstrap.Modal(document.getElementById('deleteUserModal')).show();
  });
});

// AJAX for Add User
const addUserForm = document.getElementById('addUserForm');
if (addUserForm) {
  addUserForm.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    formData.append('action', 'add');
    fetch('includes/process_users.php', {
      method: 'POST',
      body: formData,
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        const modal = bootstrap.Modal.getInstance(document.getElementById('addUserModal'));
        if (modal) modal.hide();
        setTimeout(() => {
          showSuccess(data.message);
          setTimeout(() => location.reload(), 1500);
        }, 400);
      } else {
        showError(data.message);
      }
    })
    .catch(() => showError('An error occurred while adding the user.'));
  });
}
// AJAX for Edit User
const editUserForm = document.getElementById('editUserForm');
if (editUserForm) {
  editUserForm.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    formData.append('action', 'edit');
    fetch('includes/process_users.php', {
      method: 'POST',
      body: formData,
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        const modal = bootstrap.Modal.getInstance(document.getElementById('editUserModal'));
        if (modal) modal.hide();
        setTimeout(() => {
          showSuccess(data.message);
          setTimeout(() => location.reload(), 1500);
        }, 400);
      } else {
        showError(data.message);
      }
    })
    .catch(() => showError('An error occurred while updating the user.'));
  });
}
// AJAX for Delete User
const deleteUserForm = document.getElementById('deleteUserForm');
if (deleteUserForm) {
  deleteUserForm.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    formData.append('action', 'delete');
    fetch('includes/process_users.php', {
      method: 'POST',
      body: formData,
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        const modal = bootstrap.Modal.getInstance(document.getElementById('deleteUserModal'));
        if (modal) modal.hide();
        setTimeout(() => {
          showSuccess(data.message);
          setTimeout(() => location.reload(), 1500);
        }, 400);
      } else {
        showError(data.message);
      }
    })
    .catch(() => showError('An error occurred while deleting the user.'));
  });
}
</script>
</body>
</html> 