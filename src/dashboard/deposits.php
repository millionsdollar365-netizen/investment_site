<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
$user = getCurrentUser();
$nav_type = 'user'; $active_nav = 'deposits';
$page_title = 'My Deposits'; $page_subtitle = 'Deposit cryptocurrency to your account';
require_once __DIR__ . '/../includes/argon-header.php';
?>
<div style="display:flex;justify-content:flex-end;margin-bottom:1.25rem">
    <button onclick="showCreateModal()" style="background:var(--argon-success);color:#fff;padding:.5rem 1.2rem;border-radius:.25rem;border:none;cursor:pointer;font-weight:600">+ New Deposit</button>
</div>
<div id="depositsList" class="card tsec"><div class="tscroll"><table><thead><tr><th>Amount</th><th>Crypto</th><th>Status</th><th>Date</th></tr></thead><tbody><tr><td colspan="4" style="text-align:center;color:var(--argon-muted)">Loading...</td></tr></tbody></table></div></div>

<div id="createModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:200;align-items:center;justify-content:center">
    <div class="card" style="max-width:460px;width:90%"><div class="card-header"><h6>New Deposit</h6></div>
    <div class="card-body"><form id="depositForm" style="display:flex;flex-direction:column;gap:.75rem">
        <div><label style="font-size:.78rem;font-weight:600;color:var(--argon-dark);display:block;margin-bottom:.25rem">Amount (USD)</label><input type="number" name="amount" step="0.01" min="0.01" required style="width:100%;padding:.45rem .6rem;border:1px solid var(--argon-border);border-radius:.25rem;font-size:.82rem"></div>
        <div><label style="font-size:.78rem;font-weight:600;color:var(--argon-dark);display:block;margin-bottom:.25rem">Cryptocurrency</label><select name="payment_method" required style="width:100%;padding:.45rem .6rem;border:1px solid var(--argon-border);border-radius:.25rem;font-size:.82rem"><option value="">Select cryptocurrency...</option><option value="btc">Bitcoin (BTC)</option><option value="usdt">USDT (Tether)</option><option value="ethereum">Ethereum (ETH)</option></select></div>
        <div style="display:flex;gap:.5rem;justify-content:flex-end;margin-top:.25rem"><button type="button" onclick="hideCreateModal()" style="background:var(--argon-light);border:1px solid var(--argon-border);padding:.45rem 1.2rem;border-radius:.25rem;cursor:pointer;font-size:.82rem">Cancel</button><button type="submit" style="background:var(--argon-primary);color:#fff;border:none;padding:.45rem 1.2rem;border-radius:.25rem;cursor:pointer;font-weight:600;font-size:.82rem">Continue</button></div>
    </form></div></div></div>

