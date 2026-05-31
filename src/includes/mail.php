<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * Email Handler — Authenticated SMTP
 *
 * Uses PHP sockets for SMTP auth (no PHPMailer dependency).
 * Falls back to PHP mail() if SMTP fails.
 */

require_once __DIR__ . '/config.php';

class Mail {

    public static function send($to, $subject, $body, $is_html = true) {
        $host   = defined('MAIL_HOST')     ? MAIL_HOST     : 'localhost';
        $port   = defined('MAIL_PORT')     ? MAIL_PORT     : 587;
        $user   = defined('MAIL_USERNAME') ? MAIL_USERNAME : '';
        $pass   = defined('MAIL_PASSWORD') ? MAIL_PASSWORD : '';
        $from   = defined('MAIL_FROM_EMAIL') ? MAIL_FROM_EMAIL : $user;
        $from_n = defined('MAIL_FROM_NAME')  ? MAIL_FROM_NAME  : SITE_NAME;
        $ctype  = $is_html ? 'text/html' : 'text/plain';

        // Try SMTP first
        try {
            $errno = 0; $errstr = '';
            $sock = @fsockopen($host, $port, $errno, $errstr, 15);
            if (!$sock) throw new \Exception("Connect failed: $errstr");

            self::expect($sock, '220');
            fwrite($sock, "EHLO $host\r\n");           self::expect($sock, '250');
            fwrite($sock, "STARTTLS\r\n");              self::expect($sock, '220');
            stream_socket_enable_crypto($sock, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
            fwrite($sock, "EHLO $host\r\n");           self::expect($sock, '250');
            fwrite($sock, "AUTH LOGIN\r\n");            self::expect($sock, '334');
            fwrite($sock, base64_encode($user)."\r\n"); self::expect($sock, '334');
            fwrite($sock, base64_encode($pass)."\r\n"); self::expect($sock, '235');
            fwrite($sock, "MAIL FROM:<$from>\r\n");     self::expect($sock, '250');
            fwrite($sock, "RCPT TO:<$to>\r\n");         self::expect($sock, '250');
            fwrite($sock, "DATA\r\n");                  self::expect($sock, '354');

            $msg = "From: $from_n <$from>\r\n"
                 . "To: $to\r\n"
                 . "Subject: $subject\r\n"
                 . "MIME-Version: 1.0\r\n"
                 . "Content-type: $ctype; charset=UTF-8\r\n"
                 . "\r\n"
                 . "$body\r\n.\r\n";
            fwrite($sock, $msg);
            self::expect($sock, '250');
            fwrite($sock, "QUIT\r\n");
            fclose($sock);
            return true;

        } catch (\Throwable $e) {
            error_log('SMTP failed (' . $e->getMessage() . ') — fallback to mail()');

            $hdr  = "MIME-Version: 1.0\r\nContent-type: $ctype; charset=UTF-8\r\n";
            $hdr .= "From: $from_n <$from>\r\nReply-To: $from\r\n";
            return @mail($to, $subject, $body, $hdr);
        }
    }

    private static function expect($sock, $code) {
        $reply = '';
        for ($i = 0; $i < 50; $i++) {
            $line = fgets($sock, 515);
            if ($line === false) break;
            $reply .= $line;
            if (isset($line[3]) && $line[3] === ' ') break;
        }
        $got = substr($reply, 0, 3);
        if ($got !== $code) throw new \Exception("Expected $code, got $got — $reply");
        return $reply;
    }

    // ── Templates ──

    public static function sendWelcome($email, $first_name) {
        $subj = 'Welcome to ' . SITE_NAME;
        $body = "<h2>Welcome, $first_name!</h2><p>Your account has been created.</p><p><a href='" . SITE_URL . "/login.php'>Login here</a></p><p>— " . SITE_NAME . "</p>";
        return self::send($email, $subj, $body);
    }

    public static function sendPasswordReset($email, $first_name, $reset_link) {
        $subj = 'Password Reset — ' . SITE_NAME;
        $body = "<h2>Password Reset</h2><p>Hi $first_name,</p><p>Click below to reset:</p><p><a href='$reset_link'>$reset_link</a></p><p>Expires in 30 min.</p><p>— " . SITE_NAME . "</p>";
        return self::send($email, $subj, $body);
    }

    public static function sendInvestmentConfirmation($email, $first_name, $amount, $plan_name, $duration) {
        $subj = 'Investment Confirmed — ' . SITE_NAME;
        $body = "<h2>Investment Created</h2><p>Hi $first_name,</p><p>Plan: <strong>$plan_name</strong><br>Amount: <strong>" . formatCurrency($amount) . "</strong><br>Duration: <strong>$duration days</strong></p><p>— " . SITE_NAME . "</p>";
        return self::send($email, $subj, $body);
    }

    public static function sendDepositApproved($email, $first_name, $amount) {
        $subj = 'Deposit Approved — ' . SITE_NAME;
        $body = "<h2>Deposit Approved</h2><p>Hi $first_name,</p><p>Your deposit of <strong>" . formatCurrency($amount) . "</strong> has been approved and credited.</p><p>— " . SITE_NAME . "</p>";
        return self::send($email, $subj, $body);
    }

    public static function sendWithdrawalProcessed($email, $first_name, $amount, $status) {
        $subj = "Withdrawal $status — " . SITE_NAME;
        $body = "<h2>Withdrawal $status</h2><p>Hi $first_name,</p><p>Your withdrawal of <strong>" . formatCurrency($amount) . "</strong> has been $status.</p><p>— " . SITE_NAME . "</p>";
        return self::send($email, $subj, $body);
    }

    public static function sendAdminNotification($subject, $body) {
        return self::send(defined('ADMIN_EMAIL') ? ADMIN_EMAIL : $GLOBALS['_ENV']['ADMIN_EMAIL'] ?? '', "[ADMIN] $subject", $body);
    }
}
