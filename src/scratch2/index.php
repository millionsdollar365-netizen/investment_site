<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo SITE_NAME; ?> — Grow Your Wealth</title>
<meta name="description" content="Premium fintech investment firm. Wealth management, crypto investments, and financial advisory with daily returns.">

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="icon" type="image/svg+xml" href="/assets/img/favicon.svg">

<!-- GSAP -->
<script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>
<link rel="stylesheet" href="style.css">
<!--
:root {
  --void: #040308;
  --deep: #0a0612;
  --card: rgba(255,255,255,0.03);
  --card-hover: rgba(255,255,255,0.06);
  --gold: #d4a843;
  --gold2: #f0c060;
  --cyan: #00d4ff;
  --violet: #7c3aed;
  --rose: #f43f5e;
  --emerald: #10b981;
  --text: #f8fafc;
  --muted: #94a3b8;
  --border: rgba(255,255,255,0.07);
  --border-glow: rgba(212,168,67,0.25);
  --r: 18px;
}

*{margin:0;padding:0;box-sizing:border-box}
html{scroll-behavior:smooth}

body {
  font-family:'Inter',sans-serif;
  background:var(--void);
  color:var(--text);
  overflow-x:hidden;
  cursor:none;
}

/* ── CUSTOM CURSOR ── */
#cursor {
  position:fixed;
  width:10px;height:10px;
  background:var(--gold);
  border-radius:50%;
  pointer-events:none;
  z-index:9999;
  transform:translate(-50%,-50%);
  transition:transform .1s;
  mix-blend-mode:difference;
}
#cursor-ring {
  position:fixed;
  width:38px;height:38px;
  border:1.5px solid rgba(212,168,67,0.5);
  border-radius:50%;
  pointer-events:none;
  z-index:9998;
  transform:translate(-50%,-50%);
  transition:all .18s cubic-bezier(.2,1,.2,1);
}
body:hover #cursor-ring {opacity:1}

