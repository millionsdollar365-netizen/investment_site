<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * API: Admin logout (POST only — clears session and redirects)
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/admin-session.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

logoutAdmin();
header('Location: ' . rtrim(SITE_URL, '/') . '/admin/login.php', true, 302);
exit;
