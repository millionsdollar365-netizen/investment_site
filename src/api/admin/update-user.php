<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * API: Admin update user (JSON)
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/response.php';
require_once __DIR__ . '/../../includes/validation.php';
require_once __DIR__ . '/../../includes/admin-session.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    error('Method not allowed', null, 405);
}

if (!isAdminSessionValid()) {
    error('Authentication required', null, 401);
}

$db = Database::getInstance();

$user_id = (int) ($_POST['id'] ?? 0);

if ($user_id <= 0) {
    error('User ID is required');
}

$user = $db->fetchOne("SELECT * FROM users WHERE id = ?", [$user_id]);

if (!$user) {
    error('User not found', null, 404);
}

$old_values = ['status' => $user['status'], 'balance' => $user['balance'], 'interest_balance' => $user['interest_balance']];

$status = trim($_POST['status'] ?? '');
$balance = $_POST['balance'] ?? null;
$interest_balance = $_POST['interest_balance'] ?? null;

$updates = [];
$params = [];

if ($status !== '' && in_array($status, ['active', 'suspended', 'banned'])) {
    $updates[] = 'status = ?';
    $params[] = $status;
}

if ($balance !== null && is_numeric($balance)) {
    $updates[] = 'balance = ?';
    $params[] = (float) $balance;
}

if ($interest_balance !== null && is_numeric($interest_balance)) {
    $updates[] = 'interest_balance = ?';
    $params[] = (float) $interest_balance;
}

if (empty($updates)) {
    error('No valid fields to update');
}

$params[] = $user_id;

$db->query("UPDATE users SET " . implode(', ', $updates) . " WHERE id = ?", $params);

$new_values = ['status' => $status ?: $user['status'], 'balance' => $balance ?? $user['balance'], 'interest_balance' => $interest_balance ?? $user['interest_balance']];

auditLog('admin_update_user', 'users', $user_id, $old_values, $new_values);

success('User updated');
