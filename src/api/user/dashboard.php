<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * API: User dashboard summary (JSON)
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/response.php';
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    error('Method not allowed', null, 405);
}

if (!isSessionValid()) {
    error('Authentication required', null, 401);
}

$user_id = getCurrentUserId();
$db = Database::getInstance();

$user = $db->fetchOne("SELECT balance, referral_code FROM users WHERE id = ?", [$user_id]);

$active_investments = (int) $db->fetchOne(
    "SELECT COUNT(*) as count FROM investments WHERE user_id = ? AND status = 'active'",
    [$user_id]
)['count'];

$total_invested = (float) $db->fetchOne(
    "SELECT COALESCE(SUM(amount), 0) as total FROM investments WHERE user_id = ? AND status = 'active'",
    [$user_id]
)['total'];

$referral_count = (int) $db->fetchOne(
    "SELECT COUNT(*) as count FROM referrals WHERE referrer_id = ? AND status = 'active'",
    [$user_id]
)['count'];

$pending_deposits = (float) $db->fetchOne(
    "SELECT COALESCE(SUM(amount), 0) as total FROM deposits WHERE user_id = ? AND status = 'pending'",
    [$user_id]
)['total'];

$pending_withdrawals = (float) $db->fetchOne(
    "SELECT COALESCE(SUM(amount), 0) as total FROM withdrawals WHERE user_id = ? AND status IN ('pending','approved')",
    [$user_id]
)['total'];

$recent_transactions = $db->fetchAll(
    "SELECT type, amount, description, created_at FROM transactions WHERE user_id = ? ORDER BY created_at DESC LIMIT 5",
    [$user_id]
);

success('Dashboard data', [
    'balance' => (float) $user['balance'],
    'active_investments' => $active_investments,
    'total_invested' => $total_invested,
    'referral_code' => $user['referral_code'],
    'referral_count' => $referral_count,
    'pending_deposits' => $pending_deposits,
    'pending_withdrawals' => $pending_withdrawals,
    'recent_transactions' => $recent_transactions,
]);
