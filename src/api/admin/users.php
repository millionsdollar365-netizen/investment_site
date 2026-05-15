<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * API: Admin user list (JSON, paginated)
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

$page = max(1, (int) ($_GET['page'] ?? 1));
$limit = min(100, max(1, (int) ($_GET['limit'] ?? 20)));
$offset = ($page - 1) * $limit;
$search = trim($_GET['search'] ?? '');
$status = trim($_GET['status'] ?? '');

$where = '';
$params = [];

if ($search !== '') {
    $where .= " WHERE (first_name LIKE ? OR last_name LIKE ? OR email LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($status !== '') {
    $where .= ($where ? ' AND' : ' WHERE') . " status = ?";
    $params[] = $status;
}

$count_params = $params;
$total = (int) $db->fetchOne("SELECT COUNT(*) as count FROM users" . $where, $count_params)['count'];

$params[] = $limit;
$params[] = $offset;

$users = $db->fetchAll(
    "SELECT id, first_name, last_name, email, phone, balance, interest_balance, status, kyc_status, created_at
     FROM users" . $where . " ORDER BY created_at DESC LIMIT ? OFFSET ?",
    $params
);

success('Users', ['users' => $users, 'page' => $page, 'limit' => $limit, 'total' => $total]);
