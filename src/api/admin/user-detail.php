<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * API: Admin single user detail (JSON)
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/response.php';
require_once __DIR__ . '/../../includes/admin-session.php';
require_once __DIR__ . '/../../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    error('Method not allowed', null, 405);
}

if (!isAdminSessionValid()) {
    error('Authentication required', null, 401);
}

$user_id = (int) ($_GET['id'] ?? 0);

if ($user_id <= 0) {
    error('User ID is required');
}

$db = Database::getInstance();

$user = $db->fetchOne("SELECT * FROM users WHERE id = ?", [$user_id]);

if (!$user) {
    error('User not found', null, 404);
}

$investments = $db->fetchAll(
    "SELECT i.*, p.name as plan_name FROM investments i
     JOIN investment_plans p ON p.id = i.plan_id
     WHERE i.user_id = ? ORDER BY i.created_at DESC",
    [$user_id]
);

$deposits = $db->fetchAll(
    "SELECT * FROM deposits WHERE user_id = ? ORDER BY created_at DESC",
    [$user_id]
);

$withdrawals = $db->fetchAll(
    "SELECT * FROM withdrawals WHERE user_id = ? ORDER BY created_at DESC",
    [$user_id]
);

$transactions = $db->fetchAll(
    "SELECT * FROM transactions WHERE user_id = ? ORDER BY created_at DESC LIMIT 50",
    [$user_id]
);

$referrals = $db->fetchAll(
    "SELECT r.*, u.first_name, u.last_name, u.email FROM referrals r
     JOIN users u ON u.id = r.referred_id
     WHERE r.referrer_id = ?",
    [$user_id]
);

success('User detail', [
    'user' => sanitizeUserForClient($user),
    'investments' => $investments,
    'deposits' => $deposits,
    'withdrawals' => $withdrawals,
    'transactions' => $transactions,
    'referrals' => $referrals,
]);
