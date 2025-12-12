// ===== MAIN JAVASCRIPT FILE =====

// Document Ready
$(document).ready(function () {

    // Back to Top Button
    const backToTop = $('#backToTop');

    $(window).scroll(function () {
        if ($(this).scrollTop() > 300) {
            backToTop.addClass('show');
        } else {
            backToTop.removeClass('show');
        }
    });

    backToTop.click(function (e) {
        e.preventDefault();
        $('html, body').animate({ scrollTop: 0 }, 500);
        return false;
    });

    // Tooltip Initialization
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Popover Initialization
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });

    // Form Validation Feedback
    $('.needs-validation').on('submit', function (event) {
        if (!this.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        $(this).addClass('was-validated');
    });

    // Password Visibility Toggle
    $('.toggle-password').click(function () {
        const input = $(this).closest('.input-group').find('input');
        const type = input.attr('type') === 'password' ? 'text' : 'password';
        input.attr('type', type);
        $(this).toggleClass('fa-eye fa-eye-slash');
    });

    // Course Card Hover Effects
    $('.course-card').hover(
        function () {
            $(this).find('.card-img-top').css('transform', 'scale(1.05)');
        },
        function () {
            $(this).find('.card-img-top').css('transform', 'scale(1)');
        }
    );

    // Notification Mark as Read
    $('.notification-item').click(function () {
        $(this).addClass('read');
        const badge = $('.notification-badge');
        let count = parseInt(badge.text());
        if (count > 0) {
            badge.text(count - 1);
            if (count - 1 === 0) {
                badge.hide();
            }
        }
    });

    // Auto-hide Alerts
    $('.alert-auto-dismiss').delay(3000).fadeOut('slow');

    // Lazy Loading Images
    if ('loading' in HTMLImageElement.prototype) {
        const images = document.querySelectorAll('img[loading="lazy"]');
        images.forEach(img => {
            img.src = img.dataset.src;
        });
    }

    // Dynamic Year in Footer
    $('#current-year').text(new Date().getFullYear());

    // Quick Search Modal
    $('#quickSearchBtn').click(function () {
        $('#quickSearchModal').modal('show');
    });

    // Smooth Scroll for Anchor Links
    $('a[href^="#"]').on('click', function (e) {
        if (this.hash !== "") {
            e.preventDefault();
            const hash = this.hash;
            $('html, body').animate({
                scrollTop: $(hash).offset().top - 80
            }, 800);
        }
    });

    // Course Filter Toggle
    $('.filter-toggle').click(function () {
        $('.filter-sidebar').toggleClass('show');
    });

    // Progress Bar Animation
    $('.progress-bar').each(function () {
        const value = $(this).attr('aria-valuenow');
        $(this).css('width', value + '%');
    });

    // Character Counter for Textareas
    $('.char-counter').each(function () {
        const textarea = $(this).closest('.form-group').find('textarea');
        const counter = $(this);

        textarea.on('input', function () {
            const length = $(this).val().length;
            const maxLength = $(this).attr('maxlength');
            counter.text(length + '/' + maxLength);

            if (length > maxLength * 0.9) {
                counter.addClass('text-danger');
            } else {
                counter.removeClass('text-danger');
            }
        });
    });

    // Theme Toggle (Dark/Light Mode)
    $('#themeToggle').click(function () {
        $('html').attr('data-bs-theme',
            $('html').attr('data-bs-theme') === 'dark' ? 'light' : 'dark'
        );
        $(this).find('i').toggleClass('fa-moon fa-sun');
    });

    // Initialize Counters
    if ($.isFunction($.fn.countTo)) {
        $('.counter').countTo({
            speed: 2000,
            refreshInterval: 50
        });
    }
});

// ===== DEBOUNCE FUNCTION =====
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// ===== THROTTLE FUNCTION =====
function throttle(func, limit) {
    let inThrottle;
    return function () {
        const args = arguments;
        const context = this;
        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}

// ===== API CALLS =====
function fetchCourseDetails(courseId) {
    return $.ajax({
        url: `/api/courses/${courseId}`,
        method: 'GET',
        dataType: 'json'
    });
}

function enrollInCourse(courseId) {
    return $.ajax({
        url: `/api/enrollments`,
        method: 'POST',
        data: { course_id: courseId },
        dataType: 'json'
    });
}

// ===== NOTIFICATION SYSTEM =====
function showNotification(message, type = 'info') {
    const alertClass = {
        'success': 'alert-success',
        'error': 'alert-danger',
        'warning': 'alert-warning',
        'info': 'alert-info'
    }[type] || 'alert-info';

    const notification = $(
        `<div class="alert ${alertClass} alert-dismissible fade show position-fixed" 
              style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            ${message}
        </div>`
    );

    $('body').append(notification);

    setTimeout(() => {
        notification.alert('close');
    }, 5000);
}

// ===== FILE UPLOAD PREVIEW =====
function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
            $(`#${previewId}`).attr('src', e.target.result).show();
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// ===== FORM SUBMISSION HANDLER =====
function handleFormSubmit(formId, successCallback, errorCallback) {
    $(`#${formId}`).on('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();

        submitBtn.prop('disabled', true);
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Đang xử lý...');

        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (successCallback) successCallback(response);
            },
            error: function (xhr) {
                if (errorCallback) errorCallback(xhr);
            },
            complete: function () {
                submitBtn.prop('disabled', false);
                submitBtn.html(originalText);
            }
        });
    });
}