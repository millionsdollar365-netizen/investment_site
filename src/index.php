<?php require_once __DIR__ . '/includes/config.php'; require_once __DIR__ . '/includes/session.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo SITE_NAME; ?> — Earn Daily Returns on Your Investments</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="description" content="Secure investment platform with daily ROI. Invest in crypto-backed plans and earn passive income daily.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="/assets/investa/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="/assets/investa/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/investa/css/style.css" rel="stylesheet">
    <style>
        .header-logo { font-size: 1.75rem; font-weight: 700; color: #0d6efd; }
        .header-logo i { margin-right: .5rem; }
        .hero-overlay { background: linear-gradient(rgba(0,0,0,.6),rgba(0,0,0,.7)); position:absolute; inset:0; }
        .cta-btn { padding: .8rem 2.2rem; font-weight: 600; border-radius: 50px; font-size: 1rem; transition: all .3s; }
        .cta-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(13,110,253,.3); }
        .stat-item { text-align: center; padding: 2rem 0; }
        .stat-item h3 { font-size: 2.5rem; font-weight: 700; color: #0d6efd; }
        .stat-item p { color: #6c757d; margin: 0; }
        .investa-navbar { background: rgba(255,255,255,.97) !important; backdrop-filter: blur(10px); }
        .investa-navbar .nav-link { font-weight: 500; color: #333 !important; margin: 0 .25rem; padding: .5rem 1rem !important; border-radius: 4px; }
        .investa-navbar .nav-link:hover { background: #f0f4ff; color: #0d6efd !important; }
        @media (max-width: 991px) {
            .investa-navbar .nav-link { padding: .75rem 1rem !important; }
            .stat-item h3 { font-size: 1.8rem; }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<div class="container-fluid sticky-top px-0">
    <nav class="navbar navbar-expand-lg investa-navbar py-3 px-4 shadow-sm">
        <a href="/" class="navbar-brand header-logo"><i class="fas fa-chart-line" style="color:#0d6efd"></i> <?php echo SITE_NAME; ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto align-items-center gap-2">
                <a href="/" class="nav-item nav-link active">Home</a>
                <a href="#about" class="nav-item nav-link">About</a>
                <a href="#services" class="nav-item nav-link">Services</a>
                <a href="#plans" class="nav-item nav-link">Plans</a>
                <?php if (isLoggedIn()): ?>
                    <a href="/dashboard/" class="btn btn-primary cta-btn ms-2">Dashboard</a>
                <?php else: ?>
                    <a href="/login.php" class="nav-item nav-link">Login</a>
                    <a href="/register.php" class="btn btn-primary cta-btn ms-2">Get Started</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
</div>

<!-- Hero -->
<div class="container-fluid bg-dark py-5 mb-5" style="background: linear-gradient(135deg, #0d1b3e 0%, #1a3a6b 50%, #0d6efd 100%); position: relative; min-height: 85vh; display: flex; align-items: center;">
    <div class="hero-overlay"></div>
    <div class="container position-relative text-center text-white" style="z-index:1">
        <p class="text-uppercase mb-3 wow fadeInUp" style="letter-spacing:.15em;font-size:.9rem;opacity:.8" data-wow-delay="0.1s">Secure & Transparent Investment Platform</p>
        <h1 class="display-3 fw-bold mb-4 wow fadeInUp" data-wow-delay="0.3s">Earn Daily Returns on<br>Your Investments</h1>
        <p class="lead mb-5 mx-auto wow fadeInUp" style="max-width:650px;opacity:.85" data-wow-delay="0.5s">Invest in carefully structured plans and receive daily ROI credited directly to your balance. Start small, grow big.</p>
        <div class="d-flex justify-content-center gap-3 flex-wrap wow fadeInUp" data-wow-delay="0.7s">
            <a href="/register.php" class="btn btn-primary cta-btn btn-lg">Get Started Now</a>
            <a href="#services" class="btn btn-outline-light cta-btn btn-lg">Learn More</a>
        </div>
    </div>
</div>

<!-- Stats Bar -->
<div class="container" style="margin-top:-5rem;position:relative;z-index:2">
    <div class="row g-4 bg-white rounded-4 shadow-lg p-4">
        <div class="col-6 col-md-3 stat-item"><h3 class="counter" data-target="5000">0</h3><p>Active Investors</p></div>
        <div class="col-6 col-md-3 stat-item"><h3 class="counter" data-target="2500000">0</h3><p>Total Invested</p></div>
        <div class="col-6 col-md-3 stat-item"><h3 class="counter" data-target="890000">0</h3><p>Profits Paid</p></div>
        <div class="col-6 col-md-3 stat-item"><h3>24/7</h3><p>Support</p></div>
    </div>
</div>

<!-- About -->
<div id="about" class="container py-5 my-5">
    <div class="row g-5 align-items-center">
        <div class="col-lg-6 wow fadeInLeft"><img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=600&h=400&fit=crop" alt="About" class="img-fluid rounded-4 shadow"></div>
        <div class="col-lg-6 wow fadeInRight">
            <h6 class="text-primary text-uppercase fw-bold mb-2">About Us</h6>
            <h2 class="display-6 fw-bold mb-4">We Help You Build Financial Freedom</h2>
            <p class="text-muted mb-3"><?php echo SITE_NAME; ?> is a trusted investment platform providing secure, transparent, and profitable investment opportunities. Our automated system ensures daily returns are credited on time, every time.</p>
            <div class="d-flex gap-3 mt-4">
                <div><i class="fas fa-check-circle text-primary me-2"></i> Secure & Encrypted</div>
                <div><i class="fas fa-check-circle text-primary me-2"></i> Daily Payouts</div>
                <div><i class="fas fa-check-circle text-primary me-2"></i> 24/7 Support</div>
            </div>
            <a href="/register.php" class="btn btn-primary cta-btn mt-4">Start Investing</a>
        </div>
    </div>
</div>

<!-- Services -->
<div id="services" class="container-fluid py-5" style="background:#f8f9fa">
    <div class="container">
        <div class="text-center mx-auto mb-5 wow fadeInUp" style="max-width:600px">
            <h6 class="text-primary text-uppercase fw-bold">Our Services</h6>
            <h2 class="display-6 fw-bold">What We Offer</h2>
        </div>
        <div class="row g-4">
            <div class="col-md-6 col-lg-3 wow fadeInUp" data-wow-delay="0.1s">
                <div class="bg-white p-4 rounded-4 shadow-sm text-center h-100">
                    <div class="bg-primary bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:70px;height:70px"><i class="fas fa-chart-line text-white fa-2x"></i></div>
                    <h5 class="fw-bold">Investment Plans</h5>
                    <p class="text-muted small">Choose from multiple plans with competitive daily ROI rates and flexible durations.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 wow fadeInUp" data-wow-delay="0.3s">
                <div class="bg-white p-4 rounded-4 shadow-sm text-center h-100">
                    <div class="bg-success bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:70px;height:70px"><i class="fas fa-coins text-white fa-2x"></i></div>
                    <h5 class="fw-bold">Daily Profits</h5>
                    <p class="text-muted small">Earn daily returns credited directly to your balance. Watch your money grow.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 wow fadeInUp" data-wow-delay="0.5s">
                <div class="bg-white p-4 rounded-4 shadow-sm text-center h-100">
                    <div class="bg-warning bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:70px;height:70px"><i class="fas fa-shield-alt text-white fa-2x"></i></div>
                    <h5 class="fw-bold">Secure Storage</h5>
                    <p class="text-muted small">Bank-level encryption and security protocols protect your funds 24/7.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 wow fadeInUp" data-wow-delay="0.7s">
                <div class="bg-white p-4 rounded-4 shadow-sm text-center h-100">
                    <div class="bg-danger bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:70px;height:70px"><i class="fas fa-headset text-white fa-2x"></i></div>
                    <h5 class="fw-bold">24/7 Support</h5>
                    <p class="text-muted small">Our support team is available around the clock to assist with any questions.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- How It Works -->
<div class="container py-5 my-5">
    <div class="text-center mx-auto mb-5 wow fadeInUp" style="max-width:600px">
        <h6 class="text-primary text-uppercase fw-bold">How It Works</h6>
        <h2 class="display-6 fw-bold">3 Simple Steps</h2>
    </div>
    <div class="row g-4">
        <div class="col-md-4 wow fadeInUp" data-wow-delay="0.1s">
            <div class="text-center p-4">
                <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:80px;height:80px;font-size:1.8rem;font-weight:700">1</div>
                <h5 class="fw-bold">Create Account</h5>
                <p class="text-muted">Register in seconds. No paperwork, no hassle. Your account is ready instantly.</p>
            </div>
        </div>
        <div class="col-md-4 wow fadeInUp" data-wow-delay="0.3s">
            <div class="text-center p-4">
                <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:80px;height:80px;font-size:1.8rem;font-weight:700">2</div>
                <h5 class="fw-bold">Make a Deposit</h5>
                <p class="text-muted">Fund your account via BTC, USDT, or Ethereum. Choose an investment plan.</p>
            </div>
        </div>
        <div class="col-md-4 wow fadeInUp" data-wow-delay="0.5s">
            <div class="text-center p-4">
                <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:80px;height:80px;font-size:1.8rem;font-weight:700">3</div>
                <h5 class="fw-bold">Earn Daily</h5>
                <p class="text-muted">Sit back and watch your profits roll in daily. Withdraw anytime.</p>
            </div>
        </div>
    </div>
</div>

<!-- Testimonials -->
<div class="container-fluid py-5" style="background:#f8f9fa">
    <div class="container">
        <div class="text-center mx-auto mb-5 wow fadeInUp" style="max-width:600px">
            <h6 class="text-primary text-uppercase fw-bold">Testimonials</h6>
            <h2 class="display-6 fw-bold">What Our Investors Say</h2>
        </div>
        <div class="row g-4">
            <div class="col-md-4 wow fadeInUp" data-wow-delay="0.1s">
                <div class="bg-white p-4 rounded-4 shadow-sm"><p class="text-muted fst-italic mb-3">"I have been investing with <?php echo SITE_NAME; ?> for 6 months. The daily returns are consistent and withdrawals are fast. Highly recommended."</p><div class="d-flex align-items-center gap-3"><div class="bg-primary rounded-circle text-white d-flex align-items-center justify-content-center" style="width:50px;height:50px;font-weight:700">JD</div><div><h6 class="mb-0 fw-bold">James D.</h6><small class="text-muted">Investor since 2025</small></div></div></div>
            </div>
            <div class="col-md-4 wow fadeInUp" data-wow-delay="0.3s">
                <div class="bg-white p-4 rounded-4 shadow-sm"><p class="text-muted fst-italic mb-3">"The platform is incredibly easy to use. I started small and have grown my portfolio significantly. The support team is fantastic."</p><div class="d-flex align-items-center gap-3"><div class="bg-success rounded-circle text-white d-flex align-items-center justify-content-center" style="width:50px;height:50px;font-weight:700">MK</div><div><h6 class="mb-0 fw-bold">Maria K.</h6><small class="text-muted">Investor since 2024</small></div></div></div>
            </div>
            <div class="col-md-4 wow fadeInUp" data-wow-delay="0.5s">
                <div class="bg-white p-4 rounded-4 shadow-sm"><p class="text-muted fst-italic mb-3">"Transparent, reliable, and profitable. What more could you ask for? The daily ROI model works exactly as advertised."</p><div class="d-flex align-items-center gap-3"><div class="bg-warning rounded-circle text-white d-flex align-items-center justify-content-center" style="width:50px;height:50px;font-weight:700">RL</div><div><h6 class="mb-0 fw-bold">Robert L.</h6><small class="text-muted">Investor since 2025</small></div></div></div>
            </div>
        </div>
    </div>
</div>

<!-- CTA -->
<div class="container-fluid py-5" style="background: linear-gradient(135deg, #0d1b3e 0%, #1a3a6b 50%, #0d6efd 100%);">
    <div class="container text-center text-white py-4 wow fadeInUp">
        <h2 class="display-5 fw-bold mb-3">Ready to Start Earning?</h2>
        <p class="lead mb-4" style="opacity:.85">Join thousands of investors earning daily returns on their investments.</p>
        <a href="/register.php" class="btn btn-light cta-btn btn-lg text-primary fw-bold">Create Free Account</a>
    </div>
</div>

<!-- Footer -->
<div class="container-fluid bg-dark text-white py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <h4 class="fw-bold mb-3"><i class="fas fa-chart-line text-primary me-2"></i> <?php echo SITE_NAME; ?></h4>
                <p class="opacity-75 small">Secure investment platform offering daily returns through diversified crypto-backed investment plans.</p>
            </div>
            <div class="col-lg-4">
                <h5 class="fw-bold mb-3">Quick Links</h5>
                <a href="/login.php" class="d-block text-white-50 mb-2 text-decoration-none">Login</a>
                <a href="/register.php" class="d-block text-white-50 mb-2 text-decoration-none">Register</a>
                <a href="#about" class="d-block text-white-50 mb-2 text-decoration-none">About</a>
                <a href="#services" class="d-block text-white-50 mb-2 text-decoration-none">Services</a>
            </div>
            <div class="col-lg-4">
                <h5 class="fw-bold mb-3">Contact</h5>
                <p class="text-white-50 small mb-1"><i class="fas fa-envelope me-2"></i> support@primeaxisinv.com</p>
                <p class="text-white-50 small mb-0"><i class="fas fa-clock me-2"></i> 24/7 Support Available</p>
            </div>
        </div>
        <hr class="my-4" style="border-color:rgba(255,255,255,.1)">
        <p class="text-center text-white-50 small mb-0">&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.</p>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
<script>
new WOW().init();
// Counter animation
document.querySelectorAll('.counter').forEach(el=>{
    const target=parseInt(el.dataset.target);
    const duration=2000;
    const step=target/(duration/16);
    let current=0;
    const update=()=>{
        current+=step;
        if(current<target){el.textContent=Math.floor(current).toLocaleString();requestAnimationFrame(update)}
        else el.textContent=target.toLocaleString()
    };
    new IntersectionObserver((entries,obs)=>{if(entries[0].isIntersecting){update();obs.disconnect()}}).observe(el);
});
</script>
</body>
</html>
