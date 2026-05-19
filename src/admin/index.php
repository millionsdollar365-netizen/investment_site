<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/admin-session.php';
requireAdminLogin();
$admin = getCurrentAdmin();
$nav_type = 'admin'; $active_nav = 'dashboard';
$page_title = 'Admin Dashboard'; $page_subtitle = 'Platform overview and management';
require_once __DIR__ . '/../includes/argon-header.php';
?>

<div class="stats-grid">
    <div class="stat-card"><div class="stat-body"><div class="stat-label">Total Users</div><div class="stat-value" id="statTotalUsers">—</div><div class="stat-change" id="statActiveUsers"></div></div><div class="stat-icon bg-primary"><i class="fas fa-users"></i></div></div>
    <div class="stat-card"><div class="stat-body"><div class="stat-label">Pending Deposits</div><div class="stat-value" id="statPendingDeposits">—</div><div class="stat-change" id="statPendingDepositsAmount"></div></div><div class="stat-icon bg-success"><i class="fas fa-coins"></i></div></div>
    <div class="stat-card"><div class="stat-body"><div class="stat-label">Pending Withdrawals</div><div class="stat-value" id="statPendingWithdrawals">—</div><div class="stat-change" id="statPendingWithdrawalsAmount"></div></div><div class="stat-icon bg-warning"><i class="fas fa-wallet"></i></div></div>
    <div class="stat-card"><div class="stat-body"><div class="stat-label">Total Balance</div><div class="stat-value" id="statTotalBalance">—</div><div class="stat-change">Invested: <span id="statTotalInvested">—</span></div></div><div class="stat-icon bg-danger"><i class="fas fa-dollar-sign"></i></div></div>
</div>

<div class="card"><div class="card-header"><h6>Management</h6></div>
<div class="card-body"><div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:.75rem">
    <a href="/admin/users.php" class="act-link" style="text-align:center;padding:.85rem;border-radius:.25rem;background:rgba(94,114,228,.08)"><i class="fas fa-users"></i> Manage Users</a>
    <a href="/admin/deposits.php" class="act-link" style="text-align:center;padding:.85rem;border-radius:.25rem;background:rgba(45,206,137,.08)"><i class="fas fa-coins"></i> Deposits</a>
    <a href="/admin/withdrawals.php" class="act-link" style="text-align:center;padding:.85rem;border-radius:.25rem;background:rgba(251,99,64,.08)"><i class="fas fa-wallet"></i> Withdrawals</a>
    <a href="/admin/investments.php" class="act-link" style="text-align:center;padding:.85rem;border-radius:.25rem;background:rgba(130,94,228,.08)"><i class="fas fa-chart-line"></i> Investments</a>
    <a href="/admin/plans.php" class="act-link" style="text-align:center;padding:.85rem;border-radius:.25rem;background:rgba(17,205,239,.08)"><i class="fas fa-layer-group"></i> Plans</a>
    <a href="/admin/settings.php" class="act-link" style="text-align:center;padding:.85rem;border-radius:.25rem;background:rgba(136,152,170,.08)"><i class="fas fa-cog"></i> Settings</a>
</div></div></div>

<script>
async function loadDashboard(){const r=await fetch('/api/admin/dashboard.php');const d=await r.json();if(!d.success)return;const s=d.data;document.getElementById('statTotalUsers').textContent=s.users.total;document.getElementById('statActiveUsers').textContent=s.users.active+' active';document.getElementById('statPendingDeposits').textContent=s.deposits.pending_count;document.getElementById('statPendingDepositsAmount').textContent='$'+s.deposits.pending_amount.toFixed(2)+' pending';document.getElementById('statPendingWithdrawals').textContent=s.withdrawals.pending_count;document.getElementById('statPendingWithdrawalsAmount').textContent='$'+s.withdrawals.pending_amount.toFixed(2)+' pending';document.getElementById('statTotalBalance').textContent='$'+s.balances.total.toFixed(2);document.getElementById('statTotalInvested').textContent='$'+s.investments.total_amount.toFixed(2)}
loadDashboard();
</script>
<?php require_once __DIR__ . '/../includes/argon-footer.php'; ?>
