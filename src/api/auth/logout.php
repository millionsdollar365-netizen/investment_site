<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * API: User logout (POST only — clears session, redirects home)
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/session.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

logoutUser();
header('Location: ' . rtrim(SITE_URL, '/') . '/?logout=1', true, 302);
exit;
