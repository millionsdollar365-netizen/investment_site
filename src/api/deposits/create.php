<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * API: Create deposit request (JSON)
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/response.php';
require_once __DIR__ . '/../../includes/validation.php';
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    error('Method not allowed', null, 405);
}

if (!isSessionValid()) {
    error('Authentication required', null, 401);
}

$user_id = getCurrentUserId();
$db = Database::getInstance();

$amount = (float) ($_POST['amount'] ?? 0);
$payment_method = trim($_POST['payment_method'] ?? '');

if (!Validator::positive($amount)) {
    error('Amount must be a positive number');
}

if (!Validator::required($payment_method)) {
    error('Payment method is required');
}

// Map payment method to wallet setting key
$wallet_map = [
    'btc' => 'wallet_btc',
    'usdt' => 'wallet_usdt',
    'ethereum' => 'wallet_ethereum'
];

if (!isset($wallet_map[$payment_method])) {
    error('Invalid payment method');
}

// Get wallet address from settings
$wallet_setting = $db->fetchOne(
    "SELECT setting_value FROM settings WHERE setting_key = ?",
    [$wallet_map[$payment_method]]
);

if (!$wallet_setting || empty($wallet_setting['setting_value'])) {
    error('Wallet not configured for this payment method. Please contact support.');
}

$wallet_address = $wallet_setting['setting_value'];

// Generate a unique reference for tracking (optional, store with deposit)
$transaction_ref = strtoupper(substr(uniqid('DEP'), 0, 12));

$db->query(
    "INSERT INTO deposits (user_id, amount, payment_method, transaction_ref, status)
     VALUES (?, ?, ?, ?, 'pending')",
    [$user_id, $amount, $payment_method, $transaction_ref]
);

$deposit_id = $db->lastInsertId();

success('Deposit request submitted', [
    'deposit_id' => $deposit_id,
    'amount' => $amount,
    'payment_method' => $payment_method,
    'wallet_address' => $wallet_address,
    'reference' => $transaction_ref
]);
