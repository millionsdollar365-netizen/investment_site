<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * API: Cron — process daily profits for active investments (JSON)
 *
 * Intended to be called by a cron job every day.
 * Credits daily ROI to each active investment's total_profit
 * and the user's interest_balance.
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/response.php';
require_once __DIR__ . '/../../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    error('Method not allowed', null, 405);
}

$db = Database::getInstance();

$investments = $db->fetchAll(
    "SELECT i.id, i.user_id, i.amount, i.daily_roi, i.total_profit, p.name as plan_name
     FROM investments i
     JOIN investment_plans p ON p.id = i.plan_id
     WHERE i.status = 'active' AND i.end_date > NOW()"
);

$processed = 0;
$total_profit = 0;

foreach ($investments as $inv) {
    $db->query(
        "UPDATE investments SET total_profit = total_profit + ? WHERE id = ?",
        [$inv['daily_roi'], $inv['id']]
    );

    $db->query(
        "UPDATE users SET interest_balance = interest_balance + ? WHERE id = ?",
        [$inv['daily_roi'], $inv['user_id']]
    );

    createTransaction($inv['user_id'], 'profit', $inv['daily_roi'], 'Daily ROI — ' . $inv['plan_name'], $inv['id'], 'investments');

    $processed++;
    $total_profit += $inv['daily_roi'];
}

success('Profits processed', ['processed' => $processed, 'total_profit' => $total_profit]);
