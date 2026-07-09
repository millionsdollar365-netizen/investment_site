<?php require_once __DIR__ . '/includes/config.php'; require_once __DIR__ . '/includes/session.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> — Premium Investment Platform</title>
    <meta name="description" content="Invest with confidence. Premium crypto-backed investment plans with daily returns.">
    <link rel="icon" type="image/svg+xml" href="/assets/img/favicon.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Playfair+Display:wght@600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root{--bg:#0a1628;--bg-card:#112240;--gold:#c5960c;--gold-lt:#d4a843;--text:#e8eaed;--muted:#8892a4;--border:rgba(255,255,255,.06);--radius:12px}
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'Inter',sans-serif;background:var(--bg);color:var(--text);line-height:1.65;overflow-x:hidden}
        .container{max-width:1200px;margin:0 auto;padding:0 1.5rem}
        h1,h2{font-family:'Playfair Display',serif;color:#fff}
        .section{padding:5rem 0}
        .section-tag{display:inline-block;font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.12em;color:var(--gold);margin-bottom:.75rem}
        .section-title{font-size:clamp(1.8rem,4vw,2.4rem);font-weight:800;margin-bottom:.75rem;line-height:1.25}
        .section-sub{font-size:.95rem;color:var(--muted);max-width:560px}

        :focus-visible{outline:2px solid var(--gold);outline-offset:3px;border-radius:4px}

        /* Nav */
        .nav{position:fixed;top:0;left:0;right:0;z-index:1000;padding:.9rem 0;background:rgba(10,22,40,.94);backdrop-filter:blur(20px);border-bottom:1px solid var(--border)}
        .nav-inner{display:flex;align-items:center;justify-content:space-between}
        .nav-logo{font-size:1.3rem;font-weight:800;color:#fff;text-decoration:none;display:flex;align-items:center;gap:.5rem}
        .nav-logo em{color:var(--gold);font-style:normal}
        .nav-links{display:flex;align-items:center;gap:1.5rem;list-style:none}
        .nav-links a{color:var(--muted);text-decoration:none;font-size:.84rem;font-weight:500;transition:color .2s}
        .nav-links a:hover{color:#fff}
        .btn{display:inline-flex;align-items:center;justify-content:center;min-height:44px;padding:.6rem 1.5rem;border-radius:50px;font-weight:600;font-size:.85rem;text-decoration:none;transition:all .3s;cursor:pointer;border:none;font-family:inherit}
        .btn-gold{background:linear-gradient(135deg,var(--gold),var(--gold-lt));color:#0a1628}
        .btn-gold:hover{transform:translateY(-2px);box-shadow:0 8px 30px rgba(197,150,12,.3)}
        .btn-outline{border:1.5px solid rgba(255,255,255,.15);background:transparent;color:#fff}
        .btn-outline:hover{border-color:var(--gold);color:var(--gold)}
        .btn-lg{padding:.75rem 2rem;font-size:.92rem}
        .hamburger{display:none;background:none;border:none;color:#fff;font-size:1.4rem;cursor:pointer;z-index:10001}
        @media(max-width:768px){.nav-links{display:none}.hamburger{display:block}}

        /* Mobile overlay */
        .mobile-overlay{display:none;position:fixed;inset:0;background:rgba(10,22,40,.98);backdrop-filter:blur(24px);flex-direction:column;justify-content:center;align-items:center;gap:1.5rem;z-index:9999}
        .mobile-overlay.open{display:flex}
        .mobile-link{color:var(--muted);font-size:1.15rem;font-weight:500;text-decoration:none}
        .mobile-link:hover{color:#fff}
        @media(min-width:769px){.mobile-overlay{display:none!important}}

        /* Hero */
        .hero{min-height:90vh;display:flex;align-items:center;padding:6rem 0 3rem;position:relative;overflow:hidden}
        .hero::before{content:'';position:absolute;top:-20%;right:-10%;width:700px;height:700px;background:radial-gradient(circle,rgba(197,150,12,.08) 0%,transparent 55%);pointer-events:none}
        .hero .container{display:grid;grid-template-columns:1fr 1fr;gap:3rem;align-items:center;position:relative;z-index:1}
        .hero h1{font-size:clamp(2.2rem,5vw,3.5rem);font-weight:900;line-height:1.1;margin-bottom:1rem}
        .hero h1 span{color:var(--gold)}
        .hero p{font-size:1.05rem;color:var(--muted);max-width:460px;margin-bottom:2rem}
        .hero-actions{display:flex;gap:.85rem;flex-wrap:wrap}
        .hero-visual{position:relative;display:flex;align-items:center;justify-content:center}
        .hero-card{background:rgba(17,34,64,.8);backdrop-filter:blur(16px);border:1px solid rgba(197,150,12,.15);border-radius:var(--radius);padding:1.5rem 2rem;text-align:center}
        .hero-card .big-num{font-size:2.2rem;font-weight:900;color:var(--gold);font-family:'Playfair Display',serif}
        .hero-card .lbl{font-size:.78rem;color:var(--muted);margin-top:.2rem}
        .coin{position:absolute;width:44px;height:44px;border-radius:50%;background:rgba(197,150,12,.12);border:2px solid rgba(197,150,12,.25);display:flex;align-items:center;justify-content:center;color:var(--gold);font-size:1rem;animation:float 5s ease-in-out infinite}
        .coin:nth-child(1){top:-15px;right:20px;animation-delay:0s}
        .coin:nth-child(2){bottom:10px;right:-10px;animation-delay:1.5s;width:34px;height:34px;font-size:.8rem}
        .coin:nth-child(3){top:50%;left:-15px;animation-delay:3s;width:36px;height:36px}
        @keyframes float{0%,100%{transform:translateY(0)}50%{transform:translateY(-10px)}}
        @media(max-width:768px){.hero .container{grid-template-columns:1fr;text-align:center}.hero p{margin:0 auto 2rem}.hero-visual{display:none}}

        /* Partner */
        .partner-strip{display:flex;justify-content:center;align-items:center;gap:2.5rem;flex-wrap:wrap;padding:2rem 0;opacity:.45}
        .partner-strip span{font-size:.95rem;font-weight:700;color:var(--muted);letter-spacing:.06em}

        /* Stats */
        .stats-row{display:grid;grid-template-columns:repeat(4,1fr);gap:1.25rem;margin-top:-2.5rem;position:relative;z-index:2}
        .stat-card{background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);padding:1.5rem;text-align:center}
        .stat-card .num{font-family:'Playfair Display',serif;font-size:1.8rem;font-weight:800;color:var(--gold)}
        .stat-card .lbl{font-size:.72rem;color:var(--muted);margin-top:.25rem;text-transform:uppercase;letter-spacing:.06em}
        @media(max-width:768px){.stats-row{grid-template-columns:1fr 1fr}}

        /* About */
        .about-grid{display:grid;grid-template-columns:1fr 1fr;gap:3rem;align-items:center}
        .about-image{position:relative}
        .about-image img{width:100%;border-radius:var(--radius);opacity:.85}
        .about-badge{position:absolute;background:var(--bg-card);border:1px solid rgba(197,150,12,.2);border-radius:10px;padding:.8rem 1.2rem}
        .about-badge .n{font-size:1.3rem;font-weight:800;color:var(--gold)}
        .about-badge .l{font-size:.65rem;color:var(--muted);text-transform:uppercase;letter-spacing:.05em}
        .about-badge.tl{top:10px;left:-15px}.about-badge.br{bottom:15px;right:-15px}
        @media(max-width:768px){.about-grid{grid-template-columns:1fr}}

        /* Cards */
        .card-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:1.25rem}
        .feature-card{background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);padding:1.75rem;transition:all .3s;position:relative;overflow:hidden}
        .feature-card:hover{transform:translateY(-3px);border-color:rgba(197,150,12,.2);box-shadow:0 16px 40px rgba(0,0,0,.2)}
        .feature-card .icon{width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;margin-bottom:1rem;background:rgba(197,150,12,.1);color:var(--gold)}
        .feature-card h4{font-size:.95rem;font-weight:700;color:#fff;margin-bottom:.3rem}
        .feature-card p{font-size:.84rem;color:var(--muted);line-height:1.6}
        .feature-card .stars{color:var(--gold);font-size:.7rem;margin-bottom:.5rem;letter-spacing:2px}

        /* Team */
        .team-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1.5rem}
        .team-card{background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);padding:1.5rem;text-align:center;transition:all .3s}
        .team-card:hover{transform:translateY(-3px);border-color:rgba(197,150,12,.15)}
        .team-card .av{width:60px;height:60px;border-radius:50%;margin:0 auto .75rem;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:1.1rem}
        .team-card .name{font-size:.9rem;font-weight:700;color:#fff}
        .team-card .role{font-size:.75rem;color:var(--muted);margin-top:.15rem}

        /* Testimonials */
        .test-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:1.25rem}
        .test-card{background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);padding:1.5rem}
        .test-card .stars{color:var(--gold);font-size:.75rem;margin-bottom:.75rem;letter-spacing:2px}
        .test-card .quote{font-style:italic;font-size:.88rem;color:var(--muted);line-height:1.7;margin-bottom:1rem}
        .test-card .author{display:flex;align-items:center;gap:.6rem;font-weight:600;font-size:.84rem;color:#fff}
        .test-card .av{width:40px;height:40px;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.75rem}

        /* FAQ */
        .faq-grid{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
        .faq-item{background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden}
        .faq-q{padding:1rem 1.25rem;cursor:pointer;display:flex;justify-content:space-between;align-items:center;font-weight:600;font-size:.88rem;min-height:44px}
        .faq-q i{color:var(--gold);transition:transform .3s;font-size:.75rem}
        .faq-item.open .faq-q i{transform:rotate(180deg)}
        .faq-a{padding:0 1.25rem;max-height:0;overflow:hidden;transition:all .3s;color:var(--muted);font-size:.84rem;line-height:1.7}
        .faq-item.open .faq-a{padding:0 1.25rem 1rem;max-height:200px}
        @media(max-width:768px){.faq-grid{grid-template-columns:1fr}}

        /* CTA */
        .cta{text-align:center;padding:4rem 2rem;background:linear-gradient(135deg,rgba(197,150,12,.08),rgba(17,34,64,.5));border:1px solid rgba(197,150,12,.1);border-radius:16px}
        .cta h2{color:#fff;margin-bottom:.5rem}
        .cta p{color:var(--muted);margin-bottom:1.5rem}

        /* Footer */
        .footer{border-top:1px solid var(--border);padding:3rem 0;font-size:.84rem;color:var(--muted)}
        .footer-grid{display:grid;grid-template-columns:2fr 1fr 1fr;gap:2rem}
        .footer h5{color:#fff;font-weight:700;margin-bottom:.75rem}
        .footer a{color:var(--muted);text-decoration:none;display:block;margin-bottom:.35rem}
        .footer a:hover{color:var(--gold)}
        .footer-bottom{border-top:1px solid var(--border);margin-top:2rem;padding-top:1.5rem;text-align:center;font-size:.76rem}
        @media(max-width:768px){.footer-grid{grid-template-columns:1fr}}
    </style>
</head>
<body>

<nav class="nav"><div class="container nav-inner">
    <a href="/" class="nav-logo"><img src="/assets/img/logo-v2.svg" alt="<?php echo SITE_NAME; ?>" style="height:32px"></a>
    <button class="hamburger" onclick="toggleMenu()"><i class="fas fa-bars" id="menuIcon"></i></button>
    <ul class="nav-links">
        <li><a href="#">Home</a></li><li><a href="#about">About</a></li><li><a href="#features">Benefits</a></li><li><a href="#team">Team</a></li><li><a href="#faq">FAQ</a></li>
        <?php if(isLoggedIn()): ?><li><a href="/dashboard/" class="btn btn-gold">Dashboard</a></li>
        <?php else: ?><li><a href="/login.php">Sign In</a></li><li><a href="/register.php" class="btn btn-gold">Join Now</a></li><?php endif; ?>
    </ul>
</div></nav>

<div class="mobile-overlay" id="mobileOverlay">
    <a href="#" class="mobile-link" onclick="toggleMenu()">Home</a>
    <a href="#about" class="mobile-link" onclick="toggleMenu()">About</a>
    <a href="#features" class="mobile-link" onclick="toggleMenu()">Benefits</a>
    <a href="#team" class="mobile-link" onclick="toggleMenu()">Team</a>
    <a href="#faq" class="mobile-link" onclick="toggleMenu()">FAQ</a>
    <?php if(isLoggedIn()): ?><a href="/dashboard/" class="btn btn-gold">Dashboard</a>
    <?php else: ?><a href="/login.php" class="mobile-link" onclick="toggleMenu()">Sign In</a><a href="/register.php" class="btn btn-gold" onclick="toggleMenu()">Join Now</a><?php endif; ?>
</div>

<section class="hero"><div class="container">
    <div>
        <h1>Invest Your Money With <span>Higher Returns</span></h1>
        <p>Join a premium investment platform built for serious investors. Structured plans, daily returns, and enterprise-grade security — all in one place.</p>
        <div class="hero-actions">
            <a href="/register.php" class="btn btn-gold btn-lg">Get Started</a>
            <a href="#features" class="btn btn-outline btn-lg">Learn More</a>
        </div>
    </div>
    <div class="hero-visual">
        <div class="coin"><i class="fas fa-coins"></i></div>
        <div class="coin"><i class="fab fa-bitcoin"></i></div>
        <div class="coin"><i class="fas fa-gem"></i></div>
        <div class="hero-card"><div class="big-num">4.8<span style="font-size:.5em;color:var(--muted)">/5</span></div><div class="lbl">TrustPilot Rating</div></div>
    </div>
</div></section>

<div class="container"><div class="partner-strip"><span>PARTNERS</span><span style="color:#fff">Binance</span><span style="color:#fff">Coinbase</span><span style="color:#fff">Blockchain</span><span style="color:#fff">MetaMask</span></div></div>

<div class="container"><div class="stats-row">
    <div class="stat-card"><div class="num">15K+</div><div class="lbl">Active Investors</div></div>
    <div class="stat-card"><div class="num">$3.2M</div><div class="lbl">Total Invested</div></div>
    <div class="stat-card"><div class="num">$1.1M</div><div class="lbl">Profits Paid</div></div>
    <div class="stat-card"><div class="num">99.9%</div><div class="lbl">Uptime</div></div>
</div></div>

<section class="section" id="about"><div class="container">
    <div class="about-grid">
        <div class="about-image">
            <img src="https://images.unsplash.com/photo-1633158829585-23ba8f7c8caf?w=600&h=500&fit=crop" alt="About">
            <div class="about-badge tl"><div class="n">5+</div><div class="l">Years Excellence</div></div>
            <div class="about-badge br"><div class="n">15K+</div><div class="l">Satisfied Investors</div></div>
        </div>
        <div>
            <span class="section-tag">About Us</span>
            <h2 class="section-title">Your Trusted Investment Partner</h2>
            <p class="section-sub" style="margin-bottom:1rem"><?php echo SITE_NAME; ?> is a premium investment platform that helps individuals grow their wealth through strategic, crypto-backed investment plans.</p>
            <p class="section-sub">Our automated system ensures daily returns are credited consistently. Whether you're a beginner or an experienced investor, our platform is built for you.</p>
            <div style="display:flex;gap:2rem;margin-top:1.5rem">
                <div><h3 style="color:var(--gold);font-size:1.3rem;font-weight:800">24/7</h3><small style="color:var(--muted)">Support</small></div>
                <div><h3 style="color:var(--gold);font-size:1.3rem;font-weight:800">100%</h3><small style="color:var(--muted)">Secure</small></div>
                <div><h3 style="color:var(--gold);font-size:1.3rem;font-weight:800">5min</h3><small style="color:var(--muted)">Withdrawal</small></div>
            </div>
        </div>
    </div>
</div></section>

<section class="section" id="features" style="background:var(--bg-card)"><div class="container">
    <div style="text-align:center;margin-bottom:3rem"><span class="section-tag">Why Choose Us</span><h2 class="section-title" style="max-width:600px;margin:0 auto 1rem">Benefits of Investing With Us</h2><p class="section-sub" style="margin:0 auto">We offer a range of benefits designed to maximize your returns.</p></div>
    <div class="card-grid">
        <div class="feature-card"><div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div><div class="icon"><i class="fas fa-chart-line"></i></div><h4>Daily ROI</h4><p>Earn returns credited every 24 hours. Your profits compound and grow automatically.</p></div>
        <div class="feature-card"><div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div><div class="icon"><i class="fas fa-shield-halved"></i></div><h4>Bank-Grade Security</h4><p>256-bit encryption, cold storage, real-time monitoring. Your funds are always protected.</p></div>
        <div class="feature-card"><div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div><div class="icon"><i class="fas fa-bolt"></i></div><h4>Instant Withdrawals</h4><p>Withdraw profits or principal anytime via BTC, USDT, or Ethereum. Fast processing guaranteed.</p></div>
        <div class="feature-card"><div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div><div class="icon"><i class="fas fa-users"></i></div><h4>Referral Program</h4><p>Earn commissions by inviting friends. Get paid for every active investor you bring.</p></div>
    </div>
</div></section>

<section class="section" id="team"><div class="container">
    <div style="text-align:center;margin-bottom:3rem"><span class="section-tag">Our Team</span><h2 class="section-title">Meet Our Advisors</h2></div>
    <div class="team-grid">
        <div class="team-card"><div class="av" style="background:linear-gradient(135deg,var(--gold),var(--gold-lt))">JD</div><div class="name">James D.</div><div class="role">Chief Executive Officer</div></div>
        <div class="team-card"><div class="av" style="background:linear-gradient(135deg,#6366f1,#818cf8)">MK</div><div class="name">Maria K.</div><div class="role">Chief Investment Officer</div></div>
        <div class="team-card"><div class="av" style="background:linear-gradient(135deg,#22c55e,#16a34a)">RL</div><div class="name">Robert L.</div><div class="role">Head of Security</div></div>
        <div class="team-card"><div class="av" style="background:linear-gradient(135deg,#f43f5e,#e11d48)">SN</div><div class="name">Sarah N.</div><div class="role">Customer Success Lead</div></div>
    </div>
</div></section>

<section class="section" style="background:var(--bg-card)"><div class="container">
    <div style="text-align:center;margin-bottom:3rem"><span class="section-tag">Testimonials</span><h2 class="section-title">What Investors Say</h2></div>
    <div class="test-grid">
        <div class="test-card"><div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div><p class="quote">"I have been investing for 8 months and the daily payouts have been consistent. The platform is transparent and easy to use."</p><div class="author"><div class="av" style="background:linear-gradient(135deg,var(--gold),var(--gold-lt))">AK</div> Alex K.</div></div>
        <div class="test-card"><div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div><p class="quote">"What sets them apart is the instant withdrawal. I requested a payout and had funds in my wallet within minutes."</p><div class="author"><div class="av" style="background:linear-gradient(135deg,#6366f1,#818cf8)">SN</div> Sarah N.</div></div>
        <div class="test-card"><div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div><p class="quote">"Best investment platform I've used. The mobile experience is flawless and I check earnings every morning."</p><div class="author"><div class="av" style="background:linear-gradient(135deg,#22c55e,#16a34a)">MT</div> Michael T.</div></div>
    </div>
</div></section>

<section class="section" id="faq"><div class="container">
    <div style="text-align:center;margin-bottom:3rem"><span class="section-tag">FAQ</span><h2 class="section-title">Frequently Asked Questions</h2></div>
    <div class="faq-grid">
        <div>
            <div class="faq-item"><div class="faq-q" onclick="this.parentElement.classList.toggle('open')">How do I start investing? <i class="fas fa-chevron-down"></i></div><div class="faq-a">Create an account, set up your wallet addresses in Settings, make a deposit via BTC/USDT/ETH, and choose an investment plan. Your daily returns start immediately.</div></div>
            <div class="faq-item"><div class="faq-q" onclick="this.parentElement.classList.toggle('open')">How are daily profits calculated? <i class="fas fa-chevron-down"></i></div><div class="faq-a">Daily ROI is based on your plan's percentage rate. A $1,000 investment at 2.5% daily ROI earns $25 per day, credited directly to your balance.</div></div>
            <div class="faq-item"><div class="faq-q" onclick="this.parentElement.classList.toggle('open')">How fast are withdrawals? <i class="fas fa-chevron-down"></i></div><div class="faq-a">Withdrawals are processed within minutes to a few hours. We support BTC, USDT, and Ethereum (ETH).</div></div>
        </div>
        <div>
            <div class="faq-item"><div class="faq-q" onclick="this.parentElement.classList.toggle('open')">Is my investment secure? <i class="fas fa-chevron-down"></i></div><div class="faq-a">Yes. Bank-grade 256-bit encryption, cold storage for majority of funds, and 24/7 real-time monitoring protect your assets.</div></div>
            <div class="faq-item"><div class="faq-q" onclick="this.parentElement.classList.toggle('open')">Do you have a referral program? <i class="fas fa-chevron-down"></i></div><div class="faq-a">Yes! Share your referral link from your dashboard. Earn commission on every active investor you bring to the platform.</div></div>
            <div class="faq-item"><div class="faq-q" onclick="this.parentElement.classList.toggle('open')">What cryptocurrencies are supported? <i class="fas fa-chevron-down"></i></div><div class="faq-a">Bitcoin (BTC), Tether (USDT), and Ethereum (ETH) for both deposits and withdrawals. More options coming soon.</div></div>
        </div>
    </div>
</div></section>

<section class="section"><div class="container"><div class="cta">
    <h2>Ready to Start Earning Daily Returns?</h2>
    <p>Join thousands of investors already growing their wealth with <?php echo SITE_NAME; ?>.</p>
    <a href="/register.php" class="btn btn-gold btn-lg">Create Your Free Account</a>
</div></div></section>

<footer class="footer"><div class="container">
    <div class="footer-grid">
        <div><h5><?php echo SITE_NAME; ?></h5><p style="max-width:280px;color:var(--muted);font-size:.84rem">Premium investment platform. Trusted by thousands worldwide.</p></div>
        <div><h5>Quick Links</h5><a href="/login.php">Sign In</a><a href="/register.php">Register</a><a href="#about">About</a></div>
        <div><h5>Support</h5><a href="#">support@primeaxisinv.com</a><a href="#faq">FAQ</a></div>
    </div>
    <div class="footer-bottom">&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.</div>
</div></footer>

<script>
function toggleMenu(){const o=document.getElementById('mobileOverlay'),i=document.getElementById('menuIcon');o.classList.toggle('open');i.className=o.classList.contains('open')?'fas fa-times':'fas fa-bars';document.body.style.overflow=o.classList.contains('open')?'hidden':''}
document.querySelectorAll('#mobileOverlay a').forEach(a=>a.addEventListener('click',()=>{document.getElementById('mobileOverlay').classList.remove('open');document.getElementById('menuIcon').className='fas fa-bars';document.body.style.overflow=''}));
document.querySelectorAll('a[href^="#"]').forEach(a=>a.addEventListener('click',e=>{e.preventDefault();const t=document.querySelector(a.getAttribute('href'));if(t)t.scrollIntoView({behavior:'smooth'})}));
document.querySelectorAll('.faq-q').forEach(q=>q.addEventListener('click',()=>q.parentElement.classList.toggle('open')));
</script>
</body>
</html>
