<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * API: User transaction history (JSON, paginated)
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

$page = max(1, (int) ($_GET['page'] ?? 1));
$limit = min(50, max(1, (int) ($_GET['limit'] ?? 20)));
$offset = ($page - 1) * $limit;

$total = (int) $db->fetchOne(
    "SELECT COUNT(*) as count FROM transactions WHERE user_id = ?",
    [$user_id]
)['count'];

$transactions = $db->fetchAll(
    "SELECT id, type, amount, old_balance, new_balance, reference_id, reference_table, description, created_at
     FROM transactions WHERE user_id = ?
     ORDER BY created_at DESC LIMIT ? OFFSET ?",
    [$user_id, $limit, $offset]
);

success('Transactions', [
    'transactions' => $transactions,
    'page' => $page,
    'limit' => $limit,
    'total' => $total,
]);
