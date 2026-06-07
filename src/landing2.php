<?php require_once __DIR__ . '/includes/config.php'; require_once __DIR__ . '/includes/session.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> — The Future of Investing</title>
    <meta name="description" content="Next-generation investment platform. Glass design, AI-optimized returns, enterprise-grade security.">
    <link rel="icon" type="image/svg+xml" href="/assets/img/favicon.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root{--bg:#06070e;--bg2:#0d0f1a;--glass:rgba(255,255,255,.04);--glass2:rgba(255,255,255,.07);--text:#e8eaed;--muted:#8b8fa6;--accent:#6366f1;--accent2:#818cf8;--gold:#f59e0b;--border:rgba(255,255,255,.06);--radius:18px;}
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'Inter',sans-serif;background:var(--bg);color:var(--text);line-height:1.65;overflow-x:hidden}
        .container{max-width:1150px;margin:0 auto;padding:0 2rem}
        h1,h2{font-family:'Space Grotesk',sans-serif;font-weight:700}
        .section{padding:6rem 0;position:relative}

        .nav{position:fixed;top:0;left:0;right:0;z-index:100;padding:.9rem 0;background:rgba(6,7,14,.85);backdrop-filter:blur(24px);-webkit-backdrop-filter:blur(24px);border-bottom:1px solid var(--border)}
        .nav-inner{display:flex;align-items:center;justify-content:space-between}
        .nav-logo{font-size:1.25rem;font-weight:800;color:var(--text);text-decoration:none;display:flex;align-items:center;gap:.5rem;font-family:'Space Grotesk',sans-serif}
        .nav-links{display:flex;align-items:center;gap:1.5rem;list-style:none}
        .nav-links a{color:var(--muted);text-decoration:none;font-size:.84rem;font-weight:500;transition:color .2s}
        .nav-links a:hover{color:var(--text)}
        .btn{display:inline-block;padding:.55rem 1.4rem;border-radius:10px;font-weight:600;font-size:.84rem;text-decoration:none;transition:all .3s;cursor:pointer;border:none;font-family:'Inter',sans-serif}
        .btn-primary{background:linear-gradient(135deg,var(--accent),var(--accent2));color:#fff;box-shadow:0 4px 18px rgba(99,102,241,.3)}
        .btn-primary:hover{transform:translateY(-2px);box-shadow:0 8px 30px rgba(99,102,241,.45)}
        .btn-ghost{background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);color:var(--text)}
        .btn-ghost:hover{background:rgba(255,255,255,.1);border-color:rgba(255,255,255,.2)}
        .btn-lg{padding:.75rem 2rem;font-size:.9rem}

        .hero{min-height:90vh;display:flex;align-items:center;position:relative;overflow:hidden}
        .hero::before{content:'';position:absolute;top:-30%;left:50%;transform:translateX(-50%);width:900px;height:900px;background:radial-gradient(circle,rgba(99,102,241,.15) 0%,transparent 55%);pointer-events:none}
        .hero::after{content:'';position:absolute;bottom:-20%;right:-10%;width:600px;height:600px;background:radial-gradient(circle,rgba(245,158,11,.08) 0%,transparent 55%);pointer-events:none}
        .hero-content{position:relative;z-index:1;max-width:650px}
        .hero-badge{display:inline-flex;align-items:center;gap:.5rem;background:rgba(99,102,241,.12);border:1px solid rgba(99,102,241,.2);color:var(--accent2);font-size:.76rem;font-weight:600;padding:.3rem .9rem;border-radius:50px;margin-bottom:1.5rem}
        .hero h1{font-size:clamp(2.5rem,6vw,4rem);font-weight:800;line-height:1.05;margin-bottom:1.25rem;letter-spacing:-.03em}
        .hero h1 span{background:linear-gradient(135deg,var(--accent2),var(--gold));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
        .hero p{font-size:1.05rem;color:var(--muted);max-width:480px;margin-bottom:2rem}
        .hero-actions{display:flex;gap:.85rem;flex-wrap:wrap}

        .stats-row{display:grid;grid-template-columns:repeat(4,1fr);gap:1.25rem;margin-top:-3rem;position:relative;z-index:2}
        .stat-card{background:var(--glass);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border:1px solid var(--border);border-radius:var(--radius);padding:1.5rem;text-align:center;transition:all .3s}
        .stat-card:hover{background:var(--glass2);border-color:rgba(99,102,241,.2)}
        .stat-card .num{font-family:'Space Grotesk',sans-serif;font-size:2rem;font-weight:800;background:linear-gradient(135deg,#fff,var(--accent2));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
        .stat-card .lbl{font-size:.7rem;color:var(--muted);margin-top:.25rem;text-transform:uppercase;letter-spacing:.08em}
        @media(max-width:768px){.stats-row{grid-template-columns:1fr 1fr}}

        .section-tag{display:inline-block;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.14em;color:var(--accent2);margin-bottom:.75rem}
        .section-title{font-size:2.2rem;margin-bottom:1rem;line-height:1.25}
        .section-sub{font-size:1rem;color:var(--muted);max-width:540px}

        .card-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(265px,1fr));gap:1.5rem}
        .tech-card{background:var(--glass);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border:1px solid var(--border);border-radius:var(--radius);padding:2rem;transition:all .35s;position:relative;overflow:hidden}
        .tech-card::after{content:'';position:absolute;inset:0;background:radial-gradient(circle at 50% 0%,rgba(99,102,241,.08) 0%,transparent 70%);opacity:0;transition:opacity .35s}
        .tech-card:hover{transform:translateY(-4px);border-color:rgba(99,102,241,.25);box-shadow:0 16px 40px rgba(0,0,0,.3)}
        .tech-card:hover::after{opacity:1}
        .tech-card .icon{width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;margin-bottom:1.25rem;background:rgba(99,102,241,.12);color:var(--accent2)}
        .tech-card h4{font-size:1rem;font-weight:700;color:var(--text);margin-bottom:.35rem;position:relative;z-index:1}
        .tech-card p{font-size:.84rem;color:var(--muted);line-height:1.7;position:relative;z-index:1}

        .process{display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem}
        .process-card{background:var(--glass);backdrop-filter:blur(16px);-webkit-backdrop-filter:blur(16px);border:1px solid var(--border);border-radius:var(--radius);padding:2rem;text-align:center;position:relative}
        .process-card .step-num{position:absolute;top:-18px;left:50%;transform:translateX(-50%);width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--accent2));display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.85rem;color:#fff;box-shadow:0 4px 15px rgba(99,102,241,.4)}
        .process-card h4{font-size:.95rem;font-weight:700;color:var(--text);margin-bottom:.3rem;margin-top:.5rem}
        .process-card p{font-size:.84rem;color:var(--muted)}
        @media(max-width:768px){.process{grid-template-columns:1fr}}

        .testimonials{display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem}
        .testimonial{background:var(--glass);backdrop-filter:blur(16px);-webkit-backdrop-filter:blur(16px);border:1px solid var(--border);border-radius:var(--radius);padding:1.75rem}
        .testimonial .stars{color:var(--gold);font-size:.75rem;margin-bottom:.75rem;letter-spacing:2px}
        .testimonial p{font-size:.88rem;color:var(--muted);line-height:1.7;margin-bottom:1.25rem;font-style:italic}
        .testimonial .author{display:flex;align-items:center;gap:.6rem;font-weight:600;font-size:.84rem;color:var(--text)}
        .testimonial .av{width:40px;height:40px;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.78rem}
        @media(max-width:768px){.testimonials{grid-template-columns:1fr}}

        .cta{text-align:center;padding:5rem 2rem;background:linear-gradient(135deg,rgba(99,102,241,.1),rgba(245,158,11,.05));border:1px solid rgba(99,102,241,.15);border-radius:24px;position:relative;overflow:hidden}
        .cta h2{margin-bottom:.5rem}
        .cta p{color:var(--muted);margin-bottom:1.75rem}

        .footer{border-top:1px solid var(--border);padding:3rem 0;font-size:.84rem;color:var(--muted)}
        .footer-grid{display:grid;grid-template-columns:2fr 1fr 1fr;gap:2rem}
        .footer h5{color:var(--text);font-weight:700;margin-bottom:.75rem}
        .footer a{color:var(--muted);text-decoration:none;display:block;margin-bottom:.35rem}
        .footer a:hover{color:var(--accent2)}
        .footer-bottom{border-top:1px solid var(--border);margin-top:2rem;padding-top:1.5rem;text-align:center;font-size:.76rem}
        @media(max-width:768px){.footer-grid{grid-template-columns:1fr}}
    </style>
