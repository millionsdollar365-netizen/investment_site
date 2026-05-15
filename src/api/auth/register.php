<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * API: User registration (JSON)
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

$first_name = trim($_POST['first_name'] ?? '');
$last_name = trim($_POST['last_name'] ?? '');
$email = strtolower(trim($_POST['email'] ?? ''));
$password = $_POST['password'] ?? '';
$referral_code = trim($_POST['referral_code'] ?? '');
$referral_code = $referral_code !== '' ? $referral_code : null;

if (!Validator::required($first_name) || !Validator::required($last_name)) {
    error('First name and last name are required');
}

if (!Validator::email($email)) {
    error('Please enter a valid email address');
}

if (!Validator::minLength($password, 8)) {
    error('Password must be at least 8 characters');
}

if ($referral_code !== null && !Validator::maxLength($referral_code, 32)) {
    error('Invalid referral code');
}

$result = registerUser($first_name, $last_name, $email, $password, $referral_code);

if (!empty($result['success'])) {
    success($result['message'], ['user_id' => (int) $result['user_id']]);
}

error($result['message'] ?? 'Registration failed');
