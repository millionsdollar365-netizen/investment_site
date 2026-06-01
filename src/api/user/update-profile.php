<?php
/**
 * API: Update user profile — text fields + avatar upload (JSON)
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/response.php';
require_once __DIR__ . '/../../includes/validation.php';
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    error('Method not allowed', null, 405);
}

if (!isSessionValid()) {
    error('Authentication required', null, 401);
}

$user_id = getCurrentUserId();
$db = Database::getInstance();

$first_name = trim($_POST['first_name'] ?? '');
$last_name = trim($_POST['last_name'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$phone_code = trim($_POST['phone_code'] ?? '');
$bio = trim($_POST['bio'] ?? '');

$wallet_keys = ['wallet_btc', 'wallet_usdt', 'wallet_ethereum'];
$has_wallet = (bool) array_intersect_key($_POST, array_flip($wallet_keys));
$is_wallet_or_avatar = ($has_wallet || !empty($_FILES['avatar'])) && !isset($_POST['first_name']) && !isset($_POST['last_name']);

if (!$is_wallet_or_avatar) {
    if (!Validator::required($first_name) || !Validator::required($last_name)) {
        error('First name and last name are required');
    }
    if ($phone !== '' && !Validator::regex($phone, '/^\+?[0-9]{7,20}$/')) {
        error('Invalid phone number format');
    }
}

// ── Avatar upload ──
$avatar_path = null;
if (!empty($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['avatar'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
        error('Avatar must be JPG or PNG');
    }
    if ($file['size'] > 2 * 1024 * 1024) {
        error('Avatar must be under 2MB');
    }

    $upload_dir = dirname(__DIR__, 2) . '/uploads/avatars/';
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

    $filename = 'user_' . $user_id . '.' . $ext;
    $dest = $upload_dir . $filename;

    // Remove old avatar (any extension)
    foreach (glob($upload_dir . 'user_' . $user_id . '.*') as $old) unlink($old);

    if (move_uploaded_file($file['tmp_name'], $dest)) {
        $avatar_path = '/uploads/avatars/' . $filename;
    }
}

if (!$is_wallet_or_avatar) {
    $country = isset($_POST['country']) ? trim($_POST['country']) : null;
    $city = isset($_POST['city']) ? trim($_POST['city']) : null;
    $state = isset($_POST['state']) ? trim($_POST['state']) : null;
    $zip_code = isset($_POST['zip_code']) ? trim($_POST['zip_code']) : null;
    $address = isset($_POST['address']) ? trim($_POST['address']) : null;

    // Update text fields
    $db->query(
        "UPDATE users SET first_name = ?, last_name = ?, phone = ?, phone_code = ?, bio = ?, country = ?, city = ?, state = ?, zip_code = ?, address = ? WHERE id = ?",
        [$first_name, $last_name, $phone ?: null, $phone_code ?: null, $bio ?: null, $country, $city, $state, $zip_code, $address, $user_id]
    );
}

// Update wallet fields if sent
$wallet_updates = [];
$wallet_params = [];
foreach (['wallet_btc', 'wallet_usdt', 'wallet_ethereum'] as $wk) {
    if (isset($_POST[$wk])) {
        $wallet_updates[] = "$wk = ?";
        $wallet_params[] = trim($_POST[$wk]);
    }
}
if ($wallet_updates) {
    $wallet_params[] = $user_id;
    $db->query("UPDATE users SET " . implode(', ', $wallet_updates) . " WHERE id = ?", $wallet_params);
}

// Update avatar path if uploaded
if ($avatar_path) {
    $db->query("UPDATE users SET avatar = ? WHERE id = ?", [$avatar_path, $user_id]);
}

$user = getCurrentUser();
success('Profile updated', ['user' => sanitizeUserForClient($user)]);
