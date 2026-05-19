<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * User Dashboard — Investments
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/auth.php';

requireLogin();

$user = getCurrentUser();

$nav_type = 'user';
$active_nav = 'investments';
$page_title = 'My Investments';
$page_subtitle = 'Manage your active investment plans';
require_once __DIR__ . '/../includes/argon-header.php';
?>

<div style="display:flex;justify-content:flex-end;margin-bottom:1.25rem">
    <button onclick="showCreateModal()" class="act-link" style="background:var(--argon-primary);color:#fff;padding:.5rem 1.2rem;border-radius:.25rem;border:none;cursor:pointer;font-weight:600">+ New Investment</button>
</div>

<div id="investmentsList" class="card tsec">
    <div class="tscroll" style="padding:.6rem 0">
        <table>
            <thead>
                <tr><th>Plan</th><th>Amount</th><th>Daily ROI</th><th>Profit</th><th>Status</th><th>Dates</th></tr>
            </thead>
            <tbody>
                <tr><td colspan="6" style="text-align:center;color:var(--argon-muted)">Loading...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Create Investment Modal -->
<div id="createModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:200;align-items:center;justify-content:center">
    <div class="card" style="max-width:460px;width:90%">
        <div class="card-header"><h6>New Investment</h6></div>
        <div class="card-body">
            <form id="investForm" style="display:flex;flex-direction:column;gap:.75rem">
                <div>
                    <label style="font-size:.78rem;font-weight:600;color:var(--argon-dark);display:block;margin-bottom:.25rem">Plan</label>
                    <select id="plan_id" name="plan_id" required style="width:100%;padding:.45rem .6rem;border:1px solid var(--argon-border);border-radius:.25rem;font-size:.82rem">
                        <option value="">Select a plan...</option>
                    </select>
                </div>
                <div>
                    <label style="font-size:.78rem;font-weight:600;color:var(--argon-dark);display:block;margin-bottom:.25rem">Amount ($)</label>
                    <input type="number" id="amount" name="amount" step="0.01" min="0.01" required style="width:100%;padding:.45rem .6rem;border:1px solid var(--argon-border);border-radius:.25rem;font-size:.82rem">
                </div>
                <div style="display:flex;gap:.5rem;justify-content:flex-end;margin-top:.25rem">
                    <button type="button" onclick="hideCreateModal()" style="background:var(--argon-light);border:1px solid var(--argon-border);padding:.45rem 1.2rem;border-radius:.25rem;cursor:pointer;font-size:.82rem">Cancel</button>
                    <button type="submit" style="background:var(--argon-primary);color:#fff;border:none;padding:.45rem 1.2rem;border-radius:.25rem;cursor:pointer;font-weight:600;font-size:.82rem">Invest</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
async function loadInvestments() {
    const res = await fetch('/api/investments/list.php');
    const data = await res.json();
    const container = document.getElementById('investmentsList');

    if (!data.success || !data.data.investments.length) {
        container.innerHTML = '<div class="tscroll"><table><tbody><tr><td colspan="6" style="text-align:center;padding:2rem;color:var(--argon-muted)">No investments yet. <a href="#" onclick="showCreateModal()" style="color:var(--argon-primary)">Create one</a></td></tr></tbody></table></div>';
        return;
    }

    container.innerHTML = `
        <div class="tscroll">
            <table>
                <thead><tr><th>Plan</th><th>Amount</th><th>Daily ROI</th><th>Profit</th><th>Status</th><th>Dates</th></tr></thead>
                <tbody>
                    ${data.data.investments.map(inv => `
                        <tr>
                            <td style="font-weight:600;color:var(--argon-dark)">${escHtml(inv.plan_name)}</td>
                            <td>$${parseFloat(inv.amount).toFixed(2)}</td>
                            <td>$${parseFloat(inv.daily_roi).toFixed(2)}</td>
                            <td>$${parseFloat(inv.total_profit).toFixed(2)}</td>
                            <td><span class="badge ${inv.status === 'active' ? 'b-success' : inv.status === 'completed' ? 'b-info' : 'b-danger'}">${inv.status}</span></td>
                            <td style="font-size:.75rem;color:var(--argon-muted)">${inv.start_date} - ${inv.end_date}</td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        </div>`;
}

async function loadPlans() {
    const res = await fetch('/api/investments/plans.php');
    const data = await res.json();
    const select = document.getElementById('plan_id');
    if (data.success) {
        data.data.plans.forEach(p => {
            select.innerHTML += `<option value="${p.id}">${escHtml(p.name)} — ${p.daily_roi}% daily / ${p.duration_days} days (Min $${parseFloat(p.min_amount).toFixed(2)})</option>`;
        });
    }
}

function showCreateModal() {
    document.getElementById('createModal').style.display = 'flex';
    if (!document.getElementById('plan_id').options.length) loadPlans();
}

function hideCreateModal() {
    document.getElementById('createModal').style.display = 'none';
}

document.getElementById('investForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    const res = await fetch('/api/investments/create.php', { method: 'POST', body: formData });
    const data = await res.json();
    if (data.success) {
        hideCreateModal();
        loadInvestments();
    } else {
        alert(data.message);
    }
});

function escHtml(s) { const d = document.createElement('div'); d.textContent = s; return d.innerHTML; }

loadInvestments();
</script>

<?php require_once __DIR__ . '/../includes/argon-footer.php'; ?>
