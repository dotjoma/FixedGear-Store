<?php
// Global notification system for admin
function showNotification($message, $type = 'success', $duration = 5000) {
    $icon = $type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
    $bgClass = $type === 'success' ? 'bg-success' : 'bg-danger';
    
    echo "<div class='admin-notification {$bgClass}' data-duration='{$duration}'>
            <div class='notification-content'>
                <i class='fas {$icon} me-2'></i>
                <span>{$message}</span>
                <button type='button' class='btn-close btn-close-white ms-auto' onclick='this.parentElement.parentElement.remove()'></button>
            </div>
          </div>";
}

// Function to show notification via JavaScript
function showNotificationJS($message, $type = 'success', $duration = 5000) {
    echo "<script>
        showNotification('{$message}', '{$type}', {$duration});
    </script>";
}
?>

<style>
.admin-notifications-container {
    position: fixed;
    top: 80px;
    right: 20px;
    z-index: 9999;
    max-width: 400px;
}

.admin-notification {
    border: 1px solid;
    border-radius: 8px;
    padding: 12px 16px;
    margin-bottom: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    animation: slideInRight 0.3s ease-out;
    backdrop-filter: blur(10px);
    color: #fff;
}

.admin-notification.bg-success {
    background: rgba(25, 135, 84, 0.95) !important;
    border-color: #198754;
}

.admin-notification.bg-danger {
    background: rgba(220, 53, 69, 0.95) !important;
    border-color: #dc3545;
}

.notification-content {
    display: flex;
    align-items: center;
    gap: 8px;
}

.notification-content i {
    font-size: 1.1rem;
}

.notification-content .btn-close {
    opacity: 0.7;
    transition: opacity 0.2s;
}

.notification-content .btn-close:hover {
    opacity: 1;
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideOutRight {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}

.admin-notification.removing {
    animation: slideOutRight 0.3s ease-in forwards;
}
</style>

<div class="admin-notifications-container" id="notificationsContainer"></div>

<script>
// Global notification function
function showNotification(message, type = 'success', duration = 5000) {
    const container = document.getElementById('notificationsContainer');
    const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
    const bgClass = type === 'success' ? 'bg-success' : 'bg-danger';
    
    const notification = document.createElement('div');
    notification.className = `admin-notification ${bgClass}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas ${icon} me-2"></i>
            <span>${message}</span>
            <button type="button" class="btn-close btn-close-white ms-auto" onclick="this.parentElement.parentElement.remove()"></button>
        </div>
    `;
    
    container.appendChild(notification);
    
    // Auto remove after duration
    if (duration > 0) {
        setTimeout(() => {
            if (notification.parentElement) {
                notification.classList.add('removing');
                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.remove();
                    }
                }, 300);
            }
        }, duration);
    }
}

// Success notification shortcut
function showSuccess(message, duration = 5000) {
    showNotification(message, 'success', duration);
}

// Error notification shortcut
function showError(message, duration = 5000) {
    showNotification(message, 'error', duration);
}

// Clear all notifications
function clearAllNotifications() {
    const container = document.getElementById('notificationsContainer');
    container.innerHTML = '';
}
</script> 