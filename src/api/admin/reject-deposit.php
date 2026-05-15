<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * API: Admin reject deposit (JSON)
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

$deposit_id = (int) ($_POST['id'] ?? 0);
$reason = trim($_POST['reason'] ?? '');

if ($deposit_id <= 0) {
    error('Deposit ID is required');
}

if ($reason === '') {
    error('Rejection reason is required');
}

$deposit = $db->fetchOne("SELECT * FROM deposits WHERE id = ?", [$deposit_id]);

if (!$deposit) {
    error('Deposit not found', null, 404);
}

if ($deposit['status'] !== 'pending') {
    error('Deposit is not pending');
}

$db->query(
    "UPDATE deposits SET status = 'rejected', approved_by = ?, rejection_reason = ?, approval_date = NOW() WHERE id = ?",
    [$admin_id, $reason, $deposit_id]
);

auditLog('admin_reject_deposit', 'deposits', $deposit_id, ['status' => 'pending'], ['status' => 'rejected']);

success('Deposit rejected');
