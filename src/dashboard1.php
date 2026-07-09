<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * Premium Glassmorphic User Dashboard (Dashboard 1)
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/auth.php';

requireLogin();

$user = getCurrentUser();
$display_name = htmlspecialchars($user['first_name'] . ' ' . $user['last_name']);
$initials = strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1));
$avatar = $user['avatar'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — <?php echo SITE_NAME; ?></title>
    
    <!-- Google Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="/assets/img/favicon.svg">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    
    <!-- Toast & SwAlert -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js@1.12.0/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js@1.12.0"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="/assets/js/app.js?v=2"></script>

    <style>
        :root {
            --bg-obsidian: #030712;
            --bg-sidebar: #090e1a;
            --bg-card: rgba(11, 15, 25, 0.65);
            --gold-primary: #dfba73;
            --gold-light: rgba(229, 192, 123, 0.06);
            --gold-gradient: linear-gradient(135deg, #e5c07b 0%, #c49a45 50%, #a27b2d 100%);
            --text-light: #f3f4f6;
            --text-muted: #9ca3af;
            --border-glass: rgba(229, 192, 123, 0.1);
            --radius-premium: 18px;
            --sidebar-width: 260px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-obsidian);
            color: var(--text-light);
            min-height: 100vh;
            display: flex;
            overflow-x: hidden;
            position: relative;
        }

        /* Ambient glows */
        .ambient-glow-dashboard {
            position: absolute;
            top: -10%;
            right: -20%;
            width: 700px;
            height: 700px;
            background: radial-gradient(circle, rgba(196, 154, 69, 0.04) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        /* OVERLAY FOR MOBILE SIDEBAR */
        #overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            z-index: 99;
            opacity: 0;
            transition: opacity 0.3s;
            backdrop-filter: blur(4px);
        }

        #overlay.show {
            display: block;
            opacity: 1;
        }

        /* SIDEBAR */
        #sidebar {
            width: var(--sidebar-width);
            background: var(--bg-sidebar);
            border-right: 1px solid var(--border-glass);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 100;
            transform: translateX(-100%);
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        #sidebar.open {
            transform: translateX(0);
        }

        @media (min-width: 768px) {
            #sidebar {
                transform: translateX(0) !important;
            }
            #overlay {
                display: none !important;
            }
        }

        .sb-brand {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-glass);
            display: flex;
            justify-content: center;
        }

        .sb-brand img {
            height: 42px;
        }

        .nav-list {
            list-style: none;
            padding: 1.5rem 0.75rem;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
            overflow-y: auto;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.75rem 1.1rem;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            border-radius: 10px;
            transition: all 0.2s;
        }

        .nav-link i {
            width: 1.25rem;
            text-align: center;
            font-size: 0.95rem;
            color: var(--text-muted);
            transition: color 0.2s;
        }

        .nav-link:hover, .nav-link.active {
            color: #fff;
            background: var(--gold-light);
            border-left: 3px solid var(--gold-primary);
        }

        .nav-link:hover i, .nav-link.active i {
            color: var(--gold-primary);
        }

        .sb-divider {
            margin: 0.75rem 0.5rem;
            border: none;
            border-top: 1px solid var(--border-glass);
        }

        /* MAIN CONTENT CONTAINER */
        #main {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            min-height: 100vh;
            position: relative;
            z-index: 1;
        }

        @media (min-width: 768px) {
            #main {
                margin-left: var(--sidebar-width);
            }
        }

        /* TOPBAR */
        #topbar {
            height: 70px;
            border-bottom: 1px solid var(--border-glass);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            position: sticky;
            top: 0;
            z-index: 50;
            background: rgba(3, 7, 18, 0.85);
            backdrop-filter: blur(16px);
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }

        .hamburger-toggle {
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-muted);
            font-size: 1.25rem;
            display: block;
            padding: 0.25rem;
        }

        @media (min-width: 768px) {
            .hamburger-toggle {
                display: none;
            }
        }

        .breadcrumb-nav {
            font-size: 0.72rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05rem;
        }

        .breadcrumb-nav a {
            color: var(--text-muted);
            text-decoration: none;
        }

        .page-title {
            font-size: 1.1rem;
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            color: #fff;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }

        .tb-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: var(--gold-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #030712;
            font-size: 0.82rem;
            font-weight: 700;
            cursor: pointer;
            overflow: hidden;
            border: 2px solid rgba(229, 192, 123, 0.3);
        }

        /* PAGE BODY CONTENT */
        #content {
            padding: 2rem;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 1.75rem;
        }

        @media (max-width: 480px) {
            #content {
                padding: 1.25rem;
            }
        }

        /* STAT CARDS */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.25rem;
        }

        @media (max-width: 992px) {
            .stats-grid { grid-template-columns: 1fr 1fr; }
        }

        @media (max-width: 480px) {
            .stats-grid { grid-template-columns: 1fr; }
        }

        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border-glass);
            border-radius: var(--radius-premium);
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.2);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(229, 192, 123, 0.02), transparent);
            pointer-events: none;
        }

        .stat-body {
            min-width: 0;
        }

        .stat-label {
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--text-muted);
            margin-bottom: 0.35rem;
        }

        .stat-value {
            font-size: 1.35rem;
            font-family: 'Outfit', sans-serif;
            font-weight: 750;
            color: #fff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.05rem;
            color: #030712;
            flex-shrink: 0;
            background: var(--gold-gradient);
            box-shadow: 0 4px 15px rgba(229, 192, 123, 0.2);
        }

        /* 2-COLUMN SECTION */
        .duo-grid {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 1.5rem;
        }

        @media (max-width: 900px) {
            .duo-grid { grid-template-columns: 1fr; }
        }

        .card {
            background: var(--bg-card);
            border: 1px solid var(--border-glass);
            border-radius: var(--radius-premium);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .card-header {
            padding: 1.2rem 1.5rem;
            border-bottom: 1px solid var(--border-glass);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-header h4 {
            font-family: 'Outfit', sans-serif;
            font-size: 0.95rem;
            font-weight: 700;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .card-body {
            padding: 1.5rem;
            flex: 1;
        }

        /* Quick Action buttons */
        .btn-action {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.9rem 1.25rem;
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            color: #fff;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-action i {
            font-size: 1rem;
            color: var(--gold-primary);
        }

        .btn-action:hover {
            background: var(--gold-light);
            border-color: var(--gold-primary);
            transform: translateX(4px);
        }

        /* Referrals Panel */
        .referral-box {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .ref-input-group {
            display: flex;
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid var(--border-glass);
            border-radius: 10px;
            overflow: hidden;
            padding: 0.25rem;
        }

        .ref-input-group input {
            background: transparent;
            border: none;
            flex: 1;
            padding: 0.5rem 0.75rem;
            color: #fff;
            font-size: 0.82rem;
            outline: none;
            font-family: monospace;
        }

        .btn-copy {
            background: var(--gold-gradient);
            border: none;
            color: #030712;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.78rem;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .btn-copy:active {
            transform: scale(0.96);
        }

        /* Chart container */
        .chart-wrapper {
            position: relative;
            width: 100%;
            height: 260px;
        }

        /* List of active investments with progress */
        .plan-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .plan-row {
            background: rgba(255, 255, 255, 0.015);
            border: 1px solid rgba(255, 255, 255, 0.03);
            border-radius: 12px;
            padding: 1.1rem;
        }

        .plan-row-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .plan-row-name {
            font-weight: 700;
            font-size: 0.88rem;
            color: #fff;
            font-family: 'Outfit', sans-serif;
        }

        .plan-row-amount {
            font-weight: 700;
            font-size: 0.88rem;
            color: var(--gold-primary);
        }

        .progress-bar-container {
            width: 100%;
            height: 6px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            overflow: hidden;
            margin: 0.5rem 0;
            position: relative;
        }

        .progress-bar-fill {
            height: 100%;
            border-radius: 10px;
            background: var(--gold-gradient);
            width: 0%; /* Animate in JS */
            transition: width 1s ease-out;
            box-shadow: 0 0 8px rgba(229, 192, 123, 0.6);
        }

        .plan-row-bottom {
            display: flex;
            justify-content: space-between;
            font-size: 0.72rem;
            color: var(--text-muted);
        }

        /* TABLES */
        .table-card {
            margin-bottom: 1.5rem;
        }

        .tscroll {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.82rem;
            text-align: left;
        }

        th {
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border-glass);
        }

        td {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border-glass);
            color: var(--text-light);
        }

        tr:hover {
            background: rgba(255, 255, 255, 0.015);
        }

        tr:last-child td {
            border-bottom: none;
        }

        .badge {
            font-size: 0.62rem;
            font-weight: 700;
            padding: 0.25rem 0.55rem;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .b-success { background: rgba(16, 185, 129, 0.15); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2); }
        .b-danger { background: rgba(239, 68, 68, 0.15); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); }
        .b-info { background: rgba(59, 130, 246, 0.15); color: #3b82f6; border: 1px solid rgba(59, 130, 246, 0.2); }

        /* FOOTER */
        footer {
            padding: 1.5rem 2rem;
            border-top: 1px solid var(--border-glass);
            text-align: center;
            font-size: 0.75rem;
            color: var(--text-muted);
            background: rgba(9, 14, 26, 0.4);
        }
    </style>
</head>
<body>

<div class="ambient-glow-dashboard"></div>
<div id="overlay" onclick="closeSidebar()"></div>

<!-- SIDEBAR -->
<nav id="sidebar">
    <div class="sb-brand">
        <img src="/assets/img/logo-v2.svg" alt="<?php echo SITE_NAME; ?>">
    </div>
    
    <ul class="nav-list">
        <li><a href="/dashboard1.php" class="nav-link active"><i class="fas fa-chart-pie"></i><span>Dashboard 1</span></a></li>
        <li><a href="/dashboard/investments.php" class="nav-link"><i class="fas fa-chart-line"></i><span>Investments</span></a></li>
        <li><a href="/dashboard/plans.php" class="nav-link"><i class="fas fa-layer-group"></i><span>Plans</span></a></li>
        <li><a href="/dashboard/deposits.php" class="nav-link"><i class="fas fa-coins"></i><span>Deposits</span></a></li>
        <li><a href="/dashboard/withdrawals.php" class="nav-link"><i class="fas fa-wallet"></i><span>Withdrawals</span></a></li>
        <li><a href="/dashboard/earnings.php" class="nav-link"><i class="fas fa-piggy-bank"></i><span>Earnings</span></a></li>
        <li><a href="/dashboard/transactions.php" class="nav-link"><i class="fas fa-list"></i><span>Transactions</span></a></li>
        <li><a href="/dashboard/referrals.php" class="nav-link"><i class="fas fa-users"></i><span>Referrals</span></a></li>
        <li><a href="/dashboard/profile.php" class="nav-link"><i class="fas fa-user-circle"></i><span>Profile</span></a></li>
        <li><a href="/dashboard/settings.php" class="nav-link"><i class="fas fa-cog"></i><span>Settings</span></a></li>
        
        <hr class="sb-divider">
        
        <li><a href="/index1.php" class="nav-link"><i class="fas fa-home"></i><span>Back to Home</span></a></li>
        <li>
            <form action="/api/auth/logout.php" method="POST" style="display: contents;">
                <button type="submit" class="nav-link" style="width: 100%; border: none; background: none; cursor: pointer; text-align: left; font-family: inherit;">
                    <i class="fas fa-sign-out-alt" style="color: #ef4444;"></i><span style="color: #ef4444;">Sign Out</span>
                </button>
            </form>
        </li>
    </ul>
</nav>

<!-- MAIN WRAPPER -->
<div id="main">
    
    <!-- TOPBAR -->
    <header id="topbar">
        <div class="topbar-left">
            <button class="hamburger-toggle" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
            <div>
                <div class="breadcrumb-nav"><a href="/index1.php">Home</a> / Premium Dashboard</div>
                <div class="page-title">Welcome back, <?php echo htmlspecialchars($user['first_name']); ?>!</div>
            </div>
        </div>
        
        <div class="topbar-right">
            <span style="font-size: 0.8rem; font-weight: 500; color: var(--text-light)"><?php echo $display_name; ?></span>
            <div class="tb-avatar" title="<?php echo $display_name; ?>">
                <?php if ($avatar): ?>
                    <img src="<?php echo htmlspecialchars($avatar); ?>" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.style.display='none';this.parentElement.textContent='<?php echo $initials; ?>'">
                <?php else: ?>
                    <?php echo $initials; ?>
                <?php endif; ?>
            </div>
        </div>
    </header>
    
    <!-- CONTENT -->
    <main id="content">
        
        <!-- STATS GRID -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-body">
                    <div class="stat-label">Total Balance</div>
                    <div class="stat-value" id="statBalance" style="color:var(--gold-primary);">$0.00</div>
                </div>
                <div class="stat-icon"><i class="fas fa-wallet"></i></div>
            </div>
            <div class="stat-card">
                <div class="stat-body">
                    <div class="stat-label">Active Invested</div>
                    <div class="stat-value" id="statInvested">$0.00</div>
                </div>
                <div class="stat-icon"><i class="fas fa-chart-line"></i></div>
            </div>
            <div class="stat-card">
                <div class="stat-body">
                    <div class="stat-label">Pending Deposits</div>
                    <div class="stat-value" id="statPendingDeposits">$0.00</div>
                </div>
                <div class="stat-icon"><i class="fas fa-coins"></i></div>
            </div>
            <div class="stat-card">
                <div class="stat-body">
                    <div class="stat-label">My Referrals</div>
                    <div class="stat-value" id="statReferrals">0</div>
                </div>
                <div class="stat-icon"><i class="fas fa-users"></i></div>
            </div>
        </div>
        
        <!-- DUO SECTION (CHART + QUICK ACTIONS) -->
        <div class="duo-grid">
            <!-- Balance Curve Chart -->
            <div class="card">
                <div class="card-header">
                    <h4>Balance Curve Log</h4>
                </div>
                <div class="card-body">
                    <div class="chart-wrapper">
                        <canvas id="balanceChart"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions / Referrals -->
            <div class="card" style="display:flex; flex-direction:column; gap:1.25rem;">
                <div class="card-header">
                    <h4>Quick Actions</h4>
                </div>
                <div class="card-body" style="display:flex; flex-direction:column; gap:0.75rem; padding-top:0;">
                    <a href="/dashboard/plans.php" class="btn-action"><i class="fas fa-chart-line"></i> Invest Capital</a>
                    <a href="/dashboard/deposits.php" class="btn-action"><i class="fas fa-plus"></i> Make Deposit</a>
                    <a href="/dashboard/withdrawals.php" class="btn-action"><i class="fas fa-wallet"></i> Request Payout</a>
                    
                    <div style="border-top:1px solid var(--border-glass); margin:0.75rem 0;"></div>
                    
                    <div class="referral-box">
                        <div style="font-size: 0.72rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Share Invitation Link</div>
                        <div class="ref-input-group">
                            <input type="text" id="refLink" readonly value="https://primeaxisinv.com/register.php?ref=<?php echo htmlspecialchars($user['referral_code']); ?>">
                            <button class="btn-copy" onclick="copyRefLink()">Copy</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- DUO SECTION (ACTIVE INVESTMENTS + RECENT TRANSACTIONS) -->
        <div class="duo-grid">
            <!-- Active Investments List -->
            <div class="card">
                <div class="card-header">
                    <h4>Current Yield Contracts</h4>
                </div>
                <div class="card-body" id="activePlansContainer">
                    <div style="text-align:center; padding:2rem; color:var(--text-muted);">Scanning active yield contracts...</div>
                </div>
            </div>
            
            <!-- Recent Transactions Table -->
            <div class="card">
                <div class="card-header">
                    <h4>Recent Ledgers</h4>
                </div>
                <div class="tscroll">
                    <table>
                        <thead>
                            <tr><th>Pill</th><th>Ledger description</th><th>Amount</th></tr>
                        </thead>
                        <tbody id="recentTransactionsContainer">
                            <tr><td colspan="3" style="text-align:center; padding:2rem; color:var(--text-muted);">Fetching ledgers...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </main>

    <!-- FOOTER -->
    <footer>
        &copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.
    </footer>
</div>

<script>
    // Sidebar collapsing logic
    function toggleSidebar() {
        const sb = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const open = sb.classList.toggle('open');
        overlay.classList.toggle('show', open);
    }

    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('open');
        document.getElementById('overlay').classList.remove('show');
    }

    // Copy referral link
    function copyRefLink() {
        const linkInput = document.getElementById('refLink');
        linkInput.select();
        linkInput.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(linkInput.value).then(() => {
            showToast('Invitation link copied successfully!', 'success');
        });
    }

    // Dashboard Data fetch & animation
    let userBalance = 0;
    let incrementTimer = null;

    async function loadDashboardData() {
        try {
            // Load dashboard totals
            const res = await fetch('/api/user/dashboard.php');
            const data = await res.json();
            if (!data.success) return;

            const d = data.data;
            userBalance = parseFloat(d.balance);
            
            // Set basic values
            document.getElementById('statInvested').textContent = '$' + parseFloat(d.total_invested).toFixed(2);
            document.getElementById('statPendingDeposits').textContent = '$' + (parseFloat(d.pending_deposits) + parseFloat(d.pending_withdrawals)).toFixed(2);
            document.getElementById('statReferrals').textContent = d.referral_count;
            
            // Start live profit accumulator ticking
            document.getElementById('statBalance').textContent = '$' + userBalance.toFixed(4);
            if (d.active_investments > 0) {
                startBalanceIncrement();
            }

            // Populate recent transactions
            const tx = document.getElementById('recentTransactionsContainer');
            if (!d.recent_transactions.length) {
                tx.innerHTML = '<tr><td colspan="3" style="text-align:center; padding:2rem; color:var(--text-muted);">No ledger logs found.</td></tr>';
            } else {
                tx.innerHTML = d.recent_transactions.map(t => {
                    const badgeClass = t.type === 'deposit' ? 'b-success' : t.type === 'withdrawal' ? 'b-danger' : 'b-info';
                    return `<tr>
                        <td><span class="badge ${badgeClass}">${t.type}</span></td>
                        <td>${escHtml(t.description || '—')}</td>
                        <td style="font-weight:700;">$${parseFloat(t.amount).toFixed(2)}</td>
                    </tr>`;
                }).join('');
            }

            // Draw balance chart
            drawChart(userBalance);

        } catch (e) {
            console.error('Error loading dashboard summary:', e);
        }

        try {
            // Load investments
            const res = await fetch('/api/investments/list.php');
            const data = await res.json();
            if (!data.success) return;

            const invs = data.data.investments.filter(i => i.status === 'active');
            const container = document.getElementById('activePlansContainer');
            
            if (!invs.length) {
                container.innerHTML = '<div style="text-align:center; padding:2rem; color:var(--text-muted);">No active yield contracts.</div>';
            } else {
                container.innerHTML = invs.map(i => {
                    const start = new Date(i.start_date).getTime();
                    const end = new Date(i.end_date).getTime();
                    const now = new Date().getTime();
                    
                    let pct = 0;
                    if (end > start) {
                        pct = Math.min(100, Math.max(0, ((now - start) / (end - start)) * 100));
                    }

                    return `<div class="plan-row">
                        <div class="plan-row-top">
                            <span class="plan-row-name">${escHtml(i.plan_name)} (${i.duration_days} days)</span>
                            <span class="plan-row-amount">$${parseFloat(i.amount).toFixed(2)}</span>
                        </div>
                        <div class="progress-bar-container">
                            <div class="progress-bar-fill" style="width: ${pct.toFixed(1)}%;"></div>
                        </div>
                        <div class="plan-row-bottom">
                            <span>Maturity: ${pct.toFixed(0)}%</span>
                            <span>Ends: ${new Date(i.end_date).toLocaleDateString()}</span>
                        </div>
                    </div>`;
                }).join('');
            }

        } catch (e) {
            console.error('Error loading investments:', e);
        }
    }

    function escHtml(s) {
        const d = document.createElement('div');
        d.textContent = s;
        return d.innerHTML;
    }

    // Live balance increment (simulates daily ROI update incrementally)
    function startBalanceIncrement() {
        if (incrementTimer) clearInterval(incrementTimer);
        incrementTimer = setInterval(() => {
            // increment balance slightly (e.g. 0.0001) every 2 seconds
            userBalance += 0.0001;
            document.getElementById('statBalance').textContent = '$' + userBalance.toFixed(4);
        }, 2000);
    }

    // Draw Chart using Chart.js
    function drawChart(currentBalance) {
        const ctx = document.getElementById('balanceChart').getContext('2d');
        
        // Generate mock points that climb up to currentBalance
        const points = [];
        let running = currentBalance * 0.82;
        for (let i = 0; i < 6; i++) {
            points.push(running);
            running += (currentBalance - running) * (0.3 + Math.random() * 0.3);
        }
        points.push(currentBalance);

        // Labels (last 7 days)
        const labels = [];
        for (let i = 6; i >= 0; i--) {
            const d = new Date();
            d.setDate(d.getDate() - i);
            labels.push(d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }));
        }

        // Gradients
        const gradient = ctx.createLinearGradient(0, 0, 0, 250);
        gradient.addColorStop(0, 'rgba(229, 192, 123, 0.25)');
        gradient.addColorStop(1, 'rgba(229, 192, 123, 0.00)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Asset Curve ($)',
                    data: points,
                    borderColor: '#dfba73',
                    borderWidth: 2,
                    fill: true,
                    backgroundColor: gradient,
                    tension: 0.4,
                    pointBackgroundColor: '#dfba73',
                    pointBorderColor: '#030712',
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        grid: { color: 'rgba(255, 255, 255, 0.03)' },
                        ticks: {
                            color: '#9ca3af',
                            font: { size: 9 },
                            callback: function(value) { return '$' + value.toFixed(0); }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#9ca3af', font: { size: 9 } }
                    }
                }
            }
        });
    }

    // Init
    loadDashboardData();
</script>
</body>
</html>
