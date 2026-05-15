<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * API: Admin approve deposit (JSON)
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

if ($deposit_id <= 0) {
    error('Deposit ID is required');
}

$deposit = $db->fetchOne("SELECT * FROM deposits WHERE id = ?", [$deposit_id]);

if (!$deposit) {
    error('Deposit not found', null, 404);
}

if ($deposit['status'] !== 'pending') {
    error('Deposit is not pending');
}

$db->query(
    "UPDATE deposits SET status = 'approved', approved_by = ?, approval_date = NOW() WHERE id = ?",
    [$admin_id, $deposit_id]
);

$db->query(
    "UPDATE users SET balance = balance + ? WHERE id = ?",
    [$deposit['amount'], $deposit['user_id']]
);

createTransaction($deposit['user_id'], 'deposit', $deposit['amount'], 'Deposit approved', $deposit_id, 'deposits');

auditLog('admin_approve_deposit', 'deposits', $deposit_id, ['status' => 'pending'], ['status' => 'approved']);

success('Deposit approved');
