/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * Core Application JavaScript
 */

// ==================== UTILITY FUNCTIONS ====================

/**
 * Format currency for display
 */
function formatCurrency(amount) {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(amount);
}

/**
 * Format date for display
 */
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

/**
 * Show SweetAlert2 notification
 */
function showAlert(message, type = 'info') {
    const icons = {
        success: 'success',
        error: 'error',
        warning: 'warning',
        info: 'info'
    };
    Swal.fire({
        icon: icons[type] || 'info',
        title: type.charAt(0).toUpperCase() + type.slice(1),
        text: message,
        timer: type === 'success' ? 2500 : undefined,
        showConfirmButton: type !== 'success'
    });
}

/**
 * Handle API errors
 */
function handleError(error) {
    console.error('Error:', error);
    showAlert('An error occurred. Please try again.', 'error');
}

/**
 * Make API request
 */
async function apiCall(endpoint, method = 'GET', data = null) {
    try {
        const options = {
            method: method,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        };

        if (method !== 'GET' && data) {
            if (data instanceof FormData) {
                options.body = data;
                delete options.headers['Content-Type'];
            } else {
                options.body = new URLSearchParams(data);
            }
        }

        const response = await fetch(endpoint, options);
        const result = await response.json();

        if (!response.ok && !result.success) {
            handleError(result.message || 'Request failed');
            return null;
        }

        return result;
    } catch (error) {
        handleError(error);
        return null;
    }
}

/**
 * Check session and redirect if not logged in
 */
async function checkSession() {
    const result = await apiCall('/api/auth/check-session.php');
    if (!result || !result.data.logged_in) {
        window.location.href = '/login.php';
        return false;
    }
    return true;
}

/**
 * Initialize form submission handler
 */
function setupFormHandler(formId, endpoint, successCallback = null, errorCallback = null) {
    const form = document.getElementById(formId);
    if (!form) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(form);
        const result = await apiCall(endpoint, 'POST', formData);

        if (result && result.success) {
            showAlert(result.message, 'success');
            if (successCallback) {
                successCallback(result.data);
            }
        } else if (errorCallback) {
            errorCallback(result);
        }
    });
}

/**
 * Setup input validation
 */
function setupInputValidation() {
    const inputs = document.querySelectorAll('input[data-validate]');
    inputs.forEach(input => {
        input.addEventListener('blur', () => {
            validateInput(input);
        });
    });
}

/**
 * Validate single input
 */
function validateInput(input) {
    const type = input.dataset.validate;
    let isValid = true;
    let errorMessage = '';

    switch (type) {
        case 'email':
            isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input.value);
            errorMessage = 'Invalid email address';
            break;
        case 'phone':
            isValid = /^\d{10,}$/.test(input.value.replace(/\D/g, ''));
            errorMessage = 'Invalid phone number';
            break;
        case 'password':
            isValid = input.value.length >= 8;
            errorMessage = 'Password must be at least 8 characters';
            break;
        case 'number':
            isValid = !isNaN(input.value) && input.value > 0;
            errorMessage = 'Must be a valid number';
            break;
    }

    if (!isValid && input.value) {
        input.classList.add('error');
        showAlert(errorMessage, 'warning');
    } else {
        input.classList.remove('error');
    }

    return isValid;
}

/**
 * Parse URL parameters
 */
function getUrlParam(param) {
    const params = new URLSearchParams(window.location.search);
    return params.get(param);
}

/**
 * Sanitize HTML to prevent XSS
 */
function sanitizeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}

/**
 * Show/hide loading spinner
 */
function showSpinner(containerId) {
    const container = document.getElementById(containerId);
    if (container) {
        container.innerHTML = '<div class="spinner"></div>';
    }
}

/**
 * Debounce function for search/filter inputs
 */
function debounce(func, delay = 300) {
    let timeoutId;
    return function(...args) {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => func(...args), delay);
    };
}

/**
 * Copy to clipboard
 */
function copyToClipboard(text, message = 'Copied to clipboard') {
    navigator.clipboard.writeText(text).then(() => {
        showAlert(message, 'success');
    });
}

// ==================== DOM READY ====================

document.addEventListener('DOMContentLoaded', () => {
    setupInputValidation();

    // Add container for alerts if not exists
    if (!document.querySelector('.alert-container')) {
        const container = document.createElement('div');
        container.className = 'alert-container fixed top-4 right-4 z-50 max-w-md';
        document.body.appendChild(container);
    }
});

// ==================== EXPORT FOR MODULE USE ====================
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        formatCurrency,
        formatDate,
        showAlert,
        apiCall,
        checkSession,
        setupFormHandler,
        validateInput,
        getUrlParam,
        sanitizeHtml,
        showSpinner,
        debounce,
        copyToClipboard
    };
}
