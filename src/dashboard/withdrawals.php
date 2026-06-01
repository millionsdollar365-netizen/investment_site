<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
$user = getCurrentUser();
$nav_type = 'user'; $active_nav = 'withdrawals';
$page_title = 'Withdrawals'; $page_subtitle = 'Withdraw funds to your crypto wallet';
require_once __DIR__ . '/../includes/argon-header.php';
?>
<div style="display:flex;justify-content:flex-end;margin-bottom:1.25rem">
    <button onclick="showCreateModal()" style="background:var(--argon-warning);color:#fff;padding:.5rem 1.2rem;border-radius:.25rem;border:none;cursor:pointer;font-weight:600">+ Request Withdrawal</button>
</div>
<div id="withdrawalsList" class="card tsec"><div class="tscroll"><table><thead><tr><th>Amount</th><th>Coin</th><th>Wallet</th><th>Status</th><th>Date</th></tr></thead><tbody><tr><td colspan="5" style="text-align:center;color:var(--argon-muted)">Loading...</td></tr></tbody></table></div></div>

<div id="createModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:200;align-items:center;justify-content:center">
    <div class="card" style="max-width:460px;width:90%"><div class="card-header"><h6>Request Withdrawal</h6></div>
    <div class="card-body"><form id="withdrawalForm" style="display:flex;flex-direction:column;gap:.75rem">
        <div><label style="font-size:.78rem;font-weight:600;color:var(--argon-dark);display:block;margin-bottom:.25rem">Amount ($)</label><input type="number" name="amount" step="0.01" min="0.01" required style="width:100%;padding:.45rem .6rem;border:1px solid var(--argon-border);border-radius:.25rem;font-size:.82rem"></div>
        <div><label style="font-size:.78rem;font-weight:600;color:var(--argon-dark);display:block;margin-bottom:.25rem">Cryptocurrency</label><select name="coin" id="coinSelect" required onchange="onCoinChange()" style="width:100%;padding:.45rem .6rem;border:1px solid var(--argon-border);border-radius:.25rem;font-size:.82rem"><option value="">Select coin...</option><option value="btc">Bitcoin (BTC)</option><option value="usdt">USDT (Tether)</option><option value="ethereum">Ethereum (ETH)</option></select></div>
        <div><label style="font-size:.78rem;font-weight:600;color:var(--argon-dark);display:block;margin-bottom:.25rem">Wallet Address</label><input type="text" name="wallet_address" id="walletAddr" required placeholder="Your wallet address" style="width:100%;padding:.45rem .6rem;border:1px solid var(--argon-border);border-radius:.25rem;font-size:.82rem"><div id="walletHint" style="display:none;margin-top:.25rem;font-size:.72rem;color:var(--argon-muted)">Using your saved wallet address. <a href="/dashboard/profile.php" style="color:var(--argon-primary)">Change in profile</a></div></div>
        <div style="display:flex;gap:.5rem;justify-content:flex-end;margin-top:.25rem"><button type="button" onclick="hideCreateModal()" style="background:var(--argon-light);border:1px solid var(--argon-border);padding:.45rem 1.2rem;border-radius:.25rem;cursor:pointer;font-size:.82rem">Cancel</button><button type="submit" style="background:var(--argon-warning);color:#fff;border:none;padding:.45rem 1.2rem;border-radius:.25rem;cursor:pointer;font-weight:600;font-size:.82rem">Submit</button></div>
    </form></div></div></div>

<script>
const savedWallets={btc:'<?php echo addslashes($user['wallet_btc'] ?? ''); ?>',usdt:'<?php echo addslashes($user['wallet_usdt'] ?? ''); ?>',ethereum:'<?php echo addslashes($user['wallet_ethereum'] ?? ''); ?>'};
function onCoinChange(){const c=document.getElementById('coinSelect').value;const a=document.getElementById('walletAddr');const h=document.getElementById('walletHint');if(c&&savedWallets[c]){a.value=savedWallets[c];h.style.display='block'}else{a.value='';h.style.display='none'}}
async function loadWithdrawals(){const r=await fetch('/api/withdrawals/list.php');const d=await r.json();const c=document.getElementById('withdrawalsList');const coinL={btc:'Bitcoin (BTC)',usdt:'USDT',ethereum:'Ethereum (ETH)'};if(!d.success||!d.data.withdrawals.length){c.innerHTML='<div class="tscroll"><table><tbody><tr><td colspan="5" style="text-align:center;padding:2rem;color:var(--argon-muted)">No withdrawals yet.</td></tr></tbody></table></div>';return}c.innerHTML=`<div class="tscroll"><table><thead><tr><th>Amount</th><th>Coin</th><th>Wallet</th><th>Status</th><th>Date</th></tr></thead><tbody>${d.data.withdrawals.map(w=>`<tr><td style="font-weight:600">$${parseFloat(w.amount).toFixed(2)}</td><td>${coinL[w.coin]||escHtml(w.coin||'—')}</td><td style="font-size:.72rem;max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-family:monospace">${escHtml(w.wallet_address||'—')}</td><td><span class="badge ${w.status==='approved'?'b-success':w.status==='pending'?'b-warning':'b-danger'}">${w.status}</span></td><td style="font-size:.75rem;color:var(--argon-muted)">${w.created_at}</td></tr>`).join('')}</tbody></table></div>`}
function showCreateModal(){document.getElementById('createModal').style.display='flex'}
function hideCreateModal(){document.getElementById('createModal').style.display='none'}
document.getElementById('withdrawalForm').addEventListener('submit',async(e)=>{e.preventDefault();const f=new FormData(e.target);const r=await fetch('/api/withdrawals/create.php',{method:'POST',body:f});const d=await r.json();if(d.success){hideCreateModal();loadWithdrawals();showAlert(d.message,'success')}else{showAlert(d.message,'error')}});
function escHtml(s){const d=document.createElement('div');d.textContent=s;return d.innerHTML}
loadWithdrawals();
</script>
<?php require_once __DIR__ . '/../includes/argon-footer.php'; ?>
