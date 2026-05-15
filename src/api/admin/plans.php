<?php
/**
 * API: Admin investment plans list (JSON)
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

$plans = $db->fetchAll("SELECT * FROM investment_plans ORDER BY min_amount ASC");

success('Plans', ['plans' => $plans]);
