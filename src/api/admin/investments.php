<?php
/**
 * API: Admin investments list (JSON, paginated)
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
    $where = " WHERE i.status = ?";
    $params[] = $status;
}

$total = (int) $db->fetchOne(
    "SELECT COUNT(*) as count FROM investments i" . $where,
    $params
)['count'];

$params[] = $limit;
$params[] = $offset;

$investments = $db->fetchAll(
    "SELECT i.*, u.first_name, u.last_name, u.email, p.name as plan_name
     FROM investments i
     JOIN users u ON u.id = i.user_id
     JOIN investment_plans p ON p.id = i.plan_id" . $where . "
     ORDER BY i.created_at DESC LIMIT ? OFFSET ?",
    $params
);

success('Investments', ['investments' => $investments, 'page' => $page, 'limit' => $limit, 'total' => $total]);
