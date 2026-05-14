<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * Admin Session Handler
 */

require_once __DIR__ . '/config.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if admin is logged in
 */
function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

/**
 * Get current logged-in admin ID
 */
function getCurrentAdminId() {
    return $_SESSION['admin_id'] ?? null;
}

/**
 * Get current logged-in admin data
 */
function getCurrentAdmin() {
    if (!isAdminLoggedIn()) {
        return null;
    }
    
    $db = Database::getInstance();
    return $db->fetchOne(
        "SELECT * FROM admin_users WHERE id = ? AND status = 'active'",
        [getCurrentAdminId()]
    );
}

/**
 * Login admin
 */
function loginAdmin($admin_id, $admin_data = null) {
    $_SESSION['admin_id'] = $admin_id;
    $_SESSION['admin_username'] = $admin_data['username'] ?? '';
    $_SESSION['admin_role'] = $admin_data['role'] ?? 'admin';
    $_SESSION['admin_login_time'] = time();
    $_SESSION['admin_ip_address'] = $_SERVER['REMOTE_ADDR'] ?? '';
}

/**
 * Logout admin
 */
function logoutAdmin() {
    // Log the logout
    if (isAdminLoggedIn()) {
        $db = Database::getInstance();
        $db->query(
            "INSERT INTO audit_logs (admin_id, action, ip_address) VALUES (?, ?, ?)",
            [getCurrentAdminId(), 'admin_logout', $_SERVER['REMOTE_ADDR'] ?? '']
        );
    }
    
    session_destroy();
    setcookie(SESSION_NAME, '', time() - 3600, '/');
}

/**
 * Check admin session validity
 */
function isAdminSessionValid() {
    if (!isAdminLoggedIn()) {
        return false;
    }
    
    // Check session timeout
    if (isset($_SESSION['admin_login_time'])) {
        $elapsed = time() - $_SESSION['admin_login_time'];
        if ($elapsed > SESSION_TIMEOUT) {
            logoutAdmin();
            return false;
        }
    }
    
    // Check admin still exists and is active
    $admin = getCurrentAdmin();
    if (!$admin) {
        logoutAdmin();
        return false;
    }
    
    // Check IP address hasn't changed (security)
    if (isset($_SESSION['admin_ip_address']) && $_SESSION['admin_ip_address'] !== ($_SERVER['REMOTE_ADDR'] ?? '')) {
        logoutAdmin();
        return false;
    }
    
    // Update login time
    $_SESSION['admin_login_time'] = time();
    
    return true;
}

/**
 * Require admin login (redirect if not logged in)
 */
function requireAdminLogin() {
    if (!isAdminSessionValid()) {
        header('Location: ' . SITE_URL . '/admin/login.php');
        exit;
    }
}

/**
 * Require admin logout (redirect if logged in)
 */
function requireAdminLogout() {
    if (isAdminLoggedIn()) {
        header('Location: ' . SITE_URL . '/admin/');
        exit;
    }
}

/**
 * Check admin role
 */
function hasAdminRole($required_role = 'admin') {
    if (!isAdminSessionValid()) {
        return false;
    }
    
    $role = $_SESSION['admin_role'] ?? '';
    
    // Super admin can do everything
    if ($role === 'super_admin') {
        return true;
    }
    
    // Check specific role
    return $role === $required_role;
}

/**
 * Require specific admin role
 */
function requireAdminRole($required_role = 'admin') {
    if (!hasAdminRole($required_role)) {
        http_response_code(403);
        die('Access denied. Required role: ' . $required_role);
    }
}
?>
