<?php 
require_once __DIR__ . '/includes/config.php'; 
require_once __DIR__ . '/includes/session.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo SITE_NAME; ?> — Elevate Your Capital</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="description" content="Secure, high-yield digital asset growth. Experience next-generation returns with glassmorphic transparency and premium institutional security.">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Icons & Notyf -->
    <link rel="icon" type="image/svg+xml" href="/assets/img/favicon.svg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/assets/js/app.js?v=2"></script>

    <style>
        :root {
            --bg-obsidian: #030712;
            --bg-card: rgba(11, 15, 25, 0.65);
            --gold-primary: #dfba73;
            --gold-light: rgba(229, 192, 123, 0.08);
            --gold-gradient: linear-gradient(135deg, #e5c07b 0%, #c49a45 50%, #a27b2d 100%);
            --text-light: #f3f4f6;
            --text-muted: #9ca3af;
            --border-glass: rgba(229, 192, 123, 0.12);
            --radius-premium: 20px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-obsidian);
            color: var(--text-light);
            line-height: 1.6;
            overflow-x: hidden;
            position: relative;
        }

        /* Ambient Glow Effects */
        .ambient-glow-1 {
            position: absolute;
            top: -10%;
            right: -20%;
            width: 800px;
            height: 800px;
            background: radial-gradient(circle, rgba(196, 154, 69, 0.07) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        .ambient-glow-2 {
            position: absolute;
            top: 40%;
            left: -20%;
            width: 700px;
            height: 700px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.05) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
            position: relative;
            z-index: 1;
        }

        /* Navigation */
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
            background: rgba(3, 7, 18, 0.8);
            backdrop-filter: blur(24px);
            border-bottom: 1px solid var(--border-glass);
            padding: 0.85rem 0;
        }

        .nav-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .nav-logo {
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .nav-logo img {
            height: 42px;
            transition: transform 0.3s;
        }

        .nav-logo:hover img {
            transform: scale(1.02);
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
            transition: color 0.3s, transform 0.3s;
            position: relative;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            width: 100%;
            transform: scaleX(0);
            height: 1px;
            bottom: -4px;
            left: 0;
            background: var(--gold-gradient);
            transform-origin: bottom right;
            transition: transform 0.25s ease-out;
        }

        .nav-links a:hover {
            color: #fff;
        }

        .nav-links a:hover::after {
            transform: scaleX(1);
            transform-origin: bottom left;
        }

        /* Ticker Bar */
        .ticker-bar {
            width: 100%;
            background: rgba(11, 15, 25, 0.9);
            border-bottom: 1px solid var(--border-glass);
            overflow: hidden;
            white-space: nowrap;
            padding: 0.6rem 0;
            margin-top: 80px;
            position: relative;
            z-index: 10;
        }

        .ticker-wrapper {
            display: inline-block;
            animation: ticker 25s linear infinite;
        }

        .ticker-item {
            display: inline-flex;
            align-items: center;
            margin-right: 3rem;
            font-size: 0.78rem;
            color: var(--text-muted);
            font-weight: 600;
        }

        .ticker-item i {
            margin-right: 0.4rem;
        }

        .ticker-item .price {
            color: #fff;
            margin-left: 0.4rem;
            font-family: monospace;
        }

        .ticker-item .change-up {
            color: #10b981;
            margin-left: 0.4rem;
        }

        @keyframes ticker {
            0% { transform: translate3d(0, 0, 0); }
            100% { transform: translate3d(-50%, 0, 0); }
        }

        /* Buttons */
        .btn {
            display: inline-block;
            padding: 0.65rem 1.6rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.88rem;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            cursor: pointer;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .btn-gold {
            background: var(--gold-gradient);
            color: #030712;
            font-weight: 700;
            box-shadow: 0 4px 15px rgba(229, 192, 123, 0.2);
        }

        .btn-gold::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.3) 0%, transparent 60%);
            opacity: 0;
            transition: opacity 0.3s;
            pointer-events: none;
        }

        .btn-gold:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(229, 192, 123, 0.4);
        }

        .btn-gold:hover::before {
            opacity: 1;
        }

        .btn-outline {
            border: 1px solid rgba(229, 192, 123, 0.3);
            color: #fff;
            background: transparent;
        }

        .btn-outline:hover {
            border-color: var(--gold-primary);
            color: var(--gold-primary);
            background: rgba(229, 192, 123, 0.04);
            transform: translateY(-2px);
        }

        .btn-lg {
            padding: 0.85rem 2.2rem;
            font-size: 0.95rem;
        }

        /* Mobile Hamburger & Overlay */
        .hamburger {
            display: none;
            background: none;
            border: none;
            color: #fff;
            font-size: 1.55rem;
            cursor: pointer;
            z-index: 10001;
        }

        .mobile-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(3, 7, 18, 0.98);
            backdrop-filter: blur(24px);
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 1.75rem;
            z-index: 9999;
            padding: 2rem;
        }

        .mobile-overlay.open {
            display: flex;
        }

        .mobile-link {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 1.3rem;
            font-weight: 500;
            transition: color 0.2s;
        }

        .mobile-link:hover {
            color: #fff;
        }

        @media (max-width: 768px) {
            .nav-links { display: none; }
            .hamburger { display: block; }
        }

        /* Hero */
        .hero {
            min-height: 80vh;
            display: flex;
            align-items: center;
            padding: 4rem 0;
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
            padding: 0.35rem 1rem;
            border-radius: 50px;
            background: var(--gold-light);
            border: 1px solid rgba(229, 192, 123, 0.2);
            font-size: 0.78rem;
            color: var(--gold-primary);
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .hero-badge i {
            font-size: 0.65rem;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }

        .hero h1 {
            font-size: clamp(2.4rem, 5.5vw, 3.8rem);
            font-family: 'Outfit', sans-serif;
            font-weight: 900;
            line-height: 1.15;
            margin-bottom: 1.25rem;
            letter-spacing: -0.02em;
        }

        .hero h1 span {
            background: var(--gold-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero p {
            font-size: 1.08rem;
            color: var(--text-muted);
            margin-bottom: 2.2rem;
            max-width: 520px;
        }

        .hero-actions {
            display: flex;
            gap: 1.2rem;
            flex-wrap: wrap;
        }

        /* ROI Calculator Widget */
        .calc-box {
            background: var(--bg-card);
            backdrop-filter: blur(24px);
            border: 1px solid var(--border-glass);
            border-radius: var(--radius-premium);
            padding: 2.2rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
            position: relative;
        }

        .calc-box h3 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .calc-box h3 i {
            color: var(--gold-primary);
        }

        .calc-group {
            margin-bottom: 1.5rem;
        }

        .calc-label {
            display: flex;
            justify-content: space-between;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-muted);
            margin-bottom: 0.6rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .calc-label span.val {
            color: var(--gold-primary);
            font-weight: 700;
            font-family: monospace;
            font-size: 0.95rem;
        }

        .range-slider {
            width: 100%;
            -webkit-appearance: none;
            height: 5px;
            border-radius: 5px;
            background: rgba(255, 255, 255, 0.1);
            outline: none;
            margin: 0.5rem 0;
        }

        .range-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: var(--gold-primary);
            cursor: pointer;
            transition: transform 0.1s;
            box-shadow: 0 0 10px rgba(229, 192, 123, 0.5);
        }

        .range-slider::-webkit-slider-thumb:hover {
            transform: scale(1.2);
        }

        .plan-selector {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.6rem;
        }

        .plan-btn {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 10px;
            padding: 0.6rem;
            color: var(--text-muted);
            font-size: 0.78rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-align: center;
        }

        .plan-btn.active, .plan-btn:hover {
            background: var(--gold-light);
            border-color: var(--gold-primary);
            color: #fff;
        }

        .calc-results {
            background: rgba(0, 0, 0, 0.3);
            border-radius: 12px;
            padding: 1.25rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-top: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.03);
        }

        .result-item h5 {
            font-size: 0.72rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 0.25rem;
        }

        .result-item div.val {
            font-size: 1.35rem;
            font-weight: 800;
            color: #fff;
            font-family: 'Outfit', sans-serif;
        }

        .result-item div.val.gold {
            color: var(--gold-primary);
        }

        @media (max-width: 900px) {
            .hero-grid { grid-template-columns: 1fr; gap: 3rem; }
            .hero { padding: 2rem 0 4rem; }
        }

        /* Stats */
        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            margin: 2rem 0 5rem;
        }

        .stat-card {
            background: var(--bg-card);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-glass);
            border-radius: var(--radius-premium);
            padding: 1.8rem;
            text-align: center;
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            border-color: rgba(229, 192, 123, 0.25);
        }

        .stat-card h3 {
            font-size: 2.1rem;
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            background: var(--gold-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.25rem;
        }

        .stat-card p {
            font-size: 0.75rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .stats { grid-template-columns: 1fr 1fr; gap: 1rem; }
        }

        /* Sections */
        .section {
            padding: 6rem 0;
            position: relative;
        }

        .section-header {
            text-align: center;
            max-width: 650px;
            margin: 0 auto 4rem;
        }

        .section-header .tag {
            display: inline-block;
            padding: 0.25rem 0.95rem;
            border-radius: 50px;
            background: var(--gold-light);
            border: 1px solid rgba(229, 192, 123, 0.2);
            font-size: 0.72rem;
            color: var(--gold-primary);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 1.1rem;
        }

        .section-header h2 {
            font-size: 2.2rem;
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            line-height: 1.25;
            margin-bottom: 0.75rem;
        }

        .section-header h2 span {
            background: var(--gold-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .section-header p {
            color: var(--text-muted);
            font-size: 1rem;
        }

        /* Features/Benefits */
        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 1.5rem;
        }

        .glass-card {
            background: var(--bg-card);
            backdrop-filter: blur(24px);
            border: 1px solid var(--border-glass);
            border-radius: var(--radius-premium);
            padding: 2.2rem;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
        }

        .glass-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 50% 0%, rgba(229, 192, 123, 0.05) 0%, transparent 60%);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .glass-card:hover {
            transform: translateY(-5px);
            border-color: rgba(229, 192, 123, 0.3);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
        }

        .glass-card:hover::before {
            opacity: 1;
        }

        .glass-card .icon {
            width: 54px;
            height: 54px;
            border-radius: 14px;
            background: var(--gold-light);
            border: 1px solid rgba(229, 192, 123, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gold-primary);
            font-size: 1.35rem;
            margin-bottom: 1.5rem;
            transition: transform 0.3s;
        }

        .glass-card:hover .icon {
            transform: scale(1.06) rotate(3deg);
        }

        .glass-card h4 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.15rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: #fff;
        }

        .glass-card p {
            font-size: 0.88rem;
            color: var(--text-muted);
            line-height: 1.65;
        }

        /* How it works Steps */
        .steps {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
        }

        .step {
            background: var(--bg-card);
            border: 1px solid var(--border-glass);
            border-radius: var(--radius-premium);
            padding: 2.2rem;
            text-align: center;
            position: relative;
            transition: border-color 0.3s;
        }

        .step:hover {
            border-color: rgba(229, 192, 123, 0.25);
        }

        .step-num {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: var(--gold-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            color: #030712;
            margin: 0 auto 1.25rem;
            box-shadow: 0 5px 15px rgba(229, 192, 123, 0.3);
        }

        .step h4 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.15rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: #fff;
        }

        .step p {
            font-size: 0.88rem;
            color: var(--text-muted);
        }

        @media (max-width: 768px) {
            .steps { grid-template-columns: 1fr; gap: 1.5rem; }
        }

        /* FAQ Accordion */
        .faq-list {
            max-width: 800px;
            margin: 0 auto;
        }

        .faq-item {
            background: var(--bg-card);
            border: 1px solid var(--border-glass);
            border-radius: 14px;
            margin-bottom: 0.85rem;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .faq-header {
            padding: 1.25rem 1.75rem;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 600;
            font-family: 'Outfit', sans-serif;
            font-size: 0.98rem;
            transition: background 0.3s;
        }

        .faq-header:hover {
            background: rgba(255, 255, 255, 0.02);
        }

        .faq-header i {
            transition: transform 0.3s;
            color: var(--gold-primary);
            font-size: 0.85rem;
        }

        .faq-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.35s cubic-bezier(0.16, 1, 0.3, 1), padding 0.35s;
            padding: 0 1.75rem;
            color: var(--text-muted);
            font-size: 0.88rem;
            line-height: 1.7;
        }

        .faq-item.open {
            border-color: rgba(229, 192, 123, 0.25);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .faq-item.open .faq-header i {
            transform: rotate(180deg);
        }

        .faq-item.open .faq-content {
            padding-bottom: 1.25rem;
        }

        /* CTA */
        .cta-wrapper {
            background: linear-gradient(135deg, rgba(229, 192, 123, 0.08), rgba(99, 102, 241, 0.03));
            border: 1px solid var(--border-glass);
            border-radius: 28px;
            padding: 4.5rem 2rem;
            text-align: center;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
        }

        .cta-wrapper h2 {
            font-size: 2.3rem;
            font-family: 'Outfit', sans-serif;
            font-weight: 900;
            margin-bottom: 0.5rem;
        }

        .cta-wrapper p {
            color: var(--text-muted);
            max-width: 500px;
            margin: 0 auto 2.2rem;
            font-size: 1.05rem;
        }

        /* Footer */
        .footer {
            border-top: 1px solid var(--border-glass);
            padding: 4rem 0 2rem;
            margin-top: 5rem;
            background: rgba(3, 7, 18, 0.95);
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 4rem;
        }

        .footer-brand h5 {
            margin-bottom: 1rem;
        }

        .footer-brand p {
            color: var(--text-muted);
            font-size: 0.88rem;
            max-width: 300px;
            line-height: 1.7;
        }

        .footer h6 {
            font-family: 'Outfit', sans-serif;
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
            color: var(--gold-primary);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            margin-top: 3rem;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            color: var(--text-muted);
            font-size: 0.78rem;
        }

        @media (max-width: 768px) {
            .footer-grid { grid-template-columns: 1fr; gap: 2rem; }
        }
    </style>
</head>
<body>

<div class="ambient-glow-1"></div>
<div class="ambient-glow-2"></div>

<!-- Nav -->
<nav class="nav" id="nav">
    <div class="container nav-inner">
        <a href="/" class="nav-logo">
            <img src="/assets/img/logo-v2.svg" alt="<?php echo SITE_NAME; ?>">
        </a>
        <button class="hamburger" id="hamburger" onclick="toggleMobileMenu()">
            <i class="fas fa-bars" id="menuIcon"></i>
        </button>
        <ul class="nav-links" id="navLinks">
            <li><a href="/">Home</a></li>
            <li><a href="/#about">About</a></li>
            <li><a href="/#features">Benefits</a></li>
            <li><a href="/#how">How It Works</a></li>
            <li><a href="/#faq">FAQ</a></li>
            <?php if (isLoggedIn()): ?>
                <li><a href="/dashboard1.php" class="btn btn-gold">Dashboard</a></li>
            <?php else: ?>
                <li><a href="/login.php">Login</a></li>
                <li><a href="/register.php" class="btn btn-gold">Get Started</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<!-- Live Price Ticker -->
<div class="ticker-bar">
    <div class="ticker-wrapper" id="tickerWrapper">
        <!-- Will be populated by JS for real-time look -->
        <div class="ticker-item"><i class="fab fa-bitcoin" style="color:#f7931a"></i> BTC<span class="price" id="tickBTC">$68,245.50</span><span class="change-up">+1.85%</span></div>
        <div class="ticker-item"><i class="fab fa-ethereum" style="color:#627eea"></i> ETH<span class="price" id="tickETH">$3,542.20</span><span class="change-up">+2.41%</span></div>
        <div class="ticker-item"><i class="fas fa-coins" style="color:#26a17b"></i> USDT<span class="price">$1.00</span><span style="color:#9ca3af;margin-left:0.4rem">0.00%</span></div>
        <div class="ticker-item"><i class="fas fa-coins" style="color:#14f195"></i> SOL<span class="price" id="tickSOL">$148.95</span><span class="change-up">+4.12%</span></div>
        <!-- Double items to loop seamless -->
        <div class="ticker-item"><i class="fab fa-bitcoin" style="color:#f7931a"></i> BTC<span class="price" id="tickBTC2">$68,245.50</span><span class="change-up">+1.85%</span></div>
        <div class="ticker-item"><i class="fab fa-ethereum" style="color:#627eea"></i> ETH<span class="price" id="tickETH2">$3,542.20</span><span class="change-up">+2.41%</span></div>
        <div class="ticker-item"><i class="fas fa-coins" style="color:#26a17b"></i> USDT<span class="price">$1.00</span><span style="color:#9ca3af;margin-left:0.4rem">0.00%</span></div>
        <div class="ticker-item"><i class="fas fa-coins" style="color:#14f195"></i> SOL<span class="price" id="tickSOL2">$148.95</span><span class="change-up">+4.12%</span></div>
    </div>
</div>

<!-- Mobile menu overlay -->
<div class="mobile-overlay" id="mobileOverlay">
    <a href="/" class="mobile-link">Home</a>
    <a href="/#about" class="mobile-link">About</a>
    <a href="/#features" class="mobile-link">Benefits</a>
    <a href="/#how" class="mobile-link">How It Works</a>
    <a href="/#faq" class="mobile-link">FAQ</a>
    <?php if (isLoggedIn()): ?>
        <a href="/dashboard1.php" class="btn btn-gold">Dashboard</a>
    <?php else: ?>
        <a href="/login.php" class="mobile-link">Login</a>
        <a href="/register.php" class="btn btn-gold">Get Started</a>
    <?php endif; ?>
</div>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="hero-grid">
            <div class="hero-content">
                <div class="hero-badge"><i class="fas fa-shield-halved"></i> Institutional Grade Wealth Management</div>
                <h1>Invest Your Capital With <span>Higher ROI</span></h1>
                <p>Secure, premium yield generation powered by crypto-backed investment solutions. Designed for discerning investors who demand transparency, safety, and consistent daily returns.</p>
                <div class="hero-actions">
                    <a href="/register.php" class="btn btn-gold btn-lg">Start Generating Yield</a>
                    <a href="#calculator" class="btn btn-outline btn-lg">Calculate ROI</a>
                </div>
            </div>
            
            <!-- ROI Calculator Widget -->
            <div class="calc-box" id="calculator">
                <h3><i class="fas fa-calculator"></i> Premium Yield Estimator</h3>
                
                <div class="calc-group">
                    <div class="calc-label">
                        <span>Investment Principal</span>
                        <span class="val" id="calcPrincipalDisplay">$5,000</span>
                    </div>
                    <input type="range" class="range-slider" id="calcSlider" min="100" max="50000" step="100" value="5000">
                </div>

                <div class="calc-group">
                    <div class="calc-label">
                        <span>Select Plan Duration</span>
                    </div>
                    <div class="plan-selector">
                        <button class="plan-btn active" data-rate="2.0" data-days="7">7 Days (2.0%)</button>
                        <button class="plan-btn" data-rate="2.5" data-days="30">30 Days (2.5%)</button>
                        <button class="plan-btn" data-rate="3.2" data-days="90">90 Days (3.2%)</button>
                    </div>
                </div>

                <div class="calc-results">
                    <div class="result-item">
                        <h5>Daily Payout</h5>
                        <div class="val gold" id="calcDailyReturn">$100.00</div>
                    </div>
                    <div class="result-item">
                        <h5>Total Profit</h5>
                        <div class="val" id="calcTotalProfit">$700.00</div>
                    </div>
                </div>
                
                <div style="margin-top: 1.5rem;">
                    <a href="/register.php" class="btn btn-gold" style="width: 100%; text-align: center; border-radius: 10px;">Lock In Plan Now</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats counters -->
<div class="container">
    <div class="stats" id="statsGrid">
        <div class="stat-card" data-val="15420" data-suffix="+">
            <h3 id="stat1">0</h3>
            <p>Active Investors</p>
        </div>
        <div class="stat-card" data-val="3250000" data-prefix="$" data-decimals="0">
            <h3 id="stat2">$0</h3>
            <p>Total Capital Invested</p>
        </div>
        <div class="stat-card" data-val="1180000" data-prefix="$" data-decimals="0">
            <h3 id="stat3">$0</h3>
            <p>Profits Paid Out</p>
        </div>
        <div class="stat-card" data-val="99.9" data-suffix="%">
            <h3 id="stat4">0</h3>
            <p>Platform Uptime</p>
        </div>
    </div>
</div>

<!-- Features -->
<section class="section" id="features">
    <div class="container">
        <div class="section-header">
            <span class="tag">Exclusive Benefits</span>
            <h2>Why Institutional Investors <span>Choose Us</span></h2>
            <p>We combine security, automation, and speed to provide a premium asset growth experience.</p>
        </div>
        
        <div class="card-grid">
            <div class="glass-card">
                <div class="icon"><i class="fas fa-chart-line"></i></div>
                <h4>Automated Daily ROI</h4>
                <p>Your returns are compiled and credited to your ledger every single day. Full compound options available.</p>
            </div>
            
            <div class="glass-card">
                <div class="icon"><i class="fas fa-shield-halved"></i></div>
                <h4>Fortress Infrastructure</h4>
                <p>256-bit AES database encryption, isolated API layers, and majority assets maintained in secure cold storage.</p>
            </div>
            
            <div class="glass-card">
                <div class="icon"><i class="fas fa-bolt"></i></div>
                <h4>Accelerated Withdrawals</h4>
                <p>Seamless execution. Profits and principal are withdrawable instantly via BTC, USDT, or ETH networks.</p>
            </div>
            
            <div class="glass-card">
                <div class="icon"><i class="fas fa-users-viewfinder"></i></div>
                <h4>Tiered Referrals</h4>
                <p>Earn premium commissions of up to 10% by introducing verified partners to the PrimeAxis portal.</p>
            </div>
        </div>
    </div>
</section>

<!-- Steps -->
<section class="section" id="how">
    <div class="container">
        <div class="section-header">
            <span class="tag">Get Started</span>
            <h2>Three Steps to <span>Capital Growth</span></h2>
            <p>Open and fund your portfolio securely in less than two minutes.</p>
        </div>
        
        <div class="steps">
            <div class="step">
                <div class="step-num">1</div>
                <h4>Create Account</h4>
                <p>Establish your profile with secure credentials. Access active platforms instantly without delays.</p>
            </div>
            <div class="step">
                <div class="step-num">2</div>
                <h4>Secure Allocation</h4>
                <p>Fund your ledger using your choice of crypto assets, then allocate into one of our high-performing plans.</p>
            </div>
            <div class="step">
                <div class="step-num">3</div>
                <h4>Daily Returns</h4>
                <p>Monitor daily profits on your dashboard. Withdraw earnings or reinvest to accelerate returns.</p>
            </div>
        </div>
    </div>
</section>

<!-- Accordion FAQ -->
<section class="section" id="faq">
    <div class="container">
        <div class="section-header">
            <span class="tag">FAQ</span>
            <h2>Frequently <span>Asked Questions</span></h2>
            <p>Find answers to critical inquiries about security, plans, and withdrawals.</p>
        </div>
        
        <div class="faq-list">
            <div class="faq-item">
                <div class="faq-header">How do I begin investing? <i class="fas fa-chevron-down"></i></div>
                <div class="faq-content">
                    Simply establish your free account, navigate to the settings pane to update your target payout wallet addresses, execute a deposit, and select the plan that fits your financial timeline.
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-header">How are returns compiled and credited? <i class="fas fa-chevron-down"></i></div>
                <div class="faq-content">
                    Returns are calculated as a fixed daily ROI based on the specific plan chosen. Payouts are updated on your dashboard ledger in real-time, matching your exact deposit timestamp.
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-header">What is the standard processing speed for withdrawals? <i class="fas fa-chevron-down"></i></div>
                <div class="faq-content">
                    All withdrawal requests undergo automated security scans and are executed immediately onto the BTC, USDT, or ETH networks, typically clearing in 5 to 30 minutes.
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-header">How does PrimeAxis secure investor capital? <i class="fas fa-chevron-down"></i></div>
                <div class="faq-content">
                    Our platform relies on a zero-trust architecture. 95% of active funds are stored in off-line ledger vaults, backed by multi-signature protocols and real-time security scanning.
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="section">
    <div class="container">
        <div class="cta-wrapper">
            <h2>Ready to Optimize Your Assets?</h2>
            <p>Join thousands of sophisticated investors who choose PrimeAxis for consistent yield generation.</p>
            <a href="/register.php" class="btn btn-gold btn-lg">Establish Your Portfolio</a>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <h5><img src="/assets/img/logo-v2.svg" alt="<?php echo SITE_NAME; ?>" style="height:34px"></h5>
                <p>Secure, premium wealth generation powered by automated crypto-backed asset portfolios. Trusted globally.</p>
            </div>
            <div>
                <h6>Quick Actions</h6>
                <a href="/login.php">Portal Sign In</a>
                <a href="/register.php">Establish Account</a>
                <a href="#calculator">ROI Calculator</a>
                <a href="#features">Platform Benefits</a>
            </div>
            <div>
                <h6>Institutional Support</h6>
                <a href="mailto:support@primeaxisinv.com">support@primeaxisinv.com</a>
                <a href="#faq">Frequently Asked Questions</a>
                <a href="#">Security Audit Log</a>
            </div>
        </div>
        <div class="footer-bottom">
            &copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.
        </div>
    </div>
</footer>

<script>
    // Navigation scrolled background blur effect
    window.addEventListener('scroll', () => {
        document.getElementById('nav').classList.toggle('scrolled', window.scrollY > 40);
    });

    // Mobile navigation overlay handler
    function toggleMobileMenu() {
        const overlay = document.getElementById('mobileOverlay');
        const icon = document.getElementById('menuIcon');
        const open = overlay.classList.toggle('open');
        icon.className = open ? 'fas fa-times' : 'fas fa-bars';
        document.body.style.overflow = open ? 'hidden' : '';
    }

    document.querySelectorAll('#mobileOverlay a').forEach(a => {
        a.addEventListener('click', () => {
            document.getElementById('mobileOverlay').classList.remove('open');
            document.getElementById('menuIcon').className = 'fas fa-bars';
            document.body.style.overflow = '';
        });
    });

    // FAQ Accordion Transition
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

    // Interactive ROI Calculator Logic
    const slider = document.getElementById('calcSlider');
    const principalDisplay = document.getElementById('calcPrincipalDisplay');
    const dailyReturnDisplay = document.getElementById('calcDailyReturn');
    const totalProfitDisplay = document.getElementById('calcTotalProfit');
    const planButtons = document.querySelectorAll('.plan-btn');

    let currentRate = 2.0;
    let currentDays = 7;

    function formatNum(val) {
        return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(val);
    }

    function recalculate() {
        const principal = parseFloat(slider.value);
        principalDisplay.textContent = formatNum(principal);
        
        const dailyProfit = principal * (currentRate / 100);
        const totalProfit = dailyProfit * currentDays;

        dailyReturnDisplay.textContent = formatNum(dailyProfit);
        totalProfitDisplay.textContent = formatNum(totalProfit);
    }

    slider.addEventListener('input', recalculate);

    planButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            planButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            
            currentRate = parseFloat(btn.dataset.rate);
            currentDays = parseInt(btn.dataset.days);
            recalculate();
        });
    });

    recalculate();

    // Stats Viewport CountUp Animation
    const statsSection = document.getElementById('statsGrid');
    let animated = false;

    function countUp(el, target, duration = 1500, prefix = '', suffix = '', decimals = 0) {
        let startTime = null;

        function animate(timestamp) {
            if (!startTime) startTime = timestamp;
            const progress = Math.min((timestamp - startTime) / duration, 1);
            const val = progress * target;
            
            el.textContent = prefix + val.toFixed(decimals).replace(/\B(?=(\d{3})+(?!\d))/g, ",") + suffix;

            if (progress < 1) {
                requestAnimationFrame(animate);
            } else {
                el.textContent = prefix + target.toFixed(decimals).replace(/\B(?=(\d{3})+(?!\d))/g, ",") + suffix;
            }
        }
        requestAnimationFrame(animate);
    }

    const observer = new IntersectionObserver((entries) => {
        if (entries[0].isIntersecting && !animated) {
            animated = true;
            document.querySelectorAll('.stat-card').forEach((card, idx) => {
                const h3 = card.querySelector('h3');
                const target = parseFloat(card.dataset.val);
                const prefix = card.dataset.prefix || '';
                const suffix = card.dataset.suffix || '';
                const decimals = card.dataset.decimals !== undefined ? parseInt(card.dataset.decimals) : (target % 1 === 0 ? 0 : 1);
                
                setTimeout(() => {
                    countUp(h3, target, 1500, prefix, suffix, decimals);
                }, idx * 100);
            });
        }
    }, { threshold: 0.1 });

    observer.observe(statsSection);

    // Live Ticker Price Simulation
    setInterval(() => {
        const btc = 68000 + Math.random() * 800;
        const eth = 3500 + Math.random() * 60;
        const sol = 145 + Math.random() * 6;
        
        const btcVal = formatNum(btc);
        const ethVal = formatNum(eth);
        const solVal = formatNum(sol);

        document.getElementById('tickBTC').textContent = btcVal;
        document.getElementById('tickBTC2').textContent = btcVal;
        document.getElementById('tickETH').textContent = ethVal;
        document.getElementById('tickETH2').textContent = ethVal;
        document.getElementById('tickSOL').textContent = solVal;
        document.getElementById('tickSOL2').textContent = solVal;
    }, 4000);
</script>
</body>
</html>
