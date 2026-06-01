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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js@1.12.0/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js@1.12.0"></script>
    <script src="/assets/js/app.js?v=2"></script>
    <style>
        :root { --bg: #0f172a; --gold: #fbbf24; --gold-light: rgba(251,191,36,.15); --text: #f1f5f9; --muted: #94a3b8; --card-bg: rgba(30,41,59,.8); --border: rgba(148,163,184,.12); --radius: 16px; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; display: flex; align-items: center; justify-content: center; overflow: hidden; }
        body::before { content: ''; position: fixed; top: -30%; right: -20%; width: 700px; height: 700px; background: radial-gradient(circle, rgba(251,191,36,.08) 0%, transparent 60%); pointer-events: none; }
        body::after { content: ''; position: fixed; bottom: -20%; left: -15%; width: 500px; height: 500px; background: radial-gradient(circle, rgba(251,191,36,.05) 0%, transparent 60%); pointer-events: none; }
        .login-card { background: var(--card-bg); backdrop-filter: blur(20px); border: 1px solid var(--border); border-radius: var(--radius); padding: 2.5rem; width: 100%; max-width: 420px; margin: 1.5rem; position: relative; z-index: 1; }
        .login-card .logo { display: flex; justify-content: center; margin-bottom: 1.5rem; }
        .login-card .logo img { height: 36px; }
        .login-card h2 { font-size: 1.4rem; font-weight: 700; text-align: center; margin-bottom: .25rem; }
        .login-card .subtitle { text-align: center; color: var(--muted); font-size: .85rem; margin-bottom: 1.75rem; }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; font-size: .8rem; font-weight: 600; color: #cbd5e1; margin-bottom: .35rem; }
        .form-group input { width: 100%; padding: .6rem .85rem; background: rgba(15,23,42,.8); border: 1px solid var(--border); border-radius: 10px; color: #fff; font-size: .9rem; font-family: inherit; transition: all .2s; outline: none; }
        .form-group input:focus { border-color: var(--gold); box-shadow: 0 0 0 3px rgba(251,191,36,.1); }
        .btn-gold { width: 100%; padding: .7rem; background: linear-gradient(135deg, #fbbf24, #f59e0b); color: #0f172a; border: none; border-radius: 10px; font-weight: 700; font-size: .95rem; cursor: pointer; transition: all .3s; font-family: inherit; margin-top: .25rem; }
        .btn-gold:hover { transform: translateY(-1px); box-shadow: 0 8px 25px rgba(251,191,36,.25); }
        .links { text-align: center; margin-top: 1.25rem; font-size: .82rem; color: var(--muted); }
        .links a { color: var(--gold); text-decoration: none; font-weight: 500; transition: color .2s; }
        .links a:hover { color: #f59e0b; text-decoration: underline; }
        .back-home { position: fixed; top: 1.5rem; left: 1.5rem; z-index: 2; }
        .back-home a { color: var(--muted); text-decoration: none; font-size: .85rem; font-weight: 500; transition: color .2s; }
        .back-home a:hover { color: var(--gold); }
    </style>
</head>
<body>
    <div class="back-home"><a href="/"><i class="fas fa-arrow-left"></i> Back to Home</a></div>
    <div class="login-card">
        <div class="logo"><img src="/assets/img/logo.svg" alt="<?php echo SITE_NAME; ?>"></div>
        <h2>Welcome Back</h2>
        <p class="subtitle">Login to access your dashboard</p>
        <form id="loginForm">
            <div class="form-group"><label>Email Address</label><input type="email" id="email" name="email" required placeholder="you@example.com"></div>
            <div class="form-group"><label>Password</label><input type="password" id="password" name="password" required placeholder="Enter your password"></div>
            <button type="submit" class="btn-gold">Login</button>
        </form>
        <p class="links"><a href="/forgot-password.php">Forgot your password?</a></p>
        <p class="links" style="margin-top:.5rem">Don't have an account? <a href="/register.php">Create one</a></p>
    </div>
<script>
document.getElementById('loginForm').addEventListener('submit',async(e)=>{e.preventDefault();const f=new FormData(e.target);f.append('csrf_token',document.querySelector('meta[name="csrf-token"]').content);const r=await fetch('/api/auth/login.php',{method:'POST',body:f});const d=await r.json();if(d.success){window.location.href='/dashboard/?login=1'}else{showAlert(d.message,'error')}});
setTimeout(()=>{const p=new URLSearchParams(window.location.search);if(p.get('registered')==='1'){showToast('Account created! You can now sign in.','success');window.history.replaceState({},document.title,window.location.pathname)};},300);
</script>
</body>
</html>