<div id="walletModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:210;align-items:center;justify-content:center">
    <div class="card" style="max-width:460px;width:90%"><div class="card-header"><h6 style="color:var(--argon-success)"><i class="fas fa-check-circle"></i> Deposit Request Created</h6></div>
    <div class="card-body" style="display:flex;flex-direction:column;gap:.75rem">
        <div style="background:rgba(94,114,228,.1);border-left:4px solid var(--argon-primary);padding:.75rem;border-radius:.25rem"><p style="font-size:.75rem;color:var(--argon-muted);margin-bottom:.15rem">Please send exactly:</p><p style="font-size:1.25rem;font-weight:700;color:var(--argon-dark)" id="walletAmount">$0.00</p></div>
        <div style="background:var(--argon-light);border:1px solid var(--argon-border);border-radius:.25rem;padding:.75rem"><p style="font-size:.7rem;color:var(--argon-muted);margin-bottom:.2rem;font-weight:600;text-transform:uppercase">Wallet Address</p><div style="display:flex;align-items:center;justify-content:space-between;gap:.5rem"><code style="font-size:.72rem;word-break:break-all;color:var(--argon-dark)" id="walletAddress">Loading...</code><button onclick="copyWallet()" style="background:none;border:none;color:var(--argon-primary);cursor:pointer;font-weight:600;font-size:.75rem;flex-shrink:0"><i class="fas fa-copy"></i> Copy</button></div></div>
        <div style="background:rgba(251,99,64,.1);border-left:4px solid var(--argon-warning);padding:.75rem;border-radius:.25rem"><p style="font-size:.75rem;color:var(--argon-text)"><strong>Important:</strong> Send exactly the amount shown. Your deposit will be credited once payment is confirmed.</p></div>
        <div style="background:var(--argon-light);border-radius:.25rem;padding:.5rem .75rem"><p style="font-size:.7rem;color:var(--argon-muted)">Reference ID: <strong id="walletRef" style="color:var(--argon-dark)">-</strong></p></div>
        <div style="display:flex;gap:.5rem;justify-content:flex-end"><button onclick="hideWalletModal()" style="background:var(--argon-light);border:1px solid var(--argon-border);padding:.45rem 1.2rem;border-radius:.25rem;cursor:pointer;font-size:.82rem">Done</button><button onclick="copyWallet()" style="background:var(--argon-primary);color:#fff;border:none;padding:.45rem 1.2rem;border-radius:.25rem;cursor:pointer;font-weight:600;font-size:.82rem"><i class="fas fa-copy"></i> Copy Address</button></div>
    </div></div></div>

<script>
let currentWalletData=null;
async function loadDeposits(){const c=document.getElementById('depositsList');try{const r=await fetch('/api/deposits/list.php');const d=await r.json();if(!d.success||!d.data.deposits.length){c.innerHTML='<div class="tscroll"><table><tbody><tr><td colspan="4" style="text-align:center;padding:2rem;color:var(--argon-muted)">No deposits yet.</td></tr></tbody></table></div>';return}const l={btc:'Bitcoin',usdt:'USDT',ethereum:'Ethereum'};c.innerHTML=`<div class="tscroll"><table><thead><tr><th>Amount</th><th>Crypto</th><th>Status</th><th>Date</th></tr></thead><tbody>${d.data.deposits.map(d=>`<tr><td style="font-weight:600">$${parseFloat(d.amount).toFixed(2)}</td><td>${l[d.payment_method]||d.payment_method}</td><td><span class="badge ${d.status==='approved'?'b-success':d.status==='pending'?'b-warning':'b-danger'}">${d.status.charAt(0).toUpperCase()+d.status.slice(1)}</span></td><td style="font-size:.75rem;color:var(--argon-muted)">${new Date(d.created_at).toLocaleDateString()}</td></tr>`).join('')}</tbody></table></div>`}catch(e){c.innerHTML=`<div style="text-align:center;padding:2rem;color:var(--argon-danger)">Error loading deposits: ${e}</div>`}}
function showCreateModal(){document.getElementById('createModal').style.display='flex'}
function hideCreateModal(){document.getElementById('createModal').style.display='none';document.getElementById('depositForm').reset()}
function showWalletModal(data){currentWalletData=data;document.getElementById('walletAmount').textContent='$'+parseFloat(data.amount).toFixed(2);document.getElementById('walletAddress').textContent=data.wallet_address;document.getElementById('walletRef').textContent=data.reference;document.getElementById('walletModal').style.display='flex'}
function hideWalletModal(){document.getElementById('walletModal').style.display='none';hideCreateModal();loadDeposits()}
function copyWallet(){const a=document.getElementById('walletAddress').textContent;navigator.clipboard.writeText(a).then(()=>{showAlert('Wallet address copied!','success')})}
document.getElementById('depositForm').addEventListener('submit',async(e)=>{e.preventDefault();const f=new FormData(e.target);try{const r=await apiCall('/api/deposits/create.php','POST',f);if(r&&r.success){showWalletModal(r.data)}else if(r){showAlert(r.message,'error')}}catch(e){showAlert(e,'error')}});
loadDeposits();
</script>
<?php require_once __DIR__ . '/../includes/argon-footer.php'; ?>
