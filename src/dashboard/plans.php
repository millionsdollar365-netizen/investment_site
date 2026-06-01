<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
$user = getCurrentUser();
$nav_type = 'user'; $active_nav = 'plans';
$page_title = 'Investment Plans'; $page_subtitle = 'Choose a plan and start earning daily returns';
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
.btn-invest { display:block;width:100%;padding:.75rem;border-radius:8px;font-weight:700;font-size:.92rem;text-decoration:none;cursor:pointer;border:none;background:linear-gradient(135deg,#fbbf24,#f59e0b);color:#0f172a;transition:all .3s;font-family:inherit; }
.btn-invest:hover { box-shadow:0 8px 25px rgba(251,191,36,.35);transform:translateY(-1px); }
.plan-amounts { display:flex;justify-content:center;gap:1.5rem;margin:.5rem 0; }
.plan-amounts div { text-align:center; }
.plan-amounts .am-num { font-size:1rem;font-weight:700;color:#172b4d; }
.plan-amounts .am-lbl { font-size:.65rem;color:#8898aa;text-transform:uppercase;letter-spacing:.04em; }

/* Modal */
#investModal { display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:300;align-items:center;justify-content:center; }
#investModal.open { display:flex; }
.modal-card { background:#fff;border-radius:16px;width:90%;max-width:440px;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,.2); }
.modal-head { background:linear-gradient(135deg,#fbbf24,#f59e0b);padding:1.5rem 2rem;color:#0f172a; }
.modal-head h3 { font-size:1.2rem;font-weight:800;margin:0; }
.modal-head .plan-name-sub { font-size:.82rem;opacity:.8;margin-top:.15rem; }
.modal-body { padding:1.5rem 2rem;display:flex;flex-direction:column;gap:1rem; }
.modal-body .info-row { display:flex;justify-content:space-between;font-size:.85rem;color:#525f7f;padding:.35rem 0;border-bottom:1px solid #f1f5f9; }
.modal-body .info-row strong { color:#172b4d; }
.modal-body .total-row { font-size:.95rem;font-weight:700;color:#172b4d;padding:.5rem 0; }
.modal-body input { width:100%;padding:.65rem .85rem;border:2px solid #e2e8f0;border-radius:10px;font-size:1rem;font-family:inherit;outline:none;transition:border .2s; }
.modal-body input:focus { border-color:#fbbf24; }
.modal-footer { padding:1.25rem 2rem;border-top:1px solid #f1f5f9;display:flex;gap:.75rem;justify-content:flex-end; }
.btn-cancel { padding:.6rem 1.5rem;border-radius:8px;font-weight:600;font-size:.85rem;cursor:pointer;border:1px solid #e2e8f0;background:#fff;color:#525f7f;font-family:inherit; }
.btn-confirm { padding:.6rem 1.5rem;border-radius:8px;font-weight:700;font-size:.85rem;cursor:pointer;border:none;background:linear-gradient(135deg,#fbbf24,#f59e0b);color:#0f172a;font-family:inherit; }
</style>

<div class="pricing-grid" id="plansGrid">
    <div class="pricing-card"><div class="pricing-header"><div class="plan-name">Loading...</div></div><div class="pricing-body"><p style="text-align:center;color:#8898aa">Loading plans...</p></div></div>
</div>

<div id="investModal"><div class="modal-card">
    <div class="modal-head"><h3 id="modPlanName">—</h3><div class="plan-name-sub" id="modPlanDesc"></div></div>
    <div class="modal-body">
        <div id="modDetails"></div>
        <div><label style="font-size:.8rem;font-weight:600;color:#172b4d;display:block;margin-bottom:.3rem">Amount to Invest ($)</label><input type="number" id="modAmount" min="1" step="0.01" placeholder="Enter amount" oninput="calcReturn()"></div>
    </div>
    <div class="modal-footer">
        <button class="btn-cancel" onclick="closeInvestModal()">Cancel</button>
        <button class="btn-confirm" id="btnConfirm" onclick="submitInvest()">Confirm Investment</button>
    </div>
</div></div>

<script>
let plansData={},currentPlan=null;
async function loadPlans(){
    const r=await fetch('/api/investments/plans.php');const d=await r.json();
    if(!d.success||!d.data.plans.length){document.getElementById('plansGrid').innerHTML='<div class="pricing-card" style="grid-column:1/-1;text-align:center;padding:3rem"><p style="color:#8898aa">No investment plans available right now. Check back soon.</p></div>';return}
    d.data.plans.forEach(p=>plansData[p.id]=p);
    document.getElementById('plansGrid').innerHTML=d.data.plans.map((p,i)=>{
        const isPopular=i===1;
        return `<div class="pricing-card${isPopular?' popular':''}">
            ${isPopular?'<div class="popular-badge">Most Popular</div>':''}
            <div class="pricing-header"><div class="plan-name">${escHtml(p.name)}</div><div class="plan-desc">${escHtml(p.description||'Investment plan')}</div></div>
            <div class="pricing-body">
                <div class="pricing-roi"><div class="roi-num">${parseFloat(p.daily_roi).toFixed(1)}%</div><div class="roi-label">Daily Return</div></div>
                <div class="plan-amounts"><div><div class="am-num">$${parseFloat(p.min_amount).toLocaleString()}</div><div class="am-lbl">Min</div></div><div style="color:#cbd5e1">—</div><div><div class="am-num">${p.max_amount?'$'+parseFloat(p.max_amount).toLocaleString():'Unlimited'}</div><div class="am-lbl">Max</div></div></div>
                <ul class="pricing-features">
                    <li><i class="fas fa-check"></i> ${p.duration_days} Days Term</li>
                    <li><i class="fas fa-check"></i> Daily Profit Credited</li>
                    <li><i class="fas fa-check"></i> Principal Returned</li>
                    <li><i class="fas fa-check"></i> Instant Withdrawal</li>
                    <li><i class="fas fa-check"></i> 24/7 Support</li>
                </ul>
            </div>
            <div class="pricing-footer"><button class="btn-invest" onclick="openInvestModal(${p.id})">Invest Now</button></div>
        </div>`;
    }).join('');
}
function openInvestModal(pid){
    currentPlan=plansData[pid];if(!currentPlan)return;
    document.getElementById('modPlanName').textContent='Invest in '+currentPlan.name;
    document.getElementById('modPlanDesc').textContent=currentPlan.daily_roi+'% daily / '+currentPlan.duration_days+' days';
    document.getElementById('modAmount').value='';document.getElementById('modAmount').min=currentPlan.min_amount;
    if(currentPlan.max_amount)document.getElementById('modAmount').max=currentPlan.max_amount;
    calcReturn();document.getElementById('investModal').classList.add('open');
}
function closeInvestModal(){document.getElementById('investModal').classList.remove('open');currentPlan=null}
function calcReturn(){
    const amt=parseFloat(document.getElementById('modAmount').value)||0;
    const details=document.getElementById('modDetails');
    if(!currentPlan||amt<=0){details.innerHTML='';return}
    const daily=(amt*parseFloat(currentPlan.daily_roi)/100);
    const total=daily*currentPlan.duration_days;
    const payout=amt+total;
    details.innerHTML=`<div class="info-row"><span>Investment</span><strong>$${amt.toFixed(2)}</strong></div>
        <div class="info-row"><span>Daily Earnings</span><strong style="color:#22c55e">+$${daily.toFixed(2)}/day</strong></div>
        <div class="info-row"><span>Duration</span><strong>${currentPlan.duration_days} days</strong></div>
        <div class="total-row"><span>Total Return</span><span style="color:#22c55e">+$${total.toFixed(2)}</span></div>
        <div class="total-row" style="font-size:1.1rem"><span>Total Payout</span><span>$${payout.toFixed(2)}</span></div>`;
}
async function submitInvest(){
    if(!currentPlan)return;
    const amt=parseFloat(document.getElementById('modAmount').value);
    if(!amt||amt<parseFloat(currentPlan.min_amount)){showAlert('Minimum investment is $'+parseFloat(currentPlan.min_amount).toFixed(2),'error');return}
    if(currentPlan.max_amount&&amt>parseFloat(currentPlan.max_amount)){showAlert('Maximum investment is $'+parseFloat(currentPlan.max_amount).toFixed(2),'error');return}
    const f=new FormData();f.append('plan_id',currentPlan.id);f.append('amount',amt);
    const r=await fetch('/api/investments/create.php',{method:'POST',body:f});const d=await r.json();
    if(d.success){closeInvestModal();const daily=(amt*parseFloat(currentPlan.daily_roi)/100);const total=(daily*currentPlan.duration_days).toFixed(2);const payout=(amt+parseFloat(total)).toFixed(2);
        showToast('Investment of $'+amt.toFixed(2)+' in '+currentPlan.name+' created successfully!','success');
        Swal.fire({icon:'success',title:'Investment Created!',html:`<div style="text-align:left;font-size:.85rem;line-height:1.8"><b>Plan:</b> ${escHtml(currentPlan.name)}<br><b>Amount:</b> $${amt.toFixed(2)}<br><b>Daily:</b> $${daily.toFixed(2)}<br><b>Return:</b> $${total}<br><b>Payout:</b> $${payout}</div>`,confirmButtonColor:'#f59e0b'});
    }else{showAlert(d.message,'error')}
}
function escHtml(s){const d=document.createElement('div');d.textContent=String(s);return d.innerHTML}
loadPlans();
</script>
<?php require_once __DIR__ . '/../includes/argon-footer.php'; ?>
