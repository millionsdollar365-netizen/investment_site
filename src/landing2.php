<?php require_once __DIR__ . '/includes/config.php'; require_once __DIR__ . '/includes/session.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> — Structured Investment Platform</title>
    <meta name="description" content="Professional investment platform. Structured plans, transparent returns, enterprise-grade security.">
    <link rel="icon" type="image/svg+xml" href="/assets/img/favicon.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --bg: #f8fafc; --white: #ffffff; --ink: #0f172a; --text: #334155;
            --muted: #64748b; --accent: #3b82f6; --accent-dark: #2563eb;
            --gold: #f59e0b; --gold-dark: #d97706;
            --border: #e2e8f0; --radius: 10px;
            --shadow-sm: 0 1px 2px rgba(0,0,0,.05);
            --shadow: 0 1px 3px rgba(0,0,0,.06),0 1px 2px rgba(0,0,0,.04);
            --shadow-md: 0 4px 6px rgba(0,0,0,.05),0 2px 4px rgba(0,0,0,.04);
        }
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'Inter',sans-serif;background:var(--bg);color:var(--text);line-height:1.6}
        .container{max-width:1120px;margin:0 auto;padding:0 1.5rem}

        .nav{position:fixed;top:0;left:0;right:0;z-index:100;padding:1rem 0;background:rgba(255,255,255,.95);backdrop-filter:blur(12px);border-bottom:1px solid var(--border)}
        .nav-inner{display:flex;align-items:center;justify-content:space-between}
        .nav-logo{font-size:1.2rem;font-weight:800;color:var(--ink);text-decoration:none;display:flex;align-items:center;gap:.5rem}
        .nav-links{display:flex;align-items:center;gap:1.5rem;list-style:none}
        .nav-links a{color:var(--text);text-decoration:none;font-size:.85rem;font-weight:500;transition:color .15s}
        .nav-links a:hover{color:var(--accent)}
        .btn{padding:.5rem 1.2rem;border-radius:6px;font-weight:600;font-size:.85rem;text-decoration:none;transition:all .2s;cursor:pointer;border:none;display:inline-block}
        .btn-primary{background:var(--accent);color:#fff}
        .btn-primary:hover{background:var(--accent-dark);transform:translateY(-1px);box-shadow:0 4px 12px rgba(59,130,246,.25)}
        .btn-gold{background:var(--gold);color:#fff}
        .btn-gold:hover{background:var(--gold-dark);transform:translateY(-1px)}
        .btn-outline{border:2px solid var(--border);background:transparent;color:var(--text)}
        .btn-outline:hover{border-color:var(--accent);color:var(--accent)}
        .btn-lg{padding:.7rem 1.8rem;font-size:.92rem}

        .hero{padding:8rem 0 4rem;display:grid;grid-template-columns:1fr 1fr;gap:3rem;align-items:center}
        .hero h1{font-size:clamp(2rem,4vw,3rem);font-weight:900;color:var(--ink);line-height:1.15;margin-bottom:1rem}
        .hero h1 span{color:var(--accent)}
        .hero p{font-size:1.05rem;color:var(--muted);margin-bottom:2rem;max-width:480px}
        .hero-actions{display:flex;gap:.75rem;flex-wrap:wrap}
        .hero-visual{background:linear-gradient(135deg,var(--accent),#7c3aed);border-radius:20px;padding:2rem;color:#fff;text-align:center;box-shadow:0 20px 40px rgba(59,130,246,.15)}
        .hero-visual .big-stat{font-size:3rem;font-weight:900}
        @media(max-width:768px){.hero{grid-template-columns:1fr;text-align:center}.hero p{margin:0 auto 2rem}.hero-visual{display:none}}

        .section{padding:4rem 0}
        .section-header{text-align:center;margin-bottom:3rem}
        .section-header .tag{display:inline-block;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--accent);background:rgba(59,130,246,.08);padding:.25rem .85rem;border-radius:50px;margin-bottom:.75rem}
        .section-header h2{font-size:1.9rem;font-weight:800;color:var(--ink);margin-bottom:.5rem}
        .section-header p{color:var(--muted);max-width:500px;margin:0 auto}

        .card-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:1.25rem}
        .card{background:var(--white);border:1px solid var(--border);border-radius:var(--radius);padding:1.5rem;box-shadow:var(--shadow);transition:all .2s}
        .card:hover{box-shadow:var(--shadow-md);border-color:#cbd5e1}
        .card .card-icon{width:44px;height:44px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;margin-bottom:1rem;background:rgba(59,130,246,.08);color:var(--accent)}
        .card h4{font-size:.95rem;font-weight:700;color:var(--ink);margin-bottom:.3rem}
        .card p{font-size:.84rem;color:var(--muted);line-height:1.6}

        .steps-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem}
        .step-block{background:var(--white);border:1px solid var(--border);border-radius:var(--radius);padding:1.5rem;box-shadow:var(--shadow);position:relative;padding-top:2.5rem}
        .step-block .step-num{position:absolute;top:-16px;left:1.5rem;width:32px;height:32px;border-radius:50%;background:var(--accent);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.85rem}
        .step-block h4{font-size:.95rem;font-weight:700;color:var(--ink);margin-bottom:.3rem}
        .step-block p{font-size:.84rem;color:var(--muted)}
        @media(max-width:768px){.steps-grid{grid-template-columns:1fr}}

        .profiles-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:1.25rem}
        .profile-card{background:var(--white);border:1px solid var(--border);border-radius:var(--radius);padding:1.25rem;text-align:center;box-shadow:var(--shadow);transition:all .2s}
        .profile-card:hover{box-shadow:var(--shadow-md)}
        .profile-card .av{width:56px;height:56px;border-radius:50%;margin:0 auto .75rem;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:1.1rem}
        .profile-card .name{font-size:.9rem;font-weight:700;color:var(--ink)}
        .profile-card .role{font-size:.75rem;color:var(--muted)}
        @media(max-width:768px){.profiles-grid{grid-template-columns:1fr 1fr}}

        .cta{background:linear-gradient(135deg,var(--ink),#1e293b);border-radius:16px;padding:3.5rem 2rem;text-align:center;color:#fff}
        .cta h2{font-size:1.8rem;font-weight:800;color:#fff;margin-bottom:.5rem}
        .cta p{color:rgba(255,255,255,.7);margin-bottom:1.5rem}

        .footer{border-top:1px solid var(--border);padding:3rem 0;font-size:.85rem;color:var(--muted)}
        .footer-grid{display:grid;grid-template-columns:2fr 1fr 1fr;gap:2rem}
        .footer h5{color:var(--ink);font-weight:700;margin-bottom:.75rem}
        .footer a{color:var(--muted);text-decoration:none;display:block;margin-bottom:.35rem}
        .footer a:hover{color:var(--accent)}
        .footer-bottom{border-top:1px solid var(--border);margin-top:2rem;padding-top:1.5rem;text-align:center;font-size:.78rem}
        @media(max-width:768px){.footer-grid{grid-template-columns:1fr}}
    </style>
</head>
<body>

<nav class="nav"><div class="container nav-inner">
    <a href="/" class="nav-logo"><img src="/assets/img/logo.svg" alt="<?php echo SITE_NAME; ?>" style="height:32px"></a>
    <ul class="nav-links">
        <li><a href="#features">Features</a></li>
        <li><a href="#how">Process</a></li>
        <li><a href="#team">Team</a></li>
        <?php if(isLoggedIn()): ?>
            <li><a href="/dashboard/" class="btn btn-primary">Dashboard</a></li>
        <?php else: ?>
            <li><a href="/login.php">Sign In</a></li>
            <li><a href="/register.php" class="btn btn-primary">Get Started</a></li>
        <?php endif; ?>
    </ul>
</div></nav>

<section class="hero"><div class="container" style="display:grid;grid-template-columns:1fr 1fr;gap:3rem;align-items:center">
    <div>
        <h1>Invest with <span>structure</span> and <span style="color:var(--gold)">confidence</span></h1>
        <p>A professional investment platform built for serious investors. Structured plans, transparent returns, and enterprise-grade security.</p>
        <div class="hero-actions">
            <a href="/register.php" class="btn btn-primary btn-lg">Start Investing</a>
            <a href="#features" class="btn btn-outline btn-lg">Explore Features</a>
        </div>
    </div>
    <div class="hero-visual"><div class="big-stat">4.8★</div><div style="opacity:.85;margin-top:.5rem">TrustPilot Rating</div><div style="margin-top:1.5rem;display:flex;gap:1rem;justify-content:center"><div><div style="font-size:1.5rem;font-weight:800">15K+</div><div style="font-size:.7rem;opacity:.7">Investors</div></div><div><div style="font-size:1.5rem;font-weight:800">$3.2M</div><div style="font-size:.7rem;opacity:.7">Invested</div></div><div><div style="font-size:1.5rem;font-weight:800">$1.1M</div><div style="font-size:.7rem;opacity:.7">Paid Out</div></div></div></div>
</div></section>

<section class="section" id="features"><div class="container">
    <div class="section-header"><span class="tag">Platform Features</span><h2>Built for serious investors</h2><p>Every feature designed with security, transparency, and performance in mind.</p></div>
    <div class="card-grid">
        <div class="card"><div class="card-icon"><i class="fas fa-chart-line"></i></div><h4>Structured Plans</h4><p>Clearly defined investment plans with transparent ROI, duration, and min/max amounts.</p></div>
        <div class="card"><div class="card-icon"><i class="fas fa-clock"></i></div><h4>Daily Payouts</h4><p>Returns credited every 24 hours. Track every transaction with complete audit trail.</p></div>
        <div class="card"><div class="card-icon"><i class="fas fa-lock"></i></div><h4>Enterprise Security</h4><p>256-bit encryption, cold storage, and real-time monitoring protecting your assets 24/7.</p></div>
        <div class="card"><div class="card-icon"><i class="fas fa-wallet"></i></div><h4>Multi-Crypto</h4><p>Deposit and withdraw via BTC, USDT, or Ethereum. Multiple wallet support per user.</p></div>
        <div class="card"><div class="card-icon"><i class="fas fa-users"></i></div><h4>Team Referrals</h4><p>Earn commission on every active referral. Track your network growth in real-time.</p></div>
        <div class="card"><div class="card-icon"><i class="fas fa-headset"></i></div><h4>24/7 Support</h4><p>Dedicated support team available around the clock. Average response time under 5 minutes.</p></div>
    </div>
</div></section>

<section class="section" style="background:#fff" id="how"><div class="container">
    <div class="section-header"><span class="tag">How It Works</span><h2>A clear path to returns</h2></div>
    <div class="steps-grid">
        <div class="step-block"><div class="step-num">1</div><h4>Register & Verify</h4><p>Create your account in seconds. Set up your wallet addresses and you're ready to go.</p></div>
        <div class="step-block"><div class="step-num">2</div><h4>Fund & Invest</h4><p>Deposit crypto and choose from structured investment plans with clear terms and returns.</p></div>
        <div class="step-block"><div class="step-num">3</div><h4>Track & Withdraw</h4><p>Monitor daily returns on your dashboard. Withdraw profits or principal anytime.</p></div>
    </div>
</div></section>

<section class="section" id="team"><div class="container">
    <div class="section-header"><span class="tag">Leadership</span><h2>The team behind the platform</h2></div>
    <div class="profiles-grid">
        <div class="profile-card"><div class="av" style="background:linear-gradient(135deg,#3b82f6,#2563eb)">JD</div><div class="name">James D.</div><div class="role">CEO & Founder</div></div>
        <div class="profile-card"><div class="av" style="background:linear-gradient(135deg,#7c3aed,#6d28d9)">MK</div><div class="name">Maria K.</div><div class="role">Chief Investment Officer</div></div>
        <div class="profile-card"><div class="av" style="background:linear-gradient(135deg,#f59e0b,#d97706)">RL</div><div class="name">Robert L.</div><div class="role">Head of Security</div></div>
        <div class="profile-card"><div class="av" style="background:linear-gradient(135deg,#22c55e,#16a34a)">SN</div><div class="name">Sarah N.</div><div class="role">Customer Success</div></div>
    </div>
</div></section>

<section class="section"><div class="container"><div class="cta">
    <h2>Ready to invest with structure and confidence?</h2>
    <p>Join thousands of investors on the most transparent investment platform available.</p>
    <a href="/register.php" class="btn btn-gold btn-lg">Create Free Account</a>
</div></div></section>

<footer class="footer"><div class="container">
    <div class="footer-grid">
        <div><h5><?php echo SITE_NAME; ?></h5><p style="max-width:280px">Professional investment platform with structured plans and transparent returns.</p></div>
        <div><h5>Links</h5><a href="/login.php">Sign In</a><a href="/register.php">Get Started</a></div>
        <div><h5>Support</h5><a href="#">support@primeaxisinv.com</a><a href="#">Documentation</a></div>
    </div>
    <div class="footer-bottom">&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.</div>
</div></footer>

</body>
</html>
