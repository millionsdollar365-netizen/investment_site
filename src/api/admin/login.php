<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * API: Admin login (JSON, establishes admin session)
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/response.php';
require_once __DIR__ . '/../../includes/validation.php';
require_once __DIR__ . '/../../includes/admin-session.php';
require_once __DIR__ . '/../../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    error('Method not allowed', null, 405);
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if (!Validator::required($username) || !Validator::required($password)) {
    error('Username and password are required');
}

$result = authenticateAdmin($username, $password);

if (empty($result['success'])) {
    error($result['message'] ?? 'Login failed', null, 401);
}

loginAdmin((int) $result['admin']['id'], $result['admin']);

$admin = $result['admin'];
unset($admin['password_hash']);

success($result['message'] ?? 'Login successful', ['admin' => $admin]);
