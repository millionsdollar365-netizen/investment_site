<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/security.php';
requireLogout();
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — <?php echo SITE_NAME; ?></title>
    <meta name="csrf-token" content="<?php echo Security::getCsrfToken(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="/assets/img/favicon.svg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@23/build/css/intlTelInput.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@23/build/js/intlTelInput.min.js"></script>
    <script src="/assets/js/app.js?v=2"></script>
    <style>
        :root { --bg: #0f172a; --gold: #fbbf24; --muted: #94a3b8; --card-bg: rgba(30,41,59,.8); --border: rgba(148,163,184,.12); --radius: 16px; }
        * { margin:0;padding:0;box-sizing:border-box; }
        body { font-family:'Inter',sans-serif; background:var(--bg); color:#f1f5f9; min-height:100vh; display:flex;align-items:center;justify-content:center; overflow:hidden; }
        body::before { content:''; position:fixed; top:-30%;right:-20%;width:700px;height:700px; background:radial-gradient(circle,rgba(251,191,36,.08) 0%,transparent 60%); pointer-events:none; }
        body::after { content:''; position:fixed; bottom:-20%;left:-15%;width:500px;height:500px; background:radial-gradient(circle,rgba(251,191,36,.05) 0%,transparent 60%); pointer-events:none; }
        .reg-card { background:var(--card-bg); backdrop-filter:blur(20px); border:1px solid var(--border); border-radius:var(--radius); padding:2rem 2.5rem; width:100%;max-width:500px; margin:1.5rem; position:relative;z-index:1; max-height:95vh; overflow-y:auto; }
        .reg-card .logo { display:flex;justify-content:center;margin-bottom:1rem; }
        .reg-card .logo img { height:30px; }
        .reg-card h2 { font-size:1.3rem;font-weight:700;text-align:center;margin-bottom:.1rem; }
        .reg-card .subtitle { text-align:center;color:var(--muted);font-size:.8rem;margin-bottom:1.25rem; }
        .form-group { margin-bottom:.75rem; }
        .form-group label { display:block;font-size:.76rem;font-weight:600;color:#cbd5e1;margin-bottom:.25rem; }
        .form-group input { width:100%;padding:.5rem .75rem;background:rgba(15,23,42,.8);border:1px solid var(--border);border-radius:8px;color:#fff;font-size:.85rem;font-family:inherit;outline:none;transition:all .2s; }
        .form-group input:focus { border-color:var(--gold);box-shadow:0 0 0 3px rgba(251,191,36,.1); }
        .form-row { display:grid;grid-template-columns:1fr 1fr;gap:.65rem; }
        .iti { width:100%; }
        .iti__country-list { background:#1e293b !important; border:1px solid var(--border) !important; border-radius:8px !important; }
        .iti__country { color:#f1f5f9 !important; }
        .iti__country.iti__highlight { background:rgba(251,191,36,.15) !important; }
        .iti__selected-flag { background:rgba(15,23,42,.8) !important; border:1px solid var(--border) !important; border-radius:8px 0 0 8px !important; }
        .iti__search-input { background:#0f172a !important; color:#fff !important; border-color:var(--border) !important; }
        .btn-gold { width:100%;padding:.6rem;background:linear-gradient(135deg,#fbbf24,#f59e0b);color:#0f172a;border:none;border-radius:8px;font-weight:700;font-size:.9rem;cursor:pointer;transition:all .3s;font-family:inherit;margin-top:.25rem; }
        .btn-gold:hover { transform:translateY(-1px);box-shadow:0 8px 25px rgba(251,191,36,.25); }
        .links { text-align:center;margin-top:1rem;font-size:.8rem;color:var(--muted); }
        .links a { color:var(--gold);text-decoration:none;font-weight:500; }
        .links a:hover { text-decoration:underline; }
        .back-home { position:fixed;top:1.5rem;left:1.5rem;z-index:2; }
        .back-home a { color:var(--muted);text-decoration:none;font-size:.85rem;font-weight:500; }
        .back-home a:hover { color:var(--gold); }
        @media (max-width:400px) { .form-row { grid-template-columns:1fr; } }
    </style>
</head>
<body>
    <?php require_once __DIR__ . '/includes/public-header.php'; ?>
    <div class="reg-card" style="margin-top:5rem">
        <div class="logo"><img src="/assets/img/logo.svg" alt="<?php echo SITE_NAME; ?>"></div>
        <h2>Create Your Account</h2>
        <p class="subtitle">Start earning daily returns in minutes</p>
        <form id="registerForm">
            <div class="form-row">
                <div class="form-group"><label>First Name</label><input type="text" name="first_name" required placeholder="John"></div>
                <div class="form-group"><label>Last Name</label><input type="text" name="last_name" required placeholder="Doe"></div>
            </div>
            <div class="form-group"><label>Email Address</label><input type="email" name="email" required placeholder="you@example.com"></div>
            <div class="form-group"><label>Phone Number</label><input type="tel" id="phone" name="phone" required></div>
            <input type="hidden" name="phone_code" id="phoneCode">
            <input type="hidden" name="country" id="countryCode">
            <input type="hidden" name="referral_code" id="referralCode" value="<?php echo htmlspecialchars($_GET['ref'] ?? ''); ?>">
            <div class="form-group"><label>Password</label><input type="password" name="password" required placeholder="Min 8 characters" minlength="8"></div>
            <button type="submit" class="btn-gold">Create Account</button>
        </form>
        <p class="links">Already have an account? <a href="/login.php">Login here</a></p>
    </div>
<script>
let iti;
document.addEventListener('DOMContentLoaded',()=>{
    const input=document.querySelector('#phone');
    iti=window.intlTelInput(input,{
        initialCountry:'auto',
        geoIpLookup:cb=>{fetch('https://ipapi.co/json/').then(r=>r.json()).then(d=>cb(d.country_code||'US')).catch(()=>cb('US'))},
        utilsScript:'https://cdn.jsdelivr.net/npm/intl-tel-input@23/build/js/utils.js'
    });
});
document.getElementById('registerForm').addEventListener('submit',async(e)=>{
    e.preventDefault();
    const f=new FormData(e.target);
    if(iti){
        document.getElementById('phoneCode').value=iti.getSelectedCountryData().dialCode;
        document.getElementById('countryCode').value=iti.getSelectedCountryData().iso2.toUpperCase();
    }
    f.append('csrf_token',document.querySelector('meta[name="csrf-token"]').content);
    const r=await fetch('/api/auth/register.php',{method:'POST',body:f});const d=await r.json();
    if(d.success){window.location.href='/login.php?registered=1'}else{showAlert(d.message,'error')}
});
</script>
</body>
</html>
