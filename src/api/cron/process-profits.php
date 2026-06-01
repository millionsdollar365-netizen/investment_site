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
require_once __DIR__ . '/../../includes/mail.php';

// Check execution authority: allow if CLI or if HTTP POST with valid CRON_SECRET token
$is_cli = (php_sapi_name() === 'cli');
if (!$is_cli) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        error('Method not allowed', null, 405);
    }
    $token = $_GET['secret'] ?? $_POST['secret'] ?? '';
    if (empty(CRON_SECRET) || !hash_equals(CRON_SECRET, $token)) {
        error('Unauthorized', null, 401);
    }
}

$db = Database::getInstance();

$investments = $db->fetchAll(
    "SELECT i.id, i.user_id, i.amount, i.daily_roi, i.total_profit, p.name as plan_name,
            u.first_name, u.last_name, u.email, u.balance
     FROM investments i
     JOIN investment_plans p ON p.id = i.plan_id
     JOIN users u ON u.id = i.user_id
     WHERE i.status = 'active' AND i.end_date > NOW()"
);

$processed = 0;
$total_profit = 0;

foreach ($investments as $inv) {
    $db->query(
        "UPDATE investments SET total_profit = total_profit + ? WHERE id = ?",
        [$inv['daily_roi'], $inv['id']]
    );

    $old_balance = getUserBalance($inv['user_id']);

    $db->query(
        "UPDATE users SET balance = balance + ? WHERE id = ?",
        [$inv['daily_roi'], $inv['user_id']]
    );

    createTransaction($inv['user_id'], 'profit', $inv['daily_roi'], 'Daily ROI — ' . $inv['plan_name'], $inv['id'], 'investments', $old_balance);

    // Send ROI Payout Email
    $new_balance = (float)$old_balance + (float)$inv['daily_roi'];
    $mail_data = [
        'plan_name'     => $inv['plan_name'],
        'amount'        => formatCurrency($inv['amount']),
        'daily_roi'     => formatCurrency($inv['daily_roi']),
        'total_profit'  => formatCurrency((float)$inv['total_profit'] + (float)$inv['daily_roi']),
        'new_balance'   => formatCurrency($new_balance),
        'investment_id' => $inv['id']
    ];
    Mail::sendRoiPayout($inv['email'], $inv['first_name'], $mail_data);

    $processed++;
    $total_profit += $inv['daily_roi'];
}

success('Profits processed', ['processed' => $processed, 'total_profit' => $total_profit]);
