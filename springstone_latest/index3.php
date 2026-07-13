<?php
$includes_dir = is_file(__DIR__ . '/../includes/config.php')
    ? __DIR__ . '/../includes'
    : __DIR__ . '/../src/includes';
require_once $includes_dir . '/config.php';
require_once $includes_dir . '/session.php';

$brand = 'PrimeAxis';
$is_logged_in = function_exists('isLoggedIn') && isLoggedIn();
$base = '/springstone_latest';
$join_url = $is_logged_in ? '/dashboard/' : '/register.php';
$login_url = $is_logged_in ? '/dashboard/' : '/login.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($brand); ?> - Intelligent Wealth Infrastructure</title>
    <meta name="description" content="PrimeAxis is a modern investment platform for guided portfolios, crypto funding, daily ROI tracking, and transparent financial operations.">
    <link rel="icon" type="image/svg+xml" href="/assets/img/favicon.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            color-scheme: light;
            --ink: #172026;
            --body: #4e5d63;
            --soft: #eef7f7;
            --paper: #fbfdfc;
            --panel: #ffffff;
            --line: #d8e5e4;
            --teal: #147c78;
            --cyan: #27bfd0;
            --coral: #ec615b;
            --lime: #a5c94f;
            --plum: #5a446e;
            --graphite: #263238;
            --shadow: 0 20px 55px rgba(18, 42, 45, .12);
        }
        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            margin: 0;
            background: var(--paper);
            color: var(--ink);
            font-family: Inter, system-ui, sans-serif;
            line-height: 1.55;
        }
        body.locked { overflow: hidden; }
        a { color: inherit; text-decoration: none; }
        img { display: block; max-width: 100%; }
        button, input { font: inherit; }
        :focus-visible { outline: 3px solid rgba(39, 191, 208, .45); outline-offset: 3px; }
        .container { width: min(100% - 32px, 1200px); margin: 0 auto; }
        .wide { width: min(100% - 24px, 1440px); margin: 0 auto; }
        h1, h2, h3 {
            margin: 0;
            font-family: Manrope, Inter, sans-serif;
            line-height: 1.04;
            letter-spacing: 0;
        }
        h1 { font-size: clamp(44px, 7vw, 88px); font-weight: 800; max-width: 860px; }
        h2 { font-size: clamp(32px, 4.8vw, 58px); font-weight: 800; }
        h3 { font-size: 20px; font-weight: 800; }
        p { margin: 0; color: var(--body); }
        .kicker {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            color: var(--teal);
            font-size: 12px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: .1em;
        }
        .kicker::before {
            content: "";
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--coral);
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            min-height: 48px;
            border: 1px solid transparent;
            border-radius: 8px;
            padding: 12px 20px;
            font-weight: 800;
            cursor: pointer;
            transition: transform .2s ease, box-shadow .2s ease, background .2s ease, border-color .2s ease;
        }
        .btn:hover { transform: translateY(-2px); }
        .btn-primary { background: var(--teal); color: #fff; box-shadow: 0 16px 32px rgba(20, 124, 120, .22); }
        .btn-light { background: #fff; color: var(--ink); border-color: rgba(23, 32, 38, .12); }
        .btn-dark { background: var(--ink); color: #fff; }
        .icon-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: rgba(255, 255, 255, .72);
            color: var(--ink);
            cursor: pointer;
        }
        .nav {
            position: fixed;
            inset: 0 0 auto;
            z-index: 60;
            border-bottom: 1px solid rgba(255,255,255,.28);
            background: rgba(251, 253, 252, .78);
            backdrop-filter: blur(18px);
        }
        .nav-inner {
            height: 76px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }
        .brand {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-family: Manrope, sans-serif;
            font-size: 21px;
            font-weight: 900;
        }
        .brand-mark {
            width: 38px;
            height: 38px;
            display: inline-grid;
            place-items: center;
            border-radius: 9px;
            background: var(--ink);
            color: #fff;
        }
        .nav-links { display: none; align-items: center; gap: 4px; }
        .nav-links a {
            display: inline-flex;
            align-items: center;
            min-height: 40px;
            padding: 8px 12px;
            border-radius: 8px;
            color: var(--body);
            font-weight: 800;
            font-size: 14px;
        }
        .nav-links a:hover { color: var(--teal); background: rgba(20, 124, 120, .08); }
        .nav-actions { display: none; align-items: center; gap: 10px; }
        .menu { display: inline-flex; }
        .mobile-panel {
            position: fixed;
            inset: 0 0 0 auto;
            z-index: 90;
            width: min(88vw, 390px);
            background: var(--paper);
            box-shadow: -20px 0 70px rgba(0,0,0,.18);
            transform: translateX(110%);
            transition: transform .24s ease;
            display: flex;
            flex-direction: column;
        }
        .mobile-panel.open { transform: translateX(0); }
        .mobile-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px;
            border-bottom: 1px solid var(--line);
        }
        .mobile-links { padding: 18px; display: grid; gap: 8px; }
        .mobile-links a {
            padding: 12px;
            border-radius: 8px;
            font-weight: 800;
            color: var(--body);
        }
        .mobile-links a:hover { background: var(--soft); color: var(--teal); }
        .mobile-cta { margin-top: auto; padding: 18px; border-top: 1px solid var(--line); }
        .mobile-cta .btn { width: 100%; }
        @media (min-width: 980px) {
            .nav-links, .nav-actions { display: flex; }
            .menu { display: none; }
        }

        .hero {
            min-height: 100svh;
            position: relative;
            isolation: isolate;
            display: grid;
            align-items: end;
            overflow: hidden;
            background: #dceff2;
        }
        .hero picture, .hero-bg {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            z-index: -3;
        }
        .hero-bg { object-fit: cover; object-position: center; }
        .hero::before {
            content: "";
            position: absolute;
            inset: 0;
            z-index: -2;
            background:
                linear-gradient(90deg, rgba(251,253,252,.97) 0%, rgba(251,253,252,.78) 32%, rgba(251,253,252,.08) 70%),
                linear-gradient(0deg, rgba(251,253,252,.96) 0%, rgba(251,253,252,0) 38%);
        }
        .hero-content {
            padding: 132px 0 70px;
        }
        .hero-copy { max-width: 760px; }
        .hero-copy p {
            margin-top: 22px;
            max-width: 610px;
            font-size: clamp(17px, 2vw, 21px);
            color: #385056;
        }
        .hero-actions { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 34px; }
        .hero-metrics {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1px;
            width: min(100%, 780px);
            margin-top: 50px;
            overflow: hidden;
            border: 1px solid rgba(23, 32, 38, .12);
            border-radius: 8px;
            background: rgba(23, 32, 38, .1);
            box-shadow: var(--shadow);
        }
        .metric {
            min-height: 110px;
            padding: 20px;
            background: rgba(255,255,255,.78);
            backdrop-filter: blur(16px);
        }
        .metric strong {
            display: block;
            font-family: Manrope, sans-serif;
            font-size: clamp(25px, 4vw, 38px);
            line-height: 1;
        }
        .metric span { display: block; margin-top: 8px; color: var(--body); font-size: 13px; font-weight: 700; }
        @media (max-width: 720px) {
            .hero { min-height: 96svh; align-items: start; }
            .hero::before {
                background:
                    linear-gradient(0deg, rgba(251,253,252,.98) 0%, rgba(251,253,252,.82) 42%, rgba(251,253,252,.1) 76%),
                    linear-gradient(90deg, rgba(251,253,252,.4), rgba(251,253,252,.05));
            }
            .hero-bg { object-position: center top; }
            .hero-content { padding-top: 112px; padding-bottom: 38px; }
            .hero-copy { padding-top: 46svh; }
            .hero-metrics { grid-template-columns: 1fr; margin-top: 28px; }
            .metric { min-height: auto; padding: 16px; }
        }

        .strip {
            padding: 18px 0;
            background: var(--ink);
            color: #fff;
            overflow: hidden;
        }
        .strip-track {
            display: flex;
            width: max-content;
            gap: 42px;
            animation: move 34s linear infinite;
            font-weight: 900;
            color: rgba(255,255,255,.78);
            white-space: nowrap;
        }
        .strip-track span { display: inline-flex; align-items: center; gap: 10px; }
        .strip-track i { color: var(--lime); }
        @keyframes move { from { transform: translateX(0); } to { transform: translateX(-50%); } }

        .section { padding: 82px 0; }
        .section.alt { background: var(--soft); }
        .section.dark { background: var(--graphite); color: #fff; }
        .section.dark p { color: rgba(255,255,255,.72); }
        .section-head {
            display: grid;
            gap: 18px;
            align-items: end;
            margin-bottom: 42px;
        }
        .section-head p { max-width: 620px; font-size: 17px; }
        @media (min-width: 900px) {
            .section { padding: 112px 0; }
            .section-head { grid-template-columns: 1fr .72fr; }
        }

        .signal-grid {
            display: grid;
            gap: 16px;
        }
        .signal-card {
            min-height: 255px;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: var(--panel);
            padding: 26px;
            box-shadow: 0 8px 30px rgba(18, 42, 45, .06);
        }
        .signal-card.featured {
            background: var(--ink);
            color: #fff;
            border-color: var(--ink);
        }
        .signal-card.featured p { color: rgba(255,255,255,.72); }
        .signal-icon {
            width: 46px;
            height: 46px;
            display: grid;
            place-items: center;
            border-radius: 8px;
            background: rgba(39, 191, 208, .13);
            color: var(--teal);
            margin-bottom: 22px;
        }
        .signal-card.featured .signal-icon { background: rgba(255,255,255,.12); color: var(--cyan); }
        .signal-card h3 { margin-bottom: 10px; }
        .signal-card p { font-size: 15px; }
        @media (min-width: 820px) {
            .signal-grid { grid-template-columns: 1.15fr .85fr .85fr; }
        }

        .image-split {
            display: grid;
            gap: 36px;
            align-items: center;
        }
        .media-frame {
            overflow: hidden;
            border-radius: 8px;
            box-shadow: var(--shadow);
            background: #dfe8e8;
        }
        .media-frame img {
            width: 100%;
            height: 100%;
            min-height: 360px;
            object-fit: cover;
        }
        .stack { display: grid; gap: 22px; }
        .check-list { display: grid; gap: 14px; margin-top: 14px; }
        .check {
            display: grid;
            grid-template-columns: 34px 1fr;
            gap: 14px;
            align-items: start;
        }
        .check i {
            width: 34px;
            height: 34px;
            display: grid;
            place-items: center;
            border-radius: 8px;
            background: rgba(165, 201, 79, .18);
            color: #5f7f15;
        }
        .check strong { display: block; margin-bottom: 3px; }
        @media (min-width: 900px) {
            .image-split { grid-template-columns: 1.05fr .95fr; gap: 64px; }
            .image-split.flip { grid-template-columns: .92fr 1.08fr; }
            .image-split.flip .media-frame { order: 2; }
        }

        .plans {
            display: grid;
            gap: 16px;
        }
        .plan {
            border: 1px solid rgba(255,255,255,.18);
            border-radius: 8px;
            padding: 28px;
            background: rgba(255,255,255,.06);
        }
        .plan.hot { background: #ffffff; color: var(--ink); }
        .plan.hot p, .plan.hot li { color: var(--body); }
        .plan-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 20px;
        }
        .tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 999px;
            padding: 6px 10px;
            background: rgba(236, 97, 91, .14);
            color: var(--coral);
            font-size: 12px;
            font-weight: 900;
        }
        .price { font-family: Manrope, sans-serif; font-size: 42px; font-weight: 900; line-height: 1; margin: 16px 0 8px; }
        .plan ul { list-style: none; padding: 0; margin: 24px 0 0; display: grid; gap: 10px; color: rgba(255,255,255,.72); }
        .plan li { display: flex; gap: 10px; align-items: start; }
        .plan li i { color: var(--lime); margin-top: 4px; }
        @media (min-width: 900px) {
            .plans { grid-template-columns: repeat(3, 1fr); align-items: stretch; }
        }

        .proof {
            display: grid;
            gap: 18px;
        }
        .proof-item {
            display: grid;
            gap: 14px;
            align-content: start;
            min-height: 230px;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: var(--panel);
            padding: 24px;
        }
        .proof-item strong { font-size: 42px; line-height: 1; font-family: Manrope, sans-serif; }
        .proof-item:nth-child(2) strong { color: var(--coral); }
        .proof-item:nth-child(3) strong { color: var(--plum); }
        @media (min-width: 780px) {
            .proof { grid-template-columns: repeat(3, 1fr); }
        }

        .cta {
            padding: 88px 0;
            background: #cdeeee;
        }
        .cta-inner {
            display: grid;
            gap: 28px;
            align-items: center;
        }
        .cta p { max-width: 650px; font-size: 18px; }
        .cta-actions { display: flex; flex-wrap: wrap; gap: 12px; }
        @media (min-width: 880px) {
            .cta-inner { grid-template-columns: 1fr auto; }
        }

        .footer {
            background: var(--ink);
            color: #fff;
            padding: 46px 0 28px;
        }
        .footer p, .footer a { color: rgba(255,255,255,.68); }
        .footer-grid {
            display: grid;
            gap: 26px;
            padding-bottom: 34px;
            border-bottom: 1px solid rgba(255,255,255,.12);
        }
        .footer-support {
            display: grid;
            gap: 12px;
            padding: 22px;
            border: 1px solid rgba(255,255,255,.12);
            border-radius: 8px;
            background: rgba(255,255,255,.04);
        }
        .footer-support h3 {
            font-size: 18px;
        }
        .support-links {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .support-links a {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            min-height: 40px;
            padding: 9px 12px;
            border-radius: 8px;
            border: 1px solid rgba(255,255,255,.14);
            background: rgba(255,255,255,.06);
            color: #fff;
            font-size: 14px;
            font-weight: 800;
        }
        .support-links a:hover { background: rgba(255,255,255,.12); }
        .help-note {
            font-size: 14px;
            color: rgba(255,255,255,.68);
        }
        .footer-links { display: flex; flex-wrap: wrap; gap: 14px 22px; justify-content: start; }
        .footer-bottom {
            padding-top: 24px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 16px;
            color: rgba(255,255,255,.54);
            font-size: 14px;
        }
        @media (min-width: 780px) {
            .footer-grid { grid-template-columns: 1fr 1fr auto; align-items: start; }
            .footer-links { justify-content: end; }
        }

        .reveal {
            opacity: 0;
            transform: translateY(18px);
            transition: opacity .6s ease, transform .6s ease;
        }
        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation: none !important; transition: none !important; scroll-behavior: auto !important; }
            .reveal { opacity: 1; transform: none; }
        }
    </style>
