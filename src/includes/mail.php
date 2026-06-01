<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * Email Handler — Authenticated SMTP + HTML templates
 *
 * Templates live in src/messages/ — edit them there, no PHP changes needed.
 * Uses {{placeholder}} syntax. Conditional blocks: {{#key}}...{{/key}}
 */

require_once __DIR__ . '/config.php';

class Mail {

    private static $tplDir = null;

    private static function tplDir() {
        if (self::$tplDir === null) {
            self::$tplDir = dirname(__DIR__) . '/messages/';
        }
        return self::$tplDir;
    }

    // ── Public API ──

    public static function sendWelcome($email, $first_name) {
        return self::render('welcome', [
            'first_name' => $first_name,
            'email'      => $email,
        ], 'Welcome to ' . SITE_NAME, $email);
    }

    public static function sendPasswordReset($email, $first_name, $reset_link) {
        return self::render('password-reset', [
            'first_name' => $first_name,
            'reset_link' => $reset_link,
        ], 'Password Reset — ' . SITE_NAME, $email);
    }

    public static function sendInvestmentConfirmation($email, $first_name, $data) {
        return self::render('investment-confirmation', array_merge($data, [
            'first_name' => $first_name,
        ]), 'Investment Confirmed — ' . SITE_NAME, $email);
    }

    public static function sendDepositApproved($email, $first_name, $data) {
        return self::render('deposit-approved', array_merge($data, [
            'first_name' => $first_name,
        ]), 'Deposit Approved — ' . SITE_NAME, $email);
    }

    public static function sendWithdrawalUpdate($email, $first_name, $data) {
        $status = $data['status'] ?? 'updated';
        // Dynamic color/icon based on status
        if ($status === 'approved') {
            $data['header_color'] = '#2dce89,#2dcecc';
            $data['status_bg']    = '#e8f5e9';
            $data['status_icon']  = '&#x2705;';
            $data['status_color'] = '#1aae6f';
            $data['amount_color'] = '#f5365c';
            $data['refunded']     = false;
        } elseif ($status === 'rejected') {
            $data['header_color'] = '#f5365c,#f56036';
            $data['status_bg']    = '#fff5f5';
            $data['status_icon']  = '&#x274C;';
            $data['status_color'] = '#f5365c';
            $data['amount_color'] = '#2dce89';
            $data['refunded']     = true;
        } else {
            $data['header_color'] = '#fb6340,#fbb140';
            $data['status_bg']    = '#fff8e1';
            $data['status_icon']  = '&#x23F3;';
            $data['status_color'] = '#c4541a';
            $data['amount_color'] = '#f5365c';
            $data['refunded']     = false;
        }
        return self::render('withdrawal-update', array_merge($data, [
            'first_name' => $first_name,
        ]), "Withdrawal $status — " . SITE_NAME, $email);
    }

    public static function sendRoiPayout($email, $first_name, $data) {
        return self::render('roi-payout', array_merge($data, [
            'first_name' => $first_name,
        ]), 'Daily ROI Credited — ' . SITE_NAME, $email);
    }

    public static function sendInvestmentCompleted($email, $first_name, $data) {
        return self::render('investment-completed', array_merge($data, [
            'first_name' => $first_name,
        ]), 'Investment Term Completed — ' . SITE_NAME, $email);
    }

    public static function sendAdminNotification($subject, $body, $action_url = '', $action_label = '') {
        return self::render('admin-notification', [
            'subject'      => $subject,
            'body'         => $body,
            'action_url'   => $action_url,
            'action_label' => $action_label,
            'timestamp'    => date('Y-m-d H:i:s T'),
        ], "[ADMIN] $subject", ADMIN_EMAIL);
    }

    // ── Template engine ──

    private static function render($template, $data, $subject, $to) {
        $file = self::tplDir() . $template . '.html';
        if (!file_exists($file)) {
            error_log("Mail template missing: $file");
            return false;
        }
        $html = file_get_contents($file);

        // Global defaults
        $data['site_name']   = SITE_NAME;
        $data['site_url']    = rtrim(SITE_URL, '/');
        $data['year']        = date('Y');

        // Replace {{key}} placeholders
        foreach ($data as $k => $v) {
            if (is_bool($v)) continue; // handled by conditionals
            $html = str_replace('{{' . $k . '}}', htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8'), $html);
        }

        // Handle {{{key}}} for raw HTML (no escaping)
        if (isset($data['body'])) {
            $html = str_replace('{{{body}}}', $data['body'], $html);
        }

        // Handle {{#key}}...{{/key}} conditional blocks
        $html = preg_replace_callback('/\{\{#(\w+)\}\}(.*?)\{\{\/\1\}\}/s', function ($m) use ($data) {
            return !empty($data[$m[1]]) ? $m[2] : '';
        }, $html);

        // Clean unresolved placeholders
        $html = preg_replace('/\{\{[\#\/]?\w+\}\}/', '', $html);

        return self::transmit($to, $subject, $html);
    }

    // ── SMTP transport ──

    private static function transmit($to, $subject, $html) {
        $host = defined('MAIL_HOST') ? MAIL_HOST : 'localhost';
        $port = defined('MAIL_PORT') ? (int) MAIL_PORT : 587;
        $user = defined('MAIL_USERNAME') ? MAIL_USERNAME : '';
        $pass = defined('MAIL_PASSWORD') ? MAIL_PASSWORD : '';
        $from = defined('MAIL_FROM_EMAIL') ? MAIL_FROM_EMAIL : $user;
        $from_n = defined('MAIL_FROM_NAME') ? MAIL_FROM_NAME : SITE_NAME;

        try {
            $errno = 0; $errstr = '';
            $sock = @fsockopen($host, $port, $errno, $errstr, 15);
            if (!$sock) throw new \Exception("Connect: $errstr");

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
                 . "Content-type: text/html; charset=UTF-8\r\n"
                 . "\r\n"
                 . "$html\r\n.\r\n";
            fwrite($sock, $msg);
            self::expect($sock, '250');
            fwrite($sock, "QUIT\r\n");
            fclose($sock);
            return true;

        } catch (\Throwable $e) {
            error_log('SMTP: ' . $e->getMessage() . ' — fallback mail()');
            $hdr  = "MIME-Version: 1.0\r\nContent-type: text/html; charset=UTF-8\r\n";
            $hdr .= "From: $from_n <$from>\r\nReply-To: $from\r\n";
            return @mail($to, $subject, $html, $hdr);
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
}
