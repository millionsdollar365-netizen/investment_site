<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * API: List user investments (JSON)
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/response.php';
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    error('Method not allowed', null, 405);
}

if (!isSessionValid()) {
    error('Authentication required', null, 401);
}

$user_id = getCurrentUserId();
$db = Database::getInstance();

$investments = $db->fetchAll(
    "SELECT i.id, i.amount, i.daily_roi, i.total_profit, i.status, i.start_date, i.end_date, i.completed_date,
            p.name as plan_name, p.duration_days, p.daily_roi as plan_roi
     FROM investments i
     JOIN investment_plans p ON p.id = i.plan_id
     WHERE i.user_id = ?
     ORDER BY i.created_at DESC",
    [$user_id]
);

success('Investments', ['investments' => $investments]);
