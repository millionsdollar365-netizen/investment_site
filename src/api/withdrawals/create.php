<?php
/**
 * API: Create withdrawal request — coin + wallet address (JSON)
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
$coin = strtolower(trim($_POST['coin'] ?? ''));
$wallet_address = trim($_POST['wallet_address'] ?? '');

if (!Validator::positive($amount)) {
    error('Amount must be a positive number');
}

$valid_coins = ['btc', 'usdt', 'ethereum'];
if (!in_array($coin, $valid_coins)) {
    error('Please select a valid cryptocurrency');
}

if (!Validator::required($wallet_address)) {
    error('Wallet address is required. Set it in your Profile → Payment Wallets.');
}

$user_balance = (float) $db->fetchOne(
    "SELECT balance FROM users WHERE id = ?", [$user_id]
)['balance'];

$old_balance = $user_balance;

if ($amount > $user_balance) {
    error('Insufficient balance. Available: ' . formatCurrency($user_balance));
}

$db->query("UPDATE users SET balance = balance - ? WHERE id = ?", [$amount, $user_id]);

$db->query(
    "INSERT INTO withdrawals (user_id, amount, coin, wallet_address, status) VALUES (?, ?, ?, ?, 'pending')",
    [$user_id, $amount, $coin, $wallet_address]
);

$withdrawal_id = $db->lastInsertId();

$coin_labels = ['btc' => 'Bitcoin', 'usdt' => 'USDT', 'ethereum' => 'Ethereum'];
createTransaction($user_id, 'withdrawal', $amount, 'Withdrawal to ' . $wallet_address . ' (' . $coin_labels[$coin] . ')', $withdrawal_id, 'withdrawals', $old_balance);

success('Withdrawal request submitted', ['withdrawal_id' => $withdrawal_id]);
