<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/admin-session.php';
require_once __DIR__ . '/../includes/security.php';
requireAdminLogout();
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — <?php echo SITE_NAME; ?></title>
    <meta name="csrf-token" content="<?php echo Security::getCsrfToken(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="/assets/img/favicon.svg">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/argon.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/assets/js/app.js?v=2"></script>
</head>
<body style="display:flex;align-items:center;justify-content:center">
    <div class="card" style="width:100%;max-width:420px;margin:2rem;border-top:3px solid var(--argon-danger)">
        <div class="card-header" style="justify-content:center"><h6>Admin Portal</h6></div>
        <div class="card-body">
            <p style="text-align:center;color:var(--argon-muted);font-size:.78rem;margin-bottom:1rem">Restricted Access Only</p>
            <form id="adminLoginForm" style="display:flex;flex-direction:column;gap:.75rem">
                <div><label style="font-size:.78rem;font-weight:600;color:var(--argon-dark);display:block;margin-bottom:.25rem">Username</label><input type="text" id="username" name="username" required style="width:100%;padding:.45rem .6rem;border:1px solid var(--argon-border);border-radius:.25rem;font-size:.82rem"></div>
                <div><label style="font-size:.78rem;font-weight:600;color:var(--argon-dark);display:block;margin-bottom:.25rem">Password</label><input type="password" id="password" name="password" required style="width:100%;padding:.45rem .6rem;border:1px solid var(--argon-border);border-radius:.25rem;font-size:.82rem"></div>
                <button type="submit" style="background:var(--argon-danger);color:#fff;border:none;padding:.55rem;border-radius:.25rem;cursor:pointer;font-weight:600;font-size:.85rem">Login</button>
            </form>
            <p style="text-align:center;margin-top:.75rem"><a href="/" style="color:var(--argon-muted);font-size:.78rem">Back to Home</a></p>
        </div>
    </div>
<script>
document.getElementById('adminLoginForm').addEventListener('submit',async(e)=>{e.preventDefault();const f=new FormData(e.target);f.append('csrf_token',document.querySelector('meta[name="csrf-token"]').content);const r=await fetch('/api/admin/login.php',{method:'POST',body:f});const d=await r.json();if(d.success){window.location.href='/admin/'}else{alert(d.message)}});
// Logout redirect alert
const urlParams=new URLSearchParams(window.location.search);
if(urlParams.get('logout')==='1'){
    Swal.fire({icon:'success',title:'Logged Out',text:'You have been logged out successfully.',timer:2500,showConfirmButton:false});
    window.history.replaceState({},document.title,window.location.pathname);
}
</script>
</body>
</html>