</head>
<body>
<nav class="nav" aria-label="Primary navigation">
    <div class="container nav-inner">
        <a class="brand" href="<?php echo $base; ?>/index3.php" aria-label="PrimeAxis home">
            <span class="brand-mark"><?php include __DIR__ . '/logo-mark.svg.php'; ?></span>
            <span><?php echo htmlspecialchars($brand); ?></span>
        </a>
        <div class="nav-links">
            <a href="#platform">Platform</a>
            <a href="#advisory">Advisory</a>
            <a href="#plans">Plans</a>
            <a href="#security">Security</a>
        </div>
        <div class="nav-actions">
            <a class="btn btn-light" href="<?php echo $login_url; ?>"><?php echo $is_logged_in ? 'Dashboard' : 'Sign In'; ?></a>
            <a class="btn btn-primary" href="<?php echo $join_url; ?>">Start Investing <i class="fa-solid fa-arrow-right"></i></a>
        </div>
        <button class="icon-btn menu" id="openMenu" type="button" aria-label="Open menu"><i class="fa-solid fa-bars"></i></button>
    </div>
</nav>

<aside class="mobile-panel" id="mobilePanel" aria-label="Mobile navigation">
    <div class="mobile-head">
        <a class="brand" href="<?php echo $base; ?>/index3.php"><span class="brand-mark"><?php include __DIR__ . '/logo-mark.svg.php'; ?></span><span><?php echo htmlspecialchars($brand); ?></span></a>
        <button class="icon-btn" id="closeMenu" type="button" aria-label="Close menu"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="mobile-links">
        <a href="#platform">Platform</a>
        <a href="#advisory">Advisory</a>
        <a href="#plans">Plans</a>
        <a href="#security">Security</a>
        <a href="<?php echo $login_url; ?>"><?php echo $is_logged_in ? 'Dashboard' : 'Sign In'; ?></a>
    </div>
    <div class="mobile-cta">
        <a class="btn btn-primary" href="<?php echo $join_url; ?>">Start Investing <i class="fa-solid fa-arrow-right"></i></a>
    </div>
