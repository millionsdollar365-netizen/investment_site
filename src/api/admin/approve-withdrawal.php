<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * API: Admin approve withdrawal (JSON)
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

if ($withdrawal_id <= 0) {
    error('Withdrawal ID is required');
}

$withdrawal = $db->fetchOne("SELECT * FROM withdrawals WHERE id = ?", [$withdrawal_id]);

if (!$withdrawal) {
    error('Withdrawal not found', null, 404);
}

if ($withdrawal['status'] !== 'pending') {
    error('Withdrawal is not pending');
}

$db->query(
    "UPDATE withdrawals SET status = 'approved', approved_by = ?, approval_date = NOW() WHERE id = ?",
    [$admin_id, $withdrawal_id]
);

auditLog('admin_approve_withdrawal', 'withdrawals', $withdrawal_id, ['status' => 'pending'], ['status' => 'approved']);

success('Withdrawal approved');
