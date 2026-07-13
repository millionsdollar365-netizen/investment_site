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
    <title><?php echo htmlspecialchars($brand); ?> — Build Wealth With Clarity</title>
    <meta name="description" content="PrimeAxis is a modern investment platform for guided portfolios, crypto funding, daily ROI tracking, and transparent financial operations.">
    <link rel="icon" type="image/svg+xml" href="/assets/img/favicon.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --ink: #172026; --body: #4e5d63; --soft: #eef7f7; --paper: #fbfdfc;
            --panel: #ffffff; --line: #d8e5e4; --teal: #147c78; --cyan: #27bfd0;
            --coral: #ec615b; --lime: #a5c94f; --plum: #5a446e; --graphite: #263238;
            --shadow: 0 20px 55px rgba(18, 42, 45, .12);
        }
        *{box-sizing:border-box}html{scroll-behavior:smooth}
        body{margin:0;background:var(--paper);color:var(--ink);font-family:Inter,system-ui,sans-serif;line-height:1.55}
        body.locked{overflow:hidden}
        a{color:inherit;text-decoration:none}img{display:block;max-width:100%}
        button,input{font:inherit}
        :focus-visible{outline:3px solid rgba(39,191,208,.45);outline-offset:3px}
        .container{width:min(100% - 32px, 1200px);margin:0 auto}
        h1,h2,h3{margin:0;font-family:Manrope,Inter,sans-serif;line-height:1.04}
        h1{font-size:clamp(44px,7vw,88px);font-weight:800;max-width:860px}
        h2{font-size:clamp(32px,4.8vw,58px);font-weight:800}
        h3{font-size:20px;font-weight:800}
        p{margin:0;color:var(--body)}
        .kicker{display:inline-flex;align-items:center;gap:9px;color:var(--teal);font-size:12px;font-weight:900;text-transform:uppercase;letter-spacing:.1em}
        .kicker::before{content:"";width:8px;height:8px;border-radius:50%;background:var(--coral)}
        .btn{display:inline-flex;align-items:center;justify-content:center;gap:10px;min-height:48px;border:1px solid transparent;border-radius:8px;padding:12px 20px;font-weight:800;cursor:pointer;transition:transform .2s ease,box-shadow .2s ease}
        .btn:hover{transform:translateY(-2px)}
        .btn-primary{background:var(--teal);color:#fff;box-shadow:0 16px 32px rgba(20,124,120,.22)}
        .btn-light{background:#fff;color:var(--ink);border-color:rgba(23,32,38,.12)}
        .btn-dark{background:var(--ink);color:#fff}
        .reveal{opacity:0;transform:translateY(28px);transition:opacity .65s ease,transform .65s ease}
        .reveal.visible{opacity:1;transform:translateY(0)}

        .nav{position:fixed;inset:0 0 auto;z-index:60;border-bottom:1px solid rgba(255,255,255,.28);background:rgba(251,253,252,.78);backdrop-filter:blur(18px)}
        .nav-inner{height:76px;display:flex;align-items:center;justify-content:space-between;gap:20px}
        .brand{display:inline-flex;align-items:center;gap:10px;font-family:Manrope,sans-serif;font-size:21px;font-weight:900}
        .brand-mark{width:38px;height:38px;display:inline-grid;place-items:center;border-radius:9px;background:var(--ink);color:#fff}
        .nav-links{display:none;align-items:center;gap:4px}
        .nav-links a{display:inline-flex;align-items:center;min-height:40px;padding:8px 12px;border-radius:8px;color:var(--body);font-weight:800;font-size:14px}
        .nav-links a:hover{color:var(--teal);background:rgba(20,124,120,.08)}
        .nav-actions{display:none;align-items:center;gap:10px}
        .menu-btn{display:inline-flex;background:none;border:none;cursor:pointer;color:var(--ink);font-size:22px}
        .mobile-overlay{position:fixed;inset:0;z-index:70;background:var(--paper);display:none;flex-direction:column;padding:90px 24px 30px}
        .mobile-overlay.open{display:flex}
        .mobile-overlay a{padding:14px 8px;font-size:18px;font-weight:800;color:var(--ink);border-bottom:1px solid var(--line)}
        .mobile-overlay a:hover{color:var(--teal)}
        .mobile-overlay .btn{width:100%;margin-top:14px}
        @media(min-width:980px){.nav-links,.nav-actions{display:flex}.menu-btn{display:none}}

        .hero{padding:140px 0 80px;position:relative;overflow:hidden}
        .hero::before{content:'';position:absolute;top:-30%;right:-15%;width:800px;height:800px;background:radial-gradient(circle,rgba(39,191,208,.08) 0%,transparent 60%);pointer-events:none}
        .hero-grid{display:grid;grid-template-columns:1fr 1fr;gap:40px;align-items:center}
        .hero h1{margin-bottom:20px}
        .hero p{font-size:18px;max-width:480px;margin-bottom:30px}
        .hero-actions{display:flex;gap:12px;flex-wrap:wrap}
        .hero-img img{border-radius:16px;box-shadow:var(--shadow)}
        @media(max-width:840px){.hero-grid{grid-template-columns:1fr;text-align:center}.hero p{margin:0 auto 30px}.hero-img{display:none}}

        .partner-strip{overflow:hidden;padding:30px 0;border-top:1px solid var(--line);border-bottom:1px solid var(--line)}
        .partner-track{display:flex;gap:60px;animation:scroll 20s linear infinite;width:max-content}
        @keyframes scroll{0%{transform:translateX(0)}100%{transform:translateX(-50%)}}
        .partner-track span{font-size:18px;font-weight:900;white-space:nowrap;color:var(--body);opacity:.6}

        .section{padding:90px 0}
        .section.alt{background:var(--soft)}
        .section-head{margin-bottom:50px}
        .section-head.center{text-align:center;max-width:650px;margin:0 auto 50px}
        .section-head h2{margin-bottom:12px}
        .section-head p{font-size:17px;max-width:520px}

        .about-grid{display:grid;grid-template-columns:1fr 1fr;gap:50px;align-items:center}
        .about-grid img{border-radius:16px;box-shadow:var(--shadow)}
        @media(max-width:840px){.about-grid{grid-template-columns:1fr}}

        .services-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:20px}
        .service-card{background:var(--panel);border:1px solid var(--line);border-radius:12px;padding:28px;transition:all .25s}
        .service-card:hover{box-shadow:var(--shadow);transform:translateY(-3px);border-color:var(--teal)}
        .service-card .card-icon{width:48px;height:48px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;background:rgba(20,124,120,.08);color:var(--teal);margin-bottom:16px}
        .service-card h3{font-size:18px;margin-bottom:8px}
        .service-card p{font-size:14px;margin-bottom:14px}
        .service-card a{font-weight:800;font-size:14px;color:var(--teal)}

        .roadmap{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:20px}
        .roadmap-card{background:var(--panel);border:1px solid var(--line);border-radius:12px;padding:24px;position:relative;padding-left:60px}
        .roadmap-card .step{position:absolute;left:16px;top:24px;width:32px;height:32px;border-radius:50%;background:var(--teal);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:14px}
        .roadmap-card h4{font-size:16px;font-weight:800;margin-bottom:6px}

        .team-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:20px}
        .team-card{text-align:center}
        .team-card img{width:100%;border-radius:14px;aspect-ratio:3/4;object-fit:cover;box-shadow:var(--shadow)}
        .team-card .name{font-weight:800;margin-top:12px;font-size:15px}
        .team-card .role{font-size:13px;color:var(--body);margin-top:2px}

        .test-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:20px}
        .test-card{background:var(--panel);border:1px solid var(--line);border-radius:14px;padding:28px}
        .test-card img{width:48px;height:48px;border-radius:50%;object-fit:cover;margin-bottom:12px}
        .test-card .stars{color:var(--teal);font-size:14px;margin-bottom:10px}
        .test-card blockquote{font-size:15px;color:var(--body);font-style:italic;line-height:1.7;margin:0 0 14px}
        .test-card figcaption{font-weight:800;font-size:14px}
        .test-card figcaption span{display:block;font-weight:500;font-size:12px;color:var(--body)}

        .faq-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px;align-items:start}
        .faq-item{background:var(--panel);border:1px solid var(--line);border-radius:10px;overflow:hidden}
        .faq-q{padding:16px 20px;cursor:pointer;display:flex;justify-content:space-between;align-items:center;font-weight:800;font-size:15px;min-height:48px}
        .faq-q i{color:var(--teal);transition:transform .3s;font-size:13px}
        .faq-item.open .faq-q i{transform:rotate(180deg)}
        .faq-a{padding:0 20px;max-height:0;overflow:hidden;transition:all .3s;color:var(--body);font-size:14px;line-height:1.7}
        .faq-item.open .faq-a{padding:0 20px 16px;max-height:200px}
        @media(max-width:768px){.faq-grid{grid-template-columns:1fr}}

        .cta-section{text-align:center;padding:80px 0;background:linear-gradient(135deg,var(--ink),var(--graphite));color:#fff;border-radius:0}
        .cta-section h2{color:#fff;margin-bottom:12px}
        .cta-section p{color:rgba(255,255,255,.7);margin-bottom:24px;font-size:17px}

        .footer{padding:50px 0 30px;border-top:1px solid var(--line);font-size:14px;color:var(--body)}
        .footer-grid{display:grid;grid-template-columns:2fr 1fr 1fr;gap:30px}
        .footer h5{color:var(--ink);font-weight:800;margin-bottom:10px}
        .footer a{display:block;color:var(--body);margin-bottom:6px}
        .footer a:hover{color:var(--teal)}
        .footer-bottom{border-top:1px solid var(--line);margin-top:30px;padding-top:20px;text-align:center;font-size:13px}
        @media(max-width:768px){.footer-grid{grid-template-columns:1fr}}
    </style>
</head>
<body>

<nav class="nav"><div class="container nav-inner">
    <a href="/" class="brand"><img src="/assets/img/logo-v2.svg" alt="<?php echo htmlspecialchars($brand); ?>" style="height:32px"></a>
    <div class="nav-links">
        <a href="#about">About</a><a href="#services">Services</a><a href="#roadmap">Roadmap</a><a href="#team">Advisors</a><a href="#testimonials">Clients</a><a href="#faq">FAQ</a>
    </div>
    <div class="nav-actions">
        <a href="<?php echo $login_url; ?>" class="btn btn-light">Sign In</a>
        <a href="<?php echo $join_url; ?>" class="btn btn-primary">Join Now</a>
    </div>
    <button class="menu-btn" id="menuToggle" aria-label="Toggle menu"><i class="fas fa-bars" id="menuIcon"></i></button>
</div></nav>

<div class="mobile-overlay" id="mobileOverlay">
    <a href="#about" onclick="toggleMenu()">About</a><a href="#services" onclick="toggleMenu()">Services</a><a href="#roadmap" onclick="toggleMenu()">Roadmap</a><a href="#team" onclick="toggleMenu()">Advisors</a><a href="#testimonials" onclick="toggleMenu()">Clients</a><a href="#faq" onclick="toggleMenu()">FAQ</a>
    <a href="<?php echo $join_url; ?>" class="btn btn-primary">Join Now</a>
</div>

<!-- HERO (Index3) -->
<section class="hero"><div class="container"><div class="hero-grid">
    <div>
        <span class="kicker">Wealth Infrastructure</span>
        <h1>Build wealth with a clearer command of every move</h1>
        <p>PrimeAxis delivers guided portfolios, daily ROI tracking, and institutional-grade transparency — so you invest with precision, not guesswork.</p>
        <div class="hero-actions">
            <a href="<?php echo $join_url; ?>" class="btn btn-primary">Get Started Free</a>
            <a href="#about" class="btn btn-light">Learn More</a>
        </div>
    </div>
    <div class="hero-img"><img src="<?php echo $base; ?>/images/index3-hero-desktop.webp" alt="PrimeAxis analysts reviewing market dashboards" width="600" height="450"></div>
</div></div></section>

<!-- PARTNER STRIP (Index3) -->
<div class="partner-strip"><div class="partner-track">
    <span>Nexa Bank</span><span>FinVault</span><span>EdgePay</span><span>VertexTrade</span><span>Arbor Capital</span><span>Pulse Markets</span>
    <span>Nexa Bank</span><span>FinVault</span><span>EdgePay</span><span>VertexTrade</span><span>Arbor Capital</span><span>Pulse Markets</span>
</div></div>

<!-- ABOUT (Index) -->
<section class="section" id="about"><div class="container">
    <div class="about-grid">
        <img src="<?php echo $base; ?>/images/index3-advisory.webp" alt="Advisors reviewing portfolio analytics with a client" width="560" height="400">
        <div>
            <span class="kicker">About Us</span>
            <h2>Financial advisory, reimagined for the modern investor</h2>
            <p style="margin-top:16px"><?php echo htmlspecialchars($brand); ?> combines seasoned wealth-management expertise with real-time market intelligence. Our team of Trade Captains, Strategic Advisors, and Financial Analysts work around the clock to ensure your capital generates consistent, daily returns — with complete transparency and institutional-grade security.</p>
            <p style="margin-top:16px">Whether you're building long-term wealth or seeking daily passive income, our platform gives you the tools, data, and expert support to make informed decisions.</p>
        </div>
    </div>
</div></section>

<!-- SERVICES (Index) -->
<section class="section alt" id="services"><div class="container">
    <div class="section-head center"><span class="kicker">What We Offer</span><h2>Services built for serious investors</h2><p>Every service designed with transparency, security, and performance at the core.</p></div>
    <div class="services-grid">
        <article class="service-card"><div class="card-icon"><i class="fa-solid fa-scale-balanced"></i></div><h3>Wealth Management</h3><p>End-to-end planning, asset allocation, retirement strategy, and long-term growth support.</p><a href="#contact">Learn More <i class="fa-solid fa-arrow-right"></i></a></article>
        <article class="service-card"><div class="card-icon"><i class="fa-solid fa-brain"></i></div><h3>Financial Advisory</h3><p>Algorithm-assisted advisory workflows that make high-quality financial guidance accessible.</p><a href="#contact">Learn More <i class="fa-solid fa-arrow-right"></i></a></article>
        <article class="service-card"><div class="card-icon"><i class="fa-solid fa-chart-simple"></i></div><h3>Real-Time Market Analytics</h3><p>Live market signals, custom alerts, heatmaps, and decision support for fast-moving investors.</p><a href="#contact">Learn More <i class="fa-solid fa-arrow-right"></i></a></article>
        <article class="service-card"><div class="card-icon"><i class="fa-brands fa-bitcoin"></i></div><h3>Cryptocurrency Investments</h3><p>Diversified digital-asset exposure with secure portfolio workflows and 24/7 market access.</p><a href="#contact">Learn More <i class="fa-solid fa-arrow-right"></i></a></article>
        <article class="service-card"><div class="card-icon"><i class="fa-solid fa-users"></i></div><h3>HR Consulting</h3><p>Talent strategy, compensation planning, leadership systems, and team growth consulting.</p><a href="#contact">Learn More <i class="fa-solid fa-arrow-right"></i></a></article>
        <article class="service-card"><div class="card-icon"><i class="fa-solid fa-bullhorn"></i></div><h3>Marketing Consulting</h3><p>Brand positioning, customer acquisition, analytics, paid media, and content strategy.</p><a href="#contact">Learn More <i class="fa-solid fa-arrow-right"></i></a></article>
    </div>
</div></section>

<!-- ROADMAP (Index) -->
<section class="section" id="roadmap"><div class="container">
    <div class="section-head center"><span class="kicker">Our Process</span><h2>Company Roadmap</h2><p>A clear path from strategy to execution — every investment follows a proven framework.</p></div>
    <div class="roadmap">
        <div class="roadmap-card"><div class="step">1</div><h4>Research & Discovery</h4><p>Deep market analysis, risk assessment, and opportunity identification.</p></div>
        <div class="roadmap-card"><div class="step">2</div><h4>Strategy Formulation</h4><p>Custom investment strategies aligned with your financial goals and risk tolerance.</p></div>
        <div class="roadmap-card"><div class="step">3</div><h4>Portfolio Construction</h4><p>Diversified allocation across asset classes with optimal risk-reward ratios.</p></div>
        <div class="roadmap-card"><div class="step">4</div><h4>Active Monitoring</h4><p>24/7 portfolio surveillance with real-time adjustments and daily profit distribution.</p></div>
        <div class="roadmap-card"><div class="step">5</div><h4>Performance Review</h4><p>Comprehensive reporting, analytics, and strategy refinement for continuous growth.</p></div>
        <div class="roadmap-card"><div class="step">6</div><h4>Scale & Compound</h4><p>Reinvest profits, expand portfolio, and unlock higher-tier investment opportunities.</p></div>
    </div>
</div></section>

<!-- TEAM (Index) -->
<section class="section alt" id="team"><div class="container">
    <div class="section-head center"><span class="kicker">Our Advisors</span><h2>Meet the team behind your returns</h2><p>Experienced professionals dedicated to growing your wealth.</p></div>
    <div class="team-grid">
        <div class="team-card"><img src="<?php echo $base; ?>/images/team-1.webp" alt="Advisor"><div class="name">James D.</div><div class="role">Chief Investment Officer</div></div>
        <div class="team-card"><img src="<?php echo $base; ?>/images/team-2.webp" alt="Advisor"><div class="name">Maria K.</div><div class="role">Head of Wealth Strategy</div></div>
        <div class="team-card"><img src="<?php echo $base; ?>/images/team-3.webp" alt="Advisor"><div class="name">Robert L.</div><div class="role">Security Director</div></div>
        <div class="team-card"><img src="<?php echo $base; ?>/images/team-4.webp" alt="Advisor"><div class="name">Sarah N.</div><div class="role">Client Success Lead</div></div>
    </div>
</div></section>

<!-- TESTIMONIALS (Index) -->
<section class="section" id="testimonials"><div class="container">
    <div class="section-head center"><span class="kicker">Testimonials</span><h2>What our clients say</h2></div>
    <div class="test-grid">
        <figure class="test-card"><img src="<?php echo $base; ?>/images/client-1.webp" alt="Client"><div class="stars">★★★★★</div><blockquote>"PrimeAxis has completely transformed how I approach investing. The daily returns are consistent and the platform is incredibly intuitive."</blockquote><figcaption>Mobarok Hossain<span>Trade Master</span></figcaption></figure>
        <figure class="test-card"><img src="<?php echo $base; ?>/images/client-2.webp" alt="Client"><div class="stars">★★★★★</div><blockquote>"The transparency and real-time analytics give me complete confidence. I've seen significant portfolio growth in just a few months."</blockquote><figcaption>Guy Hawkins<span>Trade Boss</span></figcaption></figure>
        <figure class="test-card"><img src="<?php echo $base; ?>/images/client-3.webp" alt="Client"><div class="stars">★★★★★</div><blockquote>"As a small business owner, their expert guidance and personalized strategies have helped me secure my financial future."</blockquote><figcaption>Belal Hossain<span>Trade Genius</span></figcaption></figure>
    </div>
</div></section>

<!-- FAQ (Index) -->
<section class="section alt" id="faq"><div class="container">
    <div class="section-head center"><span class="kicker">FAQ</span><h2>Frequently Asked Questions</h2></div>
    <div class="faq-grid">
        <div>
            <div class="faq-item open"><div class="faq-q" onclick="this.parentElement.classList.toggle('open')">How do I start investing?<i class="fas fa-chevron-down"></i></div><div class="faq-a">Create an account, set up your wallet addresses in Settings, make a deposit via BTC/USDT/ETH, and choose an investment plan. Your daily returns start immediately.</div></div>
            <div class="faq-item"><div class="faq-q" onclick="this.parentElement.classList.toggle('open')">How are daily profits calculated?<i class="fas fa-chevron-down"></i></div><div class="faq-a">Daily ROI is based on your chosen plan's percentage rate. For example, $1,000 at 2.5% daily ROI earns $25 per day, credited directly to your balance.</div></div>
            <div class="faq-item"><div class="faq-q" onclick="this.parentElement.classList.toggle('open')">How fast are withdrawals processed?<i class="fas fa-chevron-down"></i></div><div class="faq-a">Withdrawals are processed within minutes to a few hours. We support BTC, USDT, and Ethereum (ETH).</div></div>
        </div>
        <div>
            <div class="faq-item"><div class="faq-q" onclick="this.parentElement.classList.toggle('open')">Is my investment secure?<i class="fas fa-chevron-down"></i></div><div class="faq-a">Yes. Bank-grade 256-bit encryption, cold storage, and 24/7 real-time monitoring protect your assets at every layer.</div></div>
            <div class="faq-item"><div class="faq-q" onclick="this.parentElement.classList.toggle('open')">Do you have a referral program?<i class="fas fa-chevron-down"></i></div><div class="faq-a">Yes! Share your referral link from your dashboard and earn commission on every active investor you bring to the platform.</div></div>
            <div class="faq-item"><div class="faq-q" onclick="this.parentElement.classList.toggle('open')">What cryptocurrencies are supported?<i class="fas fa-chevron-down"></i></div><div class="faq-a">Bitcoin (BTC), Tether (USDT), and Ethereum (ETH) for both deposits and withdrawals. More options coming soon.</div></div>
        </div>
    </div>
</div></section>

<!-- CTA -->
<section class="cta-section"><div class="container">
    <h2>Ready to build wealth with clarity?</h2>
    <p>Join thousands of investors already growing their portfolio with PrimeAxis.</p>
    <a href="<?php echo $join_url; ?>" class="btn btn-primary">Get Started Free</a>
</div></section>

<footer class="footer"><div class="container">
    <div class="footer-grid">
        <div><h5><?php echo htmlspecialchars($brand); ?></h5><p style="max-width:280px">Modern investment platform with guided portfolios, daily ROI tracking, and institutional-grade transparency.</p></div>
        <div><h5>Links</h5><a href="<?php echo $login_url; ?>">Sign In</a><a href="<?php echo $join_url; ?>">Get Started</a></div>
        <div><h5>Support</h5><a href="#">support@primeaxisinv.com</a><a href="#faq">FAQ</a></div>
    </div>
    <div class="footer-bottom">&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($brand); ?>. All rights reserved.</div>
</div></footer>

<script>
function toggleMenu(){const o=document.getElementById('mobileOverlay'),i=document.getElementById('menuIcon');o.classList.toggle('open');i.className=o.classList.contains('open')?'fas fa-times':'fas fa-bars';document.body.classList.toggle('locked',o.classList.contains('open'))}
document.querySelectorAll('#mobileOverlay a').forEach(a=>a.addEventListener('click',toggleMenu));
const observer=new IntersectionObserver(entries=>{entries.forEach(e=>{if(e.isIntersecting)e.target.classList.add('visible')})},{threshold:.15});
document.querySelectorAll('.reveal').forEach(el=>observer.observe(el));
document.querySelectorAll('a[href^="#"]').forEach(a=>a.addEventListener('click',e=>{e.preventDefault();const t=document.querySelector(a.getAttribute('href'));if(t)t.scrollIntoView({behavior:'smooth'})}));
</script>
</body>
</html>
