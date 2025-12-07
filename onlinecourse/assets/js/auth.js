// ===============================================
// AUTH.JS - JavaScript cho trang đăng nhập/đăng ký
// ===============================================

// Toggle Password Visibility
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.querySelector('.toggle-password .eye-icon');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.innerHTML = `
            <path d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z"/>
            <path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z"/>
        `;
    } else {
        passwordInput.type = 'password';
        eyeIcon.innerHTML = `
            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"/>
        `;
    }
}

// Toggle Confirm Password (for register page)
function toggleConfirmPassword() {
    const confirmPasswordInput = document.getElementById('confirm_password');
    const eyeIcon = document.querySelector('.toggle-confirm-password .eye-icon');

    if (confirmPasswordInput.type === 'password') {
        confirmPasswordInput.type = 'text';
        eyeIcon.innerHTML = `
            <path d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z"/>
            <path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z"/>
        `;
    } else {
        confirmPasswordInput.type = 'password';
        eyeIcon.innerHTML = `
            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"/>
        `;
    }
}

// Form Validation
document.addEventListener('DOMContentLoaded', function () {
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');

    // Login Form Validation
    if (loginForm) {
        loginForm.addEventListener('submit', function (e) {
            const email = document.getElementById('email');
            const password = document.getElementById('password');
            let isValid = true;

            // Clear previous errors
            clearErrors();

            // Email validation
            if (!email.value.trim()) {
                showError(email, 'Email không được để trống');
                isValid = false;
            } else if (!isValidEmail(email.value)) {
                showError(email, 'Email không hợp lệ');
                isValid = false;
            }

            // Password validation
            if (!password.value.trim()) {
                showError(password, 'Mật khẩu không được để trống');
                isValid = false;
            } else if (password.value.length < 6) {
                showError(password, 'Mật khẩu phải có ít nhất 6 ký tự');
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
            }
        });
    }

    // Register Form Validation
    if (registerForm) {
        registerForm.addEventListener('submit', function (e) {
            const fullname = document.getElementById('fullname');
            const username = document.getElementById('username');
            const email = document.getElementById('email');
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirm_password');
            let isValid = true;

            // Clear previous errors
            clearErrors();

            // Fullname validation
            if (!fullname.value.trim()) {
                showError(fullname, 'Họ và tên không được để trống');
                isValid = false;
            }

            // Username validation
            if (!username.value.trim()) {
                showError(username, 'Tên đăng nhập không được để trống');
                isValid = false;
            } else if (username.value.length < 3) {
                showError(username, 'Tên đăng nhập phải có ít nhất 3 ký tự');
                isValid = false;
            }

            // Email validation
            if (!email.value.trim()) {
                showError(email, 'Email không được để trống');
                isValid = false;
            } else if (!isValidEmail(email.value)) {
                showError(email, 'Email không hợp lệ');
                isValid = false;
            }

            // Password validation
            if (!password.value.trim()) {
                showError(password, 'Mật khẩu không được để trống');
                isValid = false;
            } else if (password.value.length < 6) {
                showError(password, 'Mật khẩu phải có ít nhất 6 ký tự');
                isValid = false;
            }

            // Confirm password validation
            if (!confirmPassword.value.trim()) {
                showError(confirmPassword, 'Vui lòng xác nhận mật khẩu');
                isValid = false;
            } else if (password.value !== confirmPassword.value) {
                showError(confirmPassword, 'Mật khẩu xác nhận không khớp');
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
            }
        });
    }
});

// Helper Functions
function isValidEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function showError(input, message) {
    const formGroup = input.closest('.form-group');
    const errorElement = formGroup.querySelector('.error-message') || document.createElement('span');

    errorElement.className = 'error-message';
    errorElement.textContent = message;

    if (!formGroup.querySelector('.error-message')) {
        formGroup.appendChild(errorElement);
    }

    input.classList.add('error');
    input.style.borderColor = '#EF4444';
}

function clearErrors() {
    const errorMessages = document.querySelectorAll('.error-message');
    errorMessages.forEach(error => error.remove());

    const errorInputs = document.querySelectorAll('.form-control.error');
    errorInputs.forEach(input => {
        input.classList.remove('error');
        input.style.borderColor = '';
    });
}

// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function () {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.remove();
            }, 500);
        }, 5000);
    });
});