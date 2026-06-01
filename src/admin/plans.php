<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/admin-session.php';
requireAdminLogin();
$admin = getCurrentAdmin();
$nav_type = 'admin'; $active_nav = 'plans';
$page_title = 'Investment Plans'; $page_subtitle = 'Create and manage investment plan offerings';
require_once __DIR__ . '/../includes/argon-header.php';
?>

<div style="display:flex;justify-content:flex-end;margin-bottom:1.25rem">
    <button onclick="showCreateModal()" style="background:var(--argon-primary);color:#fff;padding:.5rem 1.2rem;border-radius:.25rem;border:none;cursor:pointer;font-weight:600">+ Create Plan</button>
</div>

<div id="plansList" class="card tsec"><div class="tscroll"><table><thead><tr><th>ID</th><th>Name</th><th>Description</th><th>Min</th><th>Max</th><th>Days</th><th>ROI/Day</th><th>Status</th><th>Actions</th></tr></thead><tbody><tr><td colspan="9" style="text-align:center;color:var(--argon-muted)">Loading...</td></tr></tbody></table></div></div>

<!-- Create/Edit Modal -->
<div id="planModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:200;align-items:center;justify-content:center">
    <div class="card" style="max-width:520px;width:90%;max-height:90vh;overflow-y:auto">
        <div class="card-header"><h6 id="modalTitle">Create Plan</h6></div>
        <div class="card-body">
            <form id="planForm" style="display:flex;flex-direction:column;gap:.75rem">
                <input type="hidden" name="id" id="planId">
                <input type="hidden" name="action" id="planAction" value="create">
                <div><label style="font-size:.78rem;font-weight:600;color:var(--argon-dark);display:block;margin-bottom:.25rem">Plan Name *</label><input type="text" name="name" id="planName" required style="width:100%;padding:.45rem .6rem;border:1px solid var(--argon-border);border-radius:.25rem;font-size:.82rem" placeholder="e.g. Silver Plan"></div>
                <div><label style="font-size:.78rem;font-weight:600;color:var(--argon-dark);display:block;margin-bottom:.25rem">Description</label><textarea name="description" id="planDesc" rows="2" style="width:100%;padding:.45rem .6rem;border:1px solid var(--argon-border);border-radius:.25rem;font-size:.82rem" placeholder="Brief description of the plan"></textarea></div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem">
                    <div><label style="font-size:.78rem;font-weight:600;color:var(--argon-dark);display:block;margin-bottom:.25rem">Min Amount ($) *</label><input type="number" name="min_amount" id="planMin" step="0.01" min="0.01" required style="width:100%;padding:.45rem .6rem;border:1px solid var(--argon-border);border-radius:.25rem;font-size:.82rem" placeholder="100"></div>
                    <div><label style="font-size:.78rem;font-weight:600;color:var(--argon-dark);display:block;margin-bottom:.25rem">Max Amount ($)</label><input type="number" name="max_amount" id="planMax" step="0.01" min="0" style="width:100%;padding:.45rem .6rem;border:1px solid var(--argon-border);border-radius:.25rem;font-size:.82rem" placeholder="0 = unlimited"></div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem">
                    <div><label style="font-size:.78rem;font-weight:600;color:var(--argon-dark);display:block;margin-bottom:.25rem">Duration (Days) *</label><input type="number" name="duration_days" id="planDuration" min="1" required style="width:100%;padding:.45rem .6rem;border:1px solid var(--argon-border);border-radius:.25rem;font-size:.82rem" placeholder="30"></div>
                    <div><label style="font-size:.78rem;font-weight:600;color:var(--argon-dark);display:block;margin-bottom:.25rem">Daily ROI (%) *</label><input type="number" name="daily_roi" id="planRoi" step="0.01" min="0.01" required style="width:100%;padding:.45rem .6rem;border:1px solid var(--argon-border);border-radius:.25rem;font-size:.82rem" placeholder="2.5"></div>
                </div>
                <div><label style="font-size:.78rem;font-weight:600;color:var(--argon-dark);display:block;margin-bottom:.25rem">Status</label><select name="status" id="planStatus" style="width:100%;padding:.45rem .6rem;border:1px solid var(--argon-border);border-radius:.25rem;font-size:.82rem"><option value="active">Active</option><option value="inactive">Inactive</option></select></div>
                <div style="display:flex;gap:.5rem;justify-content:flex-end;margin-top:.25rem">
                    <button type="button" onclick="hidePlanModal()" style="background:var(--argon-light);border:1px solid var(--argon-border);padding:.45rem 1.2rem;border-radius:.25rem;cursor:pointer;font-size:.82rem">Cancel</button>
                    <button type="submit" style="background:var(--argon-primary);color:#fff;border:none;padding:.45rem 1.2rem;border-radius:.25rem;cursor:pointer;font-weight:600;font-size:.82rem">Save Plan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
