<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * API: Logout (clears session). GET redirects for browser links; POST returns JSON.
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/../../includes/response.php';
    logoutUser();
    success('Logged out successfully');
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    logoutUser();
    header('Location: ' . rtrim(SITE_URL, '/') . '/login.php', true, 302);
    exit;
}

http_response_code(405);
header('Content-Type: application/json');
echo json_encode(['success' => false, 'message' => 'Method not allowed']);
exit;
