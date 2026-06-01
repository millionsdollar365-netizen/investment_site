<?php require_once __DIR__ . '/includes/config.php'; require_once __DIR__ . '/includes/session.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo SITE_NAME; ?> — Invest Your Money With Higher Returns</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="description" content="Secure investment platform. Earn daily returns on crypto-backed plans. Trusted by thousands worldwide.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js@1.12.0/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js@1.12.0"></script>
    <script src="/assets/js/app.js"></script>
    <style>
        :root {
            --bg: #0f172a;
            --bg2: #1e293b;
            --gold: #fbbf24;
            --gold-hover: #f59e0b;
            --gold-light: rgba(251,191,36,.18);
            --text: #f1f5f9;
            --muted: #94a3b8;
            --card-bg: rgba(30,41,59,.6);
            --glass: rgba(30,41,59,.5);
            --border: rgba(148,163,184,.1);
            --radius: 16px;
            --navy: #1e293b;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            line-height: 1.65;
            overflow-x: hidden;
        }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 1.5rem; }
        .text-gold { color: var(--gold); }
        .text-muted { color: var(--muted); }
        .text-center { text-align: center; }

        /* Nav */
        .nav { position: fixed; top: 0; left: 0; right: 0; z-index: 100; padding: 1rem 0; transition: all .3s; }
        .nav.scrolled { background: rgba(15,23,42,.94); backdrop-filter: blur(20px); border-bottom: 1px solid var(--border); }
        .nav-inner { display: flex; align-items: center; justify-content: space-between; }
        .nav-logo { font-size: 1.3rem; font-weight: 800; color: #fff; text-decoration: none; display: flex; align-items: center; gap: .5rem; }
        .nav-logo span { color: var(--gold); }
        .nav-links { display: flex; align-items: center; gap: 2rem; list-style: none; }
        .nav-links a { color: var(--muted); text-decoration: none; font-size: .88rem; font-weight: 500; transition: color .2s; }
        .nav-links a:hover { color: #fff; }
        .btn { display: inline-block; padding: .6rem 1.5rem; border-radius: 50px; font-weight: 600; font-size: .88rem; text-decoration: none; transition: all .3s; cursor: pointer; border: none; }
        .btn-gold { background: linear-gradient(135deg, #fbbf24, #f59e0b); color: #0f172a; font-weight: 700; }
        .btn-gold:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(245,158,11,.3); }
        .btn-outline { border: 2px solid rgba(255,255,255,.15); color: #fff; background: transparent; }
        .btn-outline:hover { border-color: var(--gold); color: var(--gold); background: rgba(245,158,11,.05); }
        .btn-lg { padding: .8rem 2rem; font-size: .95rem; }
        .hamburger { display: none; background: none; border: none; color: #fff; font-size: 1.5rem; cursor: pointer; z-index: 101; }
        @media (max-width: 768px) {
            .nav-links { display: none; position: fixed; inset: 0; background: var(--bg2); flex-direction: column; justify-content: center; gap: 2rem; z-index: 99; }
            .nav-links.open { display: flex; }
            .hamburger { display: block; }
        }

        /* Hero */
        .hero { min-height: 100vh; display: flex; align-items: center; padding: 8rem 0 4rem; position: relative; overflow: hidden; }
        .hero::before {
            content: ''; position: absolute; top: -30%; right: -20%; width: 800px; height: 800px;
            background: radial-gradient(circle, rgba(245,158,11,.1) 0%, transparent 60%);
        }
        .hero::after {
            content: ''; position: absolute; bottom: -20%; left: -15%; width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(245,158,11,.06) 0%, transparent 60%);
        }
        .hero .container { display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: center; position: relative; z-index: 1; }
        .hero-content { max-width: 580px; }
        .hero-badge { display: inline-flex; align-items: center; gap: .5rem; padding: .3rem 1rem; border-radius: 50px; background: var(--gold-light); border: 1px solid rgba(245,158,11,.25); font-size: .78rem; color: var(--gold); font-weight: 600; margin-bottom: 1.5rem; }
        .hero-badge i { font-size: .7rem; animation: pulse 2s infinite; }
        @keyframes pulse { 0%,100% { opacity: 1; } 50% { opacity: .4; } }
        .hero h1 { font-size: clamp(2.2rem, 5vw, 3.4rem); font-weight: 900; line-height: 1.15; margin-bottom: 1.25rem; font-family: 'Playfair Display', serif; }
        .hero h1 span { color: var(--gold); }
        .hero p { font-size: 1.05rem; color: var(--muted); max-width: 480px; margin-bottom: 2rem; }
        .hero-actions { display: flex; gap: 1rem; flex-wrap: wrap; }
        .hero-visual { position: relative; display: flex; align-items: center; justify-content: center; }
        .hero-card { background: var(--glass); border: 1px solid var(--border); border-radius: var(--radius); padding: 1.5rem 2rem; backdrop-filter: blur(20px); }
        .hero-card .big-num { font-size: 2.5rem; font-weight: 900; color: var(--gold); }
        .hero-card .label { font-size: .8rem; color: var(--muted); margin-top: .25rem; }
        .floating-coin { position: absolute; width: 48px; height: 48px; border-radius: 50%; background: var(--gold-light); border: 2px solid rgba(245,158,11,.3); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; color: var(--gold); animation: float 4s ease-in-out infinite; }
        .floating-coin:nth-child(1) { top: -10px; right: 40px; animation-delay: 0s; }
        .floating-coin:nth-child(2) { bottom: 20px; right: -10px; animation-delay: 1.5s; width: 36px; height: 36px; font-size: .85rem; }
        .floating-coin:nth-child(3) { top: 50%; left: -15px; animation-delay: 3s; width: 40px; height: 40px; }
        @keyframes float { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-12px); } }
        @media (max-width: 768px) {
            .hero .container { grid-template-columns: 1fr; }
            .hero-visual { display: none; }
        }

        /* Stats */
        .stats { display: grid; grid-template-columns: repeat(4,1fr); gap: 1.25rem; margin: -3rem auto 5rem; position: relative; z-index: 2; }
        .stat-card { background: rgba(30,41,59,.8); backdrop-filter: blur(20px); border: 1px solid rgba(251,191,36,.12); border-radius: var(--radius); padding: 1.5rem; text-align: center; }
        .stat-card h3 { font-size: 1.7rem; font-weight: 800; color: var(--gold); }
        .stat-card p { font-size: .75rem; color: var(--muted); margin-top: .25rem; text-transform: uppercase; letter-spacing: .06em; }
        @media (max-width: 768px) { .stats { grid-template-columns: 1fr 1fr; } }

        /* Section */
        .section { padding: 5rem 0; }
        .section-header { text-align: center; max-width: 600px; margin: 0 auto 3.5rem; }
        .section-header .tag { display: inline-block; padding: .25rem .85rem; border-radius: 50px; background: var(--gold-light); border: 1px solid rgba(245,158,11,.2); font-size: .75rem; color: var(--gold); font-weight: 600; text-transform: uppercase; letter-spacing: .08em; margin-bottom: 1rem; }
        .section-header h2 { font-size: 2rem; font-weight: 800; margin-bottom: .6rem; font-family: 'Playfair Display', serif; }
        .section-header p { color: var(--muted); font-size: .95rem; }

        /* Partner Strip */
        .partner-strip { display: flex; justify-content: center; align-items: center; gap: 3rem; flex-wrap: wrap; padding: 2rem 0; opacity: .5; }
        .partner-strip span { font-size: 1.1rem; font-weight: 700; color: var(--muted); letter-spacing: .05em; }

        /* Cards */
        .card-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.5rem; }
        .feature-card { background: var(--card-bg); border: 1px solid var(--border); border-radius: var(--radius); padding: 2rem; transition: all .3s; position: relative; overflow: hidden; }
        .feature-card:hover { transform: translateY(-4px); border-color: rgba(245,158,11,.25); box-shadow: 0 12px 40px rgba(0,0,0,.3); }
        .feature-card .icon { width: 52px; height: 52px; border-radius: 14px; background: var(--gold-light); display: flex; align-items: center; justify-content: center; color: var(--gold); font-size: 1.3rem; margin-bottom: 1.25rem; }
        .feature-card h4 { font-size: 1.05rem; font-weight: 700; margin-bottom: .4rem; }
        .feature-card p { font-size: .85rem; color: var(--muted); line-height: 1.7; }
        .feature-card .stars { color: var(--gold); font-size: .7rem; margin-bottom: .75rem; letter-spacing: 2px; }

        /* About */
        .about-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: center; }
        .about-image { position: relative; }
        .about-image .main-img { width: 100%; border-radius: var(--radius); object-fit: cover; opacity: .9; }
        .about-badge { position: absolute; background: rgba(30,41,59,.95); border: 1px solid rgba(245,158,11,.25); border-radius: 12px; padding: 1rem 1.5rem; backdrop-filter: blur(10px); }
        .about-badge .num { font-size: 1.5rem; font-weight: 800; color: var(--gold); }
        .about-badge .lbl { font-size: .7rem; color: var(--muted); text-transform: uppercase; letter-spacing: .05em; }
        .about-badge.tl { top: 15px; left: -15px; }
        .about-badge.br { bottom: 20px; right: -15px; }
        @media (max-width: 768px) { .about-grid { grid-template-columns: 1fr; } }

        /* Steps */
        .steps { display: grid; grid-template-columns: repeat(3,1fr); gap: 2rem; }
        .step { text-align: center; padding: 2rem 1rem; position: relative; }
        .step-num { width: 52px; height: 52px; border-radius: 50%; background: linear-gradient(135deg, #fbbf24, #f59e0b); display: flex; align-items: center; justify-content: center; font-size: 1.2rem; font-weight: 800; color: #0f172a; margin: 0 auto 1rem; }
        .step h4 { font-weight: 700; margin-bottom: .4rem; }
        .step p { font-size: .85rem; color: var(--muted); }
        @media (max-width: 768px) { .steps { grid-template-columns: 1fr; } }

        /* Testimonials */
        .testimonials { display: grid; grid-template-columns: repeat(3,1fr); gap: 1.5rem; }
        .testimonial { background: var(--glass); border: 1px solid var(--border); border-radius: var(--radius); padding: 1.75rem; }
        .testimonial .stars { color: var(--gold); margin-bottom: .75rem; font-size: .8rem; letter-spacing: 2px; }
        .testimonial p { font-size: .88rem; color: var(--muted); font-style: italic; line-height: 1.7; margin-bottom: 1.25rem; }
        .testimonial .author { display: flex; align-items: center; gap: .75rem; }
        .testimonial .avatar { width: 44px; height: 44px; border-radius: 50%; background: linear-gradient(135deg, #fbbf24, #f59e0b); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: .85rem; color: #0f172a; }
        @media (max-width: 768px) { .testimonials { grid-template-columns: 1fr; } }

        /* FAQ */
        .faq-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: start; }
        .faq-item { background: var(--glass); border: 1px solid var(--border); border-radius: var(--radius); margin-bottom: .75rem; overflow: hidden; }
        .faq-q { padding: 1rem 1.25rem; cursor: pointer; display: flex; justify-content: space-between; align-items: center; font-weight: 600; font-size: .92rem; transition: all .2s; }
        .faq-q:hover { background: rgba(255,255,255,.03); }
        .faq-q i { transition: transform .3s; color: var(--gold); font-size: .8rem; }
        .faq-item.open .faq-q i { transform: rotate(180deg); }
        .faq-a { padding: 0 1.25rem; max-height: 0; overflow: hidden; transition: all .3s; color: var(--muted); font-size: .85rem; line-height: 1.7; }
        .faq-item.open .faq-a { padding: 0 1.25rem 1rem; max-height: 200px; }
        @media (max-width: 768px) { .faq-grid { grid-template-columns: 1fr; } }

        /* CTA */
        .cta-card { background: linear-gradient(135deg, rgba(245,158,11,.1), rgba(245,158,11,.04)); border: 1px solid rgba(245,158,11,.15); border-radius: 24px; padding: 4rem 2rem; text-align: center; }
        .cta-card h2 { font-size: 2rem; font-weight: 800; font-family: 'Playfair Display', serif; margin-bottom: .5rem; }
        .cta-card p { color: var(--muted); margin-bottom: 2rem; font-size: 1rem; }

        /* Footer */
        .footer { border-top: 1px solid var(--border); padding: 3rem 0; margin-top: 2rem; }
        .footer-grid { display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 3rem; }
        .footer h5 { font-weight: 700; margin-bottom: 1rem; font-size: .95rem; }
        .footer a { display: block; color: var(--muted); text-decoration: none; font-size: .85rem; margin-bottom: .5rem; transition: color .2s; }
        .footer a:hover { color: var(--gold); }
        .footer-bottom { text-align: center; padding-top: 2rem; margin-top: 2rem; border-top: 1px solid var(--border); color: var(--muted); font-size: .78rem; }
        @media (max-width: 768px) { .footer-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

<!-- Nav -->
<nav class="nav" id="nav">
    <div class="container nav-inner">
        <a href="/" class="nav-logo"><img src="/assets/img/logo.svg" alt="<?php echo SITE_NAME; ?>" style="height:38px"></a>
        <button class="hamburger" id="hamburger" onclick="document.getElementById('navLinks').classList.toggle('open')"><i class="fas fa-bars"></i></button>
        <ul class="nav-links" id="navLinks">
            <li><a href="#" onclick="document.getElementById('navLinks').classList.remove('open')">Home</a></li>
            <li><a href="#about" onclick="document.getElementById('navLinks').classList.remove('open')">About</a></li>
            <li><a href="#features" onclick="document.getElementById('navLinks').classList.remove('open')">Benefits</a></li>
            <li><a href="#how" onclick="document.getElementById('navLinks').classList.remove('open')">How It Works</a></li>
            <li><a href="#faq" onclick="document.getElementById('navLinks').classList.remove('open')">FAQ</a></li>
            <?php if (isLoggedIn()): ?>
                <li><a href="/dashboard/" class="btn btn-gold">Dashboard</a></li>
            <?php else: ?>
                <li><a href="/login.php">Login</a></li>
                <li><a href="/register.php" class="btn btn-gold">Get Started</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<!-- Hero -->
<section class="hero">
    <div class="container">
        <div class="hero-content">
            <div class="hero-badge"><i class="fas fa-circle"></i> Trusted by Thousands Worldwide</div>
            <h1>Invest Your Money With <span>Higher Returns</span></h1>
            <p>Join a growing community of investors earning daily returns through secure, crypto-backed investment plans. Start small, grow consistently.</p>
            <div class="hero-actions">
                <a href="/register.php" class="btn btn-gold btn-lg">Get Started</a>
                <a href="#features" class="btn btn-outline btn-lg">Learn More</a>
            </div>
        </div>
        <div class="hero-visual">
            <div class="floating-coin"><i class="fas fa-coins"></i></div>
            <div class="floating-coin"><i class="fab fa-bitcoin"></i></div>
            <div class="floating-coin"><i class="fas fa-gem"></i></div>
            <div class="hero-card"><div class="big-num">4.8<span style="font-size:.6em;color:var(--muted)">/5</span></div><div class="label">TrustPilot Rating</div></div>
        </div>
    </div>
</section>

<div class="container">
    <div class="stats">
        <div class="stat-card"><h3>15K+</h3><p>Active Investors</p></div>
        <div class="stat-card"><h3>$3.2M</h3><p>Total Invested</p></div>
        <div class="stat-card"><h3>$1.1M</h3><p>Profits Paid Out</p></div>
        <div class="stat-card"><h3>99.9%</h3><p>Uptime</p></div>
    </div>

    <div class="partner-strip"><span>TRUSTED BY</span> <span style="color:#fff">Binance</span> <span style="color:#fff">Coinbase</span> <span style="color:#fff">Blockchain</span> <span style="color:#fff">MetaMask</span></div>
</div>

<!-- About -->
<section class="section" id="about">
    <div class="container">
        <div class="about-grid">
            <div class="about-image">
                <img src="https://images.unsplash.com/photo-1633158829585-23ba8f7c8caf?w=600&h=500&fit=crop" alt="About" class="main-img">
                <div class="about-badge tl"><div class="num">5+</div><div class="lbl">Years of Excellence</div></div>
                <div class="about-badge br"><div class="num">15K+</div><div class="lbl">Satisfied Investors</div></div>
            </div>
            <div>
                <div class="section-header" style="text-align:left;margin-bottom:1.5rem"><span class="tag">About Us</span><h2>Your Trusted Investment Partner</h2></div>
                <p class="text-muted" style="margin-bottom:1rem"><?php echo SITE_NAME; ?> is a leading investment platform dedicated to helping individuals grow their wealth through strategic, crypto-backed investment plans. We combine security, transparency, and consistent returns to deliver a premium investment experience.</p>
                <p class="text-muted" style="margin-bottom:1.5rem">Our automated profit distribution system ensures you receive your daily returns on time, every time. Whether you're a beginner or an experienced investor, our platform is designed for you.</p>
                <div style="display:flex;gap:2rem">
                    <div><h4 style="color:var(--gold);font-size:1.5rem;font-weight:800">24/7</h4><small class="text-muted">Support Available</small></div>
                    <div><h4 style="color:var(--gold);font-size:1.5rem;font-weight:800">100%</h4><small class="text-muted">Secure & Encrypted</small></div>
                    <div><h4 style="color:var(--gold);font-size:1.5rem;font-weight:800">5min</h4><small class="text-muted">Avg. Withdrawal Time</small></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features -->
<section class="section" id="features" style="background:var(--glass)">
    <div class="container">
        <div class="section-header"><span class="tag">Why Choose Us</span><h2>Benefits of Investing With Us</h2><p>We offer a range of benefits designed to maximize your returns and minimize your risk.</p></div>
        <div class="card-grid">
            <div class="feature-card"><div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div><div class="icon"><i class="fas fa-chart-line"></i></div><h4>Daily ROI</h4><p>Earn returns credited every single day. Your profits compound and grow automatically.</p></div>
            <div class="feature-card"><div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div><div class="icon"><i class="fas fa-shield-halved"></i></div><h4>Secure & Insured</h4><p>Bank-grade encryption, cold storage, and real-time monitoring. Your funds are protected.</p></div>
            <div class="feature-card"><div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div><div class="icon"><i class="fas fa-bolt"></i></div><h4>Instant Withdrawals</h4><p>Withdraw profits or principal anytime via BTC, USDT, or Ethereum. Fast processing.</p></div>
            <div class="feature-card"><div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div><div class="icon"><i class="fas fa-users"></i></div><h4>Referral Program</h4><p>Earn commissions by inviting friends. Get paid for every active investor you refer.</p></div>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="section" id="how">
    <div class="container">
        <div class="section-header"><span class="tag">Get Started</span><h2>Start in 3 Simple Steps</h2><p>Getting started takes less than 2 minutes.</p></div>
        <div class="steps">
            <div class="step"><div class="step-num">1</div><h4>Create Account</h4><p>Register with your email and set up your profile. Instant activation — no complex verification needed.</p></div>
            <div class="step"><div class="step-num">2</div><h4>Deposit & Invest</h4><p>Fund your account via BTC, USDT, or ETH. Choose an investment plan that fits your goals.</p></div>
            <div class="step"><div class="step-num">3</div><h4>Earn Daily</h4><p>Watch your balance grow. Profits credited daily. Withdraw anytime with fast processing.</p></div>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="section" style="background:var(--glass)">
    <div class="container">
        <div class="section-header"><span class="tag">Testimonials</span><h2>What Our Investors Say</h2></div>
        <div class="testimonials">
            <div class="testimonial"><div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div><p>"I have been investing for 8 months and the daily payouts have been consistent every single day. The platform is transparent and easy to use."</p><div class="author"><div class="avatar">AK</div><div><div style="font-weight:600">Alex K.</div><div class="text-muted" style="font-size:.75rem">Investor since 2025</div></div></div></div>
            <div class="testimonial"><div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div><p>"What sets them apart is the instant withdrawal. I requested a payout and had the funds in my wallet within minutes. Incredible service."</p><div class="author"><div class="avatar">SN</div><div><div style="font-weight:600">Sarah N.</div><div class="text-muted" style="font-size:.75rem">Investor since 2024</div></div></div></div>
            <div class="testimonial"><div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div><p>"The mobile experience is flawless. I check my earnings on my phone every morning. Best investment platform I have used."</p><div class="author"><div class="avatar">MT</div><div><div style="font-weight:600">Michael T.</div><div class="text-muted" style="font-size:.75rem">Investor since 2025</div></div></div></div>
        </div>
    </div>
</section>

<!-- FAQ -->
<section class="section" id="faq">
    <div class="container">
        <div class="section-header"><span class="tag">FAQ</span><h2>Frequently Asked Questions</h2></div>
        <div class="faq-grid">
            <div>
                <div class="faq-item open"><div class="faq-q" onclick="this.parentElement.classList.toggle('open')">How do I start investing? <i class="fas fa-chevron-down"></i></div><div class="faq-a">Create an account, set up your wallet addresses in Settings, make a deposit via BTC/USDT/ETH, and choose an investment plan. Your daily returns start immediately.</div></div>
                <div class="faq-item"><div class="faq-q" onclick="this.parentElement.classList.toggle('open')">How are daily profits calculated? <i class="fas fa-chevron-down"></i></div><div class="faq-a">Daily ROI is calculated based on your chosen plan's percentage rate. For example, a $1,000 investment at 2.5% daily ROI earns $25 per day, credited directly to your balance.</div></div>
                <div class="faq-item"><div class="faq-q" onclick="this.parentElement.classList.toggle('open')">How fast are withdrawals processed? <i class="fas fa-chevron-down"></i></div><div class="faq-a">Withdrawals are processed within minutes to a few hours, depending on network congestion. We support BTC, USDT (Tether), and Ethereum (ETH) withdrawals.</div></div>
            </div>
            <div>
                <div class="faq-item"><div class="faq-q" onclick="this.parentElement.classList.toggle('open')">Is my investment secure? <i class="fas fa-chevron-down"></i></div><div class="faq-a">Yes. We use bank-grade 256-bit encryption, cold storage for majority of funds, and real-time monitoring. Your data and assets are protected 24/7.</div></div>
                <div class="faq-item"><div class="faq-q" onclick="this.parentElement.classList.toggle('open')">Do you have a referral program? <i class="fas fa-chevron-down"></i></div><div class="faq-a">Yes! Share your referral link from your dashboard. You earn commission on every active investor you bring to the platform. The more you refer, the more you earn.</div></div>
                <div class="faq-item"><div class="faq-q" onclick="this.parentElement.classList.toggle('open')">What cryptocurrencies are supported? <i class="fas fa-chevron-down"></i></div><div class="faq-a">We currently support Bitcoin (BTC), Tether (USDT), and Ethereum (ETH) for both deposits and withdrawals. More options coming soon.</div></div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="section">
    <div class="container">
        <div class="cta-card">
            <h2>Ready to Start Earning Daily Returns?</h2>
            <p>Join thousands of investors already growing their wealth with <?php echo SITE_NAME; ?>.</p>
            <a href="/register.php" class="btn btn-gold btn-lg">Create Your Free Account</a>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <div><h5><img src="/assets/img/logo.svg" alt="<?php echo SITE_NAME; ?>" style="height:32px"></h5><p class="text-muted" style="font-size:.85rem">Secure investment platform offering daily returns through crypto-backed plans. Trusted by thousands worldwide.</p></div>
            <div><h5>Quick Links</h5><a href="/login.php">Login</a><a href="/register.php">Register</a><a href="#about">About</a><a href="#features">Benefits</a></div>
            <div><h5>Support</h5><a href="#">support@primeaxisinv.com</a><a href="#faq">FAQ</a><a href="#">Terms & Conditions</a></div>
        </div>
        <div class="footer-bottom">&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.</div>
    </div>
</footer>

<script>
window.addEventListener('scroll',()=>document.getElementById('nav').classList.toggle('scrolled',window.scrollY>50));
document.querySelectorAll('a[href^="#"]').forEach(a=>a.addEventListener('click',e=>{e.preventDefault();const t=document.querySelector(a.getAttribute('href'));if(t)t.scrollIntoView({behavior:'smooth'})}));
setTimeout(()=>{const p=new URLSearchParams(window.location.search);if(p.get('logout')==='1'){showToast('You have successfully signed out.','success');window.history.replaceState({},document.title,window.location.pathname)};},300);
</script>
</body>
</html>
