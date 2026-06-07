<?php require_once __DIR__ . '/includes/config.php'; require_once __DIR__ . '/includes/session.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> — Premium Investment Platform</title>
    <meta name="description" content="Premium investment platform. Glassy, modern, built for discerning investors.">
    <link rel="icon" type="image/svg+xml" href="/assets/img/favicon.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Playfair+Display:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root{--bg:#f5f3ef;--card:rgba(255,255,255,.7);--glass:rgba(255,255,255,.55);--ink:#1a1a1a;--text:#3d3d3d;--muted:#8c8c8c;--gold:#c5960c;--gold-lt:rgba(197,150,12,.12);--border:rgba(0,0,0,.06);--radius:20px;}
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'Inter',sans-serif;background:var(--bg);color:var(--text);line-height:1.7;overflow-x:hidden}
        .container{max-width:1150px;margin:0 auto;padding:0 2rem}
        h1,h2{font-family:'Playfair Display',serif;color:var(--ink)}
        .section{padding:6rem 0;position:relative}

        .nav{position:fixed;top:0;left:0;right:0;z-index:100;padding:.9rem 0;background:rgba(245,243,239,.85);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border-bottom:1px solid var(--border)}
        .nav-inner{display:flex;align-items:center;justify-content:space-between}
        .nav-logo{font-size:1.3rem;font-weight:800;color:var(--ink);text-decoration:none;display:flex;align-items:center;gap:.5rem}
        .nav-links{display:flex;align-items:center;gap:1.75rem;list-style:none}
        .nav-links a{color:var(--muted);text-decoration:none;font-size:.85rem;font-weight:500;transition:color .2s}
        .nav-links a:hover{color:var(--ink)}
        .btn{display:inline-block;padding:.55rem 1.4rem;border-radius:50px;font-weight:600;font-size:.85rem;text-decoration:none;transition:all .3s;cursor:pointer;border:none}
        .btn-gold{background:linear-gradient(135deg,var(--gold),#a67c00);color:#fff;box-shadow:0 4px 15px rgba(197,150,12,.25)}
        .btn-gold:hover{transform:translateY(-2px);box-shadow:0 8px 25px rgba(197,150,12,.4)}
        .btn-glass{background:rgba(255,255,255,.6);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);border:1px solid rgba(0,0,0,.08);color:var(--ink)}
        .btn-glass:hover{background:rgba(255,255,255,.85)}
        .btn-lg{padding:.75rem 2rem;font-size:.92rem}

        .hero{min-height:90vh;display:flex;align-items:center;position:relative;overflow:hidden}
        .hero::before{content:'';position:absolute;top:-20%;right:-10%;width:700px;height:700px;background:radial-gradient(circle,rgba(197,150,12,.1) 0%,transparent 60%);pointer-events:none}
        .hero::after{content:'';position:absolute;bottom:-15%;left:-5%;width:500px;height:500px;background:radial-gradient(circle,rgba(197,150,12,.06) 0%,transparent 60%);pointer-events:none}
        .hero-content{position:relative;z-index:1;max-width:680px}
        .hero-badge{display:inline-flex;align-items:center;gap:.5rem;background:var(--gold-lt);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);border:1px solid rgba(197,150,12,.15);color:var(--gold);font-size:.78rem;font-weight:600;padding:.35rem 1rem;border-radius:50px;margin-bottom:1.5rem}
        .hero h1{font-size:clamp(2.4rem,6vw,4rem);font-weight:800;line-height:1.1;margin-bottom:1.25rem;letter-spacing:-.02em}
        .hero h1 em{font-style:italic;color:var(--gold)}
        .hero p{font-size:1.1rem;color:var(--muted);max-width:500px;margin-bottom:2rem}
        .hero-actions{display:flex;gap:.85rem;flex-wrap:wrap}

        .stats-row{display:grid;grid-template-columns:repeat(4,1fr);gap:1.25rem;margin-top:-3rem;position:relative;z-index:2}
        .stat-card{background:var(--glass);backdrop-filter:blur(16px);-webkit-backdrop-filter:blur(16px);border:1px solid rgba(255,255,255,.3);border-radius:var(--radius);padding:1.5rem;text-align:center;box-shadow:0 4px 20px rgba(0,0,0,.04)}
        .stat-card .num{font-family:'Playfair Display',serif;font-size:2rem;font-weight:700;color:var(--ink)}
        .stat-card .lbl{font-size:.72rem;color:var(--muted);margin-top:.25rem;text-transform:uppercase;letter-spacing:.06em}
        @media(max-width:768px){.stats-row{grid-template-columns:1fr 1fr}}

        .section-tag{display:inline-block;font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.12em;color:var(--gold);margin-bottom:.75rem}
        .section-title{font-size:2.2rem;font-weight:700;margin-bottom:1rem;line-height:1.25}
        .section-sub{font-size:1rem;color:var(--muted);max-width:540px}

        .card-row{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:1.5rem}
        .glass-card{background:var(--card);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,.4);border-radius:var(--radius);padding:2rem;transition:all .35s;position:relative;overflow:hidden}
        .glass-card::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,var(--gold),#d4a017,var(--gold));opacity:0;transition:opacity .35s}
        .glass-card:hover{transform:translateY(-4px);box-shadow:0 16px 40px rgba(0,0,0,.06)}
        .glass-card:hover::before{opacity:1}
        .glass-card .icon{width:52px;height:52px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;margin-bottom:1.25rem}
        .glass-card h4{font-size:1.05rem;font-weight:700;color:var(--ink);margin-bottom:.4rem}
        .glass-card p{font-size:.85rem;color:var(--muted);line-height:1.7}

        .steps{display:grid;grid-template-columns:repeat(3,1fr);gap:2rem;text-align:center}
        .step-circle{width:64px;height:64px;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;font-weight:800;font-size:1.2rem;color:#fff;background:var(--gold);box-shadow:0 8px 25px rgba(197,150,12,.25)}
        .step h4{font-size:1.05rem;font-weight:700;color:var(--ink);margin-bottom:.3rem}
        .step p{font-size:.85rem;color:var(--muted)}
        @media(max-width:768px){.steps{grid-template-columns:1fr}}

        .testimonials{display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem}
        .testimonial{background:var(--glass);backdrop-filter:blur(16px);-webkit-backdrop-filter:blur(16px);border:1px solid rgba(255,255,255,.3);border-radius:var(--radius);padding:1.75rem}
        .testimonial .stars{color:var(--gold);font-size:.8rem;margin-bottom:.75rem;letter-spacing:2px}
        .testimonial .quote{font-style:italic;font-size:.9rem;color:var(--text);line-height:1.7;margin-bottom:1.25rem}
        .testimonial .author{display:flex;align-items:center;gap:.6rem;font-weight:600;font-size:.85rem;color:var(--ink)}
        .testimonial .av{width:42px;height:42px;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.8rem}
        @media(max-width:768px){.testimonials{grid-template-columns:1fr}}

        .cta{text-align:center;padding:5rem 2rem;background:var(--ink);border-radius:24px;color:#fff;position:relative;overflow:hidden}
        .cta::before{content:'';position:absolute;top:-30%;right:-20%;width:500px;height:500px;background:radial-gradient(circle,rgba(197,150,12,.15) 0%,transparent 60%);pointer-events:none}
        .cta h2{color:#fff;margin-bottom:.5rem}
        .cta p{color:rgba(255,255,255,.6);margin-bottom:1.75rem}

        .footer{border-top:1px solid var(--border);padding:3rem 0;font-size:.85rem;color:var(--muted)}
        .footer-grid{display:grid;grid-template-columns:2fr 1fr 1fr;gap:2rem}
        .footer h5{color:var(--ink);font-weight:700;margin-bottom:.75rem}
        .footer a{color:var(--muted);text-decoration:none;display:block;margin-bottom:.35rem}
        .footer a:hover{color:var(--gold)}
        .footer-bottom{border-top:1px solid var(--border);margin-top:2rem;padding-top:1.5rem;text-align:center;font-size:.78rem}
        @media(max-width:768px){.footer-grid{grid-template-columns:1fr}}
    </style>
</head>
<body>

<nav class="nav"><div class="container nav-inner">
    <a href="/" class="nav-logo"><img src="/assets/img/logo-v2.svg" alt="<?php echo SITE_NAME; ?>" style="height:36px"></a>
    <ul class="nav-links">
        <li><a href="#features">Features</a></li>
        <li><a href="#how">Process</a></li>
        <?php if(isLoggedIn()): ?>
            <li><a href="/dashboard/" class="btn btn-gold">Dashboard</a></li>
        <?php else: ?>
            <li><a href="/login.php">Sign In</a></li>
            <li><a href="/register.php" class="btn btn-gold">Get Started</a></li>
        <?php endif; ?>
    </ul>
</div></nav>

<section class="hero"><div class="container"><div class="hero-content">
    <div class="hero-badge"><i class="fas fa-gem"></i> Premium Investment Platform</div>
    <h1>Invest with <em>elegance</em> and <em>confidence</em></h1>
    <p>A refined investment experience. Glass-grade transparency, daily returns, and the security of a premium platform designed for discerning investors.</p>
    <div class="hero-actions">
        <a href="/register.php" class="btn btn-gold btn-lg">Begin Investing</a>
        <a href="#features" class="btn btn-glass btn-lg">Discover More</a>
    </div>
</div></div></section>

<div class="container"><div class="stats-row">
    <div class="stat-card"><div class="num">15K+</div><div class="lbl">Active Investors</div></div>
    <div class="stat-card"><div class="num">$3.2M</div><div class="lbl">Under Management</div></div>
    <div class="stat-card"><div class="num">$1.1M</div><div class="lbl">Returns Paid</div></div>
    <div class="stat-card"><div class="num">99.9%</div><div class="lbl">Platform Uptime</div></div>
</div></div>

<section class="section" id="features"><div class="container">
    <span class="section-tag">Platform Features</span>
    <h2 class="section-title">Crafted for the <span style="color:var(--gold)">discerning</span> investor</h2>
    <p class="section-sub">Every detail refined, every interaction polished. This is what premium investing feels like.</p>
    <div class="card-row" style="margin-top:2.5rem">
        <div class="glass-card"><div class="icon" style="background:var(--gold-lt);color:var(--gold)"><i class="fas fa-chart-line"></i></div><h4>Daily Returns</h4><p>Earnings credited every 24 hours with full transparency. Watch your portfolio flourish daily.</p></div>
        <div class="glass-card"><div class="icon" style="background:rgba(34,197,94,.12);color:#22c55e"><i class="fas fa-shield-check"></i></div><h4>Fortress Security</h4><p>256-bit encryption, cold storage, real-time monitoring. Your assets, protected beyond industry standards.</p></div>
        <div class="glass-card"><div class="icon" style="background:rgba(59,130,246,.12);color:#3b82f6"><i class="fas fa-bolt"></i></div><h4>Instant Withdrawals</h4><p>Request a withdrawal and receive funds within minutes. BTC, USDT, Ethereum — your choice.</p></div>
        <div class="glass-card"><div class="icon" style="background:rgba(139,92,246,.12);color:#8b5cf6"><i class="fas fa-users"></i></div><h4>Referral Network</h4><p>Earn commissions when your network grows. Elegant, automatic, rewarding.</p></div>
    </div>
</div></section>

<section class="section" style="background:rgba(255,255,255,.3)" id="how"><div class="container" style="text-align:center">
    <span class="section-tag">Your Journey</span>
    <h2 class="section-title" style="max-width:600px;margin:0 auto 1rem">Three steps to your first return</h2>
    <div class="steps" style="margin-top:3rem">
        <div class="step"><div class="step-circle">1</div><h4>Create Account</h4><p>Register in under a minute. Elegant onboarding, no friction.</p></div>
        <div class="step"><div class="step-circle">2</div><h4>Fund & Select</h4><p>Deposit crypto and choose from curated investment plans.</p></div>
        <div class="step"><div class="step-circle">3</div><h4>Earn & Withdraw</h4><p>Daily returns credited. Withdraw anytime with grace.</p></div>
    </div>
</div></section>

<section class="section" id="testimonials"><div class="container">
    <span class="section-tag">Testimonials</span>
    <h2 class="section-title">What our investors <span style="color:var(--gold)">experience</span></h2>
    <div class="testimonials" style="margin-top:2rem">
        <div class="testimonial"><div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div><p class="quote">"The most refined investment platform I've used. Every detail feels intentional. The returns speak for themselves."</p><div class="author"><div class="av" style="background:var(--gold)">AK</div> Alex K.</div></div>
        <div class="testimonial"><div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div><p class="quote">"Glass-grade transparency. I can see every transaction, every profit calculation. This is how investing should work."</p><div class="author"><div class="av" style="background:#22c55e">SN</div> Sarah N.</div></div>
        <div class="testimonial"><div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div><p class="quote">"The design alone makes me feel like I'm in good hands. Combined with the returns? Exceptional platform."</p><div class="author"><div class="av" style="background:#3b82f6">MT</div> Michael T.</div></div>
    </div>
</div></section>

<section class="section"><div class="container"><div class="cta">
    <h2>Begin your premium investment journey</h2>
    <p>Join the platform designed for those who appreciate quality, transparency, and returns.</p>
    <a href="/register.php" class="btn btn-gold btn-lg">Create Your Account</a>
</div></div></section>

<footer class="footer"><div class="container">
    <div class="footer-grid">
        <div><h5><?php echo SITE_NAME; ?></h5><p style="max-width:280px">A premium investment experience. Glass-grade clarity, fortress security, daily returns.</p></div>
        <div><h5>Navigate</h5><a href="/login.php">Sign In</a><a href="/register.php">Get Started</a></div>
        <div><h5>Connect</h5><a href="#">support@primeaxisinv.com</a><a href="#">FAQ</a></div>
    </div>
    <div class="footer-bottom">&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.</div>
</div></footer>
</body>
</html>
