<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
$user = getCurrentUser();
$nav_type = 'user'; $active_nav = 'referrals';
$page_title = 'My Referrals'; $page_subtitle = 'Share your link and earn commissions';
require_once __DIR__ . '/../includes/argon-header.php';
?>
<div class="card" style="margin-bottom:1.25rem"><div class="card-header"><h6>Your Referral Link</h6></div><div class="card-body"><div style="display:flex;gap:.5rem"><input type="text" id="referralLink" readonly style="flex:1;padding:.5rem .75rem;border:1px solid var(--argon-border);border-radius:.25rem;font-size:.82rem;background:var(--argon-light)" value="<?php echo rtrim(SITE_URL, '/'); ?>/register.php?ref=<?php echo htmlspecialchars($user['referral_code']); ?>"><button onclick="copyReferralLink()" style="background:var(--argon-primary);color:#fff;border:none;padding:.5rem 1.2rem;border-radius:.25rem;cursor:pointer;font-weight:600;white-space:nowrap"><i class="fas fa-copy"></i> Copy</button></div></div></div>
<div id="referralsList" class="card tsec"><div class="tscroll"><table><thead><tr><th>User</th><th>Email</th><th>Commission</th><th>Status</th><th>Date</th></tr></thead><tbody><tr><td colspan="5" style="text-align:center;color:var(--argon-muted)">Loading...</td></tr></tbody></table></div></div>
<script>
async function loadReferrals(){const r=await fetch('/api/user/referrals.php');const d=await r.json();const c=document.getElementById('referralsList');if(!d.success||!d.data.referrals.length){c.innerHTML='<div class="tscroll"><table><tbody><tr><td colspan="5" style="text-align:center;padding:2rem;color:var(--argon-muted)">No referrals yet. Share your referral link to earn commissions!</td></tr></tbody></table></div>';return}c.innerHTML=`<div style="padding:.6rem 1.1rem;background:var(--argon-light);border-bottom:1px solid var(--argon-border);font-size:.82rem;font-weight:600;color:var(--argon-dark)">Total Commission: $${parseFloat(d.data.total_commission).toFixed(2)} | Referrals: ${d.data.count}</div><div class="tscroll"><table><thead><tr><th>User</th><th>Email</th><th>Commission</th><th>Status</th><th>Date</th></tr></thead><tbody>${d.data.referrals.map(r=>`<tr><td style="font-weight:600;color:var(--argon-dark)">${escHtml(r.first_name+' '+r.last_name)}</td><td>${escHtml(r.email)}</td><td>$${parseFloat(r.commission_amount).toFixed(2)}</td><td><span class="badge ${r.status==='active'?'b-success':'b-default'}">${r.status}</span></td><td style="font-size:.75rem;color:var(--argon-muted)">${r.created_at}</td></tr>`).join('')}</tbody></table></div>`}
function copyReferralLink(){const i=document.getElementById('referralLink');i.select();document.execCommand('copy');alert('Referral link copied!')}
function escHtml(s){const d=document.createElement('div');d.textContent=s;return d.innerHTML}
loadReferrals();
</script>
<?php require_once __DIR__ . '/../includes/argon-footer.php'; ?>
