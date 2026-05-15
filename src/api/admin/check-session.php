<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * API: Admin session status (JSON)
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/response.php';
require_once __DIR__ . '/../../includes/admin-session.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    error('Method not allowed', null, 405);
}

if (!isAdminLoggedIn()) {
    success('', ['logged_in' => false]);
}

if (!isAdminSessionValid()) {
    success('', ['logged_in' => false]);
}

$admin = getCurrentAdmin();
if (!$admin) {
    success('', ['logged_in' => false]);
}

unset($admin['password_hash']);
success('', ['logged_in' => true, 'admin' => $admin]);