</head>
<body>

<nav class="nav"><div class="container nav-inner">
    <a href="/" class="nav-logo"><img src="/assets/img/logo.svg" alt="<?php echo SITE_NAME; ?>" style="height:34px;filter:brightness(10)"></a>
    <ul class="nav-links">
        <li><a href="#features">Features</a></li>
        <li><a href="#how">Process</a></li>
        <?php if(isLoggedIn()): ?>
            <li><a href="/dashboard/" class="btn btn-primary">Dashboard</a></li>
        <?php else: ?>
            <li><a href="/login.php">Sign In</a></li>
            <li><a href="/register.php" class="btn btn-primary">Get Started</a></li>
        <?php endif; ?>
    </ul>
</div></nav>

<section class="hero"><div class="container"><div class="hero-content">
    <div class="hero-badge"><i class="fas fa-cube"></i> Next-Generation Platform</div>
    <h1>The <span>future</span> of investment is here</h1>
    <p>Glass-grade transparency meets AI-optimized returns. A platform engineered for the next generation of investors.</p>
    <div class="hero-actions">
        <a href="/register.php" class="btn btn-primary btn-lg">Start Now</a>
        <a href="#features" class="btn btn-ghost btn-lg">Explore</a>
    </div>
</div></div></section>

<div class="container"><div class="stats-row">
    <div class="stat-card"><div class="num">15K+</div><div class="lbl">Active Investors</div></div>
    <div class="stat-card"><div class="num">$3.2M</div><div class="lbl">Under Management</div></div>
    <div class="stat-card"><div class="num">$1.1M</div><div class="lbl">Returns Paid</div></div>
    <div class="stat-card"><div class="num">99.99%</div><div class="lbl">Platform Uptime</div></div>
