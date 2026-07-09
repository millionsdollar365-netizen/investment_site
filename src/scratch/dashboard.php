<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * Creative DeFi Synth User Dashboard (Scratch Dashboard)
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/auth.php';

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
    <title>Protocol Terminal — Aetheris</title>
    
    <!-- Google Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=Outfit:wght@700;800&display=swap" rel="stylesheet">
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
            --bg-void: #05020c;
            --bg-sidebar: #080414;
            --bg-card: rgba(13, 8, 28, 0.75);
            --neon-cyan: #06b6d4;
            --neon-violet: #6366f1;
            --neon-magenta: #d946ef;
            --gold-accent: #f59e0b;
            --text-light: #f3f4f6;
            --text-muted: #8b85a3;
            --border-neon: rgba(99, 102, 241, 0.15);
            --border-neon-active: rgba(6, 182, 212, 0.35);
            --radius-terminal: 14px;
            --sidebar-width: 250px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Space Grotesk', sans-serif;
            background: var(--bg-void);
            color: var(--text-light);
            min-height: 100vh;
            display: flex;
            overflow-x: hidden;
            position: relative;
        }

        /* Ambient glow */
        .ambient-glow {
            position: absolute;
            top: -10%;
            right: -20%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(6, 182, 212, 0.05) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        /* OVERLAY */
        #overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.7);
            z-index: 99;
            opacity: 0;
            transition: opacity 0.3s;
            backdrop-filter: blur(4px);
        }

        #overlay.show {
            display: block;
            opacity: 1;
        }

        /* SIDEBAR (Collapsible) */
        #sidebar {
            width: var(--sidebar-width);
            background: var(--bg-sidebar);
            border-right: 1px solid var(--border-neon);
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
            border-bottom: 1px solid var(--border-neon);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-size: 1.25rem;
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        .sb-brand span {
            background: linear-gradient(135deg, var(--neon-cyan), var(--neon-magenta));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-list {
            list-style: none;
            padding: 1.5rem 0.75rem;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
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
            border-radius: 8px;
            transition: all 0.25s;
            border: 1px solid transparent;
        }

        .nav-link i {
            width: 1.25rem;
            text-align: center;
            font-size: 0.95rem;
            color: var(--text-muted);
            transition: color 0.25s;
        }

        .nav-link:hover, .nav-link.active {
            color: #fff;
            background: rgba(6, 182, 212, 0.04);
            border-color: rgba(6, 182, 212, 0.15);
            box-shadow: 0 0 10px rgba(6, 182, 212, 0.08);
        }

        .nav-link:hover i, .nav-link.active i {
            color: var(--neon-cyan);
        }

        .sb-divider {
            margin: 0.75rem 0.5rem;
            border: none;
            border-top: 1px solid var(--border-neon);
        }

        /* MAIN CONTENT AREA */
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
            border-bottom: 1px solid var(--border-neon);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            position: sticky;
            top: 0;
            z-index: 50;
            background: rgba(5, 2, 12, 0.9);
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
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            color: #fff;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }

        .tb-avatar {
            width: 36px;
            height: 36px;
            border-radius: 4px;
            background: linear-gradient(135deg, var(--neon-cyan), var(--neon-violet));
            display: flex;
            align-items: center;
            justify-content: center;
            color: #05020c;
            font-size: 0.82rem;
            font-weight: 700;
            cursor: pointer;
            overflow: hidden;
            border: 1px solid rgba(6, 182, 212, 0.3);
        }

        /* CONTENT GRID */
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

        /* METRIC CARDS */
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
            border: 1px solid var(--border-neon);
            border-radius: var(--radius-terminal);
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.4);
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
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 750;
            color: #fff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .stat-icon {
            width: 42px;
            height: 42px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.05rem;
            color: var(--neon-cyan);
            flex-shrink: 0;
            background: rgba(6, 182, 212, 0.08);
            border: 1px solid rgba(6, 182, 212, 0.2);
            box-shadow: 0 0 10px rgba(6, 182, 212, 0.1);
        }

        /* DUO GRID */
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
            border: 1px solid var(--border-neon);
            border-radius: var(--radius-terminal);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .card-header {
            padding: 1.2rem 1.5rem;
            border-bottom: 1px solid var(--border-neon);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-header h4 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 0.9rem;
            font-weight: 700;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .card-body {
            padding: 1.5rem;
            flex: 1;
        }

        /* Terminal Logs style table */
        .tscroll {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.8rem;
            text-align: left;
            font-family: monospace;
        }

        th {
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border-neon);
        }

        td {
            padding: 0.85rem 1.5rem;
            border-bottom: 1px solid var(--border-neon);
            color: var(--text-light);
        }

        tr:hover {
            background: rgba(6, 182, 212, 0.02);
        }

        tr:last-child td {
            border-bottom: none;
        }

        .badge {
            font-size: 0.62rem;
            font-weight: 700;
            padding: 0.2rem 0.5rem;
            border-radius: 3px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .b-success { background: rgba(16, 185, 129, 0.08); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2); }
        .b-danger { background: rgba(239, 68, 68, 0.08); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); }
        .b-info { background: rgba(6, 182, 212, 0.08); color: var(--neon-cyan); border: 1px solid rgba(6, 182, 212, 0.2); }

        /* Validator Rig box */
        .rig-container {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .rig-status-card {
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid var(--border-neon);
            border-radius: 10px;
            padding: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .rig-info {
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }

        .rig-spin {
            font-size: 1.5rem;
            color: var(--neon-cyan);
            animation: spin 3s linear infinite;
        }

        @keyframes spin {
            100% { transform: rotate(360deg); }
        }

        .rig-text h5 {
            font-size: 0.85rem;
            font-weight: 700;
            color: #fff;
        }

        .rig-text p {
            font-size: 0.7rem;
            color: var(--text-muted);
            font-family: monospace;
        }

        .rig-value {
            font-family: monospace;
            font-size: 1rem;
            font-weight: 700;
            color: #10b981;
        }

        /* Staking contract lists */
        .staking-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .staking-row {
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid var(--border-neon);
            border-radius: 10px;
            padding: 1.1rem;
        }

        .staking-row-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .staking-name {
            font-weight: 700;
            font-size: 0.88rem;
            color: #fff;
        }

        .staking-amount {
            font-weight: 700;
            font-size: 0.88rem;
            color: var(--neon-cyan);
            font-family: monospace;
        }

        .prog-bar {
            width: 100%;
            height: 4px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            overflow: hidden;
            margin: 0.6rem 0;
            position: relative;
        }

        .prog-bar-fill {
            height: 100%;
            border-radius: 10px;
            background: linear-gradient(90deg, var(--neon-cyan), var(--neon-violet));
            width: 0%;
            transition: width 1s ease-out;
            box-shadow: 0 0 10px rgba(6, 182, 212, 0.5);
        }

        .staking-row-bottom {
            display: flex;
            justify-content: space-between;
            font-size: 0.7rem;
            color: var(--text-muted);
        }

        /* Referral input panel */
        .ref-input-group {
            display: flex;
            background: rgba(0, 0, 0, 0.5);
            border: 1px solid var(--border-neon);
            border-radius: 8px;
            overflow: hidden;
            padding: 0.2rem;
        }

        .ref-input-group input {
            background: transparent;
            border: none;
            flex: 1;
            padding: 0.5rem 0.75rem;
            color: #fff;
            font-size: 0.8rem;
            outline: none;
            font-family: monospace;
        }

        .btn-copy {
            background: linear-gradient(135deg, var(--neon-cyan), var(--neon-violet));
            border: none;
            color: #05020c;
            padding: 0.5rem 0.95rem;
            border-radius: 6px;
            font-weight: 700;
            font-size: 0.75rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-copy:hover {
            color: #fff;
            box-shadow: 0 0 10px rgba(6, 182, 212, 0.4);
        }

        /* Action links */
        .btn-action {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.85rem 1.15rem;
            background: rgba(255, 255, 255, 0.015);
            border: 1px solid var(--border-neon);
            border-radius: 10px;
            color: #fff;
            text-decoration: none;
            font-size: 0.82rem;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-action i {
            color: var(--neon-cyan);
        }

        .btn-action:hover {
            background: rgba(6, 182, 212, 0.04);
            border-color: var(--border-neon-active);
            transform: translateX(4px);
        }

        /* Chart container */
        .chart-wrapper {
            position: relative;
            width: 100%;
            height: 250px;
        }

        /* FOOTER */
        footer {
            padding: 1.5rem 2rem;
            border-top: 1px solid var(--border-neon);
            text-align: center;
            font-size: 0.72rem;
            color: var(--text-muted);
            background: #030106;
        }
    </style>
</head>
<body>

<div class="ambient-glow"></div>
<div id="overlay" onclick="closeSidebar()"></div>

<!-- SIDEBAR -->
<nav id="sidebar">
    <div class="sb-brand">
        <i class="fas fa-microchip" style="color:var(--neon-cyan)"></i> <span>AETHERIS</span>
    </div>
    
    <ul class="nav-list">
        <li><a href="/scratch/dashboard.php" class="nav-link active"><i class="fas fa-chart-pie"></i><span>Protocol Terminal</span></a></li>
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
        
        <li><a href="/scratch/" class="nav-link"><i class="fas fa-home"></i><span>Back to Home</span></a></li>
        <li>
            <form action="/api/auth/logout.php" method="POST" style="display: contents;">
                <button type="submit" class="nav-link" style="width: 100%; border: none; background: none; cursor: pointer; text-align: left; font-family: inherit;">
                    <i class="fas fa-sign-out-alt" style="color: var(--neon-magenta);"></i><span style="color: var(--neon-magenta);">Node Signout</span>
                </button>
            </form>
        </li>
    </ul>
</nav>

<!-- MAIN CONTAINER -->
<div id="main">
    
    <!-- TOPBAR -->
    <header id="topbar">
        <div class="topbar-left">
            <button class="hamburger-toggle" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
            <div>
                <div class="breadcrumb-nav"><a href="/scratch/">Protocol</a> / Terminal Panel</div>
                <div class="page-title">Validator Connected: <?php echo htmlspecialchars($user['first_name']); ?></div>
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
        
        <!-- METRICS -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-body">
                    <div class="stat-label">Accumulated Stake</div>
                    <div class="stat-value" id="statBalance" style="color:var(--neon-cyan);">$0.0000</div>
                </div>
                <div class="stat-icon"><i class="fas fa-vault"></i></div>
            </div>
            <div class="stat-card">
                <div class="stat-body">
                    <div class="stat-label">Active Node Staking</div>
                    <div class="stat-value" id="statInvested">$0.00</div>
                </div>
                <div class="stat-icon"><i class="fas fa-microchip"></i></div>
            </div>
            <div class="stat-card">
                <div class="stat-body">
                    <div class="stat-label">Pending Settlements</div>
                    <div class="stat-value" id="statPending">$0.00</div>
                </div>
                <div class="stat-icon"><i class="fas fa-receipt"></i></div>
            </div>
            <div class="stat-card">
                <div class="stat-body">
                    <div class="stat-label">Affiliated Validator nodes</div>
                    <div class="stat-value" id="statReferrals">0</div>
                </div>
                <div class="stat-icon"><i class="fas fa-network-wired"></i></div>
            </div>
        </div>
        
        <!-- DUO ROW (CHART + TERMINAL VALIDATOR DETAILS) -->
        <div class="duo-grid">
            <!-- Staking Yield Chart -->
            <div class="card">
                <div class="card-header">
                    <h4>Staking Analytics (Calculated vs Real Yield)</h4>
                </div>
                <div class="card-body">
                    <div class="chart-wrapper">
                        <canvas id="yieldChart"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Validator Details Rig simulator -->
            <div class="card">
                <div class="card-header">
                    <h4>Validator Performance Console</h4>
                </div>
                <div class="card-body rig-container">
                    <div class="rig-status-card">
                        <div class="rig-info">
                            <i class="fas fa-gear rig-spin"></i>
                            <div class="rig-text">
                                <h5>Validator Node status</h5>
                                <p>PoS Node #<?php echo rand(100, 999); ?> active</p>
                            </div>
                        </div>
                        <div class="rig-value" id="rigSpeed">99.8% Efficiency</div>
                    </div>
                    
                    <a href="/dashboard/plans.php" class="btn-action"><i class="fas fa-bolt"></i> Lock Capital to Node</a>
                    <a href="/dashboard/deposits.php" class="btn-action"><i class="fas fa-plus"></i> Stake Crypto Assets</a>
                    <a href="/dashboard/withdrawals.php" class="btn-action"><i class="fas fa-wallet"></i> Extract Staking Profits</a>
                    
                    <div style="border-top:1px solid var(--border-neon); margin:0.5rem 0;"></div>
                    
                    <div class="referral-box" style="display:flex; flex-direction:column; gap:0.5rem;">
                        <div style="font-size: 0.65rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Validator Invite Port</div>
                        <div class="ref-input-group">
                            <input type="text" id="refLink" readonly value="https://primeaxisinv.com/register.php?ref=<?php echo htmlspecialchars($user['referral_code']); ?>">
                            <button class="btn-copy" onclick="copyRefLink()">Port copy</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- DUO ROW (STAKING POOLS + LEDGER ARCHIVE) -->
        <div class="duo-grid">
            <!-- Staking Contracts -->
            <div class="card">
                <div class="card-header">
                    <h4>Locked Validator Contracts</h4>
                </div>
                <div class="card-body staking-list" id="stakingListContainer">
                    <div style="text-align:center; padding:2rem; color:var(--text-muted);">Syncing staking pools...</div>
                </div>
            </div>
            
            <!-- Ledger Archive -->
            <div class="card">
                <div class="card-header">
                    <h4>Ledger Socket Stream</h4>
                </div>
                <div class="tscroll">
                    <table>
                        <thead>
                            <tr><th>Type</th><th>Protocol Details</th><th>Value</th></tr>
                        </thead>
                        <tbody id="ledgerContainer">
                            <tr><td colspan="3" style="text-align:center; padding:2rem; color:var(--text-muted);">Reading stream payload...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </main>

    <!-- FOOTER -->
    <footer>
        &copy; <?php echo date('Y'); ?> Aetheris Protocol. All rights reserved.
    </footer>
</div>

<script>
    // Sidebar Collapsible
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

    // Copy ref link
    function copyRefLink() {
        const linkInput = document.getElementById('refLink');
        linkInput.select();
        linkInput.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(linkInput.value).then(() => {
            showToast('Invite URL copied successfully!', 'success');
        });
    }

    // Load Data
    let userBalance = 0;
    let timer = null;

    async function loadDashboard() {
        try {
            // Load dashboard summary
            const res = await fetch('/api/user/dashboard.php');
            const data = await res.json();
            if (!data.success) return;

            const d = data.data;
            userBalance = parseFloat(d.balance);
            
            // Populate metrics
            document.getElementById('statInvested').textContent = '$' + parseFloat(d.total_invested).toFixed(2);
            document.getElementById('statPending').textContent = '$' + (parseFloat(d.pending_deposits) + parseFloat(d.pending_withdrawals)).toFixed(2);
            document.getElementById('statReferrals').textContent = d.referral_count;
            
            // Start live proof-of-stake incremental ticker
            document.getElementById('statBalance').textContent = '$' + userBalance.toFixed(5);
            if (d.active_investments > 0) {
                startYieldAccumulator();
            }

            // Populate table log list
            const l = document.getElementById('ledgerContainer');
            if (!d.recent_transactions.length) {
                l.innerHTML = '<tr><td colspan="3" style="text-align:center; padding:2rem; color:var(--text-muted);">No logs found in socket ledger.</td></tr>';
            } else {
                l.innerHTML = d.recent_transactions.map(t => {
                    const badgeClass = t.type === 'deposit' ? 'b-success' : t.type === 'withdrawal' ? 'b-danger' : 'b-info';
                    return `<tr>
                        <td><span class="badge ${badgeClass}">${t.type}</span></td>
                        <td>${escHtml(t.description || '—')}</td>
                        <td style="font-weight:700; color:var(--neon-cyan)">$${parseFloat(t.amount).toFixed(2)}</td>
                    </tr>`;
                }).join('');
            }

            // Draw yield chart
            drawYieldChart(userBalance);

        } catch(e) {
            console.error('Error loading summary:', e);
        }

        try {
            // Load locked staking contracts
            const res = await fetch('/api/investments/list.php');
            const data = await res.json();
            if (!data.success) return;

            const stakes = data.data.investments.filter(i => i.status === 'active');
            const container = document.getElementById('stakingListContainer');
            
            if (!stakes.length) {
                container.innerHTML = '<div style="text-align:center; padding:2rem; color:var(--text-muted);">No active staking contracts.</div>';
            } else {
                container.innerHTML = stakes.map(s => {
                    const start = new Date(s.start_date).getTime();
                    const end = new Date(s.end_date).getTime();
                    const now = new Date().getTime();
                    
                    let pct = 0;
                    if (end > start) {
                        pct = Math.min(100, Math.max(0, ((now - start) / (end - start)) * 100));
                    }

                    return `<div class="staking-row">
                        <div class="staking-row-top">
                            <span class="staking-name">${escHtml(s.plan_name)} (${s.duration_days} days)</span>
                            <span class="staking-amount">$${parseFloat(s.amount).toFixed(2)}</span>
                        </div>
                        <div class="prog-bar">
                            <div class="prog-bar-fill" style="width: ${pct.toFixed(1)}%;"></div>
                        </div>
                        <div class="staking-row-bottom">
                            <span>Validation weight: ${pct.toFixed(0)}%</span>
                            <span>Maturity date: ${new Date(s.end_date).toLocaleDateString()}</span>
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

    // Live balance PoS accumulator
    function startYieldAccumulator() {
        if (timer) clearInterval(timer);
        timer = setInterval(() => {
            // increment user balance slightly every second to simulate proof-of-stake validation ROI updates
            userBalance += 0.00005;
            document.getElementById('statBalance').textContent = '$' + userBalance.toFixed(5);
        }, 1000);
    }

    // Double data-set Chart.js graph
    function drawYieldChart(currentBalance) {
        const ctx = document.getElementById('yieldChart').getContext('2d');
        
        // Mock two data-sets climbing to current balance
        const estData = [];
        const realData = [];
        let rRunning = currentBalance * 0.8;
        let eRunning = currentBalance * 0.78;
        
        for (let i = 0; i < 6; i++) {
            realData.push(rRunning);
            estData.push(eRunning);
            rRunning += (currentBalance - rRunning) * (0.35 + Math.random() * 0.25);
            eRunning += (currentBalance - eRunning) * (0.32 + Math.random() * 0.25);
        }
        realData.push(currentBalance);
        estData.push(currentBalance * 0.99);

        // Labels
        const labels = [];
        for (let i = 6; i >= 0; i--) {
            const d = new Date();
            d.setDate(d.getDate() - i);
            labels.push(d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }));
        }

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Real Yield',
                        data: realData,
                        borderColor: '#06b6d4',
                        borderWidth: 2,
                        fill: false,
                        tension: 0.35,
                        pointBackgroundColor: '#06b6d4',
                        pointBorderColor: '#05020c',
                        pointHoverRadius: 6
                    },
                    {
                        label: 'Estimated Yield',
                        data: estData,
                        borderColor: '#d946ef',
                        borderWidth: 1.5,
                        borderDash: [5, 5],
                        fill: false,
                        tension: 0.35,
                        pointBackgroundColor: '#d946ef',
                        pointBorderColor: '#05020c',
                        pointHoverRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: { color: '#8b85a3', font: { size: 9 } }
                    }
                },
                scales: {
                    y: {
                        grid: { color: 'rgba(255, 255, 255, 0.02)' },
                        ticks: {
                            color: '#8b85a3',
                            font: { size: 9 },
                            callback: function(value) { return '$' + value.toFixed(0); }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#8b85a3', font: { size: 9 } }
                    }
                }
            }
        });
    }

    // Node rig efficiency oscillation
    setInterval(() => {
        const base = 99.4;
        const diff = Math.random() * 0.5;
        document.getElementById('rigSpeed').textContent = (base + diff).toFixed(2) + '% Efficiency';
    }, 5000);

    // Initial load
    loadDashboard();
</script>
</body>
</html>
