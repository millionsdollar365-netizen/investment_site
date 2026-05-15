<?php
/**
 * API: Admin settings (JSON) — GET list, POST update
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/response.php';
require_once __DIR__ . '/../../includes/admin-session.php';
require_once __DIR__ . '/../../includes/functions.php';

if (!isAdminSessionValid()) {
    error('Authentication required', null, 401);
}

$db = Database::getInstance();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $settings = $db->fetchAll("SELECT * FROM settings ORDER BY setting_key ASC");
    success('Settings', ['settings' => $settings]);

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $key = trim($_POST['key'] ?? '');
    $value = trim($_POST['value'] ?? '');

    if ($key === '') {
        error('Setting key is required');
    }

    $old = $db->fetchOne("SELECT * FROM settings WHERE setting_key = ?", [$key]);

    setSetting($key, $value);

    auditLog(
        'admin_update_setting',
        'settings',
        $old['id'] ?? null,
        $old ? ['setting_value' => $old['setting_value']] : null,
        ['setting_value' => $value]
    );

    success('Setting updated');

} else {
    error('Method not allowed', null, 405);
}
