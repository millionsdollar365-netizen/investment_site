<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * API: Cron — complete expired investments (JSON)
 *
 * Intended to be called by a cron job every day.
 * Marks investments with passed end_date as completed,
 * returns principal to user balance.
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

$expired = $db->fetchAll(
    "SELECT i.id, i.user_id, i.amount, i.total_profit, p.name as plan_name,
            u.first_name, u.last_name, u.email, u.balance
     FROM investments i
     JOIN investment_plans p ON p.id = i.plan_id
     JOIN users u ON u.id = i.user_id
     WHERE i.status = 'active' AND i.end_date <= NOW()"
);

$completed = 0;
$total_returned = 0;

foreach ($expired as $inv) {
    $db->query(
        "UPDATE investments SET status = 'completed', completed_date = NOW() WHERE id = ?",
        [$inv['id']]
    );

    $return_amount = $inv['amount'];
    $old_balance = getUserBalance($inv['user_id']);

    $db->query(
        "UPDATE users SET balance = balance + ? WHERE id = ?",
        [$return_amount, $inv['user_id']]
    );

    createTransaction($inv['user_id'], 'investment', $return_amount, 'Investment completed — principal returned', $inv['id'], 'investments', $old_balance);

    // Send Investment Term Completed Email
    $new_balance = (float)$old_balance + (float)$return_amount;
    $mail_data = [
        'plan_name'     => $inv['plan_name'],
        'amount'        => formatCurrency($inv['amount']),
        'total_profit'  => formatCurrency($inv['total_profit']),
        'new_balance'   => formatCurrency($new_balance),
        'investment_id' => $inv['id']
    ];
    Mail::sendInvestmentCompleted($inv['email'], $inv['first_name'], $mail_data);

    $completed++;
    $total_returned += $return_amount;
}

success('Investments completed', ['completed' => $completed, 'total_returned' => $total_returned]);
