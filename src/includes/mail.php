<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * Email Handler (PHPMailer Wrapper)
 */

require_once __DIR__ . '/config.php';

class Mail {
    private static $instance = null;
    private $mailer;
    
    private function __construct() {
        // For now, using PHP mail() function
        // In production, consider using PHPMailer library
    }
    
    /**
     * Get singleton instance
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Mail();
        }
        return self::$instance;
    }
    
    /**
     * Send email using SMTP
     */
    public static function send($to, $subject, $body, $is_html = true) {
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type: " . ($is_html ? "text/html" : "text/plain") . "; charset=UTF-8" . "\r\n";
        $headers .= "From: " . MAIL_FROM_NAME . " <" . MAIL_FROM_EMAIL . ">" . "\r\n";
        $headers .= "Reply-To: " . MAIL_FROM_EMAIL . "\r\n";
        
        return mail($to, $subject, $body, $headers);
    }
    
    /**
     * Send registration confirmation email
     */
    public static function sendRegistrationConfirmation($email, $first_name, $verification_link) {
        $subject = 'Welcome to ' . SITE_NAME;
        
        $body = "
        <html>
        <body>
            <h2>Welcome, $first_name!</h2>
            <p>Thank you for registering on " . SITE_NAME . "</p>
            <p><a href='$verification_link'>Click here to verify your email</a></p>
            <p>If you didn't create this account, please ignore this email.</p>
            <br>
            <p>Best regards,<br>" . SITE_NAME . "</p>
        </body>
        </html>
        ";
        
        return self::send($email, $subject, $body, true);
    }
    
    /**
     * Send password reset email
     */
    public static function sendPasswordReset($email, $first_name, $reset_link) {
        $subject = 'Password Reset Request - ' . SITE_NAME;
        
        $body = "
        <html>
        <body>
            <h2>Password Reset Request</h2>
            <p>Hi $first_name,</p>
            <p>We received a request to reset your password.</p>
            <p><a href='$reset_link'>Click here to reset your password</a></p>
            <p>This link will expire in 30 minutes.</p>
            <p>If you didn't request this, please ignore this email.</p>
            <br>
            <p>Best regards,<br>" . SITE_NAME . "</p>
        </body>
        </html>
        ";
        
        return self::send($email, $subject, $body, true);
    }
    
    /**
     * Send investment confirmation email
     */
    public static function sendInvestmentConfirmation($email, $first_name, $amount, $plan_name, $duration) {
        $subject = 'Investment Confirmation - ' . SITE_NAME;
        
        $body = "
        <html>
        <body>
            <h2>Investment Confirmation</h2>
            <p>Hi $first_name,</p>
            <p>Your investment has been successfully created!</p>
            <table border='1' cellpadding='10'>
                <tr><td>Investment Plan</td><td>$plan_name</td></tr>
                <tr><td>Amount</td><td>" . formatCurrency($amount) . "</td></tr>
                <tr><td>Duration</td><td>$duration days</td></tr>
                <tr><td>Status</td><td>Active</td></tr>
            </table>
            <p>Your investment will mature in $duration days.</p>
            <br>
            <p>Best regards,<br>" . SITE_NAME . "</p>
        </body>
        </html>
        ";
        
        return self::send($email, $subject, $body, true);
    }
    
    /**
     * Send deposit request confirmation
     */
    public static function sendDepositConfirmation($email, $first_name, $amount) {
        $subject = 'Deposit Request Received - ' . SITE_NAME;
        
        $body = "
        <html>
        <body>
            <h2>Deposit Request Received</h2>
            <p>Hi $first_name,</p>
            <p>We have received your deposit request for " . formatCurrency($amount) . "</p>
            <p>Your deposit is pending approval from our team.</p>
            <p>You will receive a notification once it has been approved.</p>
            <br>
            <p>Best regards,<br>" . SITE_NAME . "</p>
        </body>
        </html>
        ";
        
        return self::send($email, $subject, $body, true);
    }
    
    /**
     * Send withdrawal request confirmation
     */
    public static function sendWithdrawalConfirmation($email, $first_name, $amount) {
        $subject = 'Withdrawal Request Received - ' . SITE_NAME;
        
        $body = "
        <html>
        <body>
            <h2>Withdrawal Request Received</h2>
            <p>Hi $first_name,</p>
            <p>We have received your withdrawal request for " . formatCurrency($amount) . "</p>
            <p>Your withdrawal is pending approval from our team.</p>
            <p>You will receive a notification once it has been processed.</p>
            <br>
            <p>Best regards,<br>" . SITE_NAME . "</p>
        </body>
        </html>
        ";
        
        return self::send($email, $subject, $body, true);
    }
    
    /**
     * Send admin notification
     */
    public static function sendAdminNotification($subject, $body) {
        return self::send(ADMIN_EMAIL, "[ADMIN] $subject", $body, true);
    }
}
?>
