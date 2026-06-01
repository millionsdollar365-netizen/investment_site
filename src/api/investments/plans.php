<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * API: List active investment plans (JSON)
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/response.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    error('Method not allowed', null, 405);
}

$db = Database::getInstance();

$plans = $db->fetchAll(
    "SELECT id, name, description, min_amount, max_amount, duration_days, daily_roi, total_return, sort_order, is_popular
     FROM investment_plans WHERE status = 'active' ORDER BY sort_order ASC, min_amount ASC"
);

success('Investment plans', ['plans' => $plans]);
