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
    <title>Register — <?php echo SITE_NAME; ?></title>
    <meta name="csrf-token" content="<?php echo Security::getCsrfToken(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/argon.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/assets/js/app.js"></script>
</head>
<body style="display:flex;align-items:center;justify-content:center">
    <div class="card" style="width:100%;max-width:420px;margin:2rem">
        <div class="card-header" style="justify-content:center"><h6>Create Account</h6></div>
        <div class="card-body">
            <form id="registerForm" style="display:flex;flex-direction:column;gap:.75rem">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem">
                    <div><label style="font-size:.78rem;font-weight:600;color:var(--argon-dark);display:block;margin-bottom:.25rem">First Name</label><input type="text" id="first_name" name="first_name" required style="width:100%;padding:.45rem .6rem;border:1px solid var(--argon-border);border-radius:.25rem;font-size:.82rem"></div>
                    <div><label style="font-size:.78rem;font-weight:600;color:var(--argon-dark);display:block;margin-bottom:.25rem">Last Name</label><input type="text" id="last_name" name="last_name" required style="width:100%;padding:.45rem .6rem;border:1px solid var(--argon-border);border-radius:.25rem;font-size:.82rem"></div>
                </div>
                <div><label style="font-size:.78rem;font-weight:600;color:var(--argon-dark);display:block;margin-bottom:.25rem">Email</label><input type="email" id="email" name="email" required style="width:100%;padding:.45rem .6rem;border:1px solid var(--argon-border);border-radius:.25rem;font-size:.82rem"></div>
                <div><label style="font-size:.78rem;font-weight:600;color:var(--argon-dark);display:block;margin-bottom:.25rem">Password</label><input type="password" id="password" name="password" required style="width:100%;padding:.45rem .6rem;border:1px solid var(--argon-border);border-radius:.25rem;font-size:.82rem"></div>
                <div><label style="font-size:.78rem;font-weight:600;color:var(--argon-dark);display:block;margin-bottom:.25rem">Referral Code (Optional)</label><input type="text" id="referral_code" name="referral_code" style="width:100%;padding:.45rem .6rem;border:1px solid var(--argon-border);border-radius:.25rem;font-size:.82rem"></div>
                <button type="submit" style="background:var(--argon-primary);color:#fff;border:none;padding:.55rem;border-radius:.25rem;cursor:pointer;font-weight:600;font-size:.85rem">Register</button>
            </form>
            <p style="text-align:center;margin-top:.75rem;font-size:.78rem;color:var(--argon-muted)">Already have an account? <a href="/login.php" style="color:var(--argon-primary)">Login here</a></p>
        </div>
    </div>
<script>
document.getElementById('registerForm').addEventListener('submit',async(e)=>{e.preventDefault();const f=new FormData(e.target);f.append('csrf_token',document.querySelector('meta[name="csrf-token"]').content);const r=await fetch('/api/auth/register.php',{method:'POST',body:f});const d=await r.json();if(d.success){alert('Registration successful! Please login.');window.location.href='/login.php'}else{alert(d.message)}});
</script>
</body>
</html>
