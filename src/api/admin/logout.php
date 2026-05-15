<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * API: Admin logout (JSON or redirect)
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/response.php';
require_once __DIR__ . '/../../includes/admin-session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    logoutAdmin();
    success('Logged out successfully');
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    logoutAdmin();
    header('Location: ' . rtrim(SITE_URL, '/') . '/admin/login.php');
    exit;
}

http_response_code(405);
header('Content-Type: application/json');
echo json_encode(['success' => false, 'message' => 'Method not allowed']);
exit;
