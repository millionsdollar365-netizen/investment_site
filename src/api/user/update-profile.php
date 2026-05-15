<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * API: Update user profile (JSON)
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

$user_id = getCurrentUserId();
$db = Database::getInstance();

$first_name = trim($_POST['first_name'] ?? '');
$last_name = trim($_POST['last_name'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$bio = trim($_POST['bio'] ?? '');

if (!Validator::required($first_name) || !Validator::required($last_name)) {
    error('First name and last name are required');
}

if ($phone !== '' && !Validator::regex($phone, '/^\+?[0-9]{7,20}$/')) {
    error('Invalid phone number format');
}

$db->query(
    "UPDATE users SET first_name = ?, last_name = ?, phone = ?, bio = ? WHERE id = ?",
    [$first_name, $last_name, $phone ?: null, $bio ?: null, $user_id]
);

$user = getCurrentUser();
success('Profile updated', ['user' => sanitizeUserForClient($user)]);
