/*!
    * Start Bootstrap - SB Admin v7.0.7 (https://startbootstrap.com/template/sb-admin)
    * Copyright 2013-2023 Start Bootstrap
    * Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-sb-admin/blob/master/LICENSE)
    */
    // 
// Scripts
// 

// Ensure Bootstrap dropdowns work properly
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all dropdowns
    var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
    var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
        return new bootstrap.Dropdown(dropdownToggleEl);
    });

    // Debug: Check if Bootstrap is loaded
    if (typeof bootstrap !== 'undefined') {
        console.log('Bootstrap is loaded successfully');
    } else {
        console.error('Bootstrap is not loaded');
    }

    // Debug: Check if dropdown elements exist
    var userDropdown = document.getElementById('userDropdown');
    if (userDropdown) {
        console.log('User dropdown found');
        userDropdown.addEventListener('click', function(e) {
            console.log('User dropdown clicked');
        });
    } else {
        console.error('User dropdown not found');
    }
});

// Password visibility toggle for login/register forms
document.addEventListener('DOMContentLoaded', function() {
    const passwordFields = document.querySelectorAll('input[type="password"]');
    const eyeIcons = document.querySelectorAll('.input-group-text i.fa-eye, .input-group-text i.fa-eye-slash');
    
    eyeIcons.forEach((icon, index) => {
        icon.addEventListener('click', function() {
            const passwordField = passwordFields[index];
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });
}); 