</div></div>

<section class="section" id="features"><div class="container">
    <span class="section-tag">Capabilities</span>
    <h2 class="section-title">Engineered for <span class="section-tag" style="display:inline;background:rgba(99,102,241,.12);color:var(--accent2);letter-spacing:0;font-size:inherit;margin:0;padding:.1rem .5rem;border-radius:6px">performance</span></h2>
    <p class="section-sub">Every layer optimized. From security to returns, built with precision.</p>
    <div class="card-grid" style="margin-top:2.5rem">
        <div class="tech-card"><div class="icon"><i class="fas fa-brain"></i></div><h4>Optimized Plans</h4><p>Investment plans calibrated for maximum returns within defined risk parameters.</p></div>
        <div class="tech-card"><div class="icon"><i class="fas fa-fingerprint"></i></div><h4>Zero-Trust Security</h4><p>Cold storage, multi-sig wallets, real-time threat detection. Beyond bank-grade.</p></div>
        <div class="tech-card"><div class="icon"><i class="fas fa-rocket"></i></div><h4>Instant Execution</h4><p>Deposits processed in seconds. Withdrawals confirmed in minutes. No delays, ever.</p></div>
        <div class="tech-card"><div class="icon"><i class="fas fa-chart-pie"></i></div><h4>Live Analytics</h4><p>Real-time dashboards. Track every cent, every profit, every transaction as it happens.</p></div>
        <div class="tech-card"><div class="icon"><i class="fas fa-gem"></i></div><h4>Premium Support</h4><p>Dedicated support team. Average response time under 3 minutes. 24/7 availability.</p></div>
        <div class="tech-card"><div class="icon"><i class="fas fa-globe"></i></div><h4>Multi-Chain</h4><p>BTC, USDT, Ethereum. More chains coming. Your assets, your choice of network.</p></div>
    </div>
</div></section>

<section class="section" id="how"><div class="container" style="text-align:center">
    <span class="section-tag">Your Journey</span>
    <h2 class="section-title" style="max-width:600px;margin:0 auto 1rem">Three steps. Infinite potential.</h2>
    <div class="process" style="margin-top:3rem">
        <div class="process-card"><div class="step-num">1</div><h4>Onboard</h4><p>Create your account. Seamless registration with enterprise-grade identity protection.</p></div>
        <div class="process-card"><div class="step-num">2</div><h4>Allocate</h4><p>Deposit crypto. Select from optimized plans. Your capital, intelligently deployed.</p></div>
        <div class="process-card"><div class="step-num">3</div><h4>Compound</h4><p>Daily returns credited automatically. Reinvest or withdraw. The choice is yours.</p></div>
    </div>
</div></section>

<section class="section" id="testimonials"><div class="container">
    <span class="section-tag">Social Proof</span>
    <h2 class="section-title">Trusted by <span style="background:linear-gradient(135deg,var(--accent2),var(--gold));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">thousands</span></h2>
    <div class="testimonials" style="margin-top:2rem">
        <div class="testimonial"><div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div><p>"The most technologically advanced investment platform I've seen. The real-time analytics are game-changing."</p><div class="author"><div class="av" style="background:linear-gradient(135deg,#6366f1,#818cf8)">AK</div> Alex K.</div></div>
        <div class="testimonial"><div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div><p>"Zero-trust security was the reason I joined. The consistent returns are why I stayed. Exceptional execution."</p><div class="author"><div class="av" style="background:linear-gradient(135deg,#f59e0b,#d97706)">SN</div> Sarah N.</div></div>
        <div class="testimonial"><div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div><p>"Withdrew $25K in under 3 minutes. The execution speed on this platform is unmatched. Highly recommended."</p><div class="author"><div class="av" style="background:linear-gradient(135deg,#22c55e,#16a34a)">MT</div> Michael T.</div></div>
    </div>
</div></section>

<section class="section"><div class="container"><div class="cta">
    <h2>Ready for the future of investing?</h2>
    <p>Join thousands who have already made the switch to a smarter, faster, more transparent platform.</p>
    <a href="/register.php" class="btn btn-primary btn-lg">Get Started Free</a>
</div></div></section>

<footer class="footer"><div class="container">
    <div class="footer-grid">
        <div><h5><?php echo SITE_NAME; ?></h5><p style="max-width:280px">Next-generation investment platform. Glass-grade transparency, AI-optimized returns.</p></div>
        <div><h5>Navigate</h5><a href="/login.php">Sign In</a><a href="/register.php">Get Started</a></div>
        <div><h5>Connect</h5><a href="#">support@primeaxisinv.com</a><a href="#">Documentation</a></div>
    </div>
    <div class="footer-bottom">&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.</div>
</div></footer>
</body>
</html>
