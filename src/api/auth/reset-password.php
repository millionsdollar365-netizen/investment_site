<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * API: Complete password reset with token (JSON)
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/response.php';
require_once __DIR__ . '/../../includes/validation.php';
require_once __DIR__ . '/../../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    error('Method not allowed', null, 405);
}

require_once __DIR__ . '/../../includes/security.php';
Security::requireCsrf();

$token = trim($_POST['token'] ?? '');
$password = $_POST['password'] ?? '';
$password_confirm = $_POST['password_confirm'] ?? '';

if (!Validator::required($token)) {
    error('Reset token is required');
}

if (!Validator::minLength($password, 8)) {
    error('Password must be at least 8 characters');
}

if ($password !== $password_confirm) {
    error('Passwords do not match');
}

$result = resetPassword($token, $password);

if (!empty($result['success'])) {
    success($result['message']);
}

error($result['message'] ?? 'Password reset failed', null, 400);
