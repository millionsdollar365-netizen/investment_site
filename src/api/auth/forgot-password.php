<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * API: Request password reset email (JSON)
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/response.php';
require_once __DIR__ . '/../../includes/validation.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/mail.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    error('Method not allowed', null, 405);
}

$email = strtolower(trim($_POST['email'] ?? ''));

if (!Validator::email($email)) {
    error('Please enter a valid email address');
}

$db = Database::getInstance();
$user = $db->fetchOne(
    "SELECT id, first_name, email FROM users WHERE email = ? AND status = 'active'",
    [$email]
);

$generic = 'If an account exists for this email, password reset instructions have been sent.';

if ($user) {
    $token_result = generatePasswordResetToken($email);
    if (!empty($token_result['success']) && !empty($token_result['token'])) {
        $reset_link = rtrim(SITE_URL, '/') . '/reset-password.php?token=' . urlencode($token_result['token']);
        Mail::sendPasswordReset($user['email'], $user['first_name'], $reset_link);
    }
}

success($generic);
