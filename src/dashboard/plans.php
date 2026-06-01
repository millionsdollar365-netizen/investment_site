<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
$user = getCurrentUser();
$nav_type = 'user'; $active_nav = 'plans';
$page_title = 'Investment Plans'; $page_subtitle = 'Choose a plan that matches your goals';
require_once __DIR__ . '/../includes/argon-header.php';
?>

<style>
.pricing-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(280px,1fr)); gap:1.5rem; }
.pricing-card { background:var(--argon-white); border-radius:12px; overflow:hidden; box-shadow:0 0 2rem rgba(136,152,170,.1); transition:all .3s; position:relative; display:flex;flex-direction:column; }
.pricing-card:hover { transform:translateY(-6px); box-shadow:0 1rem 3rem rgba(0,0,0,.12); }
.pricing-card.popular { border:2px solid #fbbf24; }
.pricing-card .popular-badge { position:absolute;top:12px;right:12px;background:linear-gradient(135deg,#fbbf24,#f59e0b);color:#0f172a;font-size:.68rem;font-weight:700;padding:.25rem .8rem;border-radius:50px;text-transform:uppercase;letter-spacing:.05em; }
.pricing-header { text-align:center;padding:2rem 1.5rem 1.5rem;border-bottom:1px solid #f1f5f9; }
.pricing-header .plan-name { font-size:1.15rem;font-weight:700;color:#172b4d;margin-bottom:.25rem; }
.pricing-header .plan-desc { font-size:.78rem;color:#8898aa; }
.pricing-body { padding:1.5rem;flex:1;display:flex;flex-direction:column;gap:.85rem; }
.pricing-roi { text-align:center;margin-bottom:.25rem; }
.pricing-roi .roi-num { font-size:2.5rem;font-weight:800;color:#172b4d; }
.pricing-roi .roi-label { font-size:.75rem;color:#8898aa;text-transform:uppercase;letter-spacing:.05em; }
.pricing-features { list-style:none;padding:0;margin:0;flex:1; }
.pricing-features li { padding:.5rem 0;font-size:.82rem;color:#525f7f;display:flex;align-items:center;gap:.5rem;border-bottom:1px solid #f8f9fa; }
.pricing-features li i { color:#22c55e;font-size:.75rem;width:16px;text-align:center; }
.pricing-footer { padding:1.25rem 1.5rem;border-top:1px solid #f1f5f9;text-align:center; }
.pricing-footer .btn-invest { display:block;width:100%;padding:.7rem;border-radius:8px;font-weight:700;font-size:.9rem;text-decoration:none;transition:all .3s;border:none;cursor:pointer; }
.btn-invest-gold { background:linear-gradient(135deg,#fbbf24,#f59e0b);color:#0f172a; }
.btn-invest-gold:hover { box-shadow:0 8px 25px rgba(251,191,36,.3);transform:translateY(-1px); }
.btn-invest-outline { background:transparent;border:2px solid #e2e8f0 !important;color:#525f7f; }
.btn-invest-outline:hover { border-color:#fbbf24 !important;color:#f59e0b; }

.plan-amounts { display:flex;justify-content:center;gap:1.5rem;margin:.5rem 0; }
.plan-amounts div { text-align:center; }
.plan-amounts .am-num { font-size:1rem;font-weight:700;color:#172b4d; }
.plan-amounts .am-lbl { font-size:.65rem;color:#8898aa;text-transform:uppercase;letter-spacing:.04em; }
</style>

<div class="pricing-grid" id="plansGrid">
    <div class="pricing-card"><div class="pricing-header"><div class="plan-name">Loading...</div></div><div class="pricing-body"><p style="text-align:center;color:#8898aa">Loading plans...</p></div></div>
</div>

<script>
async function loadPlans(){
    const r=await fetch('/api/investments/plans.php');const d=await r.json();
    if(!d.success||!d.data.plans.length){document.getElementById('plansGrid').innerHTML='<div class="pricing-card" style="grid-column:1/-1;text-align:center;padding:3rem"><p style="color:#8898aa">No investment plans available right now. Check back soon.</p></div>';return}
    const plans=d.data.plans;
    const grid=document.getElementById('plansGrid');
    grid.innerHTML=plans.map((p,i)=>{
        const isPopular=i===1; // middle plan = popular
        return `<div class="pricing-card${isPopular?' popular':''}">
            ${isPopular?'<div class="popular-badge">Most Popular</div>':''}
            <div class="pricing-header"><div class="plan-name">${escHtml(p.name)}</div><div class="plan-desc">${escHtml(p.description||'Investment plan')}</div></div>
            <div class="pricing-body">
                <div class="pricing-roi"><div class="roi-num">${parseFloat(p.daily_roi).toFixed(1)}%</div><div class="roi-label">Daily Return</div></div>
                <div class="plan-amounts"><div><div class="am-num">$${parseFloat(p.min_amount).toLocaleString()}</div><div class="am-lbl">Min</div></div><div style="color:#cbd5e1">—</div><div><div class="am-num">${p.max_amount?'$'+parseFloat(p.max_amount).toLocaleString():'Unlimited'}</div><div class="am-lbl">Max</div></div></div>
                <ul class="pricing-features">
                    <li><i class="fas fa-check"></i> ${p.duration_days} Days Investment Term</li>
                    <li><i class="fas fa-check"></i> Daily Profit Credited</li>
                    <li><i class="fas fa-check"></i> Principal Returned at Maturity</li>
                    <li><i class="fas fa-check"></i> Instant Withdrawal Available</li>
                    <li><i class="fas fa-check"></i> 24/7 Support</li>
                </ul>
            </div>
            <div class="pricing-footer"><a href="/dashboard/investments.php" class="btn-invest ${isPopular?'btn-invest-gold':'btn-invest-outline'}">${isPopular?'Start Investing':'View Plan'}</a></div>
        </div>`;
    }).join('');
}
function escHtml(s){const d=document.createElement('div');d.textContent=String(s);return d.innerHTML}
loadPlans();
</script>
<?php require_once __DIR__ . '/../includes/argon-footer.php'; ?>
