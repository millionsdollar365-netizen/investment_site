<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * API: Create withdrawal request (JSON)
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/response.php';
require_once __DIR__ . '/../../includes/validation.php';
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    error('Method not allowed', null, 405);
}

if (!isSessionValid()) {
    error('Authentication required', null, 401);
}

$user_id = getCurrentUserId();
$db = Database::getInstance();

$amount = (float) ($_POST['amount'] ?? 0);
$bank_name = trim($_POST['bank_name'] ?? '');
$account_number = trim($_POST['account_number'] ?? '');
$account_holder_name = trim($_POST['account_holder_name'] ?? '');

if (!Validator::positive($amount)) {
    error('Amount must be a positive number');
}

if (!Validator::required($bank_name)) {
    error('Bank name is required');
}

if (!Validator::required($account_number)) {
    error('Account number is required');
}

if (!Validator::required($account_holder_name)) {
    error('Account holder name is required');
}

$user_balance = (float) $db->fetchOne(
    "SELECT balance FROM users WHERE id = ?",
    [$user_id]
)['balance'];

if ($amount > $user_balance) {
    error('Insufficient balance. Available: ' . formatCurrency($user_balance));
}

$db->query(
    "UPDATE users SET balance = balance - ? WHERE id = ?",
    [$amount, $user_id]
);

$db->query(
    "INSERT INTO withdrawals (user_id, amount, bank_name, account_number, account_holder_name, status)
     VALUES (?, ?, ?, ?, ?, 'pending')",
    [$user_id, $amount, $bank_name, $account_number, $account_holder_name]
);

$withdrawal_id = $db->lastInsertId();

createTransaction($user_id, 'withdrawal', $amount, 'Withdrawal to ' . $bank_name, $withdrawal_id, 'withdrawals');

success('Withdrawal request submitted', ['withdrawal_id' => $withdrawal_id]);
