<?php
/**
 * API: Admin investment plans — list (GET) and create/update (POST)
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/response.php';
require_once __DIR__ . '/../../includes/validation.php';
require_once __DIR__ . '/../../includes/admin-session.php';

if (!isAdminSessionValid()) {
    error('Authentication required', null, 401);
}

$db = Database::getInstance();

// ── GET: List all plans ──
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $plans = $db->fetchAll("SELECT * FROM investment_plans ORDER BY min_amount ASC");
    success('Plans', ['plans' => $plans]);
}

// ── POST: Create or update plan ──
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = trim($_POST['action'] ?? 'create');
    $plan_id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

    // ── Toggle status (quick action) ──
    if ($action === 'toggle' && $plan_id > 0) {
        $plan = $db->fetchOne("SELECT id, status FROM investment_plans WHERE id = ?", [$plan_id]);
        if (!$plan) error('Plan not found');

        $new_status = $plan['status'] === 'active' ? 'inactive' : 'active';
        $db->query("UPDATE investment_plans SET status = ? WHERE id = ?", [$new_status, $plan_id]);
        success('Plan ' . $new_status, ['status' => $new_status]);
    }

    // ── Create new plan ──
    if ($action === 'create') {
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $min_amount = (float) ($_POST['min_amount'] ?? 0);
        $max_amount_raw = trim($_POST['max_amount'] ?? '');
        $max_amount = $max_amount_raw === '' ? null : (float) $max_amount_raw;
        $duration_days = (int) ($_POST['duration_days'] ?? 0);
        $daily_roi = (float) ($_POST['daily_roi'] ?? 0);
        $status = trim($_POST['status'] ?? 'active');
        $sort_order = (int) ($_POST['sort_order'] ?? 0);
        $is_popular = isset($_POST['is_popular']) ? 1 : 0;

        if (!Validator::required($name)) error('Plan name is required');
        if (!Validator::positive($min_amount)) error('Minimum amount must be a positive number');
        if ($max_amount !== null && $max_amount <= $min_amount) error('Maximum amount must be greater than minimum');
        if ($duration_days < 1) error('Duration must be at least 1 day');
        if (!Validator::positive($daily_roi)) error('Daily ROI must be a positive number');
        if (!in_array($status, ['active', 'inactive'])) error('Invalid status');

        $db->query(
            "INSERT INTO investment_plans (name, description, min_amount, max_amount, duration_days, daily_roi, status, sort_order, is_popular)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [$name, $description, $min_amount, $max_amount, $duration_days, $daily_roi, $status, $sort_order, $is_popular]
        );

        success('Plan created successfully', ['id' => $db->lastInsertId()]);
    }

    // ── Update existing plan ──
    if ($action === 'update' && $plan_id > 0) {
        $plan = $db->fetchOne("SELECT id FROM investment_plans WHERE id = ?", [$plan_id]);
        if (!$plan) error('Plan not found');

        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $min_amount = (float) ($_POST['min_amount'] ?? 0);
        $max_amount_raw = trim($_POST['max_amount'] ?? '');
        $max_amount = $max_amount_raw === '' ? null : (float) $max_amount_raw;
        $duration_days = (int) ($_POST['duration_days'] ?? 0);
        $daily_roi = (float) ($_POST['daily_roi'] ?? 0);
        $status = trim($_POST['status'] ?? 'active');
        $sort_order = (int) ($_POST['sort_order'] ?? 0);
        $is_popular = isset($_POST['is_popular']) ? 1 : 0;

        if (!Validator::required($name)) error('Plan name is required');
        if (!Validator::positive($min_amount)) error('Minimum amount must be a positive number');
        if ($max_amount !== null && $max_amount <= $min_amount) error('Maximum amount must be greater than minimum');
        if ($duration_days < 1) error('Duration must be at least 1 day');
        if (!Validator::positive($daily_roi)) error('Daily ROI must be a positive number');
        if (!in_array($status, ['active', 'inactive'])) error('Invalid status');

        $db->query(
            "UPDATE investment_plans SET name=?, description=?, min_amount=?, max_amount=?, duration_days=?, daily_roi=?, status=?, sort_order=?, is_popular=? WHERE id=?",
            [$name, $description, $min_amount, $max_amount, $duration_days, $daily_roi, $status, $sort_order, $is_popular, $plan_id]
        );

        success('Plan updated successfully');
    }

    error('Invalid action');
} else {
    error('Method not allowed', null, 405);
}
