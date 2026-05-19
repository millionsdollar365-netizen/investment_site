<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
$user = getCurrentUser();
$nav_type = 'user'; $active_nav = 'settings';
$page_title = 'Settings'; $page_subtitle = 'Change your password';
require_once __DIR__ . '/../includes/argon-header.php';
?>
<div class="card" style="max-width:500px"><div class="card-header"><h6>Change Password</h6></div>
<div class="card-body"><form id="passwordForm" style="display:flex;flex-direction:column;gap:.75rem">
    <div><label style="font-size:.78rem;font-weight:600;color:var(--argon-dark);display:block;margin-bottom:.25rem">Current Password</label><input type="password" name="current_password" required style="width:100%;padding:.45rem .6rem;border:1px solid var(--argon-border);border-radius:.25rem;font-size:.82rem"></div>
    <div><label style="font-size:.78rem;font-weight:600;color:var(--argon-dark);display:block;margin-bottom:.25rem">New Password</label><input type="password" name="new_password" required minlength="8" style="width:100%;padding:.45rem .6rem;border:1px solid var(--argon-border);border-radius:.25rem;font-size:.82rem"></div>
    <div><label style="font-size:.78rem;font-weight:600;color:var(--argon-dark);display:block;margin-bottom:.25rem">Confirm New Password</label><input type="password" name="new_password_confirm" required minlength="8" style="width:100%;padding:.45rem .6rem;border:1px solid var(--argon-border);border-radius:.25rem;font-size:.82rem"></div>
    <div><button type="submit" style="background:var(--argon-primary);color:#fff;border:none;padding:.5rem 1.5rem;border-radius:.25rem;cursor:pointer;font-weight:600">Change Password</button></div>
</form></div></div>
<script>
document.getElementById('passwordForm').addEventListener('submit',async(e)=>{e.preventDefault();const f=new FormData(e.target);const r=await fetch('/api/user/change-password.php',{method:'POST',body:f});const d=await r.json();alert(d.message);if(d.success)e.target.reset()});
</script>
<?php require_once __DIR__ . '/../includes/argon-footer.php'; ?>
