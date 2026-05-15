<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * API: Admin deposits list (JSON, paginated)
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
$status = trim($_GET['status'] ?? '');

$where = '';
$params = [];

if ($status !== '') {
    $where = " WHERE d.status = ?";
    $params[] = $status;
}

$total = (int) $db->fetchOne(
    "SELECT COUNT(*) as count FROM deposits d" . $where,
    $params
)['count'];

$params[] = $limit;
$params[] = $offset;

$deposits = $db->fetchAll(
    "SELECT d.*, u.first_name, u.last_name, u.email
     FROM deposits d JOIN users u ON u.id = d.user_id" . $where . "
     ORDER BY d.created_at DESC LIMIT ? OFFSET ?",
    $params
);

success('Deposits', ['deposits' => $deposits, 'page' => $page, 'limit' => $limit, 'total' => $total]);
