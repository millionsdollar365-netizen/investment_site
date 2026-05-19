<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/admin-session.php';
requireAdminLogin();
$admin = getCurrentAdmin();
$nav_type = 'admin'; $active_nav = 'plans';
$page_title = 'Investment Plans'; $page_subtitle = 'View and manage investment plan offerings';
require_once __DIR__ . '/../includes/argon-header.php';
?>
<div id="plansList" class="card tsec"><div class="tscroll"><table><thead><tr><th>ID</th><th>Name</th><th>Description</th><th>Min Amount</th><th>Max Amount</th><th>Duration</th><th>Daily ROI</th><th>Status</th></tr></thead><tbody><tr><td colspan="8" style="text-align:center;color:var(--argon-muted)">Loading...</td></tr></tbody></table></div></div>
<script>
async function loadPlans(){const r=await fetch('/api/admin/plans.php');const d=await r.json();const c=document.getElementById('plansList');if(!d.success||!d.data.plans.length){c.innerHTML='<div class="tscroll"><table><tbody><tr><td colspan="8" style="text-align:center;padding:2rem;color:var(--argon-muted)">No plans found.</td></tr></tbody></table></div>';return}c.innerHTML=`<div class="tscroll"><table><thead><tr><th>ID</th><th>Name</th><th>Description</th><th>Min Amount</th><th>Max Amount</th><th>Duration</th><th>Daily ROI</th><th>Status</th></tr></thead><tbody>${d.data.plans.map(p=>`<tr><td>#${p.id}</td><td style="font-weight:600;color:var(--argon-dark)">${escHtml(p.name)}</td><td style="font-size:.75rem">${escHtml(p.description||'—')}</td><td>$${parseFloat(p.min_amount).toLocaleString()}</td><td>$${parseFloat(p.max_amount).toLocaleString()}</td><td>${p.duration_days} days</td><td>${parseFloat(p.daily_roi).toFixed(2)}%</td><td><span class="badge ${p.status==='active'?'b-success':'b-default'}">${p.status}</span></td></tr>`).join('')}</tbody></table></div>`}
function escHtml(s){const d=document.createElement('div');d.textContent=String(s);return d.innerHTML}
loadPlans();
</script>
<?php require_once __DIR__ . '/../includes/argon-footer.php'; ?>
