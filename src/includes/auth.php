<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * Authentication Helper Functions
 */

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/security.php';

/**
 * Register new user
 */
function registerUser($first_name, $last_name, $email, $password, $referral_code = null) {
    $db = Database::getInstance();
    
    // Check if email already exists
    $existing = $db->fetchOne(
        "SELECT id FROM users WHERE email = ?",
        [$email]
    );
    
    if ($existing) {
        return [
            'success' => false,
            'message' => 'Email already registered'
        ];
    }
    
    // Generate referral code for new user
    $user_referral_code = strtoupper(substr(md5($email . time()), 0, 8));
    
    // Find referrer if referral code provided
    $referred_by = null;
    if ($referral_code) {
        $referrer = $db->fetchOne(
            "SELECT id FROM users WHERE referral_code = ?",
            [$referral_code]
        );
        if ($referrer) {
            $referred_by = $referrer['id'];
        }
    }
    
    try {
        $db->query(
            "INSERT INTO users (first_name, last_name, email, password_hash, referral_code, referred_by)
             VALUES (?, ?, ?, ?, ?, ?)",
            [
                $first_name,
                $last_name,
                $email,
                Security::hashPassword($password),
                $user_referral_code,
                $referred_by
            ]
        );
        
        $user_id = $db->lastInsertId();
        
        // Create referral relationship if referred
        if ($referred_by) {
            $db->query(
                "INSERT INTO referrals (referrer_id, referred_id, status)
                 VALUES (?, ?, 'active')",
                [$referred_by, $user_id]
            );
        }
        
        return [
            'success' => true,
            'message' => 'User registered successfully',
            'user_id' => $user_id
        ];
    } catch (Exception $e) {
        return [
            'success' => false,
            'message' => 'Registration failed: ' . $e->getMessage()
        ];
    }
}

/**
 * Authenticate user
 */
function authenticateUser($email, $password) {
    $db = Database::getInstance();
    
    $user = $db->fetchOne(
        "SELECT * FROM users WHERE email = ?",
        [$email]
    );
    
    if (!$user) {
        return [
            'success' => false,
            'message' => 'Invalid email or password'
        ];
    }
    
    if ($user['status'] !== 'active') {
        return [
            'success' => false,
            'message' => 'Account is not active'
        ];
    }
    
    if (!Security::verifyPassword($password, $user['password_hash'])) {
        return [
            'success' => false,
            'message' => 'Invalid email or password'
        ];
    }
    
    return [
        'success' => true,
        'message' => 'Authentication successful',
        'user' => $user
    ];
}

/**
 * Authenticate admin
 */
function authenticateAdmin($username, $password) {
    $db = Database::getInstance();
    
    $admin = $db->fetchOne(
        "SELECT * FROM admin_users WHERE username = ?",
        [$username]
    );
    
    if (!$admin) {
        return [
            'success' => false,
            'message' => 'Invalid username or password'
        ];
    }
    
    if ($admin['status'] !== 'active') {
        return [
            'success' => false,
            'message' => 'Admin account is not active'
        ];
    }
    
    if (!Security::verifyPassword($password, $admin['password_hash'])) {
        return [
            'success' => false,
            'message' => 'Invalid username or password'
        ];
    }
    
    // Update last login
    $db->query(
        "UPDATE admin_users SET last_login = NOW() WHERE id = ?",
        [$admin['id']]
    );
    
    return [
        'success' => true,
        'message' => 'Authentication successful',
        'admin' => $admin
    ];
}

/**
 * Generate password reset token
 */
function generatePasswordResetToken($email) {
    $db = Database::getInstance();
    
    $user = $db->fetchOne(
        "SELECT id FROM users WHERE email = ?",
        [$email]
    );
    
    if (!$user) {
        return [
            'success' => false,
            'message' => 'Email not found'
        ];
    }
    
    $token = Security::generateToken();
    $expires = date('Y-m-d H:i:s', time() + PASSWORD_RESET_TIMEOUT);
    
    $db->query(
        "UPDATE users SET password_reset_token = ?, password_reset_expires = ? WHERE id = ?",
        [$token, $expires, $user['id']]
    );
    
    return [
        'success' => true,
        'token' => $token,
        'expires' => $expires
    ];
}

/**
 * Verify password reset token
 */
function verifyPasswordResetToken($token) {
    $db = Database::getInstance();
    
    $user = $db->fetchOne(
        "SELECT * FROM users WHERE password_reset_token = ? AND password_reset_expires > NOW()",
        [$token]
    );
    
    if (!$user) {
        return [
            'success' => false,
            'message' => 'Invalid or expired token'
        ];
    }
    
    return [
        'success' => true,
        'user_id' => $user['id'],
        'email' => $user['email']
    ];
}

/**
 * Reset password
 */
function resetPassword($token, $new_password) {
    $verify = verifyPasswordResetToken($token);
    
    if (!$verify['success']) {
        return $verify;
    }
    
    $db = Database::getInstance();
    
    $db->query(
        "UPDATE users SET password_hash = ?, password_reset_token = NULL, password_reset_expires = NULL WHERE id = ?",
        [Security::hashPassword($new_password), $verify['user_id']]
    );
    
    return [
        'success' => true,
        'message' => 'Password reset successfully'
    ];
}

/**
 * Change user password
 */
function changeUserPassword($user_id, $old_password, $new_password) {
    $db = Database::getInstance();
    
    $user = $db->fetchOne(
        "SELECT password_hash FROM users WHERE id = ?",
        [$user_id]
    );
    
    if (!$user) {
        return [
            'success' => false,
            'message' => 'User not found'
        ];
    }
    
    if (!Security::verifyPassword($old_password, $user['password_hash'])) {
        return [
            'success' => false,
            'message' => 'Current password is incorrect'
        ];
    }
    
    $db->query(
        "UPDATE users SET password_hash = ? WHERE id = ?",
        [Security::hashPassword($new_password), $user_id]
    );
    
    return [
        'success' => true,
        'message' => 'Password changed successfully'
    ];
}
?>
