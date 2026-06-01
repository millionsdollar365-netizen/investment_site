<?php require_once __DIR__ . '/includes/config.php'; require_once __DIR__ . '/includes/session.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo SITE_NAME; ?> — Smart Crypto Investments</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="description" content="Modern investment platform. Earn daily returns on crypto-backed investment plans.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --bg: #0a0f1e;
            --bg2: #111827;
            --accent: #22d3ee;
            --accent2: #a78bfa;
            --text: #e2e8f0;
            --muted: #94a3b8;
            --card-bg: rgba(255,255,255,.04);
            --glass: rgba(255,255,255,.06);
            --border: rgba(255,255,255,.08);
            --radius: 16px;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            line-height: 1.6;
            overflow-x: hidden;
        }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 1.5rem; }

        /* Nav */
        .nav { position: fixed; top: 0; left: 0; right: 0; z-index: 100; padding: 1rem 0; transition: all .3s; }
        .nav.scrolled { background: rgba(10,15,30,.92); backdrop-filter: blur(20px); border-bottom: 1px solid var(--border); }
        .nav-inner { display: flex; align-items: center; justify-content: space-between; }
        .nav-logo { font-size: 1.35rem; font-weight: 800; color: #fff; text-decoration: none; display: flex; align-items: center; gap: .5rem; }
        .nav-logo span { background: linear-gradient(135deg, var(--accent), var(--accent2)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .nav-links { display: flex; align-items: center; gap: 2rem; list-style: none; }
        .nav-links a { color: var(--muted); text-decoration: none; font-size: .9rem; font-weight: 500; transition: color .2s; }
        .nav-links a:hover { color: #fff; }
        .btn { display: inline-block; padding: .65rem 1.6rem; border-radius: 50px; font-weight: 600; font-size: .9rem; text-decoration: none; transition: all .3s; cursor: pointer; border: none; }
        .btn-primary { background: linear-gradient(135deg, var(--accent), #06b6d4); color: #000; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(34,211,238,.3); }
        .btn-outline { border: 2px solid rgba(255,255,255,.2); color: #fff; background: transparent; }
        .btn-outline:hover { border-color: #fff; background: rgba(255,255,255,.05); }
        .btn-lg { padding: .85rem 2.2rem; font-size: 1rem; }
        .hamburger { display: none; background: none; border: none; color: #fff; font-size: 1.5rem; cursor: pointer; }
        @media (max-width: 768px) {
            .nav-links { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: var(--bg2); flex-direction: column; justify-content: center; gap: 2rem; z-index: 99; }
            .nav-links.open { display: flex; }
            .hamburger { display: block; position: relative; z-index: 100; }
        }

        /* Hero */
        .hero { min-height: 100vh; display: flex; align-items: center; position: relative; padding: 8rem 0 4rem; overflow: hidden; }
        .hero::before {
            content: ''; position: absolute; top: -50%; left: -50%; width: 200%; height: 200%;
            background: radial-gradient(circle at 30% 50%, rgba(34,211,238,.12) 0%, transparent 50%),
                        radial-gradient(circle at 70% 30%, rgba(167,139,250,.08) 0%, transparent 50%);
            animation: drift 20s linear infinite;
        }
        @keyframes drift { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        .hero-content { position: relative; z-index: 1; max-width: 700px; }
        .hero-badge { display: inline-block; padding: .35rem 1rem; border-radius: 50px; background: var(--glass); border: 1px solid var(--border); font-size: .8rem; color: var(--accent); font-weight: 500; margin-bottom: 1.5rem; }
        .hero h1 { font-size: clamp(2.2rem, 6vw, 3.8rem); font-weight: 900; line-height: 1.15; margin-bottom: 1.25rem; }
        .hero h1 span { background: linear-gradient(135deg, var(--accent) 0%, var(--accent2) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .hero p { font-size: 1.15rem; color: var(--muted); max-width: 540px; margin-bottom: 2rem; }
        .hero-actions { display: flex; gap: 1rem; flex-wrap: wrap; }

        /* Stats */
        .stats { display: grid; grid-template-columns: repeat(4,1fr); gap: 1.5rem; margin: -3rem auto 5rem; position: relative; z-index: 2; }
        .stat-card { background: var(--glass); backdrop-filter: blur(20px); border: 1px solid var(--border); border-radius: var(--radius); padding: 1.5rem; text-align: center; }
        .stat-card h3 { font-size: 1.8rem; font-weight: 800; background: linear-gradient(135deg, var(--accent), var(--accent2)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .stat-card p { font-size: .78rem; color: var(--muted); margin-top: .25rem; text-transform: uppercase; letter-spacing: .05em; }
        @media (max-width: 768px) { .stats { grid-template-columns: 1fr 1fr; } }

        /* Section */
        .section { padding: 6rem 0; }
        .section-header { text-align: center; max-width: 600px; margin: 0 auto 4rem; }
        .section-header h2 { font-size: 2.2rem; font-weight: 800; margin-bottom: .75rem; }
        .section-header p { color: var(--muted); font-size: 1rem; }

        /* Cards */
        .card-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.5rem; }
        .glass-card { background: var(--card-bg); backdrop-filter: blur(20px); border: 1px solid var(--border); border-radius: var(--radius); padding: 2rem; transition: all .3s; }
        .glass-card:hover { transform: translateY(-4px); border-color: rgba(34,211,238,.3); box-shadow: 0 12px 40px rgba(0,0,0,.3); }
        .glass-card .icon { width: 56px; height: 56px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; margin-bottom: 1.25rem; }
        .glass-card h4 { font-size: 1.1rem; font-weight: 700; margin-bottom: .5rem; }
        .glass-card p { font-size: .88rem; color: var(--muted); line-height: 1.7; }

        /* Steps */
        .steps { display: grid; grid-template-columns: repeat(3,1fr); gap: 2rem; }
        .step { text-align: center; padding: 2rem; position: relative; }
        .step-num { width: 56px; height: 56px; border-radius: 50%; background: linear-gradient(135deg, var(--accent), var(--accent2)); display: flex; align-items: center; justify-content: center; font-size: 1.4rem; font-weight: 800; color: #000; margin: 0 auto 1.25rem; }
        .step h4 { font-weight: 700; margin-bottom: .5rem; }
        .step p { font-size: .88rem; color: var(--muted); }
        @media (max-width: 768px) { .steps { grid-template-columns: 1fr; } }

        /* Testimonials */
        .testimonials { display: grid; grid-template-columns: repeat(3,1fr); gap: 1.5rem; }
        .testimonial { background: var(--glass); border: 1px solid var(--border); border-radius: var(--radius); padding: 2rem; }
        .testimonial .stars { color: #fbbf24; margin-bottom: 1rem; font-size: .9rem; }
        .testimonial p { font-size: .9rem; color: var(--muted); font-style: italic; line-height: 1.7; margin-bottom: 1.25rem; }
        .testimonial .author { display: flex; align-items: center; gap: .75rem; }
        .testimonial .avatar { width: 44px; height: 44px; border-radius: 50%; background: linear-gradient(135deg, var(--accent), var(--accent2)); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: .9rem; color: #000; }
        .testimonial .name { font-weight: 600; font-size: .9rem; }
        .testimonial .role { font-size: .75rem; color: var(--muted); }
        @media (max-width: 768px) { .testimonials { grid-template-columns: 1fr; } }

        /* CTA */
        .cta { background: linear-gradient(135deg, rgba(34,211,238,.08), rgba(167,139,250,.08)); border: 1px solid var(--border); border-radius: 24px; padding: 4rem 2rem; text-align: center; }
        .cta h2 { font-size: 2.2rem; font-weight: 800; margin-bottom: .75rem; }
        .cta p { color: var(--muted); margin-bottom: 2rem; font-size: 1.05rem; }

        /* Footer */
        .footer { border-top: 1px solid var(--border); padding: 3rem 0; }
        .footer-grid { display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 3rem; }
        .footer h5 { font-weight: 700; margin-bottom: 1rem; font-size: .95rem; }
        .footer a { display: block; color: var(--muted); text-decoration: none; font-size: .85rem; margin-bottom: .5rem; transition: color .2s; }
        .footer a:hover { color: #fff; }
        .footer-bottom { text-align: center; padding-top: 2rem; margin-top: 2rem; border-top: 1px solid var(--border); color: var(--muted); font-size: .8rem; }
        @media (max-width: 768px) { .footer-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

<!-- Nav -->
<nav class="nav" id="nav">
    <div class="container nav-inner">
        <a href="/" class="nav-logo"><i class="fas fa-cubes"></i> <span><?php echo SITE_NAME; ?></span></a>
        <button class="hamburger" id="hamburger" onclick="toggleMenu()"><i class="fas fa-bars"></i></button>
        <ul class="nav-links" id="navLinks">
            <li><a href="#" onclick="toggleMenu()">Home</a></li>
            <li><a href="#features" onclick="toggleMenu()">Features</a></li>
            <li><a href="#how" onclick="toggleMenu()">How It Works</a></li>
            <li><a href="#testimonials" onclick="toggleMenu()">Reviews</a></li>
            <?php if (isLoggedIn()): ?>
                <li><a href="/dashboard/" class="btn btn-primary">Dashboard</a></li>
            <?php else: ?>
                <li><a href="/login.php">Login</a></li>
                <li><a href="/register.php" class="btn btn-primary">Get Started</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<!-- Hero -->
<section class="hero">
    <div class="container hero-content">
        <div class="hero-badge"><i class="fas fa-bolt"></i> Trusted by thousands worldwide</div>
        <h1>Grow Your Wealth with <span>Daily Returns</span></h1>
        <p>Invest in crypto-backed plans, earn daily ROI automatically, and withdraw anytime. Simple, transparent, profitable.</p>
        <div class="hero-actions">
            <a href="/register.php" class="btn btn-primary btn-lg">Start Investing Now</a>
            <a href="#features" class="btn btn-outline btn-lg">See Features</a>
        </div>
    </div>
</section>

<div class="container">
    <div class="stats">
        <div class="stat-card"><h3>12K+</h3><p>Active Investors</p></div>
        <div class="stat-card"><h3>$2.5M</h3><p>Total Invested</p></div>
        <div class="stat-card"><h3>$890K</h3><p>Profits Paid</p></div>
        <div class="stat-card"><h3>99.9%</h3><p>Uptime</p></div>
    </div>
</div>

<!-- Features -->
<section class="section" id="features">
    <div class="container">
        <div class="section-header"><h2>Why Invest With Us</h2><p>We provide a secure, automated platform designed for consistent returns.</p></div>
        <div class="card-grid">
            <div class="glass-card"><div class="icon" style="background:rgba(34,211,238,.15);color:var(--accent)"><i class="fas fa-chart-line"></i></div><h4>Daily ROI</h4><p>Earn returns every single day. Your profits are automatically credited to your balance at regular intervals.</p></div>
            <div class="glass-card"><div class="icon" style="background:rgba(167,139,250,.15);color:var(--accent2)"><i class="fas fa-lock"></i></div><h4>Bank-Grade Security</h4><p>256-bit encryption, cold storage, and real-time monitoring keep your assets protected 24/7.</p></div>
            <div class="glass-card"><div class="icon" style="background:rgba(34,211,238,.15);color:var(--accent)"><i class="fas fa-wallet"></i></div><h4>Instant Withdrawals</h4><p>Withdraw your profits or principal anytime. BTC, USDT, and Ethereum supported with fast processing.</p></div>
            <div class="glass-card"><div class="icon" style="background:rgba(167,139,250,.15);color:var(--accent2)"><i class="fas fa-users"></i></div><h4>Referral Program</h4><p>Earn commissions by referring friends. Get paid for every active investor you bring to the platform.</p></div>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="section" id="how" style="background:var(--glass)">
    <div class="container">
        <div class="section-header"><h2>Start in 3 Simple Steps</h2><p>Getting started takes less than 2 minutes.</p></div>
        <div class="steps">
            <div class="step"><div class="step-num">1</div><h4>Create Account</h4><p>Register with your email. No KYC required for basic accounts. Instant activation.</p></div>
            <div class="step"><div class="step-num">2</div><h4>Deposit & Invest</h4><p>Fund your wallet via crypto. Choose an investment plan that matches your goals.</p></div>
            <div class="step"><div class="step-num">3</div><h4>Earn Daily</h4><p>Watch your balance grow. Profits are credited daily. Withdraw anytime you want.</p></div>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="section" id="testimonials">
    <div class="container">
        <div class="section-header"><h2>What Investors Say</h2><p>Real feedback from our growing community.</p></div>
        <div class="testimonials">
            <div class="testimonial"><div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div><p>"The daily payout system is incredible. I started with $500 and have been earning consistently for 4 months now."</p><div class="author"><div class="avatar">AK</div><div><div class="name">Alex K.</div><div class="role">Investor since 2025</div></div></div></div>
            <div class="testimonial"><div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div><p>"What sets them apart is transparency. I can track every transaction, every profit. No hidden fees, no surprises."</p><div class="author"><div class="avatar">SN</div><div><div class="name">Sarah N.</div><div class="role">Investor since 2024</div></div></div></div>
            <div class="testimonial"><div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div><p>"The mobile experience is perfect. I check my earnings on my phone every morning. Withdrawals arrive same day."</p><div class="author"><div class="avatar">MT</div><div><div class="name">Michael T.</div><div class="role">Investor since 2025</div></div></div></div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="section">
    <div class="container">
        <div class="cta">
            <h2>Ready to Start Earning Daily?</h2>
            <p>Join thousands of investors who are already growing their wealth with us.</p>
            <a href="/register.php" class="btn btn-primary btn-lg">Create Your Free Account</a>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <div><h5><i class="fas fa-cubes"></i> <?php echo SITE_NAME; ?></h5><p style="color:var(--muted);font-size:.85rem">Secure crypto investment platform with daily ROI. Trusted by thousands worldwide.</p></div>
            <div><h5>Quick Links</h5><a href="/login.php">Login</a><a href="/register.php">Register</a><a href="#features">Features</a></div>
            <div><h5>Support</h5><a href="#">support@primeaxisinv.com</a><a href="#">24/7 Available</a><a href="#">FAQ</a></div>
        </div>
        <div class="footer-bottom">&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.</div>
    </div>
</footer>

<script>
// Nav scroll
window.addEventListener('scroll',()=>document.getElementById('nav').classList.toggle('scrolled',window.scrollY>50));
// Mobile menu
function toggleMenu(){document.getElementById('navLinks').classList.toggle('open');}
// Smooth scroll
document.querySelectorAll('a[href^="#"]').forEach(a=>a.addEventListener('click',e=>{e.preventDefault();const t=document.querySelector(a.getAttribute('href'));if(t)t.scrollIntoView({behavior:'smooth'})}));
</script>
</body>
</html>
