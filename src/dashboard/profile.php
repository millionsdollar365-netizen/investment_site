<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
$user = getCurrentUser();
$nav_type = 'user'; $active_nav = 'profile';
$page_title = 'Profile'; $page_subtitle = 'Manage your personal information';
require_once __DIR__ . '/../includes/argon-header.php';
?>
<div class="duo">
    <div class="card"><div class="card-header"><h6>Personal Information</h6></div>
    <div class="card-body"><form id="profileForm" style="display:flex;flex-direction:column;gap:.75rem">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem">
            <div><label style="font-size:.78rem;font-weight:600;color:var(--argon-dark);display:block;margin-bottom:.25rem">First Name</label><input type="text" name="first_name" required value="<?php echo htmlspecialchars($user['first_name']); ?>" style="width:100%;padding:.45rem .6rem;border:1px solid var(--argon-border);border-radius:.25rem;font-size:.82rem"></div>
            <div><label style="font-size:.78rem;font-weight:600;color:var(--argon-dark);display:block;margin-bottom:.25rem">Last Name</label><input type="text" name="last_name" required value="<?php echo htmlspecialchars($user['last_name']); ?>" style="width:100%;padding:.45rem .6rem;border:1px solid var(--argon-border);border-radius:.25rem;font-size:.82rem"></div>
        </div>
        <div><label style="font-size:.78rem;font-weight:600;color:var(--argon-dark);display:block;margin-bottom:.25rem">Email</label><input type="email" disabled value="<?php echo htmlspecialchars($user['email']); ?>" style="width:100%;padding:.45rem .6rem;border:1px solid var(--argon-border);border-radius:.25rem;font-size:.82rem;background:var(--argon-light)"></div>
        <div><label style="font-size:.78rem;font-weight:600;color:var(--argon-dark);display:block;margin-bottom:.25rem">Phone</label><input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" style="width:100%;padding:.45rem .6rem;border:1px solid var(--argon-border);border-radius:.25rem;font-size:.82rem"></div>
        <div><label style="font-size:.78rem;font-weight:600;color:var(--argon-dark);display:block;margin-bottom:.25rem">Bio</label><textarea name="bio" rows="3" style="width:100%;padding:.45rem .6rem;border:1px solid var(--argon-border);border-radius:.25rem;font-size:.82rem"><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea></div>
        <div><button type="submit" style="background:var(--argon-primary);color:#fff;border:none;padding:.5rem 1.5rem;border-radius:.25rem;cursor:pointer;font-weight:600">Update Profile</button></div>
    </form></div></div>

    <div class="card"><div class="card-header"><h6>Account Info</h6></div>
    <div class="card-body"><div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;font-size:.82rem">
        <div><span style="color:var(--argon-muted)">Referral Code:</span> <strong><?php echo htmlspecialchars($user['referral_code']); ?></strong></div>
        <div><span style="color:var(--argon-muted)">Status:</span> <strong><?php echo htmlspecialchars($user['status']); ?></strong></div>
        <div><span style="color:var(--argon-muted)">Joined:</span> <strong><?php echo htmlspecialchars($user['created_at']); ?></strong></div>
        <div><span style="color:var(--argon-muted)">KYC Status:</span> <strong><?php echo htmlspecialchars($user['kyc_status']); ?></strong></div>
    </div></div></div>
</div>
<script>
document.getElementById('profileForm').addEventListener('submit',async(e)=>{e.preventDefault();const f=new FormData(e.target);const r=await fetch('/api/user/update-profile.php',{method:'POST',body:f});const d=await r.json();alert(d.message)});
</script>
<?php require_once __DIR__ . '/../includes/argon-footer.php'; ?>
