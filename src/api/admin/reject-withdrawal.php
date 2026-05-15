<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * API: Admin reject withdrawal (JSON) — refunds balance
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/response.php';
require_once __DIR__ . '/../../includes/admin-session.php';
require_once __DIR__ . '/../../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    error('Method not allowed', null, 405);
}

if (!isAdminSessionValid()) {
    error('Authentication required', null, 401);
}

$db = Database::getInstance();
$admin_id = getCurrentAdminId();

$withdrawal_id = (int) ($_POST['id'] ?? 0);
$reason = trim($_POST['reason'] ?? '');

if ($withdrawal_id <= 0) {
    error('Withdrawal ID is required');
}

if ($reason === '') {
    error('Rejection reason is required');
}

$withdrawal = $db->fetchOne("SELECT * FROM withdrawals WHERE id = ?", [$withdrawal_id]);

if (!$withdrawal) {
    error('Withdrawal not found', null, 404);
}

if ($withdrawal['status'] !== 'pending') {
    error('Withdrawal is not pending');
}

$db->query(
    "UPDATE withdrawals SET status = 'rejected', approved_by = ?, rejection_reason = ?, approval_date = NOW() WHERE id = ?",
    [$admin_id, $reason, $withdrawal_id]
);

$db->query(
    "UPDATE users SET balance = balance + ? WHERE id = ?",
    [$withdrawal['amount'], $withdrawal['user_id']]
);

createTransaction($withdrawal['user_id'], 'adjustment', $withdrawal['amount'], 'Withdrawal rejected — refund', $withdrawal_id, 'withdrawals');

auditLog('admin_reject_withdrawal', 'withdrawals', $withdrawal_id, ['status' => 'pending'], ['status' => 'rejected']);

success('Withdrawal rejected, balance refunded');
