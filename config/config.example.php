<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * Configuration File Template
 * 
 * DO NOT commit this file to git!
 * Copy config.example.php to config.php and update values
 */

// Load environment variables from .env if it exists
if (file_exists(__DIR__ . '/../.env')) {
    $lines = file(__DIR__ . '/../.env');
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || strpos($line, '#') === 0) continue;
        if (strpos($line, '=') === false) continue;
        
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);
        
        $_ENV[$key] = $value;
    }
}

// ============================================
// DATABASE CONFIGURATION
// ============================================
define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
define('DB_USER', $_ENV['DB_USER'] ?? 'root');
define('DB_PASS', $_ENV['DB_PASS'] ?? '');
define('DB_NAME', $_ENV['DB_NAME'] ?? 'primeaxis');
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATION', 'utf8mb4_unicode_ci');

// ============================================
// SITE CONFIGURATION
// ============================================
define('SITE_URL', $_ENV['SITE_URL'] ?? 'http://localhost:8000');
define('SITE_NAME', $_ENV['SITE_NAME'] ?? 'Primeaxis Investment');
define('SITE_TIMEZONE', $_ENV['SITE_TIMEZONE'] ?? 'UTC');
define('ADMIN_EMAIL', $_ENV['ADMIN_EMAIL'] ?? 'admin@primeaxisinv.com');

// ============================================
// MAIL CONFIGURATION
// ============================================
define('MAIL_HOST', $_ENV['MAIL_HOST'] ?? 'smtp.gmail.com');
define('MAIL_PORT', $_ENV['MAIL_PORT'] ?? 587);
define('MAIL_USERNAME', $_ENV['MAIL_USERNAME'] ?? '');
define('MAIL_PASSWORD', $_ENV['MAIL_PASSWORD'] ?? '');
define('MAIL_FROM_EMAIL', $_ENV['MAIL_FROM_EMAIL'] ?? 'noreply@primeaxisinv.com');
define('MAIL_FROM_NAME', $_ENV['MAIL_FROM_NAME'] ?? 'Primeaxis Investment');

// ============================================
// SECURITY CONFIGURATION
// ============================================
define('JWT_SECRET', $_ENV['JWT_SECRET'] ?? 'your-secret-key-change-in-production');
define('SESSION_TIMEOUT', (int)($_ENV['SESSION_TIMEOUT'] ?? 3600));
define('PASSWORD_RESET_TIMEOUT', (int)($_ENV['PASSWORD_RESET_TIMEOUT'] ?? 1800));
define('SESSION_NAME', 'PRIMEAXIS_SESSION');

// ============================================
// PAYMENT GATEWAY (Optional)
// ============================================
define('PAYMENT_GATEWAY', $_ENV['PAYMENT_GATEWAY'] ?? 'paystack');
define('PAYSTACK_PUBLIC_KEY', $_ENV['PAYSTACK_PUBLIC_KEY'] ?? '');
define('PAYSTACK_SECRET_KEY', $_ENV['PAYSTACK_SECRET_KEY'] ?? '');

// ============================================
// APP SETTINGS
// ============================================
define('APP_ENV', getenv('APP_ENV') ?: 'development');
define('DEBUG_MODE', APP_ENV === 'development');
define('LOG_LEVEL', DEBUG_MODE ? 'debug' : 'error');

// Set timezone
date_default_timezone_set(SITE_TIMEZONE);

// ============================================
// PATH CONSTANTS
// ============================================
define('BASE_PATH', dirname(dirname(__FILE__)));
define('UPLOADS_PATH', BASE_PATH . '/assets/uploads');
define('LOGS_PATH', BASE_PATH . '/logs');

// ============================================
// ERROR HANDLING (Development)
// ============================================
if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 0);
}

// ============================================
// SESSION START
// ============================================
if (session_status() === PHP_SESSION_NONE) {
    session_name(SESSION_NAME);
    session_start([
        'cookie_httponly' => true,
        'cookie_secure' => (strpos(SITE_URL, 'https://') === 0),
        'cookie_samesite' => 'Lax',
        'gc_maxlifetime' => SESSION_TIMEOUT
    ]);
}

// Generate CSRF token if none exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
