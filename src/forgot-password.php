<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/security.php';
requireLogout();
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password — <?php echo SITE_NAME; ?></title>
    <meta name="csrf-token" content="<?php echo Security::getCsrfToken(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/argon.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/assets/js/app.js"></script>
</head>
<body style="display:flex;align-items:center;justify-content:center">
    <div class="card" style="width:100%;max-width:420px;margin:2rem">
        <div class="card-header" style="justify-content:center"><h6>Reset Password</h6></div>
        <div class="card-body">
            <form id="forgotForm" style="display:flex;flex-direction:column;gap:.75rem">
                <div><label style="font-size:.78rem;font-weight:600;color:var(--argon-dark);display:block;margin-bottom:.25rem">Email</label><input type="email" id="email" name="email" required style="width:100%;padding:.45rem .6rem;border:1px solid var(--argon-border);border-radius:.25rem;font-size:.82rem"></div>
                <button type="submit" style="background:var(--argon-primary);color:#fff;border:none;padding:.55rem;border-radius:.25rem;cursor:pointer;font-weight:600;font-size:.85rem">Send Reset Link</button>
            </form>
            <p style="text-align:center;margin-top:.75rem"><a href="/login.php" style="color:var(--argon-primary);font-size:.78rem">Back to login</a></p>
        </div>
    </div>
<script>
document.getElementById('forgotForm').addEventListener('submit',async(e)=>{e.preventDefault();const f=new FormData(e.target);f.append('csrf_token',document.querySelector('meta[name="csrf-token"]').content);const r=await fetch('/api/auth/forgot-password.php',{method:'POST',body:f});const d=await r.json();showAlert(d.message||(d.success?'Check your email — reset link sent!':'Something went wrong.'),d.success?'success':'error')});
</script>
</body>
</html>
