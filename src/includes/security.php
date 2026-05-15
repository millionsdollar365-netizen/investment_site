<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * Security & Input Sanitization
 */

class Security {
    /**
     * Sanitize input
     */
    public static function sanitize($input) {
        if (is_array($input)) {
            return array_map([self::class, 'sanitize'], $input);
        }
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Hash password using bcrypt (cost 12)
     */
    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }

    /**
     * Verify password against bcrypt hash
     */
    public static function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
    
    /**
     * Generate random token
     */
    public static function generateToken($length = 32) {
        return bin2hex(random_bytes($length));
    }
    
    /**
     * Verify CSRF token from POST request.
     * Call in any POST endpoint that needs protection.
     */
    public static function requireCsrf() {
        $token = $_POST['csrf_token'] ?? '';
        if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Invalid or missing CSRF token']);
            exit;
        }
    }

    /**
     * Get the current CSRF token (for embedding in forms/meta tags)
     */
    public static function getCsrfToken() {
        return $_SESSION['csrf_token'] ?? '';
    }
    
    /**
     * Generate CSRF token
     */
    public static function generateCsrfToken() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = self::generateToken();
        }
        return $_SESSION['csrf_token'];
    }
    
    /**
     * Escape for SQL (use prepared statements instead!)
     */
    public static function escapeSql($input) {
        // THIS IS NOT RECOMMENDED - USE PREPARED STATEMENTS INSTEAD
        return addslashes($input);
    }
}
?>
