<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * API: Get user profile (JSON)
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

$user = getCurrentUser();
if (!$user) {
    error('User not found', null, 404);
}

success('Profile data', ['user' => sanitizeUserForClient($user)]);
