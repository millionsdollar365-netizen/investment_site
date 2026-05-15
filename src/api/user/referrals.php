<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * API: User referrals list (JSON)
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

$referrals = $db->fetchAll(
    "SELECT r.id, r.commission_percentage, r.commission_amount, r.status, r.created_at,
            u.first_name, u.last_name, u.email
     FROM referrals r
     JOIN users u ON u.id = r.referred_id
     WHERE r.referrer_id = ?
     ORDER BY r.created_at DESC",
    [$user_id]
);

$total_commission = 0;
foreach ($referrals as $ref) {
    $total_commission += (float) $ref['commission_amount'];
}

success('Referrals', [
    'referrals' => $referrals,
    'total_commission' => $total_commission,
    'count' => count($referrals),
]);
