<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * API: List user deposits (JSON)
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

$deposits = $db->fetchAll(
    "SELECT id, amount, payment_method, transaction_ref, status, rejection_reason, created_at, approval_date
     FROM deposits WHERE user_id = ? ORDER BY created_at DESC",
    [$user_id]
);

success('Deposits', ['deposits' => $deposits]);
