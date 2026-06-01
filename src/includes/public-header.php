<?php
/**
 * Public page header — nav bar + mobile overlay
 * Used by: index, login, register, forgot-password, reset-password, about, etc.
 * Requires config.php + session.php already loaded.
 */
?>
<style>
.nav { position: fixed; top: 0; left: 0; right: 0; z-index: 10000; padding: 1rem 0; transition: all .3s; }
.nav.scrolled { background: rgba(15,23,42,.96); backdrop-filter: blur(24px); border-bottom: 1px solid rgba(148,163,184,.1); }
.nav-inner { display: flex; align-items: center; justify-content: space-between; }
.nav-logo { font-size: 1.55rem; font-weight: 900; color: #fff; text-decoration: none; display: flex; align-items: center; gap: .5rem; }
.nav-logo span { color: #fbbf24; }
.nav-links { display: flex; align-items: center; gap: 2rem; list-style: none; }
.nav-links a { color: #94a3b8; text-decoration: none; font-size: .88rem; font-weight: 500; transition: color .2s; }
.nav-links a:hover { color: #fff; }
.btn { display: inline-block; padding: .6rem 1.5rem; border-radius: 50px; font-weight: 600; font-size: .88rem; text-decoration: none; transition: all .3s; cursor: pointer; border: none; }
.btn-gold { background: linear-gradient(135deg, #fbbf24, #f59e0b); color: #0f172a; font-weight: 700; animation: glow 2.5s ease-in-out infinite; }
.btn-gold:hover { transform: translateY(-2px); box-shadow: 0 8px 35px rgba(251,191,36,.5); }
@keyframes glow { 0%,100% { box-shadow: 0 0 15px rgba(251,191,36,.3); } 50% { box-shadow: 0 0 30px rgba(251,191,36,.55); } }
.hamburger { display: none; background: none; border: none; color: #fff; font-size: 1.5rem; cursor: pointer; z-index: 10001; position: relative; }
.mobile-overlay { display:none;position:fixed;inset:0;background:rgba(15,23,42,.99);backdrop-filter:blur(24px);flex-direction:column;justify-content:center;align-items:flex-start;gap:1.5rem;z-index:9999;overflow:hidden;padding:2rem 2rem 2rem 2.5rem; }
.mobile-overlay.open { display:flex; }
.mobile-overlay .mobile-logo { margin-bottom: 1.5rem; align-self: flex-start; }
.mobile-link { color:#94a3b8;text-decoration:none;font-size:1.2rem;font-weight:500;transition:color .2s; }
.mobile-link:hover { color:#fff; }
.mobile-overlay .btn { width:auto;min-width:220px;text-align:center;font-size:1.05rem;padding:.75rem 2rem;margin-top:.25rem;display:inline-block; }
.mobile-overlay .btn-gold { animation: none; }
@media (min-width: 769px) { .mobile-overlay { display:none !important; } }
@media (max-width: 768px) {
    .nav-links { display: none; }
    .hamburger { display: block; }
}
</style>
<nav class="nav" id="nav">
    <div class="container nav-inner">
        <a href="/" class="nav-logo"><img src="/assets/img/logo.svg" alt="<?php echo SITE_NAME; ?>" style="height:46px"></a>
        <button class="hamburger" id="hamburger" onclick="toggleMobileMenu()"><i class="fas fa-bars" id="menuIcon"></i></button>
        <ul class="nav-links" id="navLinks">
            <li><a href="/">Home</a></li>
            <li><a href="/#about">About</a></li>
            <li><a href="/#features">Benefits</a></li>
            <li><a href="/#how">How It Works</a></li>
            <li><a href="/#faq">FAQ</a></li>
            <?php if (isLoggedIn()): ?>
                <li><a href="/dashboard/" class="btn btn-gold">Dashboard</a></li>
            <?php else: ?>
                <li><a href="/login.php">Login</a></li>
                <li><a href="/register.php" class="btn btn-gold">Get Started</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<div class="mobile-overlay" id="mobileOverlay">
    <div class="mobile-logo"><img src="/assets/img/logo.svg" alt="<?php echo SITE_NAME; ?>" style="height:38px"></div>
    <a href="/" class="mobile-link">Home</a>
    <a href="/#about" class="mobile-link">About</a>
    <a href="/#features" class="mobile-link">Benefits</a>
    <a href="/#how" class="mobile-link">How It Works</a>
    <a href="/#faq" class="mobile-link">FAQ</a>
    <?php if (isLoggedIn()): ?>
        <a href="/dashboard/" class="btn btn-gold">Dashboard</a>
    <?php else: ?>
        <a href="/login.php" class="mobile-link">Login</a>
        <a href="/register.php" class="btn btn-gold">Get Started</a>
    <?php endif; ?>
</div>

<script>
function toggleMobileMenu(){
    const ov=document.getElementById('mobileOverlay');
    const icon=document.getElementById('menuIcon');
    ov.classList.toggle('open');
    if(ov.classList.contains('open')){
        icon.className='fas fa-times';
        document.body.style.overflow='hidden';
    }else{
        icon.className='fas fa-bars';
        document.body.style.overflow='';
    }
}
document.addEventListener('DOMContentLoaded',()=>{
    document.querySelectorAll('#mobileOverlay a').forEach(a=>a.addEventListener('click',()=>{
        document.getElementById('mobileOverlay').classList.remove('open');
        document.getElementById('menuIcon').className='fas fa-bars';
        document.body.style.overflow='';
    }));
});
</script>
