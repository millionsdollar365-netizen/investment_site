<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/security.php';
requireLogout();
$token = isset($_GET['token']) ? trim($_GET['token']) : '';
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set New Password — <?php echo SITE_NAME; ?></title>
    <meta name="csrf-token" content="<?php echo Security::getCsrfToken(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="/assets/img/favicon.svg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script src="/assets/js/app.js?v=2"></script>
    <style>
        :root { --bg: #0f172a; --gold: #fbbf24; --muted: #94a3b8; --card-bg: rgba(30,41,59,.8); --border: rgba(148,163,184,.12); --radius: 16px; }
        * { margin:0;padding:0;box-sizing:border-box; }
        body { font-family:'Inter',sans-serif; background:var(--bg); color:#f1f5f9; min-height:100vh; display:flex;align-items:center;justify-content:center; }
        body::before { content:''; position:fixed; top:-30%;right:-20%;width:700px;height:700px; background:radial-gradient(circle,rgba(251,191,36,.08) 0%,transparent 60%); pointer-events:none; }
        .card { background:var(--card-bg); backdrop-filter:blur(20px); border:1px solid var(--border); border-radius:var(--radius); padding:2.5rem; width:100%;max-width:420px; margin:1.5rem; position:relative;z-index:1; }
        .card .logo { display:flex;justify-content:center;margin-bottom:1.5rem; }
        .card .logo img { height:34px; }
        .card h2 { font-size:1.35rem;font-weight:700;text-align:center;margin-bottom:.25rem; }
        .card .subtitle { text-align:center;color:var(--muted);font-size:.84rem;margin-bottom:1.75rem; }
        .form-group { margin-bottom:1rem; }
        .form-group label { display:block;font-size:.8rem;font-weight:600;color:#cbd5e1;margin-bottom:.35rem; }
        .form-group input { width:100%;padding:.6rem .85rem;background:rgba(15,23,42,.8);border:1px solid var(--border);border-radius:10px;color:#fff;font-size:.9rem;font-family:inherit;outline:none;transition:all .2s; }
        .form-group input:focus { border-color:var(--gold);box-shadow:0 0 0 3px rgba(251,191,36,.1); }
        .btn-gold { width:100%;padding:.7rem;background:linear-gradient(135deg,#fbbf24,#f59e0b);color:#0f172a;border:none;border-radius:10px;font-weight:700;font-size:.95rem;cursor:pointer;transition:all .3s;font-family:inherit;margin-top:.25rem; }
        .btn-gold:hover { transform:translateY(-1px);box-shadow:0 8px 25px rgba(251,191,36,.25); }
        .links { text-align:center;margin-top:1.25rem;font-size:.82rem;color:var(--muted); }
        .links a { color:var(--gold);text-decoration:none;font-weight:500; }
        .links a:hover { text-decoration:underline; }
        .back-home { position:fixed;top:1.5rem;left:1.5rem;z-index:2; }
        .back-home a { color:var(--muted);text-decoration:none;font-size:.85rem;font-weight:500; }
        .back-home a:hover { color:var(--gold); }
    </style>
</head>
<body>
    <?php require_once __DIR__ . '/includes/public-header.php'; ?>
    <div class="card" style="margin-top:5rem">
        <div class="logo"><img src="/assets/img/logo.svg" alt="<?php echo SITE_NAME; ?>"></div>
        <h2>New Password</h2>
        <p class="subtitle"><?php echo $token ? 'Choose a strong password' : 'Invalid or missing reset link'; ?></p>
        <?php if ($token): ?>
        <form id="resetForm"><input type="hidden" name="token" value="<?php echo htmlspecialchars($token, ENT_QUOTES, 'UTF-8'); ?>"><div class="form-group"><label>New Password</label><input type="password" id="password" name="password" required minlength="8" placeholder="Min 8 characters"></div><div class="form-group"><label>Confirm Password</label><input type="password" id="password_confirm" name="password_confirm" required minlength="8" placeholder="Re-enter password"></div><button type="submit" class="btn-gold">Update Password</button></form>
        <?php else: ?>
        <p style="text-align:center;margin-bottom:1rem"><a href="/forgot-password.php" style="color:var(--gold)">Request a new link</a></p>
        <?php endif; ?>
        <p class="links"><a href="/login.php">Back to login</a></p>
    </div>
<?php if ($token): ?>
<script>
document.getElementById('resetForm').addEventListener('submit',async(e)=>{e.preventDefault();const f=new FormData(e.target);f.append('csrf_token',document.querySelector('meta[name="csrf-token"]').content);const r=await fetch('/api/auth/reset-password.php',{method:'POST',body:f});const d=await r.json();if(d.success){showAlert(d.message,'success');setTimeout(()=>{window.location.href='/login.php'},1500)}else{showAlert(d.message,'error')}});
</script>
<?php endif; ?>
</body>
</html>
