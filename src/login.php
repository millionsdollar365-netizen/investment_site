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
    <title>Login — <?php echo SITE_NAME; ?></title>
    <meta name="csrf-token" content="<?php echo Security::getCsrfToken(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/argon.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/assets/js/app.js"></script>
</head>
<body style="display:flex;align-items:center;justify-content:center">
    <div class="card" style="width:100%;max-width:420px;margin:2rem">
        <div class="card-header" style="justify-content:center"><h6>Login</h6></div>
        <div class="card-body">
            <form id="loginForm" style="display:flex;flex-direction:column;gap:.75rem">
                <div><label style="font-size:.78rem;font-weight:600;color:var(--argon-dark);display:block;margin-bottom:.25rem">Email</label><input type="email" id="email" name="email" required style="width:100%;padding:.45rem .6rem;border:1px solid var(--argon-border);border-radius:.25rem;font-size:.82rem"></div>
                <div><label style="font-size:.78rem;font-weight:600;color:var(--argon-dark);display:block;margin-bottom:.25rem">Password</label><input type="password" id="password" name="password" required style="width:100%;padding:.45rem .6rem;border:1px solid var(--argon-border);border-radius:.25rem;font-size:.82rem"></div>
                <button type="submit" style="background:var(--argon-primary);color:#fff;border:none;padding:.55rem;border-radius:.25rem;cursor:pointer;font-weight:600;font-size:.85rem">Login</button>
            </form>
            <p style="text-align:center;margin-top:.75rem"><a href="/forgot-password.php" style="color:var(--argon-muted);font-size:.78rem">Forgot password?</a></p>
            <p style="text-align:center;margin-top:.5rem;font-size:.78rem;color:var(--argon-muted)">Don't have an account? <a href="/register.php" style="color:var(--argon-primary)">Register here</a></p>
        </div>
    </div>
<script>
document.getElementById('loginForm').addEventListener('submit',async(e)=>{e.preventDefault();const f=new FormData(e.target);f.append('csrf_token',document.querySelector('meta[name="csrf-token"]').content);const r=await fetch('/api/auth/login.php',{method:'POST',body:f});const d=await r.json();if(d.success){window.location.href='/dashboard/'}else{alert(d.message)}});
</script>
</body>
</html>
