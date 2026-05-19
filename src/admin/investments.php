<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/admin-session.php';
requireAdminLogin();
$admin = getCurrentAdmin();
$nav_type = 'admin'; $active_nav = 'investments';
$page_title = 'All Investments'; $page_subtitle = 'View all user investments across the platform';
require_once __DIR__ . '/../includes/argon-header.php';
?>
<div class="card" style="margin-bottom:1.25rem"><div class="card-body"><div style="display:flex;flex-wrap:wrap;gap:.75rem;align-items:flex-end"><div><label style="font-size:.78rem;font-weight:600;color:var(--argon-dark);display:block;margin-bottom:.25rem">Status</label><select id="statusFilter" onchange="loadInvestments()" style="padding:.45rem .6rem;border:1px solid var(--argon-border);border-radius:.25rem;font-size:.82rem"><option value="">All</option><option value="active">Active</option><option value="completed">Completed</option><option value="cancelled">Cancelled</option></select></div></div></div></div>
<div id="investmentsList" class="card tsec"><div class="tscroll"><table><thead><tr><th>ID</th><th>User</th><th>Plan</th><th>Amount</th><th>Daily ROI</th><th>Profit</th><th>Status</th><th>Period</th></tr></thead><tbody><tr><td colspan="8" style="text-align:center;color:var(--argon-muted)">Loading...</td></tr></tbody></table></div></div>
<div id="pagination" style="display:flex;justify-content:center;gap:.5rem;margin-top:1rem"></div>
<script>
let currentPage=1;
async function loadInvestments(page=1){currentPage=page;const st=document.getElementById('statusFilter').value,p=new URLSearchParams({page,limit:20});if(st)p.set('status',st);const r=await fetch(`/api/admin/investments.php?${p}`),d=await r.json(),c=document.getElementById('investmentsList');if(!d.success||!d.data.investments.length){c.innerHTML='<div class="tscroll"><table><tbody><tr><td colspan="8" style="text-align:center;padding:2rem;color:var(--argon-muted)">No investments found.</td></tr></tbody></table></div>';document.getElementById('pagination').innerHTML='';return}c.innerHTML=`<div class="tscroll"><table><thead><tr><th>ID</th><th>User</th><th>Plan</th><th>Amount</th><th>Daily ROI</th><th>Profit</th><th>Status</th><th>Period</th></tr></thead><tbody>${d.data.investments.map(inv=>`<tr><td>#${inv.id}</td><td style="font-weight:600;color:var(--argon-dark)">${escHtml(inv.first_name+' '+inv.last_name)}</td><td>${escHtml(inv.plan_name)}</td><td style="font-weight:600">$${parseFloat(inv.amount).toFixed(2)}</td><td>$${parseFloat(inv.daily_roi).toFixed(2)}</td><td style="color:var(--argon-success);font-weight:600">$${parseFloat(inv.total_profit).toFixed(2)}</td><td><span class="badge ${inv.status==='active'?'b-success':inv.status==='completed'?'b-info':'b-danger'}">${inv.status}</span></td><td style="font-size:.75rem;color:var(--argon-muted)">${inv.start_date}<br>to ${inv.end_date}</td></tr>`).join('')}</tbody></table></div>`;const tp=Math.ceil(d.data.total/d.data.limit);let h='';for(let i=1;i<=tp;i++)h+=`<button onclick="loadInvestments(${i})" style="padding:.35rem .85rem;border-radius:.25rem;font-size:.82rem;border:1px solid var(--argon-border);cursor:pointer;${i===currentPage?'background:var(--argon-primary);color:#fff;border-color:var(--argon-primary)':'background:var(--argon-white);color:var(--argon-text)'}">${i}</button>`;document.getElementById('pagination').innerHTML=h}
function escHtml(s){const d=document.createElement('div');d.textContent=String(s);return d.innerHTML}
loadInvestments();
</script>
<?php require_once __DIR__ . '/../includes/argon-footer.php'; ?>