</aside>

<main>
    <section class="hero">
        <picture>
            <source media="(max-width: 720px)" srcset="<?php echo $base; ?>/images/index3-hero-mobile.webp">
            <img class="hero-bg" src="<?php echo $base; ?>/images/index3-hero-desktop.webp" alt="PrimeAxis analysts reviewing market dashboards above a coastal city">
        </picture>
        <div class="container hero-content">
            <div class="hero-copy reveal">
                <span class="kicker">Intelligent Wealth Infrastructure</span>
                <h1>Build wealth with a clearer command of every move.</h1>
                <p>PrimeAxis combines guided investment plans, crypto-ready funding, daily ROI visibility, and human review into one fast dashboard for modern investors.</p>
                <div class="hero-actions">
                    <a class="btn btn-primary" href="<?php echo $join_url; ?>">Open Account <i class="fa-solid fa-arrow-right"></i></a>
                    <a class="btn btn-light" href="#platform">Explore Platform</a>
                </div>
            </div>
            <div class="hero-metrics reveal" aria-label="Platform highlights">
                <div class="metric"><strong>24/7</strong><span>Portfolio visibility</span></div>
                <div class="metric"><strong>3</strong><span>Crypto wallet rails</span></div>
                <div class="metric"><strong>Daily</strong><span>ROI event tracking</span></div>
            </div>
        </div>
    </section>

    <div class="strip" aria-hidden="true">
        <div class="strip-track">
            <span><i class="fa-solid fa-shield-halved"></i> Secure funding</span>
            <span><i class="fa-solid fa-chart-line"></i> Guided plans</span>
            <span><i class="fa-solid fa-wallet"></i> BTC, USDT, ETH</span>
            <span><i class="fa-solid fa-clock"></i> Daily ROI processing</span>
            <span><i class="fa-solid fa-user-check"></i> Admin-reviewed requests</span>
            <span><i class="fa-solid fa-shield-halved"></i> Secure funding</span>
            <span><i class="fa-solid fa-chart-line"></i> Guided plans</span>
            <span><i class="fa-solid fa-wallet"></i> BTC, USDT, ETH</span>
            <span><i class="fa-solid fa-clock"></i> Daily ROI processing</span>
            <span><i class="fa-solid fa-user-check"></i> Admin-reviewed requests</span>
        </div>
    </div>

    <section class="section" id="platform">
        <div class="container">
            <div class="section-head reveal">
                <div>
                    <span class="kicker">Platform Signals</span>
                    <h2>Everything important is visible before you act.</h2>
                </div>
                <p>Investment activity, balance movement, deposits, withdrawals, and earnings are kept in one connected record so decisions are easier to understand.</p>
            </div>
            <div class="signal-grid">
                <article class="signal-card featured reveal">
                    <span class="signal-icon"><i class="fa-solid fa-layer-group"></i></span>
                    <h3>Plan-led investing</h3>
                    <p>Choose active plans by minimum, maximum, duration, and daily ROI. The dashboard keeps active investments and expected movement in view.</p>
                </article>
                <article class="signal-card reveal">
                    <span class="signal-icon"><i class="fa-solid fa-arrow-right-arrow-left"></i></span>
                    <h3>Balance clarity</h3>
                    <p>Each financial event records amount, old balance, new balance, source, and description for cleaner account history.</p>
                </article>
                <article class="signal-card reveal">
                    <span class="signal-icon"><i class="fa-solid fa-bell"></i></span>
                    <h3>Lifecycle alerts</h3>
                    <p>Welcome, reset, deposit, withdrawal, ROI, and investment-completion emails keep investors informed as actions complete.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="section alt" id="advisory">
        <div class="container image-split">
            <div class="media-frame reveal">
                <img src="<?php echo $base; ?>/images/index3-advisory.webp" alt="Advisors reviewing portfolio analytics with a client">
            </div>
            <div class="stack reveal">
                <span class="kicker">Guided Decisions</span>
                <h2>Automation where it helps, human review where it matters.</h2>
                <p>PrimeAxis is designed around practical investor workflows: review plans, fund securely, track earnings, and submit withdrawal requests with a complete trail.</p>
                <div class="check-list">
                    <div class="check"><i class="fa-solid fa-check"></i><div><strong>Admin-reviewed deposits</strong><p>Deposit requests stay pending until reviewed and approved.</p></div></div>
                    <div class="check"><i class="fa-solid fa-check"></i><div><strong>Transparent withdrawals</strong><p>Withdrawals capture the selected coin, wallet address, amount, and status.</p></div></div>
                    <div class="check"><i class="fa-solid fa-check"></i><div><strong>Referral-aware growth</strong><p>Referral codes and relationships are tracked directly in the user account model.</p></div></div>
                </div>
            </div>
        </div>
    </section>

    <section class="section dark" id="plans">
        <div class="container">
            <div class="section-head reveal">
                <div>
                    <span class="kicker">Investment Modes</span>
                    <h2>Pick a plan style that matches your pace.</h2>
                </div>
                <p>Plan terms are managed by the admin dashboard, so the business can adjust active offerings without rebuilding the page.</p>
            </div>
            <div class="plans">
                <article class="plan reveal">
                    <div class="plan-top"><h3>Starter</h3><span class="tag"><i class="fa-solid fa-seedling"></i> Entry</span></div>
                    <p>For investors testing the platform with smaller deposits and short-term visibility.</p>
                    <div class="price">Low</div>
                    <p>Accessible minimums and guided onboarding.</p>
                    <ul>
                        <li><i class="fa-solid fa-check"></i><span>Active plan selection</span></li>
                        <li><i class="fa-solid fa-check"></i><span>Daily earnings history</span></li>
                        <li><i class="fa-solid fa-check"></i><span>Email confirmations</span></li>
                    </ul>
                </article>
                <article class="plan hot reveal">
                    <div class="plan-top"><h3>Core</h3><span class="tag"><i class="fa-solid fa-bolt"></i> Popular</span></div>
                    <p>For consistent investors who want a clearer balance between duration and return.</p>
                    <div class="price">Balanced</div>
                    <p>Designed for repeat investing and portfolio monitoring.</p>
                    <ul>
                        <li><i class="fa-solid fa-check"></i><span>Plan comparison dashboard</span></li>
                        <li><i class="fa-solid fa-check"></i><span>Transaction audit trail</span></li>
                        <li><i class="fa-solid fa-check"></i><span>Crypto wallet settings</span></li>
                    </ul>
                </article>
                <article class="plan reveal">
                    <div class="plan-top"><h3>Prime</h3><span class="tag"><i class="fa-solid fa-gem"></i> Advanced</span></div>
                    <p>For larger allocations that need stronger reporting and careful operation review.</p>
                    <div class="price">Focused</div>
                    <p>Best for users who track withdrawals, earnings, and active plan exposure closely.</p>
                    <ul>
                        <li><i class="fa-solid fa-check"></i><span>Admin-managed terms</span></li>
                        <li><i class="fa-solid fa-check"></i><span>Withdrawal status updates</span></li>
                        <li><i class="fa-solid fa-check"></i><span>Completion notifications</span></li>
                    </ul>
                </article>
            </div>
        </div>
    </section>

    <section class="section" id="security">
        <div class="container image-split flip">
            <div class="media-frame reveal">
                <img src="<?php echo $base; ?>/images/index3-security.webp" alt="Secure investment operations with hardware key and abstract portfolio interface">
            </div>
            <div class="stack reveal">
                <span class="kicker">Security Operations</span>
                <h2>Built around fewer blind spots.</h2>
                <p>Security is handled through hashed credentials, CSRF checks on sensitive actions, session validation, prepared database statements, and admin audit logging.</p>
                <div class="proof">
                    <article class="proof-item"><strong>PDO</strong><p>Prepared statements are the default database access pattern.</p></article>
                    <article class="proof-item"><strong>CSRF</strong><p>Auth and form actions use session-backed token verification.</p></article>
                    <article class="proof-item"><strong>Logs</strong><p>Admin actions are captured for operational accountability.</p></article>
                </div>
            </div>
        </div>
    </section>

    <section class="cta">
        <div class="container cta-inner reveal">
            <div>
                <span class="kicker">Ready When You Are</span>
                <h2>Move from watching markets to managing your position.</h2>
                <p>Create an account, set your wallet details, choose an active plan, and keep your activity visible from the dashboard.</p>
            </div>
            <div class="cta-actions">
                <a class="btn btn-dark" href="<?php echo $join_url; ?>">Create Account</a>
                <a class="btn btn-light" href="<?php echo $login_url; ?>"><?php echo $is_logged_in ? 'Open Dashboard' : 'Sign In'; ?></a>
            </div>
        </div>
    </section>
