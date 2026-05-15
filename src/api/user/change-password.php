<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * API: Change user password (JSON)
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/response.php';
require_once __DIR__ . '/../../includes/validation.php';
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    error('Method not allowed', null, 405);
}

if (!isSessionValid()) {
    error('Authentication required', null, 401);
}

$current_password = $_POST['current_password'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$new_password_confirm = $_POST['new_password_confirm'] ?? '';

if (!Validator::required($current_password)) {
    error('Current password is required');
}

if (!Validator::minLength($new_password, 8)) {
    error('New password must be at least 8 characters');
}

if ($new_password !== $new_password_confirm) {
    error('New passwords do not match');
}

$result = changeUserPassword(getCurrentUserId(), $current_password, $new_password);

if (!empty($result['success'])) {
    success($result['message']);
}

error($result['message'] ?? 'Password change failed');
