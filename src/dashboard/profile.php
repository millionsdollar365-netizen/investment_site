<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
$user = getCurrentUser();
$initials = strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1));
$nav_type = 'user'; $active_nav = 'profile';
$page_title = 'Profile'; $page_subtitle = 'Manage your personal information';
require_once __DIR__ . '/../includes/argon-header.php';
?>
<div class="duo">
    <div class="card"><div class="card-header"><h6>Personal Information</h6></div>
    <div class="card-body">
        <!-- Avatar -->
        <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.25rem">
            <div id="avatarPreview" style="width:72px;height:72px;border-radius:50%;overflow:hidden;flex-shrink:0;background:linear-gradient(87deg,#5e72e4,#825ee4);display:flex;align-items:center;justify-content:center">
                <?php if (!empty($user['avatar'])): ?>
                    <img src="<?php echo htmlspecialchars($user['avatar']); ?>" style="width:100%;height:100%;object-fit:cover" onerror="this.parentElement.innerHTML='<span style=color:#fff;font-size:1.3rem;font-weight:700><?php echo $initials; ?></span>'">
                <?php else: ?>
                    <span style="color:#fff;font-size:1.3rem;font-weight:700"><?php echo $initials; ?></span>
                <?php endif; ?>
            </div>
            <div>
                <label for="avatarInput" id="avatarLabel" style="background:var(--argon-light);border:1px solid var(--argon-border);padding:.4rem 1rem;border-radius:.25rem;cursor:pointer;font-size:.78rem;font-weight:600;color:var(--argon-text)">
                    <?php echo empty($user['avatar']) ? 'Upload Profile Picture' : 'Change Photo'; ?>
                </label>
                <input type="file" id="avatarInput" name="avatar" accept="image/jpeg,image/png" style="display:none" onchange="uploadAvatar()">
                <div style="font-size:.7rem;color:var(--argon-muted);margin-top:.25rem">JPG or PNG, max 2MB</div>
            </div>
        </div>

        <form id="profileForm" style="display:flex;flex-direction:column;gap:.75rem">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem">
                <div><label style="font-size:.78rem;font-weight:600;color:var(--argon-dark);display:block;margin-bottom:.25rem">First Name</label><input type="text" name="first_name" required value="<?php echo htmlspecialchars($user['first_name']); ?>" style="width:100%;padding:.45rem .6rem;border:1px solid var(--argon-border);border-radius:.25rem;font-size:.82rem"></div>
                <div><label style="font-size:.78rem;font-weight:600;color:var(--argon-dark);display:block;margin-bottom:.25rem">Last Name</label><input type="text" name="last_name" required value="<?php echo htmlspecialchars($user['last_name']); ?>" style="width:100%;padding:.45rem .6rem;border:1px solid var(--argon-border);border-radius:.25rem;font-size:.82rem"></div>
            </div>
            <div><label style="font-size:.78rem;font-weight:600;color:var(--argon-dark);display:block;margin-bottom:.25rem">Email</label><input type="email" disabled value="<?php echo htmlspecialchars($user['email']); ?>" style="width:100%;padding:.45rem .6rem;border:1px solid var(--argon-border);border-radius:.25rem;font-size:.82rem;background:var(--argon-light)"></div>
            <div><label style="font-size:.78rem;font-weight:600;color:var(--argon-dark);display:block;margin-bottom:.25rem">Phone</label><input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" style="width:100%;padding:.45rem .6rem;border:1px solid var(--argon-border);border-radius:.25rem;font-size:.82rem"></div>
            <div><label style="font-size:.78rem;font-weight:600;color:var(--argon-dark);display:block;margin-bottom:.25rem">Bio</label><textarea name="bio" rows="3" style="width:100%;padding:.45rem .6rem;border:1px solid var(--argon-border);border-radius:.25rem;font-size:.82rem"><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea></div>
            <div><button type="submit" style="background:var(--argon-primary);color:#fff;border:none;padding:.5rem 1.5rem;border-radius:.25rem;cursor:pointer;font-weight:600">Update Profile</button></div>
        </form>
    </div></div>

    <div class="card"><div class="card-header"><h6>Payment Wallets</h6></div>
    <div class="card-body" style="display:flex;flex-direction:column;gap:.75rem">
        <div><label style="font-size:.78rem;font-weight:600;color:var(--argon-dark);display:block;margin-bottom:.25rem"><i class="fab fa-bitcoin" style="color:#f7931a"></i> Bitcoin (BTC) Wallet</label><div style="display:flex;gap:.5rem"><input type="text" id="wallet_btc" name="wallet_btc" value="<?php echo htmlspecialchars($user['wallet_btc'] ?? ''); ?>" placeholder="Your BTC wallet address" style="flex:1;padding:.45rem .6rem;border:1px solid var(--argon-border);border-radius:.25rem;font-size:.82rem"><button onclick="saveWallet('wallet_btc')" style="background:var(--argon-primary);color:#fff;border:none;padding:.45rem 1rem;border-radius:.25rem;cursor:pointer;font-weight:600;font-size:.78rem;white-space:nowrap">Save BTC</button></div></div>
        <div><label style="font-size:.78rem;font-weight:600;color:var(--argon-dark);display:block;margin-bottom:.25rem"><i class="fas fa-dollar-sign" style="color:#26a17b"></i> USDT (Tether) Wallet</label><div style="display:flex;gap:.5rem"><input type="text" id="wallet_usdt" name="wallet_usdt" value="<?php echo htmlspecialchars($user['wallet_usdt'] ?? ''); ?>" placeholder="Your USDT wallet address" style="flex:1;padding:.45rem .6rem;border:1px solid var(--argon-border);border-radius:.25rem;font-size:.82rem"><button onclick="saveWallet('wallet_usdt')" style="background:var(--argon-primary);color:#fff;border:none;padding:.45rem 1rem;border-radius:.25rem;cursor:pointer;font-weight:600;font-size:.78rem;white-space:nowrap">Save USDT</button></div></div>
        <div><label style="font-size:.78rem;font-weight:600;color:var(--argon-dark);display:block;margin-bottom:.25rem"><i class="fab fa-ethereum" style="color:#627eea"></i> Ethereum (ETH) Wallet</label><div style="display:flex;gap:.5rem"><input type="text" id="wallet_ethereum" name="wallet_ethereum" value="<?php echo htmlspecialchars($user['wallet_ethereum'] ?? ''); ?>" placeholder="Your ETH wallet address" style="flex:1;padding:.45rem .6rem;border:1px solid var(--argon-border);border-radius:.25rem;font-size:.82rem"><button onclick="saveWallet('wallet_ethereum')" style="background:var(--argon-primary);color:#fff;border:none;padding:.45rem 1rem;border-radius:.25rem;cursor:pointer;font-weight:600;font-size:.78rem;white-space:nowrap">Save ETH</button></div></div>
    </div></div>

    <div class="card"><div class="card-header"><h6>Account Info</h6></div>
    <div class="card-body"><div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;font-size:.82rem">
        <div><span style="color:var(--argon-muted)">Referral Code:</span> <strong><?php echo htmlspecialchars($user['referral_code']); ?></strong></div>
        <div><span style="color:var(--argon-muted)">Status:</span> <strong><?php echo htmlspecialchars($user['status']); ?></strong></div>
        <div><span style="color:var(--argon-muted)">Joined:</span> <strong><?php echo htmlspecialchars($user['created_at']); ?></strong></div>
        <div><span style="color:var(--argon-muted)">KYC Status:</span> <strong><?php echo htmlspecialchars($user['kyc_status']); ?></strong></div>
    </div></div></div>
