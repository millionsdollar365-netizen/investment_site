<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * API: Cron — cleanup expired tokens and old data (JSON)
 *
 * Intended to be called by a cron job daily.
 * Clears expired password reset tokens.
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/response.php';

// Check execution authority: allow if CLI or if HTTP POST with valid CRON_SECRET token
$is_cli = (php_sapi_name() === 'cli');
if (!$is_cli) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        error('Method not allowed', null, 405);
    }
    $token = $_GET['secret'] ?? $_POST['secret'] ?? '';
    if (empty(CRON_SECRET) || !hash_equals(CRON_SECRET, $token)) {
        error('Unauthorized', null, 401);
    }
}

$db = Database::getInstance();

$db->query("UPDATE users SET password_reset_token = NULL, password_reset_expires = NULL WHERE password_reset_expires < NOW()");

$cleared_tokens = $db->getConnection()->rowCount();

success('Cleanup complete', ['cleared_reset_tokens' => $cleared_tokens]);
