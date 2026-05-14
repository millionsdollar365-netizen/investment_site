<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * API: Session status (JSON)
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/response.php';
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    error('Method not allowed', null, 405);
}

if (!isLoggedIn()) {
    success('', ['logged_in' => false]);
}

if (!isSessionValid()) {
    success('', ['logged_in' => false]);
}

$user = getCurrentUser();
if (!$user) {
    success('', ['logged_in' => false]);
}

success('', ['logged_in' => true, 'user' => sanitizeUserForClient($user)]);
