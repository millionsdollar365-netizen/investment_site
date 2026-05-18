<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * API: Admin Change Password (JSON)
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/response.php';
require_once __DIR__ . '/../../includes/validation.php';
require_once __DIR__ . '/../../includes/admin-session.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    error('Method not allowed', null, 405);
}

if (!isAdminSessionValid()) {
    error('Authentication required', null, 401);
}

$admin_id = getAdminUserId();
$db = Database::getInstance();

$current_password = $_POST['current_password'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Validation
if (!Validator::required($current_password)) {
    error('Current password is required');
}

if (!Validator::required($new_password)) {
    error('New password is required');
}

if (!Validator::required($confirm_password)) {
    error('Password confirmation is required');
}

if ($new_password !== $confirm_password) {
    error('Passwords do not match');
}

if (strlen($new_password) < 8) {
    error('Password must be at least 8 characters');
}

// Get current admin
$admin = $db->fetchOne("SELECT * FROM admin_users WHERE id = ?", [$admin_id]);

if (!$admin) {
    error('Admin not found');
}

// Verify current password
if (!password_verify($current_password, $admin['password_hash'])) {
    error('Current password is incorrect');
}

// Update password
$new_hash = password_hash($new_password, PASSWORD_BCRYPT, ['cost' => 12]);

$db->query(
    "UPDATE admin_users SET password_hash = ? WHERE id = ?",
    [$new_hash, $admin_id]
);

success('Password changed successfully');