/* ── NAV ── */
.nav {
  position:fixed;top:0;left:0;right:0;z-index:1000;
  padding:1.4rem 0;
  transition:all .4s cubic-bezier(.16,1,.3,1);
}
.nav.scrolled {
  background:rgba(4,3,8,0.92);
  backdrop-filter:blur(24px);
  border-bottom:1px solid var(--border);
  padding:.9rem 0;
}
.nav-inner {
  max-width:1200px;margin:0 auto;padding:0 2rem;
  display:flex;align-items:center;justify-content:space-between;
}
.nav-logo {
  font-family:'Syne',sans-serif;font-size:1.4rem;font-weight:800;
  color:#fff;text-decoration:none;display:flex;align-items:center;gap:.5rem;letter-spacing:-.02em;
}
.nav-logo span {
  background:linear-gradient(135deg,var(--gold),var(--gold2));
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
}
.nav-links {display:flex;align-items:center;gap:2rem;list-style:none;}
.nav-links a {
  color:var(--muted);text-decoration:none;font-size:.88rem;font-weight:500;
  transition:color .25s;position:relative;
}
.nav-links a:hover{color:#fff}
.nav-links a::after {
  content:'';position:absolute;bottom:-4px;left:0;width:0;height:1px;
  background:var(--gold);transition:width .3s;
}
.nav-links a:hover::after{width:100%}
.btn-nav {
  padding:.55rem 1.4rem;border-radius:8px;font-size:.85rem;font-weight:700;
  background:linear-gradient(135deg,var(--gold),var(--gold2));
  color:#040308;border:none;cursor:none;text-decoration:none;
  transition:all .3s;box-shadow:0 0 20px rgba(212,168,67,0.2);
}
.btn-nav:hover{transform:translateY(-2px);box-shadow:0 0 35px rgba(212,168,67,0.4);}
.hamburger{display:none;background:none;border:none;color:#fff;font-size:1.5rem;cursor:none;}
.mobile-overlay {
  display:none;position:fixed;inset:0;
  background:rgba(4,3,8,.98);backdrop-filter:blur(20px);
  flex-direction:column;justify-content:center;align-items:center;gap:1.8rem;z-index:9990;
}
.mobile-overlay.open{display:flex;}
.mobile-link{color:var(--muted);font-size:1.2rem;font-weight:500;text-decoration:none;}
@media(max-width:768px){
  .nav-links{display:none;}.hamburger{display:block;}
}

/* ── TICKER ── */
.ticker-bar {
  width:100%;background:rgba(212,168,67,0.06);
  border-bottom:1px solid var(--border-glow);
  overflow:hidden;padding:.55rem 0;white-space:nowrap;
  margin-top:80px;position:relative;z-index:10;
}
.ticker-track {
  display:inline-flex;animation:tickerRun 28s linear infinite;
}
.ticker-item {
  display:inline-flex;align-items:center;gap:.4rem;
  margin-right:3rem;font-size:.75rem;font-weight:600;
}
.ticker-item .sym{color:var(--muted);}
.ticker-item .price{color:#fff;font-family:monospace;}
.ticker-item .up{color:#10b981;}
.ticker-item .dn{color:#f43f5e;}
@keyframes tickerRun{0%{transform:translateX(0)}100%{transform:translateX(-50%)}}

/* ── HERO ── */
.hero {
  min-height:92vh;display:flex;align-items:center;
  position:relative;overflow:hidden;padding:4rem 0 3rem;
}
.hero-bg {
  position:absolute;inset:0;
  background:
    radial-gradient(ellipse 80% 60% at 70% 40%, rgba(124,58,237,0.12) 0%, transparent 60%),
    radial-gradient(ellipse 60% 50% at 20% 60%, rgba(212,168,67,0.08) 0%, transparent 55%);
}
.hero-grid {
  max-width:1200px;margin:0 auto;padding:0 2rem;
  display:grid;grid-template-columns:1fr 1fr;gap:5rem;align-items:center;position:relative;z-index:1;
}
.hero-eyebrow {
  display:inline-flex;align-items:center;gap:.5rem;
  padding:.3rem .9rem;border-radius:4px;
  background:rgba(212,168,67,0.08);border:1px solid rgba(212,168,67,0.2);
  font-size:.72rem;font-weight:700;color:var(--gold);
  text-transform:uppercase;letter-spacing:.1em;margin-bottom:1.5rem;
}
.hero h1 {
  font-family:'Syne',sans-serif;font-size:clamp(2.6rem,5.5vw,4rem);font-weight:800;
  line-height:1.1;letter-spacing:-.03em;margin-bottom:1.25rem;
}
.hero h1 em {
  font-style:normal;
  background:linear-gradient(135deg,var(--gold),var(--gold2),var(--cyan));
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
}
.hero p {
  font-size:1.05rem;color:var(--muted);line-height:1.7;max-width:500px;margin-bottom:2.2rem;
}
.hero-cta {display:flex;gap:1rem;flex-wrap:wrap;}
.btn-primary {
  display:inline-block;padding:.8rem 2rem;border-radius:10px;font-weight:700;
  font-size:.92rem;text-decoration:none;cursor:none;
  background:linear-gradient(135deg,var(--gold),var(--gold2));
  color:#040308;border:none;transition:all .3s;
  box-shadow:0 4px 20px rgba(212,168,67,0.25);
}
.btn-primary:hover{transform:translateY(-3px);box-shadow:0 8px 35px rgba(212,168,67,0.45);}
.btn-outline {
  display:inline-block;padding:.8rem 2rem;border-radius:10px;font-weight:600;
  font-size:.92rem;text-decoration:none;cursor:none;
  border:1px solid rgba(255,255,255,0.12);color:#fff;background:transparent;
  transition:all .3s;
}
.btn-outline:hover{border-color:var(--gold);color:var(--gold);background:rgba(212,168,67,0.04);}

/* ── FLOATING 3D OBJECTS ── */
.hero-objects {
  position:relative;height:480px;display:flex;align-items:center;justify-content:center;
}
.float-scene {
  position:relative;width:360px;height:360px;
}

/* Central glow orb */
.orb-center {
  position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);
  width:180px;height:180px;border-radius:50%;
  background:radial-gradient(circle,rgba(124,58,237,0.5) 0%,rgba(212,168,67,0.2) 50%,transparent 70%);
  animation:orbPulse 4s ease-in-out infinite;
  box-shadow:0 0 60px rgba(124,58,237,0.4),0 0 120px rgba(212,168,67,0.15);
}
@keyframes orbPulse{
  0%,100%{transform:translate(-50%,-50%) scale(1);opacity:.8;}
  50%{transform:translate(-50%,-50%) scale(1.12);opacity:1;}
}

/* Coin objects */
.coin {
  position:absolute;border-radius:50%;display:flex;align-items:center;justify-content:center;
  font-weight:800;color:#fff;box-shadow:0 8px 30px rgba(0,0,0,0.5);
  font-size:.9rem;letter-spacing:.04em;
  transition:transform .3s cubic-bezier(.34,1.56,.64,1);
}
.coin:hover{transform:scale(1.15) !important;}
.coin-btc {
  width:72px;height:72px;top:10px;left:50%;transform:translateX(-50%);
  background:linear-gradient(135deg,#f7931a,#f0a547);
  animation:floatA 6s ease-in-out infinite;
}
.coin-eth {
  width:62px;height:62px;top:50%;left:10px;transform:translateY(-50%);
  background:linear-gradient(135deg,#627eea,#8b6ee8);
  animation:floatB 7s ease-in-out infinite;
}
.coin-usdt {
  width:58px;height:58px;top:50%;right:10px;transform:translateY(-50%);
  background:linear-gradient(135deg,#26a17b,#1fc483);
  animation:floatC 5.5s ease-in-out infinite;
}
.coin-sol {
  width:54px;height:54px;bottom:10px;left:50%;transform:translateX(-50%);
  background:linear-gradient(135deg,#9945ff,#14f195);
  animation:floatD 8s ease-in-out infinite;
}

/* Small accent gems */
.gem {
  position:absolute;width:36px;height:36px;border-radius:8px;
  display:flex;align-items:center;justify-content:center;
  font-size:1.1rem;backdrop-filter:blur(8px);
}
.gem1 {
  top:20%;right:8%;
  background:rgba(0,212,255,0.15);border:1px solid rgba(0,212,255,0.3);color:var(--cyan);
  animation:floatB 6s ease-in-out infinite reverse;
}
.gem2 {
  bottom:20%;left:8%;
  background:rgba(244,63,94,0.12);border:1px solid rgba(244,63,94,0.25);color:var(--rose);
  animation:floatA 7.5s ease-in-out infinite;
}
.gem3 {
  top:35%;right:2%;
  background:rgba(16,185,129,0.12);border:1px solid rgba(16,185,129,0.25);color:var(--emerald);
  animation:floatC 9s ease-in-out infinite;
}

/* Ring decoration */
.ring {
  position:absolute;top:50%;left:50%;
  border-radius:50%;border:1px solid;
  transform:translate(-50%,-50%);pointer-events:none;
}
.ring1 {
  width:220px;height:220px;
  border-color:rgba(212,168,67,0.12);
  animation:ringRotate 20s linear infinite;
}
.ring2 {
  width:300px;height:300px;
  border-color:rgba(124,58,237,0.08);
  animation:ringRotate 30s linear infinite reverse;
}
.ring3 {
  width:380px;height:380px;
  border-color:rgba(0,212,255,0.05);
  animation:ringRotate 45s linear infinite;
}

@keyframes floatA{0%,100%{transform:translateX(-50%) translateY(0);}50%{transform:translateX(-50%) translateY(-14px);}}
@keyframes floatB{0%,100%{transform:translateY(-50%) translateX(0);}50%{transform:translateY(-50%) translateX(-12px);}}
@keyframes floatC{0%,100%{transform:translateY(-50%) translateX(0);}50%{transform:translateY(-50%) translateX(10px);}}
@keyframes floatD{0%,100%{transform:translateX(-50%) translateY(0);}50%{transform:translateX(-50%) translateY(12px);}}
@keyframes ringRotate{0%{transform:translate(-50%,-50%) rotate(0deg);}100%{transform:translate(-50%,-50%) rotate(360deg);}}

@media(max-width:900px){
  .hero-grid{grid-template-columns:1fr;gap:3rem;}
  .hero-objects{height:320px;}
  .float-scene{width:280px;height:280px;}
  .ring3{display:none;}
}

/* ── STATS ── */
.stats-section {
  padding:4rem 0;border-top:1px solid var(--border);border-bottom:1px solid var(--border);
}
.stats-grid {
  max-width:1200px;margin:0 auto;padding:0 2rem;
  display:grid;grid-template-columns:repeat(4,1fr);gap:2rem;
}
.stat-item {text-align:center;}
.stat-item h3 {
  font-family:'Syne',sans-serif;font-size:2.4rem;font-weight:800;
  background:linear-gradient(135deg,var(--gold),var(--gold2));
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
  margin-bottom:.3rem;
}
.stat-item p{font-size:.78rem;color:var(--muted);text-transform:uppercase;letter-spacing:.08em;font-weight:600;}
@media(max-width:768px){.stats-grid{grid-template-columns:1fr 1fr;gap:1.5rem;}}

/* ── SECTION COMMONS ── */
.section{padding:7rem 0;position:relative;}
.section-inner{max-width:1200px;margin:0 auto;padding:0 2rem;}
.section-label {
  display:inline-block;font-size:.7rem;font-weight:700;text-transform:uppercase;
  letter-spacing:.14em;color:var(--gold);margin-bottom:1rem;
}
.section-title {
  font-family:'Syne',sans-serif;font-size:clamp(1.8rem,4vw,2.6rem);
  font-weight:800;letter-spacing:-.02em;line-height:1.2;margin-bottom:1rem;
}
.section-title em {
  font-style:normal;
  background:linear-gradient(135deg,var(--gold),var(--cyan));
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
}
.section-sub{color:var(--muted);font-size:1rem;max-width:560px;line-height:1.7;}

/* ── ABOUT SECTION ── */
.about-grid {display:grid;grid-template-columns:1fr 1fr;gap:5rem;align-items:center;}
.about-img-wrap {
  position:relative;
}
.about-cards-stack {
  position:relative;height:380px;
}
.about-mini-card {
  position:absolute;background:var(--card);backdrop-filter:blur(16px);
  border:1px solid var(--border);border-radius:14px;padding:1.25rem 1.5rem;
  box-shadow:0 20px 50px rgba(0,0,0,.5);
  transition:all .3s cubic-bezier(.34,1.56,.64,1);
}
.about-mini-card:hover{transform:scale(1.05) !important;z-index:10;border-color:var(--border-glow);}
.about-card-1{top:0;left:0;right:4rem;z-index:3;animation:floatA 7s ease-in-out infinite;}
.about-card-2{top:50%;left:2rem;right:0;transform:translateY(-50%);z-index:2;animation:floatB 8s ease-in-out infinite;}
.about-card-3{bottom:0;left:4rem;right:0;z-index:1;animation:floatC 6s ease-in-out infinite;}
.about-card-label{font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);margin-bottom:.4rem;}
.about-card-value{font-size:1.3rem;font-weight:800;font-family:'Syne',sans-serif;color:#fff;}
.about-card-value.gold{color:var(--gold);}
.about-card-value.cyan{color:var(--cyan);}
.about-card-value.green{color:var(--emerald);}
.about-card-sub{font-size:.75rem;color:var(--muted);margin-top:.2rem;}
@media(max-width:900px){.about-grid{grid-template-columns:1fr;}.about-cards-stack{height:280px;}}

/* ── SERVICES (INTERACTIVE 3D TILT CARDS) ── */
.services-grid {
  display:grid;grid-template-columns:repeat(auto-fit,minmax(270px,1fr));gap:1.5rem;
  margin-top:4rem;perspective:1000px;
}
.service-card {
  background:var(--card);
  border:1px solid var(--border);
  border-radius:var(--r);padding:2.2rem;
  position:relative;overflow:hidden;
  transform-style:preserve-3d;
  transition:border-color .3s,box-shadow .3s;
  will-change:transform;
}
.service-card::before {
  content:'';position:absolute;inset:0;
  background:radial-gradient(circle at var(--mx,50%) var(--my,50%), rgba(255,255,255,0.05) 0%, transparent 55%);
  opacity:0;transition:opacity .35s;pointer-events:none;
}
.service-card:hover{border-color:var(--border-glow);box-shadow:0 20px 50px rgba(0,0,0,.5);}
.service-card:hover::before{opacity:1;}

/* Card shine sweep */
.card-shine {
  position:absolute;top:0;left:-100%;width:60%;height:100%;
  background:linear-gradient(90deg,transparent,rgba(255,255,255,0.04),transparent);
  transform:skewX(-20deg);transition:left .6s ease;pointer-events:none;
}
.service-card:hover .card-shine{left:150%;}

/* Top accent bar */
.card-accent {
  position:absolute;top:0;left:0;right:0;height:2px;
  transform:scaleX(0);transform-origin:left;transition:transform .4s ease;
}
.service-card:hover .card-accent{transform:scaleX(1);}
.accent-gold{background:linear-gradient(90deg,var(--gold),var(--gold2));}
.accent-cyan{background:linear-gradient(90deg,var(--cyan),#6366f1);}
.accent-violet{background:linear-gradient(90deg,var(--violet),var(--rose));}
.accent-emerald{background:linear-gradient(90deg,var(--emerald),var(--cyan));}

.service-icon {
  width:54px;height:54px;border-radius:12px;
  display:flex;align-items:center;justify-content:center;font-size:1.4rem;
  margin-bottom:1.5rem;transition:transform .4s cubic-bezier(.34,1.56,.64,1);
  position:relative;z-index:1;
}
.service-card:hover .service-icon{transform:scale(1.12) rotate(5deg);}
.icon-gold{background:rgba(212,168,67,0.1);color:var(--gold);border:1px solid rgba(212,168,67,0.2);}
.icon-cyan{background:rgba(0,212,255,0.08);color:var(--cyan);border:1px solid rgba(0,212,255,0.15);}
.icon-violet{background:rgba(124,58,237,0.1);color:#a78bfa;border:1px solid rgba(124,58,237,0.2);}
.icon-emerald{background:rgba(16,185,129,0.1);color:var(--emerald);border:1px solid rgba(16,185,129,0.2);}

.service-card h4 {
  font-family:'Syne',sans-serif;font-size:1.15rem;font-weight:700;
  color:#fff;margin-bottom:.6rem;position:relative;z-index:1;
}
.service-card p{font-size:.88rem;color:var(--muted);line-height:1.7;position:relative;z-index:1;}

/* Metric badge on card */
.card-metric {
  display:inline-flex;align-items:center;gap:.35rem;
  margin-top:1.25rem;padding:.3rem .7rem;border-radius:6px;
  background:rgba(255,255,255,0.04);font-size:.72rem;font-weight:700;
  position:relative;z-index:1;
}

/* ── HOW IT WORKS ── */
.steps-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:2rem;margin-top:4rem;}
.step-card {
  text-align:center;padding:2.5rem 1.5rem;
  background:var(--card);border:1px solid var(--border);border-radius:var(--r);
  position:relative;overflow:hidden;transition:all .35s;
}
.step-card:hover{border-color:var(--border-glow);transform:translateY(-6px);}
.step-num {
  width:60px;height:60px;border-radius:50%;margin:0 auto 1.5rem;
  background:linear-gradient(135deg,var(--gold),var(--gold2));
  display:flex;align-items:center;justify-content:center;
  font-family:'Syne',sans-serif;font-size:1.35rem;font-weight:800;color:#040308;
  box-shadow:0 6px 20px rgba(212,168,67,0.35);
  transition:transform .4s cubic-bezier(.34,1.56,.64,1);
}
.step-card:hover .step-num{transform:scale(1.1) rotate(-5deg);}
.step-card h4{font-family:'Syne',sans-serif;font-size:1.1rem;font-weight:700;margin-bottom:.5rem;}
.step-card p{font-size:.88rem;color:var(--muted);}
@media(max-width:768px){.steps-grid{grid-template-columns:1fr;}}

/* ── TESTIMONIALS ── */
.testimonials-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem;margin-top:3.5rem;}
.testimonial-card {
  background:var(--card);border:1px solid var(--border);border-radius:var(--r);
  padding:1.8rem;transition:all .3s;position:relative;overflow:hidden;
}
.testimonial-card::after{
  content:'\201C';position:absolute;top:-10px;right:20px;font-size:7rem;
  color:rgba(212,168,67,0.04);font-family:serif;line-height:1;pointer-events:none;
}
.testimonial-card:hover{transform:translateY(-4px);border-color:var(--border-glow);}
.stars{color:var(--gold);margin-bottom:.85rem;font-size:.8rem;letter-spacing:2px;}
.testimonial-card blockquote{font-size:.88rem;color:var(--muted);line-height:1.7;font-style:italic;margin-bottom:1.25rem;}
.testimonial-author{display:flex;align-items:center;gap:.75rem;}
.author-av {
  width:40px;height:40px;border-radius:50%;
  display:flex;align-items:center;justify-content:center;
  font-size:.8rem;font-weight:700;color:#040308;flex-shrink:0;
}
.author-name{font-weight:700;font-size:.88rem;}
.author-role{font-size:.72rem;color:var(--muted);}
@media(max-width:768px){.testimonials-grid{grid-template-columns:1fr;}}

/* ── FAQ ── */
.faq-list{max-width:760px;margin:3.5rem auto 0;}
.faq-item {
  background:var(--card);border:1px solid var(--border);border-radius:12px;
  margin-bottom:1rem;overflow:hidden;transition:border-color .3s;
}
.faq-item.open{border-color:rgba(212,168,67,0.25);}
.faq-q {
  padding:1.25rem 1.5rem;cursor:none;display:flex;justify-content:space-between;
  align-items:center;font-weight:600;font-size:.95rem;transition:background .2s;
}
.faq-q:hover{background:rgba(255,255,255,.02);}
.faq-q i{color:var(--gold);transition:transform .35s;}
.faq-item.open .faq-q i{transform:rotate(180deg);}
.faq-a {
  max-height:0;overflow:hidden;color:var(--muted);font-size:.87rem;line-height:1.7;
  transition:max-height .4s cubic-bezier(.16,1,.3,1),padding .4s;
  padding:0 1.5rem;
}
.faq-item.open .faq-a{padding-bottom:1.25rem;}

/* ── CTA ── */
.cta-section{padding:6rem 0;}
.cta-card {
  max-width:1200px;margin:0 auto;padding:0 2rem;
}
.cta-inner {
  background:linear-gradient(135deg,rgba(212,168,67,.06),rgba(124,58,237,.04));
  border:1px solid var(--border-glow);border-radius:28px;
  padding:5rem 3rem;text-align:center;position:relative;overflow:hidden;
}
.cta-inner::before {
  content:'';position:absolute;top:-40%;right:-20%;
  width:500px;height:500px;
  background:radial-gradient(circle,rgba(124,58,237,0.1),transparent 60%);
  pointer-events:none;
}
.cta-inner h2{font-family:'Syne',sans-serif;font-size:2.4rem;font-weight:800;margin-bottom:.75rem;}
.cta-inner p{color:var(--muted);font-size:1.05rem;max-width:520px;margin:0 auto 2.2rem;}

/* ── FOOTER ── */
.footer {
  border-top:1px solid var(--border);padding:4rem 0 2rem;
  background:rgba(4,3,8,.95);
}
.footer-inner{max-width:1200px;margin:0 auto;padding:0 2rem;}
.footer-grid{display:grid;grid-template-columns:2.2fr 1fr 1fr 1fr;gap:3rem;margin-bottom:3rem;}
.footer-brand p{color:var(--muted);font-size:.88rem;line-height:1.7;max-width:280px;margin-top:.75rem;}
.footer-col h6{font-family:'Syne',sans-serif;font-size:.82rem;font-weight:700;color:#fff;text-transform:uppercase;letter-spacing:.08em;margin-bottom:1.2rem;}
.footer-col a{display:block;color:var(--muted);text-decoration:none;font-size:.85rem;margin-bottom:.6rem;transition:color .2s;}
.footer-col a:hover{color:var(--gold);}
.footer-bottom {
  border-top:1px solid var(--border);padding-top:2rem;
  display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;
  font-size:.78rem;color:var(--muted);
}
@media(max-width:768px){.footer-grid{grid-template-columns:1fr 1fr;gap:2rem;}}
@media(max-width:480px){.footer-grid{grid-template-columns:1fr;}}
-->
</head>
<body>

<!-- Custom cursor -->
<div id="cursor"></div>
<div id="cursor-ring"></div>

<!-- ── NAV ── -->
<nav class="nav" id="nav">
  <div class="nav-inner">
    <a href="/scratch2/" class="nav-logo">
      <img src="/assets/img/logo-v2.svg" alt="<?php echo SITE_NAME; ?>" style="height:38px" loading="lazy">
    </a>
    <button class="hamburger" id="hamburger" onclick="toggleMenu()">
      <i class="fas fa-bars" id="menuIcon"></i>
    </button>
    <ul class="nav-links" id="navLinks">
      <li><a href="#about">About</a></li>
      <li><a href="#services">Services</a></li>
      <li><a href="#how">Process</a></li>
      <li><a href="#faq">FAQ</a></li>
      <?php if (isLoggedIn()): ?>
        <li><a href="/scratch2/dashboard.php" class="btn-nav">Dashboard</a></li>
      <?php else: ?>
        <li><a href="/login.php" style="color:var(--muted)">Sign In</a></li>
        <li><a href="/register.php" class="btn-nav">Join Now</a></li>
      <?php endif; ?>
    </ul>
  </div>
</nav>

<!-- Mobile overlay -->
<div class="mobile-overlay" id="mobileOverlay">
  <a href="#about" class="mobile-link" onclick="toggleMenu()">About</a>
  <a href="#services" class="mobile-link" onclick="toggleMenu()">Services</a>
  <a href="#how" class="mobile-link" onclick="toggleMenu()">Process</a>
  <a href="#faq" class="mobile-link" onclick="toggleMenu()">FAQ</a>
  <?php if (isLoggedIn()): ?>
    <a href="/scratch2/dashboard.php" class="btn-nav">Dashboard</a>
  <?php else: ?>
    <a href="/login.php" class="mobile-link">Sign In</a>
    <a href="/register.php" class="btn-nav">Join Now</a>
  <?php endif; ?>
</div>

<!-- ── TICKER ── -->
<div class="ticker-bar">
  <div class="ticker-track" id="tickerTrack">
    <div class="ticker-item"><span class="sym"><i class="fab fa-bitcoin" style="color:#f7931a"></i> BTC</span><span class="price" id="p1">$68,420</span><span class="up">+1.9%</span></div>
    <div class="ticker-item"><span class="sym"><i class="fab fa-ethereum" style="color:#627eea"></i> ETH</span><span class="price" id="p2">$3,548</span><span class="up">+2.3%</span></div>
    <div class="ticker-item"><span class="sym">USDT</span><span class="price">$1.000</span><span style="color:var(--muted)">0.0%</span></div>
    <div class="ticker-item"><span class="sym">SOL</span><span class="price" id="p3">$152.80</span><span class="up">+4.1%</span></div>
    <div class="ticker-item"><span class="sym">BNB</span><span class="price" id="p4">$412.50</span><span class="dn">-0.8%</span></div>
    <div class="ticker-item"><span class="sym">Daily ROI</span><span class="price">Up to 3.2%</span><span class="up">LIVE</span></div>
    <!-- Duplicate for seamless loop -->
    <div class="ticker-item"><span class="sym"><i class="fab fa-bitcoin" style="color:#f7931a"></i> BTC</span><span class="price">$68,420</span><span class="up">+1.9%</span></div>
    <div class="ticker-item"><span class="sym"><i class="fab fa-ethereum" style="color:#627eea"></i> ETH</span><span class="price">$3,548</span><span class="up">+2.3%</span></div>
    <div class="ticker-item"><span class="sym">USDT</span><span class="price">$1.000</span><span style="color:var(--muted)">0.0%</span></div>
    <div class="ticker-item"><span class="sym">SOL</span><span class="price">$152.80</span><span class="up">+4.1%</span></div>
    <div class="ticker-item"><span class="sym">BNB</span><span class="price">$412.50</span><span class="dn">-0.8%</span></div>
    <div class="ticker-item"><span class="sym">Daily ROI</span><span class="price">Up to 3.2%</span><span class="up">LIVE</span></div>
  </div>
</div>

<!-- ── HERO ── -->
<section class="hero">
  <div class="hero-bg"></div>
  <div class="hero-grid">
    <div class="hero-content">
      <div class="hero-eyebrow"><i class="fas fa-circle" style="font-size:.5rem;animation:pulse 2s infinite"></i> Live Yield Protocol Active</div>
      <h1>Your Capital.<br>Working <em>Smarter</em><br>Every Day.</h1>
      <p>We invest pooled capital into diversified digital assets — crypto, shares, bonds and funds — delivering automated daily returns to thousands of investors worldwide.</p>
      <div class="hero-cta">
        <a href="/register.php" class="btn-primary">Start Earning Today</a>
        <a href="#services" class="btn-outline">Explore Services</a>
      </div>
    </div>

    <!-- Floating Objects -->
    <div class="hero-objects">
      <div class="float-scene">
        <div class="ring ring1"></div>
        <div class="ring ring2"></div>
        <div class="ring ring3"></div>
        <div class="orb-center"></div>
        <div class="coin coin-btc" id="coinBtc">BTC</div>
        <div class="coin coin-eth" id="coinEth">ETH</div>
        <div class="coin coin-usdt" id="coinUsdt">USDT</div>
        <div class="coin coin-sol" id="coinSol">SOL</div>
        <div class="gem gem1"><i class="fas fa-bolt"></i></div>
        <div class="gem gem2"><i class="fas fa-shield-halved"></i></div>
        <div class="gem gem3"><i class="fas fa-chart-line"></i></div>
      </div>
    </div>
  </div>
</section>
<!-- ── STATS ── -->
<div class="stats-section">
  <div class="stats-grid">
    <div class="stat-item"><h3 class="stat-number" id="s1">0</h3><p class="stat-label">Active Investors</p></div>
    <div class="stat-item"><h3 class="stat-number" id="s2">$0</h3><p class="stat-label">Capital Under Management</p></div>
    <div class="stat-item"><h3 class="stat-number" id="s3">$0</h3><p class="stat-label">Total Profits Distributed</p></div>
    <div class="stat-item"><h3 class="stat-number" id="s4">0%</h3><p class="stat-label">Platform Uptime</p></div>
  </div>
</div>



<!-- ── ABOUT ── -->
<section class="section" id="about">
  <div class="section-inner">
    <div class="about-grid">
      <div class="about-img-wrap">
        <div class="about-cards-stack">
          <div class="about-mini-card about-card-1">
            <div class="about-card-label">Portfolio Balance</div>
            <div class="about-card-value gold">$128,492.30</div>
            <div class="about-card-sub">+$3,210 today ↑</div>
          </div>
          <div class="about-mini-card about-card-2">
            <div class="about-card-label">Daily ROI Earned</div>
            <div class="about-card-value cyan">+2.5% / day</div>
            <div class="about-card-sub">$3,212.31 credited</div>
          </div>
          <div class="about-mini-card about-card-3">
            <div class="about-card-label">Withdrawal Status</div>
            <div class="about-card-value green">Processed ✓</div>
            <div class="about-card-sub">$10,000 → BTC wallet</div>
          </div>
        </div>
      </div>
      <div>
        <span class="section-label">About Us</span>
        <h2 class="section-title">Your Trusted <em>Fintech</em> Investment Partner</h2>
        <p class="section-sub" style="margin-bottom:1.5rem"><?php echo SITE_NAME; ?> is a fintech investment company that invests pooled capital into a range of financial assets, including shares, cryptocurrency, bonds, and other investment funds.</p>
        <p class="section-sub">Our team of Trade Captains, Strategic Advisors, and Financial Advisors work around the clock to ensure your capital generates consistent, daily returns — with complete transparency and institutional-grade security.</p>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;margin-top:2rem;">
          <div style="padding:1.25rem;background:var(--card);border:1px solid var(--border);border-radius:12px;">
            <div style="font-size:1.5rem;font-weight:800;font-family:'Syne',sans-serif;color:var(--gold)">24/7</div>
            <div style="font-size:.78rem;color:var(--muted);margin-top:.2rem">Support Available</div>
          </div>
          <div style="padding:1.25rem;background:var(--card);border:1px solid var(--border);border-radius:12px;">
            <div style="font-size:1.5rem;font-weight:800;font-family:'Syne',sans-serif;color:var(--cyan)">5min</div>
            <div style="font-size:.78rem;color:var(--muted);margin-top:.2rem">Avg. Withdrawal Time</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ── SERVICES ── -->
<section class="section" id="services" style="background:rgba(255,255,255,0.01)">
  <div class="section-inner">
    <div style="text-align:center;max-width:600px;margin:0 auto;">
      <span class="section-label">Our Services</span>
      <h2 class="section-title">Everything You Need to <em>Grow</em></h2>
      <p class="section-sub">From automated portfolio management to crypto staking — we have a service for every type of investor.</p>
    </div>

    <div class="services-grid" id="servicesGrid">

      <div class="service-card" data-tilt>
        <div class="card-accent accent-gold"></div>
        <div class="card-shine"></div>
        <div class="service-icon icon-gold"><i class="fas fa-chart-pie"></i></div>
        <h4>Wealth Management</h4>
        <p>End-to-end asset allocation, retirement planning, and long-term financial growth strategies. Managed by certified financial advisors.</p>
        <div class="card-metric" style="color:var(--gold)"><i class="fas fa-arrow-trend-up"></i> Up to 3.2% daily ROI</div>
      </div>

      <div class="service-card" data-tilt>
        <div class="card-accent accent-cyan"></div>
        <div class="card-shine"></div>
        <div class="service-icon icon-cyan"><i class="fab fa-bitcoin"></i></div>
        <h4>Cryptocurrency Investments</h4>
        <p>Secure and diversified digital asset portfolios across BTC, ETH, USDT, and altcoins, backed by advanced risk management protocols.</p>
        <div class="card-metric" style="color:var(--cyan)"><i class="fas fa-lock"></i> Cold Storage Secured</div>
      </div>

      <div class="service-card" data-tilt>
        <div class="card-accent accent-violet"></div>
        <div class="card-shine"></div>
        <div class="service-icon icon-violet"><i class="fas fa-brain"></i></div>
        <h4>Financial Advisory</h4>
        <p>Algorithm-driven investment management with personalized strategies, accessible to investors at every level of experience and capital.</p>
        <div class="card-metric" style="color:#a78bfa"><i class="fas fa-robot"></i> AI-assisted strategies</div>
      </div>

      <div class="service-card" data-tilt>
        <div class="card-accent accent-emerald"></div>
        <div class="card-shine"></div>
        <div class="service-icon icon-emerald"><i class="fas fa-network-wired"></i></div>
        <h4>Consulting Services</h4>
        <p>HR and marketing consulting modules designed to help businesses expand their reach, workforce, and revenue-generating potential.</p>
        <div class="card-metric" style="color:var(--emerald)"><i class="fas fa-users"></i> 500+ clients served</div>
      </div>

    </div>
  </div>
</section>

<!-- ── HOW IT WORKS ── -->
<section class="section" id="how">
  <div class="section-inner">
    <div style="text-align:center;max-width:600px;margin:0 auto;">
      <span class="section-label">How It Works</span>
      <h2 class="section-title">Start Earning in <em>3 Steps</em></h2>
      <p class="section-sub">Your first daily return can be active in under 5 minutes.</p>
    </div>
    <div class="steps-grid">
      <div class="step-card">
        <div class="step-num">1</div>
        <h4>Create Account</h4>
        <p>Register with your email. Instant activation — no complex verification or waiting periods required.</p>
      </div>
      <div class="step-card">
        <div class="step-num">2</div>
        <h4>Deposit & Choose Plan</h4>
        <p>Fund your wallet via BTC, USDT, or ETH. Select from our curated investment plans based on your goals.</p>
      </div>
      <div class="step-card">
        <div class="step-num">3</div>
        <h4>Earn Daily Returns</h4>
        <p>Watch your balance grow every day. Withdraw profits instantly to your crypto wallet at any time.</p>
      </div>
    </div>
  </div>
</section>

<!-- ── TESTIMONIALS ── -->
<section class="section" style="background:rgba(255,255,255,.01)">
  <div class="section-inner">
    <div style="text-align:center;max-width:600px;margin:0 auto;">
      <span class="section-label">Testimonials</span>
      <h2 class="section-title">What Our <em>Investors</em> Say</h2>
    </div>
    <div class="testimonials-grid">
      <div class="testimonial-card">
        <div class="stars">★★★★★</div>
        <blockquote>"Eight months of consistent daily payouts. The platform is transparent, fast, and genuinely delivers on its promises. Best investment decision I've made."</blockquote>
        <div class="testimonial-author">
          <div class="author-av" style="background:linear-gradient(135deg,var(--gold),var(--gold2))">AK</div>
          <div><div class="author-name">Alex K.</div><div class="author-role">Investor since 2025</div></div>
        </div>
      </div>
      <div class="testimonial-card">
        <div class="stars">★★★★★</div>
        <blockquote>"Withdrew $25,000 in under 3 minutes. No delays, no friction. The withdrawal speed alone sets this platform apart from anything else I've used."</blockquote>
        <div class="testimonial-author">
          <div class="author-av" style="background:linear-gradient(135deg,var(--cyan),#6366f1)">SN</div>
          <div><div class="author-name">Sarah N.</div><div class="author-role">Investor since 2024</div></div>
        </div>
      </div>
      <div class="testimonial-card">
        <div class="stars">★★★★★</div>
        <blockquote>"Started with $1,000 as a test. The returns were so consistent I scaled to $50,000. The dashboard gives complete transparency into every transaction."</blockquote>
        <div class="testimonial-author">
          <div class="author-av" style="background:linear-gradient(135deg,var(--emerald),var(--cyan))">MT</div>
          <div><div class="author-name">Michael T.</div><div class="author-role">Investor since 2025</div></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ── FAQ ── -->
<section class="section" id="faq">
  <div class="section-inner">
    <div style="text-align:center;">
      <span class="section-label">FAQ</span>
      <h2 class="section-title">Common <em>Questions</em></h2>
    </div>
    <div class="faq-list">
      <div class="faq-item">
        <div class="faq-q">How do I start investing? <i class="fas fa-chevron-down"></i></div>
        <div class="faq-a">Register your account, add your crypto wallet addresses in Settings, make a deposit via BTC/USDT/ETH, and select an investment plan. Daily returns begin immediately.</div>
      </div>
      <div class="faq-item">
        <div class="faq-q">What cryptocurrencies are supported? <i class="fas fa-chevron-down"></i></div>
        <div class="faq-a">We currently support Bitcoin (BTC), Tether (USDT), and Ethereum (ETH) for both deposits and withdrawals. Additional networks are being added regularly.</div>
      </div>
      <div class="faq-item">
        <div class="faq-q">How are daily profits calculated? <i class="fas fa-chevron-down"></i></div>
        <div class="faq-a">Daily ROI is calculated based on your plan's percentage rate applied to your active investment amount. For example, $5,000 at 2.5% daily earns $125 per day, credited automatically to your balance.</div>
      </div>
      <div class="faq-item">
        <div class="faq-q">How fast are withdrawals processed? <i class="fas fa-chevron-down"></i></div>
        <div class="faq-a">Withdrawals are typically processed within 5 minutes depending on blockchain network confirmation times. We prioritize fast settlement so your funds are never held unnecessarily.</div>
      </div>
      <div class="faq-item">
        <div class="faq-q">Is my investment capital secure? <i class="fas fa-chevron-down"></i></div>
        <div class="faq-a">Yes. We use 256-bit AES encryption, cold storage for the majority of funds, multi-signature wallet protocols, and real-time security monitoring to protect your assets 24/7.</div>
      </div>
    </div>
  </div>
</section>

<!-- ── CTA ── -->
<section class="cta-section">
  <div class="cta-card">
    <div class="cta-inner">
      <h2>Ready to Start Earning?</h2>
      <p>Join thousands of investors already growing their wealth daily on <?php echo SITE_NAME; ?>.</p>
      <a href="/register.php" class="btn-primary" style="font-size:1rem;padding:.9rem 2.5rem;">Create Free Account</a>
    </div>
  </div>
</section>

<!-- ── FOOTER ── -->
<footer class="footer">
  <div class="footer-inner">
    <div class="footer-grid">
      <div class="footer-brand">
        <img src="/assets/img/logo-v2.svg" alt="<?php echo SITE_NAME; ?>" style="height:36px">
        <p>Premium fintech investment platform delivering daily returns through diversified digital asset portfolios. Trusted globally.</p>
      </div>
      <div class="footer-col">
        <h6>Company</h6>
        <a href="#about">About Us</a>
        <a href="#services">Services</a>
        <a href="#how">How It Works</a>
      </div>
      <div class="footer-col">
        <h6>Account</h6>
        <a href="/login.php">Sign In</a>
        <a href="/register.php">Join Now</a>
        <a href="/dashboard/settings.php">Settings</a>
      </div>
      <div class="footer-col">
        <h6>Support</h6>
        <a href="mailto:support@primeaxisinv.com">Contact Us</a>
        <a href="#faq">FAQ</a>
        <a href="#">Terms & Conditions</a>
        <a href="#">Privacy Policy</a>
      </div>
    </div>
    <div class="footer-bottom">
      <span>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.</span>
      <span>Fintech Investment Platform</span>
    </div>
  </div>
</footer>

<style>
@keyframes pulse{0%,100%{opacity:1}50%{opacity:.4}}
</style>

<script>
// ── CURSOR ──
const cursor = document.getElementById('cursor');
const ring = document.getElementById('cursor-ring');
let mx=0,my=0,rx=0,ry=0;

document.addEventListener('mousemove',e=>{
  mx=e.clientX; my=e.clientY;
  cursor.style.left=mx+'px'; cursor.style.top=my+'px';
});
(function animRing(){
  rx+=(mx-rx)*0.12; ry+=(my-ry)*0.12;
  ring.style.left=rx+'px'; ring.style.top=ry+'px';
  requestAnimationFrame(animRing);
})();
// scale ring on interactive elements
document.querySelectorAll('a,button,.service-card,.faq-q,.coin').forEach(el=>{
  el.addEventListener('mouseenter',()=>{ring.style.transform='translate(-50%,-50%) scale(2)';ring.style.opacity='.5';});
  el.addEventListener('mouseleave',()=>{ring.style.transform='translate(-50%,-50%) scale(1)';ring.style.opacity='1';});
});

// ── NAV ──
window.addEventListener('scroll',()=>document.getElementById('nav').classList.toggle('scrolled',scrollY>50));
function toggleMenu(){
  const ov=document.getElementById('mobileOverlay');
  const ic=document.getElementById('menuIcon');
  const o=ov.classList.toggle('open');
  ic.className=o?'fas fa-times':'fas fa-bars';
}

// ── FAQ ACCORDION ──
document.querySelectorAll('.faq-q').forEach(q=>{
  q.addEventListener('click',()=>{
    const item=q.parentElement;
    const a=item.querySelector('.faq-a');
    const open=item.classList.toggle('open');
    a.style.maxHeight=open?a.scrollHeight+'px':'0';
  });
});

// ── STATS COUNTER ──
function animateCount(el, target, duration, prefix = '', suffix = '', decimals = 0) {
  const start = performance.now();
  const step = (now) => {
    const progress = Math.min((now - start) / duration, 1);
    const value = progress * target;
    const fmt = (v) => {
      if (v >= 1e6) return `${prefix}${(v / 1e6).toFixed(1)}M${suffix}`;
      if (v >= 1e3) return `${prefix}${(v / 1e3).toFixed(1)}K${suffix}`;
      return `${prefix}${v.toFixed(decimals)}${suffix}`;
    };
    el.textContent = fmt(value);
    if (progress < 1) requestAnimationFrame(step);
  };
  requestAnimationFrame(step);
}


}
let counted=false;
const statsSection=document.querySelector('.stats-section');
new IntersectionObserver(entries=>{
  if(entries[0].isIntersecting&&!counted){
    counted=true;
    // Trigger count‑up only on larger screens
    gsap.matchMedia().add('(min-width: 600px)', () => {
      animateCount(document.getElementById('s1'), 15420, 1800);
      animateCount(document.getElementById('s2'), 4820000, 1800, '$');
      animateCount(document.getElementById('s3'), 2180000, 1800, '$');
      animateCount(document.getElementById('s4'), 99.9, 1800, '', '%', 1);
    });
  }
},{threshold:.2}).observe(statsSection);

// Desktop-only animations using GSAP matchMedia
gsap.matchMedia().add('(min-width: 769px)', () => {
  // ── 3D TILT CARDS ──
  document.querySelectorAll('[data-tilt]').forEach(card => {
    card.addEventListener('mousemove', e => {
      const r = card.getBoundingClientRect();
      const x = (e.clientX - r.left) / r.width - .5;
      const y = (e.clientY - r.top) / r.height - .5;
      // update radial gradient position for card::before
      card.style.setProperty('--mx', (x + .5) * 100 + '%');
      card.style.setProperty('--my', (y + .5) * 100 + '%');
      card.style.transform = `perspective(800px) rotateY(${x*18}deg) rotateX(${-y*18}deg) translateZ(6px)`;
    });
    card.addEventListener('mouseleave', () => {
      card.style.transform = 'perspective(800px) rotateY(0deg) rotateX(0deg) translateZ(0)';
    });
  });

  // ── GSAP SCROLL ANIMATIONS ──
  gsap.registerPlugin(ScrollTrigger);

  // Service cards staggered reveal
  gsap.from('.service-card', {
    scrollTrigger: { trigger: '#servicesGrid', start: 'top 80%' },
    y: 60, opacity: 0, duration: .8, stagger: .15, ease: 'power3.out'
  });

  // Steps
  gsap.from('.step-card', {
    scrollTrigger: { trigger: '.steps-grid', start: 'top 80%' },
    y: 50, opacity: 0, duration: .7, stagger: .2, ease: 'power3.out'
  });

  // Testimonials
  gsap.from('.testimonial-card', {
    scrollTrigger: { trigger: '.testimonials-grid', start: 'top 80%' },
    y: 40, opacity: 0, duration: .7, stagger: .15, ease: 'power3.out'
  });

  // About cards
  gsap.from('.about-mini-card', {
    scrollTrigger: { trigger: '.about-cards-stack', start: 'top 80%' },
    x: -40, opacity: 0, duration: .8, stagger: .2, ease: 'power3.out'
  });

  // About text
  gsap.from('.about-grid > div:last-child *', {
    scrollTrigger: { trigger: '#about', start: 'top 75%' },
    y: 30, opacity: 0, duration: .7, stagger: .12, ease: 'power3.out'
  });

  // Hero
  gsap.from('.hero-content > *', {
    y: 40, opacity: 0, duration: .9, stagger: .15, ease: 'power3.out', delay: .2
  });

  // Float scene
  gsap.from('.float-scene', {
    scale: .8, opacity: 0, duration: 1.2, ease: 'power3.out', delay: .4
  });
});

setInterval(() => {
  const f = v => new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD', maximumFractionDigits: 0 }).format(v);
  document.getElementById('p1').textContent = f(67000 + Math.random() * 3000);
  document.getElementById('p2').textContent = f(3400 + Math.random() * 300);
  document.getElementById('p3').textContent = '$' + (145 + Math.random() * 15).toFixed(2);
  document.getElementById('p4').textContent = '$' + (400 + Math.random() * 25).toFixed(2);
}, 5000);


  
    const r=card.getBoundingClientRect();
    const x=(e.clientX-r.left)/r.width-.5;
    const y=(e.clientY-r.top)/r.height-.5;
    // update radial gradient position for card::before
    card.style.setProperty('--mx',(x+.5)*100+'%');
    card.style.setProperty('--my',(y+.5)*100+'%');
    card.style.transform=`perspective(800px) rotateY(${x*18}deg) rotateX(${-y*18}deg) translateZ(6px)`;
  });
  card.addEventListener('mouseleave',()=>{
    card.style.transform='perspective(800px) rotateY(0deg) rotateX(0deg) translateZ(0)';
  });
});


gsap.registerPlugin(ScrollTrigger);

// Service cards staggered reveal
gsap.from('.service-card',{
  scrollTrigger:{trigger:'#servicesGrid',start:'top 80%'},
  y:60,opacity:0,duration:.8,stagger:.15,ease:'power3.out'
});
// Steps
gsap.from('.step-card',{
  scrollTrigger:{trigger:'.steps-grid',start:'top 80%'},
  y:50,opacity:0,duration:.7,stagger:.2,ease:'power3.out'
});
// Testimonials
gsap.from('.testimonial-card',{
  scrollTrigger:{trigger:'.testimonials-grid',start:'top 80%'},
  y:40,opacity:0,duration:.7,stagger:.15,ease:'power3.out'
});
// About cards
gsap.from('.about-mini-card',{
  scrollTrigger:{trigger:'.about-cards-stack',start:'top 80%'},
  x:-40,opacity:0,duration:.8,stagger:.2,ease:'power3.out'
});
// About text
gsap.from('.about-grid > div:last-child > *',{
  scrollTrigger:{trigger:'#about',start:'top 75%'},
  y:30,opacity:0,duration:.7,stagger:.12,ease:'power3.out'
});
// Hero
gsap.from('.hero-content > *',{
  y:40,opacity:0,duration:.9,stagger:.15,ease:'power3.out',delay:.2
});
gsap.from('.float-scene',{
  scale:.8,opacity:0,duration:1.2,ease:'power3.out',delay:.4
});

// ── LIVE TICKER PRICES ──
setInterval(()=>{
  const f=v=>new Intl.NumberFormat('en-US',{style:'currency',currency:'USD',maximumFractionDigits:0}).format(v);
  document.getElementById('p1').textContent=f(67000+Math.random()*3000);
  document.getElementById('p2').textContent=f(3400+Math.random()*300);
  document.getElementById('p3').textContent='$'+(145+Math.random()*15).toFixed(2);
  document.getElementById('p4').textContent='$'+(400+Math.random()*25).toFixed(2);
},5000);
</script>
</body>
</html>
