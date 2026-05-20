<?php
/**
 * API: User earnings/interest history (JSON)
 * Returns total profit earned + list of profit transactions
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/response.php';
require_once __DIR__ . '/../../includes/session.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    error('Method not allowed', null, 405);
}

if (!isSessionValid()) {
    error('Authentication required', null, 401);
}

$user_id = getCurrentUserId();
$db = Database::getInstance();

$total_earned = (float) $db->fetchOne(
    "SELECT COALESCE(SUM(amount), 0) as total FROM transactions WHERE user_id = ? AND type = 'profit'",
    [$user_id]
)['total'];

$earnings = $db->fetchAll(
    "SELECT t.id, t.amount, t.description, t.created_at, i.plan_id, p.name as plan_name
     FROM transactions t
     LEFT JOIN investments i ON i.id = t.reference_id AND t.reference_table = 'investments'
     LEFT JOIN investment_plans p ON p.id = i.plan_id
     WHERE t.user_id = ? AND t.type = 'profit'
     ORDER BY t.created_at DESC
     LIMIT 50",
    [$user_id]
);

success('Earnings', [
    'total_earned' => $total_earned,
    'earnings' => $earnings,
]);
