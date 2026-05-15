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
$transaction_ref = trim($_POST['transaction_ref'] ?? '');

if (!Validator::positive($amount)) {
    error('Amount must be a positive number');
}

if (!Validator::required($payment_method)) {
    error('Payment method is required');
}

if (!Validator::required($transaction_ref)) {
    error('Transaction reference is required');
}

$db->query(
    "INSERT INTO deposits (user_id, amount, payment_method, transaction_ref, status)
     VALUES (?, ?, ?, ?, 'pending')",
    [$user_id, $amount, $payment_method, $transaction_ref]
);

$deposit_id = $db->lastInsertId();

success('Deposit request submitted', ['deposit_id' => $deposit_id]);
