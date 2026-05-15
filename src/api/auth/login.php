<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * API: User login (JSON, establishes session)
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/response.php';
require_once __DIR__ . '/../../includes/validation.php';
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    error('Method not allowed', null, 405);
}

require_once __DIR__ . '/../../includes/security.php';
Security::requireCsrf();

$email = strtolower(trim($_POST['email'] ?? ''));
$password = $_POST['password'] ?? '';

if (!Validator::email($email) || !Validator::required($password)) {
    error('Email and password are required');
}

$result = authenticateUser($email, $password);

if (empty($result['success'])) {
    error($result['message'] ?? 'Login failed', null, 401);
}

loginUser((int) $result['user']['id'], $result['user']);

$user = sanitizeUserForClient($result['user']);
success($result['message'] ?? 'Login successful', ['user' => $user]);
