<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
$user = getCurrentUser();
$nav_type = 'user'; $active_nav = 'earnings';
$page_title = 'Earnings'; $page_subtitle = 'Track all the interest and profit you have earned';
require_once __DIR__ . '/../includes/argon-header.php';
?>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-body">
            <div class="stat-label">Total Earned</div>
            <div class="stat-value" id="statTotalEarned">—</div>
        </div>
        <div class="stat-icon bg-success"><i class="fas fa-coins"></i></div>
    </div>
    <div class="stat-card">
        <div class="stat-body">
            <div class="stat-label">Earnings Count</div>
            <div class="stat-value" id="statCount">—</div>
        </div>
        <div class="stat-icon bg-primary"><i class="fas fa-list"></i></div>
    </div>
</div>

<div id="earningsList" class="card tsec">
    <div class="tscroll"><table><thead><tr><th>Date</th><th>Plan / Source</th><th>Amount</th></tr></thead>
    <tbody><tr><td colspan="3" style="text-align:center;color:var(--argon-muted)">Loading...</td></tr></tbody></table></div>
</div>

<script>
async function loadEarnings(){
    const r=await fetch('/api/user/earnings.php');
    const d=await r.json();
    if(!d.success)return;
    const data=d.data;
    document.getElementById('statTotalEarned').textContent='$'+parseFloat(data.total_earned).toFixed(2);
    document.getElementById('statCount').textContent=data.earnings.length;
    const c=document.getElementById('earningsList');
    if(!data.earnings.length){
        c.innerHTML='<div class="tscroll"><table><tbody><tr><td colspan="3" style="text-align:center;padding:2rem;color:var(--argon-muted)">No earnings yet. Start investing to earn daily profits!</td></tr></tbody></table></div>';
        return;
    }
    c.innerHTML=`<div class="tscroll"><table><thead><tr><th>Date</th><th>Plan / Source</th><th>Amount</th></tr></thead><tbody>
        ${data.earnings.map(e=>`<tr>
            <td style="font-size:.75rem;color:var(--argon-muted)">${new Date(e.created_at).toLocaleDateString('en-US',{year:'numeric',month:'short',day:'numeric',hour:'2-digit',minute:'2-digit'})}</td>
            <td style="font-weight:600;color:var(--argon-dark)">${escHtml(e.plan_name||e.description||'Daily ROI')}</td>
            <td style="font-weight:600;color:var(--argon-success)">+$${parseFloat(e.amount).toFixed(2)}</td>
        </tr>`).join('')}
    </tbody></table></div>`;
}
function escHtml(s){const d=document.createElement('div');d.textContent=String(s);return d.innerHTML}
loadEarnings();
</script>
<?php require_once __DIR__ . '/../includes/argon-footer.php'; ?>
