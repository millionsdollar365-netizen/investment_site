<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * User Session Handler
 */

require_once __DIR__ . '/config.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Get current logged-in user ID
 */
function getCurrentUserId() {
    return $_SESSION['user_id'] ?? null;
}

/**
 * Get current logged-in user data
 */
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    $db = Database::getInstance();
    return $db->fetchOne(
        "SELECT * FROM users WHERE id = ? AND status = 'active'",
        [getCurrentUserId()]
    );
}

/**
 * Login user
 */
function loginUser($user_id, $user_data = null) {
    $_SESSION['user_id'] = $user_id;
    $_SESSION['user_email'] = $user_data['email'] ?? '';
    $_SESSION['login_time'] = time();
    $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'] ?? '';
}

/**
 * Logout user
 */
function logoutUser() {
    session_destroy();
    setcookie(SESSION_NAME, '', time() - 3600, '/');
}

/**
 * Check session validity
 */
function isSessionValid() {
    if (!isLoggedIn()) {
        return false;
    }
    
    // Check session timeout
    if (isset($_SESSION['login_time'])) {
        $elapsed = time() - $_SESSION['login_time'];
        if ($elapsed > SESSION_TIMEOUT) {
            logoutUser();
            return false;
        }
    }
    
    // Check user still exists and is active
    $user = getCurrentUser();
    if (!$user) {
        logoutUser();
        return false;
    }
    
    // Update login time (refresh session)
    $_SESSION['login_time'] = time();
    
    return true;
}

/**
 * Require login (redirect if not logged in)
 */
function requireLogin() {
    if (!isSessionValid()) {
        header('Location: ' . SITE_URL . '/login.php');
        exit;
    }
}

/**
 * Require logout (redirect if logged in)
 */
function requireLogout() {
    if (isLoggedIn()) {
        header('Location: ' . SITE_URL . '/dashboard/');
        exit;
    }
}

/**
 * Store flash message
 */
function setFlash($message, $type = 'info') {
    $_SESSION['flash_message'] = $message;
    $_SESSION['flash_type'] = $type;
}

/**
 * Get and clear flash message
 */
function getFlash() {
    $message = $_SESSION['flash_message'] ?? null;
    $type = $_SESSION['flash_type'] ?? 'info';
    
    if ($message) {
        unset($_SESSION['flash_message']);
        unset($_SESSION['flash_type']);
    }
    
    return ['message' => $message, 'type' => $type];
}
?>
