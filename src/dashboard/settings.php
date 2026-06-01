<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
$user = getCurrentUser();
$nav_type = 'user'; $active_nav = 'settings';
$page_title = 'Settings'; $page_subtitle = 'Manage wallets and account security';
require_once __DIR__ . '/../includes/argon-header.php';
?>

<!-- Wallet Settings -->
<div class="card" style="margin-bottom:1.25rem">
    <div class="card-header"><h6>Wallet Settings</h6></div>
    <div class="card-body" style="display:flex;flex-direction:column;gap:1rem">
        <div>
            <label style="font-size:.78rem;font-weight:600;color:var(--argon-dark);display:block;margin-bottom:.25rem"><i class="fab fa-bitcoin" style="color:#f7931a"></i> Bitcoin (BTC) Wallet</label>
            <div style="display:flex;gap:.5rem"><input type="text" id="wallet_btc" value="<?php echo htmlspecialchars($user['wallet_btc'] ?? ''); ?>" placeholder="Your BTC wallet address" style="flex:1;padding:.45rem .6rem;border:1px solid var(--argon-border);border-radius:.25rem;font-size:.82rem"><button onclick="saveWallet('wallet_btc')" style="background:#f7931a;color:#fff;border:none;padding:.45rem 1rem;border-radius:.25rem;cursor:pointer;font-weight:600;font-size:.78rem;white-space:nowrap">Save</button></div>
        </div>
        <div>
            <label style="font-size:.78rem;font-weight:600;color:var(--argon-dark);display:block;margin-bottom:.25rem"><i class="fas fa-dollar-sign" style="color:#26a17b"></i> USDT (Tether) Wallet</label>
            <div style="display:flex;gap:.5rem"><input type="text" id="wallet_usdt" value="<?php echo htmlspecialchars($user['wallet_usdt'] ?? ''); ?>" placeholder="Your USDT wallet address" style="flex:1;padding:.45rem .6rem;border:1px solid var(--argon-border);border-radius:.25rem;font-size:.82rem"><button onclick="saveWallet('wallet_usdt')" style="background:#26a17b;color:#fff;border:none;padding:.45rem 1rem;border-radius:.25rem;cursor:pointer;font-weight:600;font-size:.78rem;white-space:nowrap">Save</button></div>
        </div>
        <div>
            <label style="font-size:.78rem;font-weight:600;color:var(--argon-dark);display:block;margin-bottom:.25rem"><i class="fab fa-ethereum" style="color:#627eea"></i> Ethereum (ETH) Wallet</label>
            <div style="display:flex;gap:.5rem"><input type="text" id="wallet_ethereum" value="<?php echo htmlspecialchars($user['wallet_ethereum'] ?? ''); ?>" placeholder="Your ETH wallet address" style="flex:1;padding:.45rem .6rem;border:1px solid var(--argon-border);border-radius:.25rem;font-size:.82rem"><button onclick="saveWallet('wallet_ethereum')" style="background:#627eea;color:#fff;border:none;padding:.45rem 1rem;border-radius:.25rem;cursor:pointer;font-weight:600;font-size:.78rem;white-space:nowrap">Save</button></div>
        </div>
    </div>
</div>

<!-- Password Settings -->
<div class="card">
    <div class="card-header"><h6>Password Settings</h6></div>
    <div class="card-body"><form id="passwordForm" style="display:flex;flex-direction:column;gap:.75rem">
        <div><label style="font-size:.78rem;font-weight:600;color:var(--argon-dark);display:block;margin-bottom:.25rem">Current Password</label><input type="password" name="current_password" required style="width:100%;padding:.45rem .6rem;border:1px solid var(--argon-border);border-radius:.25rem;font-size:.82rem"></div>
        <div><label style="font-size:.78rem;font-weight:600;color:var(--argon-dark);display:block;margin-bottom:.25rem">New Password</label><input type="password" name="new_password" required minlength="8" style="width:100%;padding:.45rem .6rem;border:1px solid var(--argon-border);border-radius:.25rem;font-size:.82rem"></div>
        <div><label style="font-size:.78rem;font-weight:600;color:var(--argon-dark);display:block;margin-bottom:.25rem">Confirm New Password</label><input type="password" name="new_password_confirm" required minlength="8" style="width:100%;padding:.45rem .6rem;border:1px solid var(--argon-border);border-radius:.25rem;font-size:.82rem"></div>
        <div><button type="submit" style="background:var(--argon-primary);color:#fff;border:none;padding:.5rem 1.5rem;border-radius:.25rem;cursor:pointer;font-weight:600">Change Password</button></div>
    </form></div>
</div>

<script>
async function saveWallet(key){
    const val=document.getElementById(key).value.trim();
    const f=new FormData();
    f.append(key,val);
    const r=await fetch('/api/user/update-profile.php',{method:'POST',body:f});
    const d=await r.json();
    Swal.fire({icon:d.success?'success':'error',title:d.success?'Saved':'Error',text:d.message,timer:d.success?2000:undefined,showConfirmButton:!d.success});
}
document.getElementById('passwordForm').addEventListener('submit',async(e)=>{e.preventDefault();const f=new FormData(e.target);const r=await fetch('/api/user/change-password.php',{method:'POST',body:f});const d=await r.json();showAlert(d.message,d.success?'success':'error');if(d.success)e.target.reset()});
</script>
<?php require_once __DIR__ . '/../includes/argon-footer.php'; ?>
