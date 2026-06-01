<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * User Dashboard
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/auth.php';

requireLogin();

$user = getCurrentUser();

// ── Argon header params ──
$nav_type = 'user';
$active_nav = 'dashboard';
$page_title = 'Dashboard';
$page_subtitle = 'Welcome back, ' . htmlspecialchars($user['first_name']) . '!';
require_once __DIR__ . '/../includes/argon-header.php';
?>

<!-- STAT CARDS -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-body">
            <div class="stat-label">Total Balance</div>
            <div class="stat-value" id="statBalance">—</div>
        </div>
        <div class="stat-icon bg-primary"><i class="fas fa-dollar-sign"></i></div>
    </div>
    <div class="stat-card">
        <div class="stat-body">
            <div class="stat-label">Total Invested</div>
            <div class="stat-value" id="statInvested">—</div>
        </div>
        <div class="stat-icon bg-success"><i class="fas fa-chart-line"></i></div>
    </div>
    <div class="stat-card">
        <div class="stat-body">
            <div class="stat-label">Active Investments</div>
            <div class="stat-value" id="statInvestments">—</div>
        </div>
        <div class="stat-icon bg-warning"><i class="fas fa-chart-line"></i></div>
    </div>
    <div class="stat-card">
        <div class="stat-body">
            <div class="stat-label">Referrals</div>
            <div class="stat-value" id="statReferrals">—</div>
        </div>
        <div class="stat-icon bg-danger"><i class="fas fa-users"></i></div>
    </div>
</div>

<!-- QUICK ACTIONS + NAVIGATION -->
<div class="duo">
    <div class="card">
        <div class="card-header"><h6>Quick Actions</h6></div>
        <div class="card-body" style="display:flex;flex-direction:column;gap:.5rem">
            <a href="/dashboard/investments.php" class="act-link"><i class="fas fa-chart-line"></i> Invest Now</a>
            <a href="/dashboard/deposits.php" class="act-link"><i class="fas fa-coins"></i> Make Deposit</a>
            <a href="/dashboard/withdrawals.php" class="act-link"><i class="fas fa-wallet"></i> Withdraw</a>
        </div>
    </div>
    <div class="card">
        <div class="card-header"><h6>Navigation</h6></div>
        <div class="card-body" style="display:flex;flex-direction:column;gap:.5rem">
            <a href="/dashboard/referrals.php" class="act-link"><i class="fas fa-users"></i> My Referrals</a>
            <a href="/dashboard/transactions.php" class="act-link"><i class="fas fa-list"></i> Transaction History</a>
            <a href="/dashboard/profile.php" class="act-link"><i class="fas fa-user-circle"></i> Profile</a>
            <a href="/dashboard/settings.php" class="act-link"><i class="fas fa-cog"></i> Settings</a>
        </div>
    </div>
</div>

<!-- RECENT TRANSACTIONS -->
<div class="card tsec">
    <div class="card-header"><h6>Recent Transactions</h6></div>
    <div class="tscroll">
        <table>
            <thead>
                <tr><th>Type</th><th>Description</th><th>Amount</th><th>Date</th></tr>
            </thead>
            <tbody id="recentTransactions">
                <tr><td colspan="4" style="text-align:center;color:var(--argon-muted)">Loading...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<script>
async function loadDashboard() {
    const res = await fetch('/api/user/dashboard.php');
    const data = await res.json();
    if (!data.success) return;

    const d = data.data;
    document.getElementById('statBalance').textContent = '$' + parseFloat(d.balance).toFixed(2);
    document.getElementById('statInvested').textContent = '$' + parseFloat(d.total_invested).toFixed(2);
    document.getElementById('statInvestments').textContent = d.active_investments;
    document.getElementById('statReferrals').textContent = d.referral_count;

    const tx = document.getElementById('recentTransactions');
    if (!d.recent_transactions.length) {
        tx.innerHTML = '<tr><td colspan="4" style="text-align:center;color:var(--argon-muted)">No transactions yet.</td></tr>';
    } else {
        tx.innerHTML = d.recent_transactions.map(t =>
            `<tr>
                <td><span class="badge ${t.type === 'deposit' ? 'b-success' : t.type === 'withdrawal' ? 'b-danger' : 'b-info'}">${escHtml(t.type)}</span></td>
                <td>${escHtml(t.description || '—')}</td>
                <td style="font-weight:600">$${parseFloat(t.amount).toFixed(2)}</td>
                <td style="font-size:.75rem;color:var(--argon-muted)">${new Date(t.created_at).toLocaleDateString()}</td>
            </tr>`
        ).join('');
    }
}

function escHtml(s) { const d = document.createElement('div'); d.textContent = s; return d.innerHTML; }

loadDashboard();
</script>

<?php require_once __DIR__ . '/../includes/argon-footer.php'; ?>
<script>
setTimeout(()=>{const p=new URLSearchParams(window.location.search);if(p.get('login')==='1'){showToast('You have successfully signed in.','success');window.history.replaceState({},document.title,window.location.pathname)};},100);
</script>
