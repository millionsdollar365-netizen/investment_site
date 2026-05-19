<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/security.php';
requireLogout();
$token = isset($_GET['token']) ? trim($_GET['token']) : '';
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set New Password — <?php echo SITE_NAME; ?></title>
    <meta name="csrf-token" content="<?php echo Security::getCsrfToken(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/argon.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/assets/js/app.js"></script>
</head>
<body style="display:flex;align-items:center;justify-content:center">
    <div class="card" style="width:100%;max-width:420px;margin:2rem">
        <div class="card-header" style="justify-content:center"><h6>New Password</h6></div>
        <div class="card-body">
            <?php if ($token === ''): ?>
                <p style="text-align:center;color:var(--argon-muted);margin-bottom:1rem">Invalid or missing reset link.</p>
                <p style="text-align:center"><a href="/forgot-password.php" style="color:var(--argon-primary)">Request a new link</a></p>
            <?php else: ?>
            <form id="resetForm" style="display:flex;flex-direction:column;gap:.75rem">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token, ENT_QUOTES, 'UTF-8'); ?>">
                <div><label style="font-size:.78rem;font-weight:600;color:var(--argon-dark);display:block;margin-bottom:.25rem">New Password</label><input type="password" id="password" name="password" required minlength="8" style="width:100%;padding:.45rem .6rem;border:1px solid var(--argon-border);border-radius:.25rem;font-size:.82rem"></div>
                <div><label style="font-size:.78rem;font-weight:600;color:var(--argon-dark);display:block;margin-bottom:.25rem">Confirm Password</label><input type="password" id="password_confirm" name="password_confirm" required minlength="8" style="width:100%;padding:.45rem .6rem;border:1px solid var(--argon-border);border-radius:.25rem;font-size:.82rem"></div>
                <button type="submit" style="background:var(--argon-primary);color:#fff;border:none;padding:.55rem;border-radius:.25rem;cursor:pointer;font-weight:600;font-size:.85rem">Update Password</button>
            </form>
            <?php endif; ?>
            <p style="text-align:center;margin-top:.75rem"><a href="/login.php" style="color:var(--argon-primary);font-size:.78rem">Back to login</a></p>
        </div>
    </div>
<?php if ($token !== ''): ?>
<script>
document.getElementById('resetForm').addEventListener('submit',async(e)=>{e.preventDefault();const f=new FormData(e.target);f.append('csrf_token',document.querySelector('meta[name="csrf-token"]').content);const r=await fetch('/api/auth/reset-password.php',{method:'POST',body:f});const d=await r.json();if(d.success){alert(d.message);window.location.href='/login.php'}else{alert(d.message)}});
</script>
<?php endif; ?>
</body>
</html>
