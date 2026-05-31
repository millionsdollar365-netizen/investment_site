<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * API: Create investment (JSON)
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/response.php';
require_once __DIR__ . '/../../includes/validation.php';
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/mail.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    error('Method not allowed', null, 405);
}

if (!isSessionValid()) {
    error('Authentication required', null, 401);
}

$user_id = getCurrentUserId();
$db = Database::getInstance();

$plan_id = (int) ($_POST['plan_id'] ?? 0);
$amount = (float) ($_POST['amount'] ?? 0);

if ($plan_id <= 0) {
    error('Invalid investment plan');
}

if (!Validator::positive($amount)) {
    error('Amount must be a positive number');
}

$plan = $db->fetchOne(
    "SELECT * FROM investment_plans WHERE id = ? AND status = 'active'",
    [$plan_id]
);

if (!$plan) {
    error('Investment plan not found or inactive');
}

if ($amount < (float) $plan['min_amount']) {
    error('Minimum investment is ' . formatCurrency($plan['min_amount']));
}

if ($plan['max_amount'] !== null && $amount > (float) $plan['max_amount']) {
    error('Maximum investment is ' . formatCurrency($plan['max_amount']));
}

$user_balance = (float) $db->fetchOne(
    "SELECT balance FROM users WHERE id = ?",
    [$user_id]
)['balance'];

if ($amount > $user_balance) {
    error('Insufficient balance. Available: ' . formatCurrency($user_balance));
}

$daily_roi_amount = $amount * ((float) $plan['daily_roi'] / 100);
$start_date = date('Y-m-d H:i:s');
$end_date = date('Y-m-d H:i:s', strtotime("+{$plan['duration_days']} days"));

$old_balance = getUserBalance($user_id);

$db->query(
    "UPDATE users SET balance = balance - ? WHERE id = ?",
    [$amount, $user_id]
);

$db->query(
    "INSERT INTO investments (user_id, plan_id, amount, daily_roi, status, start_date, end_date)
     VALUES (?, ?, ?, ?, 'active', ?, ?)",
    [$user_id, $plan_id, $amount, $daily_roi_amount, $start_date, $end_date]
);

$investment_id = $db->lastInsertId();

createTransaction($user_id, 'investment', $amount, 'Investment in ' . $plan['name'], $investment_id, 'investments', $old_balance);

$user = $db->fetchOne("SELECT email, first_name FROM users WHERE id = ?", [$user_id]);
if ($user) {
    $daily = $amount * ((float) $plan['daily_roi'] / 100);
    $total_return = $daily * $plan['duration_days'];
    $total_payout = $amount + $total_return;

    Mail::sendInvestmentConfirmation($user['email'], $user['first_name'], [
        'plan_name'       => $plan['name'],
        'amount'          => '$' . number_format($amount, 2),
        'daily_roi'       => $plan['daily_roi'],
        'daily_amount'    => '$' . number_format($daily, 2),
        'duration'        => $plan['duration_days'],
        'end_date'        => date('M d, Y', strtotime($end_date)),
        'expected_return' => '$' . number_format($total_return, 2),
        'total_payout'    => '$' . number_format($total_payout, 2),
        'transaction_id'  => $investment_id,
    ]);
}

success('Investment created', ['investment_id' => $investment_id]);
