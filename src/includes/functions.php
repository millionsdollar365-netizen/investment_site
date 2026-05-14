<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * General Helper Functions
 */

require_once __DIR__ . '/db.php';

/**
 * Format currency
 */
function formatCurrency($amount, $decimals = 2) {
    return number_format($amount, $decimals, '.', ',');
}

/**
 * Format date
 */
function formatDate($date, $format = 'Y-m-d H:i:s') {
    if (is_string($date)) {
        $date = strtotime($date);
    }
    return date($format, $date);
}

/**
 * Get user balance
 */
function getUserBalance($user_id) {
    $db = Database::getInstance();
    $user = $db->fetchOne(
        "SELECT balance FROM users WHERE id = ?",
        [$user_id]
    );
    return $user ? $user['balance'] : 0;
}

/**
 * Get user interest balance
 */
function getUserInterestBalance($user_id) {
    $db = Database::getInstance();
    $user = $db->fetchOne(
        "SELECT interest_balance FROM users WHERE id = ?",
        [$user_id]
    );
    return $user ? $user['interest_balance'] : 0;
}

/**
 * Get user total investment
 */
function getUserTotalInvestment($user_id) {
    $db = Database::getInstance();
    $result = $db->fetchOne(
        "SELECT SUM(amount) as total FROM investments WHERE user_id = ? AND status = 'active'",
        [$user_id]
    );
    return $result ? $result['total'] : 0;
}

/**
 * Get user active investments count
 */
function getUserActiveInvestmentsCount($user_id) {
    $db = Database::getInstance();
    $result = $db->fetchOne(
        "SELECT COUNT(*) as count FROM investments WHERE user_id = ? AND status = 'active'",
        [$user_id]
    );
    return $result ? $result['count'] : 0;
}

/**
 * Get user referral count
 */
function getUserReferralCount($user_id) {
    $db = Database::getInstance();
    $result = $db->fetchOne(
        "SELECT COUNT(*) as count FROM users WHERE referred_by = ? AND status = 'active'",
        [$user_id]
    );
    return $result ? $result['count'] : 0;
}

/**
 * Get platform setting
 */
function getSetting($key, $default = null) {
    $db = Database::getInstance();
    $setting = $db->fetchOne(
        "SELECT setting_value FROM settings WHERE setting_key = ?",
        [$key]
    );
    return $setting ? $setting['setting_value'] : $default;
}

/**
 * Set platform setting
 */
function setSetting($key, $value) {
    $db = Database::getInstance();
    
    $existing = $db->fetchOne(
        "SELECT id FROM settings WHERE setting_key = ?",
        [$key]
    );
    
    if ($existing) {
        $db->query(
            "UPDATE settings SET setting_value = ? WHERE setting_key = ?",
            [$value, $key]
        );
    } else {
        $db->query(
            "INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)",
            [$key, $value]
        );
    }
}

/**
 * Log audit action
 */
function auditLog($action, $entity_type = null, $entity_id = null, $old_values = null, $new_values = null) {
    $db = Database::getInstance();
    
    $admin_id = null;
    if (function_exists('getCurrentAdminId')) {
        $admin_id = getCurrentAdminId();
    }
    
    $db->query(
        "INSERT INTO audit_logs (admin_id, action, entity_type, entity_id, old_values, new_values, ip_address)
         VALUES (?, ?, ?, ?, ?, ?, ?)",
        [
            $admin_id,
            $action,
            $entity_type,
            $entity_id,
            $old_values ? json_encode($old_values) : null,
            $new_values ? json_encode($new_values) : null,
            $_SERVER['REMOTE_ADDR'] ?? ''
        ]
    );
}

/**
 * Create transaction record
 */
function createTransaction($user_id, $type, $amount, $description = '', $reference_id = null, $reference_table = null) {
    $db = Database::getInstance();
    
    // Get old balance
    $old_balance = getUserBalance($user_id);
    
    // Calculate new balance
    $new_balance = $old_balance;
    if (in_array($type, ['deposit', 'profit', 'referral'])) {
        $new_balance += $amount;
    } elseif (in_array($type, ['withdrawal', 'investment'])) {
        $new_balance -= $amount;
    }
    
    // Create transaction record
    $db->query(
        "INSERT INTO transactions (user_id, type, amount, old_balance, new_balance, reference_id, reference_table, description)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
        [
            $user_id,
            $type,
            $amount,
            $old_balance,
            $new_balance,
            $reference_id,
            $reference_table,
            $description
        ]
    );
    
    return $db->lastInsertId();
}

/**
 * Truncate string
 */
function truncateString($string, $length = 100, $suffix = '...') {
    if (strlen($string) <= $length) {
        return $string;
    }
    return substr($string, 0, $length) . $suffix;
}

/**
 * Is valid email
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Generate random code
 */
function generateRandomCode($length = 8) {
    return strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, $length));
}

/**
 * Redirect to URL
 */
function redirect($url, $status_code = 302) {
    header("Location: $url", true, $status_code);
    exit;
}
?>
