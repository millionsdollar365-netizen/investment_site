<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * API: Admin dashboard stats (JSON)
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/response.php';
require_once __DIR__ . '/../../includes/admin-session.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    error('Method not allowed', null, 405);
}

if (!isAdminSessionValid()) {
    error('Authentication required', null, 401);
}

$db = Database::getInstance();

$total_users = (int) $db->fetchOne("SELECT COUNT(*) as count FROM users")['count'];
$active_users = (int) $db->fetchOne("SELECT COUNT(*) as count FROM users WHERE status = 'active'")['count'];
$total_invested = (float) $db->fetchOne("SELECT COALESCE(SUM(amount), 0) as total FROM investments")['total'];
$active_investments = (int) $db->fetchOne("SELECT COUNT(*) as count FROM investments WHERE status = 'active'")['count'];
$pending_deposits = (int) $db->fetchOne("SELECT COUNT(*) as count FROM deposits WHERE status = 'pending'")['count'];
$pending_deposits_amount = (float) $db->fetchOne("SELECT COALESCE(SUM(amount), 0) as total FROM deposits WHERE status = 'pending'")['total'];
$pending_withdrawals = (int) $db->fetchOne("SELECT COUNT(*) as count FROM withdrawals WHERE status = 'pending'")['count'];
$pending_withdrawals_amount = (float) $db->fetchOne("SELECT COALESCE(SUM(amount), 0) as total FROM withdrawals WHERE status = 'pending'")['total'];
$total_balance = (float) $db->fetchOne("SELECT COALESCE(SUM(balance), 0) as total FROM users")['total'];
$total_interest = (float) $db->fetchOne("SELECT COALESCE(SUM(interest_balance), 0) as total FROM users")['total'];

success('Dashboard stats', [
    'users' => ['total' => $total_users, 'active' => $active_users],
    'investments' => ['total_amount' => $total_invested, 'active_count' => $active_investments],
    'deposits' => ['pending_count' => $pending_deposits, 'pending_amount' => $pending_deposits_amount],
    'withdrawals' => ['pending_count' => $pending_withdrawals, 'pending_amount' => $pending_withdrawals_amount],
    'balances' => ['total' => $total_balance, 'total_interest' => $total_interest],
]);
