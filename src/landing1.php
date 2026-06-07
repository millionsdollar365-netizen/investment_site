<?php require_once __DIR__ . '/includes/config.php'; require_once __DIR__ . '/includes/session.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> — Invest With Confidence</title>
    <meta name="description" content="Secure investment platform with daily returns. Warm, personal, designed for you.">
    <link rel="icon" type="image/svg+xml" href="/assets/img/favicon.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --cream: #faf8f5; --warm-white: #fefdfb; --ink: #1a1a1a; --soft-ink: #3d3d3d;
            --muted: #78716c; --gold: #b8860b; --gold-light: #f5e6c8;
            --sage: #7c9a7e; --rose: #d4a5a5; --sky: #a5b8d4;
            --border: #e7e0d5; --radius: 12px;
            --shadow: 0 1px 3px rgba(0,0,0,.04),0 1px 2px rgba(0,0,0,.06);
            --shadow-lg: 0 4px 16px rgba(0,0,0,.06);
        }
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'Inter',sans-serif;background:var(--cream);color:var(--soft-ink);line-height:1.65}
        .container{max-width:1120px;margin:0 auto;padding:0 1.5rem}
        h1,h2{font-family:'Libre Baskerville',serif;color:var(--ink)}
        .section{padding:5rem 0}
        .section-tag{display:inline-block;font-size:.72rem;font-weight:600;text-transform:uppercase;letter-spacing:.1em;color:var(--gold);margin-bottom:.75rem}
        .section-title{font-size:2rem;font-weight:700;margin-bottom:.75rem;line-height:1.3}
        .section-sub{font-size:1rem;color:var(--muted);max-width:540px}

        .nav{position:fixed;top:0;left:0;right:0;z-index:100;padding:1rem 0;background:rgba(250,248,245,.94);backdrop-filter:blur(12px);border-bottom:1px solid var(--border)}
        .nav-inner{display:flex;align-items:center;justify-content:space-between}
        .nav-logo{font-size:1.25rem;font-weight:800;color:var(--ink);text-decoration:none;display:flex;align-items:center;gap:.5rem}
        .nav-links{display:flex;align-items:center;gap:1.75rem;list-style:none}
        .nav-links a{color:var(--soft-ink);text-decoration:none;font-size:.85rem;font-weight:500;transition:color .2s}
        .nav-links a:hover{color:var(--gold)}
        .btn{border-radius:8px;font-weight:600;font-size:.85rem;text-decoration:none;transition:all .2s;cursor:pointer;border:none;padding:.55rem 1.3rem;display:inline-block}
        .btn-dark{background:var(--ink);color:#fff}
        .btn-dark:hover{background:var(--gold)}
        .btn-outline{border:1.5px solid var(--border);background:transparent;color:var(--soft-ink)}
        .btn-outline:hover{border-color:var(--gold);color:var(--gold)}
        .btn-lg{padding:.7rem 1.8rem;font-size:.9rem}

        .hero{padding:9rem 0 5rem;text-align:center;background:linear-gradient(180deg,var(--warm-white) 0%,var(--cream) 100%)}
        .hero h1{font-size:clamp(2rem,5vw,3.2rem);max-width:700px;margin:0 auto 1rem;line-height:1.2}
        .hero p{font-size:1.1rem;color:var(--muted);max-width:520px;margin:0 auto 2rem}
        .hero-actions{display:flex;gap:.75rem;justify-content:center;flex-wrap:wrap}
        .hero-badge{display:inline-flex;align-items:center;gap:.4rem;background:var(--gold-light);color:var(--gold);font-size:.78rem;font-weight:600;padding:.3rem .9rem;border-radius:50px;margin-bottom:1.5rem}

        .stats-row{display:grid;grid-template-columns:repeat(4,1fr);gap:1.5rem;margin-top:-2rem;position:relative;z-index:2}
        .stat-item{background:var(--warm-white);border:1px solid var(--border);border-radius:var(--radius);padding:1.5rem;text-align:center;box-shadow:var(--shadow)}
        .stat-item .num{font-family:'Libre Baskerville',serif;font-size:1.8rem;font-weight:700;color:var(--ink)}
        .stat-item .lbl{font-size:.72rem;color:var(--muted);margin-top:.2rem;text-transform:uppercase;letter-spacing:.06em}
        @media(max-width:768px){.stats-row{grid-template-columns:1fr 1fr}}

        .card-row{display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:1.5rem}
        .feature-card{background:var(--warm-white);border:1px solid var(--border);border-radius:var(--radius);padding:1.75rem;box-shadow:var(--shadow);transition:all .25s}
        .feature-card:hover{box-shadow:var(--shadow-lg);transform:translateY(-2px);border-color:#d4c9b0}
        .feature-card .icon{width:48px;height:48px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;margin-bottom:1rem}
        .feature-card h4{font-size:1rem;font-weight:700;color:var(--ink);margin-bottom:.35rem}
        .feature-card p{font-size:.85rem;color:var(--muted);line-height:1.6}

        .steps{display:grid;grid-template-columns:repeat(3,1fr);gap:2rem;text-align:center}
        .step .step-circle{width:56px;height:56px;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;font-weight:700;font-size:1.1rem;color:#fff}
        .step h4{font-size:1rem;font-weight:700;color:var(--ink);margin-bottom:.35rem}
        .step p{font-size:.85rem;color:var(--muted)}
        @media(max-width:768px){.steps{grid-template-columns:1fr}}

        .testimonial-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem}
        .testimonial{background:var(--warm-white);border:1px solid var(--border);border-radius:var(--radius);padding:1.5rem;box-shadow:var(--shadow)}
        .testimonial .quote{font-family:'Libre Baskerville',serif;font-style:italic;font-size:.9rem;color:var(--soft-ink);line-height:1.7;margin-bottom:1rem}
        .testimonial .author{display:flex;align-items:center;gap:.6rem;font-weight:600;font-size:.85rem;color:var(--ink)}
        .testimonial .avatar{width:40px;height:40px;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:.75rem;font-weight:700}
        @media(max-width:768px){.testimonial-grid{grid-template-columns:1fr}}

        .cta{text-align:center;padding:4rem 2rem;background:var(--ink);border-radius:var(--radius);color:#fff}
        .cta h2{color:#fff;margin-bottom:.5rem}
        .cta p{color:rgba(255,255,255,.7);margin-bottom:1.5rem}

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
    <a href="/" class="nav-logo"><img src="/assets/img/logo.svg" alt="<?php echo SITE_NAME; ?>" style="height:34px"></a>
    <ul class="nav-links">
        <li><a href="#features">Benefits</a></li>
        <li><a href="#how">How It Works</a></li>
        <?php if(isLoggedIn()): ?>
            <li><a href="/dashboard/" class="btn btn-dark btn-lg">Dashboard</a></li>
        <?php else: ?>
            <li><a href="/login.php">Sign In</a></li>
            <li><a href="/register.php" class="btn btn-dark btn-lg">Get Started</a></li>
        <?php endif; ?>
    </ul>
</div></nav>

<section class="hero"><div class="container">
    <div class="hero-badge"><i class="fas fa-shield"></i> Trusted by investors worldwide</div>
    <h1>Grow your wealth with <span style="color:var(--gold)">confidence</span> and <span style="color:var(--sage)">clarity</span></h1>
    <p>A calm, transparent approach to investing. Earn daily returns on carefully structured plans — no noise, just growth.</p>
    <div class="hero-actions">
        <a href="/register.php" class="btn btn-dark btn-lg">Start Investing</a>
        <a href="#features" class="btn btn-outline btn-lg">How It Works</a>
    </div>
</div></section>

<div class="container"><div class="stats-row">
    <div class="stat-item"><div class="num">15K+</div><div class="lbl">Active Investors</div></div>
    <div class="stat-item"><div class="num">$3.2M</div><div class="lbl">Total Invested</div></div>
    <div class="stat-item"><div class="num">$1.1M</div><div class="lbl">Profits Paid</div></div>
    <div class="stat-item"><div class="num">99.9%</div><div class="lbl">Uptime</div></div>
</div></div>

<section class="section" id="features"><div class="container">
    <span class="section-tag">Why Invest With Us</span>
    <h2 class="section-title">Designed for thoughtful investors</h2>
    <p class="section-sub">A platform that prioritizes clarity, security, and consistent returns.</p>
    <div class="card-row" style="margin-top:2.5rem">
        <div class="feature-card"><div class="icon" style="background:#f5e6c8;color:var(--gold)"><i class="fas fa-chart-line"></i></div><h4>Daily Returns</h4><p>Earnings credited every day. Watch your portfolio grow with complete transparency.</p></div>
        <div class="feature-card"><div class="icon" style="background:#e8f0e8;color:var(--sage)"><i class="fas fa-shield"></i></div><h4>Bank-Grade Security</h4><p>256-bit encryption and cold storage. Your funds are protected at every layer.</p></div>
        <div class="feature-card"><div class="icon" style="background:#f5e8e8;color:var(--rose)"><i class="fas fa-bolt"></i></div><h4>Fast Withdrawals</h4><p>Request a withdrawal and receive funds quickly. BTC, USDT, and Ethereum supported.</p></div>
        <div class="feature-card"><div class="icon" style="background:#e8ecf5;color:var(--sky)"><i class="fas fa-users"></i></div><h4>Referral Rewards</h4><p>Earn commissions when you invite friends. Build your network, grow together.</p></div>
    </div>
</div></section>

<section class="section" style="background:var(--warm-white)" id="how"><div class="container" style="text-align:center">
    <span class="section-tag">Getting Started</span>
    <h2 class="section-title" style="max-width:600px;margin:0 auto 1rem">Three simple steps to your first return</h2>
    <div class="steps" style="margin-top:2.5rem">
        <div class="step"><div class="step-circle" style="background:var(--ink)">1</div><h4>Create Your Account</h4><p>Register in under a minute. No paperwork, no complexity.</p></div>
        <div class="step"><div class="step-circle" style="background:var(--gold)">2</div><h4>Choose a Plan & Deposit</h4><p>Browse investment plans, pick one that fits, and fund via crypto.</p></div>
        <div class="step"><div class="step-circle" style="background:var(--sage)">3</div><h4>Earn Daily</h4><p>Returns credited every day. Withdraw anytime. Simple and transparent.</p></div>
    </div>
</div></section>

<section class="section" id="testimonials"><div class="container">
    <span class="section-tag">Testimonials</span>
    <h2 class="section-title">What our investors say</h2>
    <div class="testimonial-grid" style="margin-top:2rem">
        <div class="testimonial"><p class="quote">"The clarity of this platform is what drew me in. I can see every transaction, every profit. No hidden fees."</p><div class="author"><div class="avatar" style="background:var(--gold)">AK</div> Alex K.</div></div>
        <div class="testimonial"><p class="quote">"I started small and watched my returns compound. The daily payout system is consistent and withdrawals are fast."</p><div class="author"><div class="avatar" style="background:var(--sage)">SN</div> Sarah N.</div></div>
        <div class="testimonial"><p class="quote">"Warm, personal support. Every question I had was answered within minutes. This is what investing should feel like."</p><div class="author"><div class="avatar" style="background:var(--rose)">MT</div> Michael T.</div></div>
    </div>
</div></section>

<section class="section"><div class="container"><div class="cta">
    <h2>Ready to start earning daily returns?</h2>
    <p>Join thousands of thoughtful investors growing their wealth with clarity and confidence.</p>
    <a href="/register.php" class="btn btn-dark btn-lg">Create Your Free Account</a>
</div></div></section>

<footer class="footer"><div class="container">
    <div class="footer-grid">
        <div><h5><?php echo SITE_NAME; ?></h5><p style="max-width:280px">A calm, transparent investment platform for thoughtful investors.</p></div>
        <div><h5>Links</h5><a href="/login.php">Sign In</a><a href="/register.php">Register</a></div>
        <div><h5>Support</h5><a href="#">support@primeaxisinv.com</a><a href="#">FAQ</a></div>
    </div>
    <div class="footer-bottom">&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.</div>
</div></footer>

</body>
</html>