</div>
<script>
async function uploadAvatar(){
    const file=document.getElementById('avatarInput').files[0];
    if(!file)return;
    // Preview immediately
    const reader=new FileReader();
    reader.onload=function(e){
        document.getElementById('avatarPreview').innerHTML='<img src="'+e.target.result+'" style="width:100%;height:100%;object-fit:cover">';
    };
    reader.readAsDataURL(file);
    // Upload
    const f=new FormData();
    f.append('avatar',file);
    const r=await fetch('/api/user/update-profile.php',{method:'POST',body:f});
    const d=await r.json();
    if(d.success){
        document.getElementById('avatarInput').value='';
        document.getElementById('avatarLabel').textContent='Change Photo';
        Swal.fire({icon:'success',title:'Done!',text:'Profile picture updated successfully.',timer:2000,showConfirmButton:false});
    }else{
        Swal.fire({icon:'error',title:'Error',text:d.message});
    }
}
document.getElementById('profileForm').addEventListener('submit',async(e)=>{
    e.preventDefault();
    const f=new FormData(e.target);
    const r=await fetch('/api/user/update-profile.php',{method:'POST',body:f});
    const d=await r.json();
    if(d.success){
        Swal.fire({icon:'success',title:'Saved',text:'Profile updated successfully.',timer:2000,showConfirmButton:false});
    }else{
        Swal.fire({icon:'error',title:'Error',text:d.message});
    }
});
async function saveWallet(key){
    const val=document.getElementById(key).value.trim();
    const f=new FormData();
    f.append(key,val);
    const r=await fetch('/api/user/update-profile.php',{method:'POST',body:f});
    const d=await r.json();
    Swal.fire({icon:d.success?'success':'error',title:d.success?'Saved':'Error',text:d.message,timer:d.success?2000:undefined,showConfirmButton:!d.success});
}
</script>
<?php require_once __DIR__ . '/../includes/argon-footer.php'; ?>