</main>

<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <div>
                <a class="brand" href="<?php echo $base; ?>/index3.php"><span class="brand-mark"><?php include __DIR__ . '/logo-mark.svg.php'; ?></span><span><?php echo htmlspecialchars($brand); ?></span></a>
                <p style="margin-top:14px;max-width:420px">Modern investment access with clear records, crypto rails, and guided portfolio movement.</p>
            </div>
            <div class="footer-support">
                <h3>Need help?</h3>
                <p class="help-note">Use the quickest channel for your question. The team can help with deposits, withdrawals, plan selection, and account access.</p>
                <div class="support-links">
                    <a href="mailto:support@primeaxisinv.com"><i class="fa-solid fa-envelope"></i> Email</a>
                    <a href="https://t.me/primeaxisinv" target="_blank" rel="noopener"><i class="fa-brands fa-telegram"></i> Telegram</a>
                    <a href="https://wa.me/15555550123" target="_blank" rel="noopener"><i class="fa-brands fa-whatsapp"></i> WhatsApp</a>
                </div>
            </div>
            <div class="footer-links">
                <a href="#platform">Platform</a>
                <a href="#advisory">Advisory</a>
                <a href="#plans">Plans</a>
                <a href="#security">Security</a>
                <a href="/login.php">Sign In</a>
            </div>
        </div>
        <div class="footer-bottom">
            <span>&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($brand); ?>. All rights reserved.</span>
            <span>PrimeAxis Investment Platform</span>
        </div>
    </div>
</footer>

<script>
    const panel = document.getElementById('mobilePanel');
    const openMenu = document.getElementById('openMenu');
    const closeMenu = document.getElementById('closeMenu');
    function hideMenu() {
        panel.classList.remove('open');
        document.body.classList.remove('locked');
    }
    openMenu.addEventListener('click', () => {
        panel.classList.add('open');
        document.body.classList.add('locked');
    });
    closeMenu.addEventListener('click', hideMenu);
    panel.querySelectorAll('a').forEach((link) => link.addEventListener('click', hideMenu));

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: .14 });
    document.querySelectorAll('.reveal').forEach((el) => observer.observe(el));
</script>
</body>
</html>