async function loadPlans(){const r=await fetch('/api/admin/plans.php');const d=await r.json();const c=document.getElementById('plansList');if(!d.success||!d.data.plans.length){c.innerHTML='<div class="tscroll"><table><thead><tr><th>ID</th><th>Name</th><th>Description</th><th>Min</th><th>Max</th><th>Days</th><th>ROI/Day</th><th>Status</th><th>Actions</th></tr></thead><tbody><tr><td colspan="9" style="text-align:center;padding:2rem;color:var(--argon-muted)">No plans yet. <a href="#" onclick="showCreateModal()" style="color:var(--argon-primary)">Create one</a></td></tr></tbody></table></div>';return}
c.innerHTML=`<div class="tscroll"><table><thead><tr><th>ID</th><th>Name</th><th>Description</th><th>Min</th><th>Max</th><th>Days</th><th>ROI/Day</th><th>Status</th><th>Actions</th></tr></thead><tbody>${d.data.plans.map(p=>`<tr><td>#${p.id}</td><td style="font-weight:600;color:var(--argon-dark)">${escHtml(p.name)}</td><td style="font-size:.75rem;max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">${escHtml(p.description||'—')}</td><td>$${parseFloat(p.min_amount).toLocaleString()}</td><td>${p.max_amount? '$'+parseFloat(p.max_amount).toLocaleString():'<span style="color:var(--argon-muted)">Unlimited</span>'}</td><td>${p.duration_days}d</td><td style="font-weight:600">${parseFloat(p.daily_roi).toFixed(2)}%</td><td><a href="#" onclick="togglePlan(${p.id})" class="badge ${p.status==='active'?'b-success':'b-default'}" style="cursor:pointer;text-decoration:none">${p.status}</a></td><td><a href="#" onclick="editPlan(${p.id},'${escHtml(p.name).replace(/'/g,"\\'")}','${escHtml(p.description||'').replace(/'/g,"\\'")}',${p.min_amount},${p.max_amount||0},${p.duration_days},${p.daily_roi},'${p.status}')" class="act-link" style="margin-right:.5rem">Edit</a></td></tr>`).join('')}</tbody></table></div>`}

function showCreateModal(){document.getElementById('modalTitle').textContent='Create Plan';document.getElementById('planAction').value='create';document.getElementById('planId').value='';document.getElementById('planForm').reset();document.getElementById('planStatus').value='active';document.getElementById('planModal').style.display='flex'}
function editPlan(id,name,desc,min,max,dur,roi,status){document.getElementById('modalTitle').textContent='Edit Plan #'+id;document.getElementById('planAction').value='update';document.getElementById('planId').value=id;document.getElementById('planName').value=name;document.getElementById('planDesc').value=desc;document.getElementById('planMin').value=min;document.getElementById('planMax').value=max||'';document.getElementById('planDuration').value=dur;document.getElementById('planRoi').value=roi;document.getElementById('planStatus').value=status;document.getElementById('planModal').style.display='flex'}
function hidePlanModal(){document.getElementById('planModal').style.display='none'}

async function togglePlan(id){const f=new FormData();f.set('action','toggle');f.set('id',id);const r=await fetch('/api/admin/plans.php',{method:'POST',body:f});const d=await r.json();if(d.success)loadPlans();else showAlert(d.message,'error')}

document.getElementById('planForm').addEventListener('submit',async(e)=>{e.preventDefault();const f=new FormData(e.target);const r=await fetch('/api/admin/plans.php',{method:'POST',body:f});const d=await r.json();if(d.success){hidePlanModal();loadPlans()}else{showAlert(d.message,'error')}});

function escHtml(s){const d=document.createElement('div');d.textContent=String(s);return d.innerHTML}
loadPlans();
</script>
<?php require_once __DIR__ . '/../includes/argon-footer.php'; ?>
