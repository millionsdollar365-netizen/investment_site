<?php 
require_once __DIR__ . '/../includes/config.php'; 
require_once __DIR__ . '/../includes/session.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Aetheris Protocol — The Quantum Yield Engine</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="description" content="Next-generation decentralized capital growth. Secure, automated yield protocol built on institutional security.">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=Outfit:wght@800;900&display=swap" rel="stylesheet">
    
    <!-- FontAwesome & Notyf -->
    <link rel="icon" type="image/svg+xml" href="/assets/img/favicon.svg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --bg-void: #05020c;
            --bg-card: rgba(13, 8, 28, 0.7);
            --neon-cyan: #06b6d4;
            --neon-violet: #6366f1;
            --neon-magenta: #d946ef;
            --gold-accent: #f59e0b;
            --text-light: #f3f4f6;
            --text-muted: #8b85a3;
            --border-neon: rgba(99, 102, 241, 0.15);
            --border-neon-active: rgba(6, 182, 212, 0.4);
            --radius-terminal: 16px;
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
            line-height: 1.6;
            overflow-x: hidden;
            position: relative;
        }

        /* Canvas Particle Background */
        #particleCanvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
            opacity: 0.65;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
            position: relative;
            z-index: 1;
        }

        /* Nav */
        .nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            padding: 1.25rem 0;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .nav.scrolled {
            background: rgba(5, 2, 12, 0.9);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-neon);
            padding: 0.9rem 0;
        }

        .nav-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .nav-logo {
            font-size: 1.35rem;
            font-weight: 800;
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            letter-spacing: -0.03em;
        }

        .nav-logo span {
            background: linear-gradient(135deg, var(--neon-cyan), var(--neon-magenta));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 2.2rem;
            list-style: none;
        }

        .nav-links a {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.88rem;
            font-weight: 500;
            transition: color 0.3s;
            position: relative;
        }

        .nav-links a:hover {
            color: #fff;
        }

        /* Buttons */
        .btn {
            display: inline-block;
            padding: 0.6rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.85rem;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            cursor: pointer;
            border: none;
            position: relative;
        }

        .btn-neon {
            background: linear-gradient(135deg, var(--neon-cyan), var(--neon-violet));
            color: #05020c;
            font-weight: 700;
            box-shadow: 0 0 15px rgba(6, 182, 212, 0.25);
        }

        .btn-neon:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 25px rgba(6, 182, 212, 0.5);
            color: #fff;
        }

        .btn-ghost {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border-neon);
            color: #fff;
        }

        .btn-ghost:hover {
            background: rgba(99, 102, 241, 0.1);
            border-color: var(--neon-violet);
            box-shadow: 0 0 15px rgba(99, 102, 241, 0.25);
            transform: translateY(-2px);
        }

        .btn-lg {
            padding: 0.8rem 2rem;
            font-size: 0.95rem;
        }

        /* Mobile nav toggle */
        .hamburger {
            display: none;
            background: none;
            border: none;
            color: #fff;
            font-size: 1.5rem;
            cursor: pointer;
            z-index: 10001;
        }

        .mobile-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(5, 2, 12, 0.99);
            backdrop-filter: blur(20px);
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 1.5rem;
            z-index: 9999;
        }

        .mobile-overlay.open {
            display: flex;
        }

        @media (max-width: 768px) {
            .nav-links { display: none; }
            .hamburger { display: block; }
        }

        /* Hero */
        .hero {
            min-height: 90vh;
            display: flex;
            align-items: center;
            padding: 8rem 0 4rem;
            position: relative;
        }

        .hero-grid {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 4rem;
            align-items: center;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.3rem 0.9rem;
            border-radius: 4px;
            background: rgba(6, 182, 212, 0.08);
            border: 1px solid rgba(6, 182, 212, 0.25);
            font-size: 0.72rem;
            color: var(--neon-cyan);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 1.5rem;
        }

        .hero h1 {
            font-size: clamp(2.4rem, 6vw, 4rem);
            font-family: 'Outfit', sans-serif;
            font-weight: 900;
            line-height: 1.1;
            margin-bottom: 1.25rem;
            letter-spacing: -0.03em;
        }

        .hero h1 span {
            background: linear-gradient(135deg, var(--neon-cyan), var(--neon-magenta));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero p {
            font-size: 1.05rem;
            color: var(--text-muted);
            margin-bottom: 2rem;
            max-width: 520px;
        }

        /* Simulated Console Feed Widget */
        .terminal-box {
            background: var(--bg-card);
            border: 1px solid var(--border-neon);
            border-radius: var(--radius-terminal);
            padding: 1.5rem;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.6);
            font-family: monospace;
            position: relative;
            overflow: hidden;
        }

        .terminal-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 25px;
            background: rgba(255, 255, 255, 0.03);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .term-dots {
            display: flex;
            gap: 0.35rem;
            margin-bottom: 1.25rem;
        }

        .term-dots span {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .term-dots span:nth-child(1) { background: #ef4444; }
        .term-dots span:nth-child(2) { background: #eab308; }
        .term-dots span:nth-child(3) { background: #22c55e; }

        .term-body {
            font-size: 0.76rem;
            line-height: 1.6;
            color: #10b981;
            height: 200px;
            overflow-y: hidden;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
        }

        .term-line {
            opacity: 0.95;
            animation: typeIn 0.3s steps(40, end);
            white-space: nowrap;
            overflow: hidden;
        }

        .term-line.cyan { color: var(--neon-cyan); }
        .term-line.magenta { color: var(--neon-magenta); }
        .term-line.muted { color: var(--text-muted); }

        @keyframes typeIn {
            from { width: 0; }
            to { width: 100%; }
        }

        /* SVG Yield Predictor Dial */
        .dial-box {
            background: var(--bg-card);
            border: 1px solid var(--border-neon);
            border-radius: var(--radius-terminal);
            padding: 2rem;
            margin-top: 2rem;
            text-align: center;
        }

        .dial-box h3 {
            font-size: 1.15rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .dial-container {
            position: relative;
            width: 160px;
            height: 160px;
            margin: 0 auto 1.5rem;
        }

        .dial-svg {
            transform: rotate(-90deg);
        }

        .dial-track {
            fill: none;
            stroke: rgba(255, 255, 255, 0.05);
            stroke-width: 10;
        }

        .dial-fill {
            fill: none;
            stroke: url(#cyanGrad);
            stroke-width: 10;
            stroke-dasharray: 440;
            stroke-dashoffset: 440;
            stroke-linecap: round;
            transition: stroke-dashoffset 0.3s;
        }

        .dial-value {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .dial-value .num {
            font-size: 1.8rem;
            font-weight: 800;
            color: #fff;
            font-family: 'Outfit', sans-serif;
        }

        .dial-value .lbl {
            font-size: 0.65rem;
            color: var(--text-muted);
            text-transform: uppercase;
        }

        .dial-controls {
            margin-top: 1rem;
        }

        .dial-slider {
            width: 100%;
            -webkit-appearance: none;
            height: 4px;
            background: rgba(255, 255, 255, 0.1);
            outline: none;
            border-radius: 4px;
        }

        .dial-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: var(--neon-cyan);
            cursor: pointer;
            box-shadow: 0 0 10px var(--neon-cyan);
        }

        @media (max-width: 900px) {
            .hero-grid { grid-template-columns: 1fr; gap: 3rem; }
        }

        /* Stats Grid */
        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            margin: 3rem 0;
        }

        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border-neon);
            border-radius: var(--radius-terminal);
            padding: 1.8rem;
            text-align: center;
            transition: all 0.3s;
        }

        .stat-card:hover {
            border-color: var(--border-neon-active);
            box-shadow: 0 0 20px rgba(6, 182, 212, 0.15);
            transform: translateY(-2px);
        }

        .stat-card h3 {
            font-size: 2rem;
            font-family: 'Outfit', sans-serif;
            font-weight: 900;
            color: #fff;
            margin-bottom: 0.25rem;
        }

        .stat-card p {
            font-size: 0.72rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .stats { grid-template-columns: 1fr 1fr; gap: 1rem; }
        }

        /* Features */
        .section-header {
            text-align: center;
            max-width: 650px;
            margin: 0 auto 4rem;
        }

        .section-header h2 {
            font-size: 2.2rem;
            font-family: 'Outfit', sans-serif;
            font-weight: 900;
            margin-bottom: 0.75rem;
        }

        .section-header h2 span {
            background: linear-gradient(135deg, var(--neon-cyan), var(--neon-magenta));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .section-header p {
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 1.5rem;
        }

        .cyber-card {
            background: var(--bg-card);
            border: 1px solid var(--border-neon);
            border-radius: var(--radius-terminal);
            padding: 2.2rem;
            position: relative;
            overflow: hidden;
            transition: all 0.3s;
        }

        .cyber-card::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(6, 182, 212, 0.04), transparent 60%);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .cyber-card:hover {
            border-color: var(--border-neon-active);
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
        }

        .cyber-card:hover::after {
            opacity: 1;
        }

        .cyber-card .icon {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            background: rgba(99, 102, 241, 0.08);
            border: 1px solid var(--border-neon);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--neon-cyan);
            font-size: 1.25rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s;
        }

        .cyber-card:hover .icon {
            color: var(--neon-magenta);
            border-color: rgba(217, 70, 239, 0.3);
            background: rgba(217, 70, 239, 0.05);
            transform: scale(1.05);
        }

        .cyber-card h4 {
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
            color: #fff;
        }

        .cyber-card p {
            font-size: 0.85rem;
            color: var(--text-muted);
            line-height: 1.65;
        }

        /* FAQ accordion */
        .faq-list {
            max-width: 800px;
            margin: 0 auto;
        }

        .faq-item {
            background: var(--bg-card);
            border: 1px solid var(--border-neon);
            border-radius: 10px;
            margin-bottom: 0.85rem;
            overflow: hidden;
        }

        .faq-header {
            padding: 1.2rem 1.5rem;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .faq-header i {
            transition: transform 0.3s;
            color: var(--neon-cyan);
        }

        .faq-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.35s cubic-bezier(0.16, 1, 0.3, 1), padding 0.35s;
            padding: 0 1.5rem;
            color: var(--text-muted);
            font-size: 0.85rem;
            line-height: 1.7;
        }

        .faq-item.open {
            border-color: var(--border-neon-active);
        }

        .faq-item.open .faq-header i {
            transform: rotate(180deg);
        }

        .faq-item.open .faq-content {
            padding-bottom: 1.2rem;
        }

        /* Footer */
        .footer {
            border-top: 1px solid var(--border-neon);
            padding: 4rem 0 2rem;
            margin-top: 5rem;
            background: #030107;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 4rem;
        }

        .footer-brand h5 {
            font-size: 1.3rem;
            margin-bottom: 1rem;
        }

        .footer-brand p {
            color: var(--text-muted);
            font-size: 0.88rem;
            max-width: 320px;
        }

        .footer h6 {
            font-size: 0.9rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 1.25rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .footer a {
            display: block;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.85rem;
            margin-bottom: 0.6rem;
            transition: color 0.2s;
        }

        .footer a:hover {
            color: var(--neon-cyan);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            margin-top: 3rem;
            border-top: 1px solid rgba(255, 255, 255, 0.03);
            color: var(--text-muted);
            font-size: 0.78rem;
        }

        @media (max-width: 768px) {
            .footer-grid { grid-template-columns: 1fr; gap: 2rem; }
        }
    </style>
</head>
<body>

<!-- Interactive canvas background -->
<canvas id="particleCanvas"></canvas>

<!-- Nav -->
<nav class="nav" id="nav">
    <div class="container nav-inner">
        <a href="/" class="nav-logo">
            <i class="fas fa-microchip" style="color:var(--neon-cyan)"></i> <span>AETHERIS</span>
        </a>
        <button class="hamburger" id="hamburger" onclick="toggleMobileMenu()">
            <i class="fas fa-bars" id="menuIcon"></i>
        </button>
        <ul class="nav-links" id="navLinks">
            <li><a href="/scratch/">Protocol</a></li>
            <li><a href="#about">Architecture</a></li>
            <li><a href="#benefits">Security</a></li>
            <li><a href="#faq">Node FAQ</a></li>
            <?php if (isLoggedIn()): ?>
                <li><a href="/scratch/dashboard.php" class="btn btn-neon">Dashboard</a></li>
            <?php else: ?>
                <li><a href="/login.php">Access Gate</a></li>
                <li><a href="/register.php" class="btn btn-neon">Initialize Node</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<!-- Mobile Overlay -->
<div class="mobile-overlay" id="mobileOverlay">
    <a href="/scratch/" class="mobile-link">Protocol</a>
    <a href="#about" class="mobile-link">Architecture</a>
    <a href="#benefits" class="mobile-link">Security</a>
    <a href="#faq" class="mobile-link">Node FAQ</a>
    <?php if (isLoggedIn()): ?>
        <a href="/scratch/dashboard.php" class="btn btn-neon">Dashboard</a>
    <?php else: ?>
        <a href="/login.php" class="mobile-link">Access Gate</a>
        <a href="/register.php" class="btn btn-neon">Initialize Node</a>
    <?php endif; ?>
</div>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="hero-grid">
            <div class="hero-content">
                <div class="hero-badge"><i class="fas fa-circle-nodes"></i> Decentralized Yield Network</div>
                <h1>Deploy Capital Into <span>Quantum Pools</span></h1>
                <p>Aetheris is an automated, decentralized capital allocation protocol. Lock in your assets, initiate validator nodes, and extract premium yields with real-time computational transparency.</p>
                <div class="hero-actions">
                    <a href="/register.php" class="btn btn-neon btn-lg">Initialize Validator</a>
                    <a href="#calculator" class="btn btn-ghost btn-lg">Simulation Panel</a>
                </div>
            </div>
            
            <!-- Terminal Log Feed -->
            <div class="terminal-box">
                <div class="term-dots">
                    <span></span><span></span><span></span>
                </div>
                <div class="term-body" id="consoleFeed">
                    <!-- Javascript populates -->
                    <div class="term-line muted">Aetheris v2.08 boot complete...</div>
                    <div class="term-line">Ready for socket payload.</div>
                </div>
            </div>
        </div>
        
        <!-- Dial Selector Simulator -->
        <div class="dial-box" id="calculator">
            <h3><i class="fas fa-sliders" style="color:var(--neon-cyan)"></i> Pool Yield Simulation</h3>
            
            <div class="hero-grid" style="gap:2rem; margin-top: 1rem;">
                <div class="dial-container">
                    <svg class="dial-svg" width="160" height="160">
                        <defs>
                            <linearGradient id="cyanGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#06b6d4" />
                                <stop offset="100%" stop-color="#d946ef" />
                            </linearGradient>
                        </defs>
                        <circle class="dial-track" cx="80" cy="80" r="70" />
                        <circle class="dial-fill" id="dialCircle" cx="80" cy="80" r="70" />
                    </svg>
                    <div class="dial-value">
                        <span class="num" id="dialText">2.0%</span>
                        <span class="lbl">Daily ROI</span>
                    </div>
                </div>
                
                <div style="text-align: left; display:flex; flex-direction:column; justify-content:center;">
                    <div class="calc-group">
                        <div class="calc-label">
                            <span>Principal Stake</span>
                            <span class="val" id="stakeVal">$5,000</span>
                        </div>
                        <input type="range" class="dial-slider" id="stakeSlider" min="200" max="100000" step="200" value="5000">
                    </div>
                    
                    <div class="calc-results" style="background:rgba(0,0,0,0.5); border:1px solid var(--border-neon); border-radius:10px; padding:1rem; display:grid; grid-template-columns:1fr 1fr; text-align:center;">
                        <div class="result-item">
                            <h5>Daily Payout</h5>
                            <div class="val cyan" id="stakeDaily" style="color:var(--neon-cyan); font-weight:800; font-size:1.2rem;">$100.00</div>
                        </div>
                        <div class="result-item">
                            <h5>Est. Weekly Return</h5>
                            <div class="val magenta" id="stakeWeekly" style="color:var(--neon-magenta); font-weight:800; font-size:1.2rem;">$700.00</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Grid -->
<div class="container">
    <div class="stats">
        <div class="stat-card">
            <h3>12.4K</h3>
            <p>Node Validators</p>
        </div>
        <div class="stat-card">
            <h3>$4.82M</h3>
            <p>Locked Liquidity</p>
        </div>
        <div class="stat-card">
            <h3>$2.15M</h3>
            <p>Extracted Yield</p>
        </div>
        <div class="stat-card">
            <h3>0.002s</h3>
            <p>Settlement Time</p>
        </div>
    </div>
</div>

<!-- Features -->
<section class="section" id="benefits">
    <div class="container">
        <div class="section-header">
            <h2>Cryptographic <span>Architecture</span></h2>
            <p>Explore the security and algorithmic features backing our liquidity protocols.</p>
        </div>
        
        <div class="card-grid">
            <div class="cyber-card">
                <div class="icon"><i class="fas fa-code-fork"></i></div>
                <h4>Liquidity Routing</h4>
                <p>Capital is automatically distributed to cross-chain liquidity vaults, isolating volatility and securing yield contracts.</p>
            </div>
            
            <div class="cyber-card">
                <div class="icon"><i class="fas fa-lock"></i></div>
                <h4>MPC Vaults</h4>
                <p>Multi-party computation ensures that withdrawal keys are fragmented across distributed server nodes, eliminating single-point vulnerabilities.</p>
            </div>
            
            <div class="cyber-card">
                <div class="icon"><i class="fas fa-gauge-high"></i></div>
                <h4>Zero Delay</h4>
                <p>Direct bridge smart contracts initiate transactions immediately. Withdraw validator node funds on-demand without lockups.</p>
            </div>
            
            <div class="cyber-card">
                <div class="icon"><i class="fas fa-network-wired"></i></div>
                <h4>Decentralized Ledger</h4>
                <p>Track every protocol action, payout calculation, and ledger adjustment instantly in the public audit panel.</p>
            </div>
        </div>
    </div>
</section>

<!-- Node FAQ -->
<section class="section" id="faq">
    <div class="container">
        <div class="section-header">
            <h2>Validator <span>Operations</span></h2>
            <p>Standard guidelines for initiating capital vaults and running node connections.</p>
        </div>
        
        <div class="faq-list">
            <div class="faq-item">
                <div class="faq-header">What is a Quantum Pool? <i class="fas fa-chevron-down"></i></div>
                <div class="faq-content">
                    A quantum pool represents an automated capital strategy aggregating cross-chain deposits. By pooling capital, transaction gas costs are minimized, maximizing yield distributions.
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-header">How are validator node daily rewards calculated? <i class="fas fa-chevron-down"></i></div>
                <div class="faq-content">
                    Validator node rewards scale with your locked capital weight and active contract terms (from 2.0% up to 3.2% daily), updated on the network block timestamp every hour.
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-header">Is there a minimum lockup time? <i class="fas fa-chevron-down"></i></div>
                <div class="faq-content">
                    Each contract defines its duration (e.g. 7 days). Once the contract matures, you can fully withdraw both your initial stake and cumulative yield rewards without any network fees.
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <h5><i class="fas fa-microchip" style="color:var(--neon-cyan)"></i> AETHERIS</h5>
                <p>Cryptographically secured liquidity routing and quantum validation pools. Managed by secure distributed ledgers.</p>
            </div>
            <div>
                <h6>Access Ports</h6>
                <a href="/login.php">Validator Portal</a>
                <a href="/register.php">Node Initialization</a>
                <a href="#calculator">Yield Simulation</a>
            </div>
            <div>
                <h6>Protocol Status</h6>
                <a href="#">Security Audits</a>
                <a href="#faq">Node Operations</a>
                <a href="mailto:support@primeaxisinv.com">Admin Desk</a>
            </div>
        </div>
        <div class="footer-bottom">
            &copy; <?php echo date('Y'); ?> Aetheris Protocol. All rights reserved.
        </div>
    </div>
</footer>

<script>
    // Navigation Scrolled Blur
    window.addEventListener('scroll', () => {
        document.getElementById('nav').classList.toggle('scrolled', window.scrollY > 40);
    });

    // Mobile nav toggle
    function toggleMobileMenu() {
        const overlay = document.getElementById('mobileOverlay');
        const icon = document.getElementById('menuIcon');
        const open = overlay.classList.toggle('open');
        icon.className = open ? 'fas fa-times' : 'fas fa-bars';
    }

    document.querySelectorAll('#mobileOverlay a').forEach(a => {
        a.addEventListener('click', () => {
            document.getElementById('mobileOverlay').classList.remove('open');
            document.getElementById('menuIcon').className = 'fas fa-bars';
        });
    });

    // FAQ Accordion
    document.querySelectorAll('.faq-header').forEach(header => {
        header.addEventListener('click', () => {
            const item = header.parentElement;
            const content = item.querySelector('.faq-content');
            const open = item.classList.toggle('open');
            
            if (open) {
                content.style.maxHeight = content.scrollHeight + "px";
            } else {
                content.style.maxHeight = "0";
            }
        });
    });

    // Canvas Interactive Background Animation
    const canvas = document.getElementById('particleCanvas');
    const ctx = canvas.getContext('2d');
    let width = canvas.width = window.innerWidth;
    let height = canvas.height = window.innerHeight;

    const particles = [];
    const maxParticles = 65;

    class Particle {
        constructor() {
            this.x = Math.random() * width;
            this.y = Math.random() * height;
            this.vx = (Math.random() - 0.5) * 0.7;
            this.vy = (Math.random() - 0.5) * 0.7;
            this.r = Math.random() * 2 + 1;
        }
        update() {
            this.x += this.vx;
            this.y += this.vy;
            if (this.x < 0 || this.x > width) this.vx *= -1;
            if (this.y < 0 || this.y > height) this.vy *= -1;
        }
        draw() {
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.r, 0, Math.PI * 2);
            ctx.fillStyle = 'rgba(99, 102, 241, 0.4)';
            ctx.fill();
        }
    }

    for (let i = 0; i < maxParticles; i++) {
        particles.push(new Particle());
    }

    function animateParticles() {
        ctx.clearRect(0, 0, width, height);
        particles.forEach(p => {
            p.update();
            p.draw();
        });
        
        // Draw connection lines
        ctx.strokeStyle = 'rgba(99, 102, 241, 0.06)';
        ctx.lineWidth = 0.8;
        for (let i = 0; i < maxParticles; i++) {
            for (let j = i + 1; j < maxParticles; j++) {
                const dist = Math.hypot(particles[i].x - particles[j].x, particles[i].y - particles[j].y);
                if (dist < 120) {
                    ctx.beginPath();
                    ctx.moveTo(particles[i].x, particles[i].y);
                    ctx.lineTo(particles[j].x, particles[j].y);
                    ctx.stroke();
                }
            }
        }
        requestAnimationFrame(animateParticles);
    }
    animateParticles();

    window.addEventListener('resize', () => {
        width = canvas.width = window.innerWidth;
        height = canvas.height = window.innerHeight;
    });

    // Simulated Console Logs Feed
    const consoleLogs = [
        "Initializing socket routing tunnel...",
        "Validating Node IP: 198.54.119.205",
        "[OK] Handshake confirmed with pool #37",
        "Aetheris core engine loading smart contracts...",
        "Querying global locked capital logs...",
        "[STATS] Locked capital updated: $4,824,510",
        "Listening for live deposits...",
        "[BLOCK] Block #928420 validated on mainnet",
        "[REWARD] Distributed $892.40 to active pool",
        "Re-computing optimal yield routing...",
        "Refreshing validator parameters...",
        "Connected: Aetheris socket stream established."
    ];

    const feedEl = document.getElementById('consoleFeed');
    setInterval(() => {
        const text = consoleLogs[Math.floor(Math.random() * consoleLogs.length)];
        const line = document.createElement('div');
        line.className = 'term-line';
        
        // Random style classes
        if (text.includes('[OK]')) line.classList.add('cyan');
        else if (text.includes('[REWARD]') || text.includes('[STATS]')) line.classList.add('magenta');
        else if (text.includes('Initializing') || text.includes('Querying')) line.classList.add('muted');
        
        line.textContent = `> ${text}`;
        feedEl.appendChild(line);
        
        if (feedEl.children.length > 9) {
            feedEl.removeChild(feedEl.firstChild);
        }
    }, 3500);

    // Yield Predictor Dial Gauge
    const stakeSlider = document.getElementById('stakeSlider');
    const stakeVal = document.getElementById('stakeVal');
    const dialCircle = document.getElementById('dialCircle');
    const dialText = document.getElementById('dialText');
    const stakeDaily = document.getElementById('stakeDaily');
    const stakeWeekly = document.getElementById('stakeWeekly');

    function formatCurrency(val) {
        return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(val);
    }

    function updateDial() {
        const val = parseFloat(stakeSlider.value);
        stakeVal.textContent = formatCurrency(val);

        // Determine rate and days based on value
        let rate = 2.0;
        let days = 7;
        if (val > 15000 && val <= 50000) {
            rate = 2.5;
            days = 30;
        } else if (val > 50000) {
            rate = 3.2;
            days = 90;
        }

        dialText.textContent = rate.toFixed(1) + '%';
        
        // SVG circle gauge dash offset math (440 = complete track)
        // maximum rate is 3.2%, scale progress bar [0 to 3.2%]
        const pct = rate / 3.2;
        const offset = 440 - (440 * pct);
        dialCircle.style.strokeDashoffset = offset;

        const dailyReturn = val * (rate / 100);
        const weeklyReturn = dailyReturn * 7;

        stakeDaily.textContent = formatCurrency(dailyReturn);
        stakeWeekly.textContent = formatCurrency(weeklyReturn);
    }

    stakeSlider.addEventListener('input', updateDial);
    updateDial();
</script>
</body>
</html>
