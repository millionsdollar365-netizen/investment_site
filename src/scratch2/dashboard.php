<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo SITE_NAME; ?> — Dashboard</title>
<meta name="description" content="Premium investor dashboard with real‑time stats, portfolio overview and performance charts.">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="icon" type="image/svg+xml" href="/assets/img/favicon.svg">
<script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<style>
:root{--void:#040308;--deep:#0a0612;--card:rgba(255,255,255,0.03);--gold:#d4a843;--gold2:#f0c060;--cyan:#00d4ff;--emerald:#10b981;--violet:#7c3aed;--rose:#f43f5e;--text:#f8fafc;--muted:#94a3b8;--border:rgba(255,255,255,0.07);--border-glow:rgba(212,168,67,0.25);--r:14px;}
*{margin:0;padding:0;box-sizing:border-box}html{scroll-behavior:smooth}
body{font-family:'Inter',sans-serif;background:var(--void);color:var(--text);overflow-x:hidden;cursor:none;}
#cursor{position:fixed;width:10px;height:10px;background:var(--gold);border-radius:50%;pointer-events:none;z-index:9999;transform:translate(-50%,-50%);transition:transform .1s;mix-blend-mode:difference;}
#cursor-ring{position:fixed;width:38px;height:38px;border:1.5px solid rgba(212,168,67,0.5);border-radius:50%;pointer-events:none;z-index:9998;transform:translate(-50%,-50%);transition:all .18s cubic-bezier(.2,1,.2,1);}
.nav{position:fixed;top:0;left:0;right:0;z-index:1000;padding:1.4rem 0;transition:all .4s cubic-bezier(.16,1,.3,1);}
.nav.scrolled{background:rgba(4,3,8,0.92);backdrop-filter:blur(24px);border-bottom:1px solid var(--border);padding:.9rem 0;}
.nav-inner{max-width:1200px;margin:0 auto;padding:0 2rem;display:flex;align-items:center;justify-content:space-between;}
.nav-logo{font-family:'Syne',sans-serif;font-size:1.4rem;font-weight:800;color:#fff;text-decoration:none;display:flex;align-items:center;gap:.5rem;letter-spacing:-.02em;}
.nav-logo span{background:linear-gradient(135deg,var(--gold),var(--gold2));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;}
.nav-links{display:flex;align-items:center;gap:2rem;list-style:none;}
.nav-links a{color:var(--muted);text-decoration:none;font-size:.88rem;font-weight:500;transition:color .25s;position:relative;}
.nav-links a:hover{color:#fff}
.nav-links a::after{content:'';position:absolute;bottom:-4px;left:0;width:0;height:1px;background:var(--gold);transition:width .3s;}
.nav-links a:hover::after{width:100%}
.btn-nav{padding:.55rem 1.4rem;border-radius:8px;font-size:.85rem;font-weight:700;background:linear-gradient(135deg,var(--gold),var(--gold2));color:#040308;border:none;cursor:none;text-decoration:none;transition:all .3s;box-shadow:0 0 20px rgba(212,168,67,0.2);}
.btn-nav:hover{transform:translateY(-2px);box-shadow:0 0 35px rgba(212,168,67,0.4);}
.dashboard{max-width:1200px;margin:6rem auto;padding:0 2rem;display:grid;grid-template-columns:2fr 1fr;gap:2rem;}
.dashboard-section{background:var(--card);border:1px solid var(--border);border-radius:var(--r);padding:2rem;overflow:hidden;position:relative;}
.dashboard-section h3{font-family:'Syne',sans-serif;font-size:1.8rem;font-weight:800;margin-bottom:.8rem;}
.dashboard-section p{color:var(--muted);font-size:.94rem;line-height:1.6;}
.chart-wrapper{position:relative;height:260px;}
/* Simple glass overlay */
.dashboard-section::before{content:'';position:absolute;inset:0;background:rgba(255,255,255,0.02);backdrop-filter:blur(6px);pointer-events:none;}
@media(max-width:900px){.dashboard{grid-template-columns:1fr;}}
</style>
</head>
<body>
<div id="cursor"></div>
<div id="cursor-ring"></div>
<nav class="nav" id="nav"><div class="nav-inner"><a href="/scratch2/" class="nav-logo"><img src="/assets/img/logo-v2.svg" alt="<?php echo SITE_NAME; ?>" style="height:38px"><span><?php echo SITE_NAME; ?></span></a><ul class="nav-links"><li><a href="/scratch2/">Home</a></li><li><a href="/scratch2/dashboard.php" class="btn-nav">Dashboard</a></li><li><a href="/logout.php">Logout</a></li></ul></div></nav>

<main class="dashboard">
  <section class="dashboard-section">
    <h3>Portfolio Overview</h3>
    <p>Total Invested: <strong>$<span id="totalInvested">0</span></strong></p>
    <p>Current Value: <strong>$<span id="currentValue">0</span></strong></p>
    <p>Daily ROI: <strong><span id="dailyRoi">0%</span></strong></p>
    <div class="chart-wrapper"><canvas id="pieChart"></canvas></div>
  </section>
  <section class="dashboard-section">
    <h3>Performance</h3>
    <div class="chart-wrapper"><canvas id="lineChart"></canvas></div>
  </section>
</main>

<script>
// Cursor effect
const cursor=document.getElementById('cursor');const ring=document.getElementById('cursor-ring');let mx=0,my=0,rx=0,ry=0;document.addEventListener('mousemove',e=>{mx=e.clientX;my=e.clientY;cursor.style.left=mx+'px';cursor.style.top=my+'px';});(function anim(){rx+=(mx-rx)*0.12;ry+=(my-ry)*0.12;ring.style.left=rx+'px';ring.style.top=ry+'px';requestAnimationFrame(anim);})();
['a','button','.dashboard-section','canvas'].forEach(s=>{document.querySelectorAll(s).forEach(el=>{el.addEventListener('mouseenter',()=>{ring.style.transform='translate(-50%,-50%) scale(2)';ring.style.opacity='.5';});el.addEventListener('mouseleave',()=>{ring.style.transform='translate(-50%,-50%) scale(1)';ring.style.opacity='1';});});});
// Nav scroll style
window.addEventListener('scroll',()=>{document.getElementById('nav').classList.toggle('scrolled',scrollY>50);});
// Simulated data (replace with real API later)
function randomInt(min,max){return Math.floor(Math.random()*(max-min+1))+min;}
function updateStats(){document.getElementById('totalInvested').textContent=randomInt(50000,150000).toLocaleString();document.getElementById('currentValue').textContent=randomInt(60000,180000).toLocaleString();document.getElementById('dailyRoi').textContent=(Math.random()*2+0.5).toFixed(2)+'%';}
updateStats();setInterval(updateStats,8000);
// Charts using Chart.js
const pieCtx=document.getElementById('pieChart').getContext('2d');
const lineCtx=document.getElementById('lineChart').getContext('2d');
const pieChart=new Chart(pieCtx,{type:'doughnut',data:{labels:['BTC','ETH','USDT','SOL'],datasets:[{data:[randomInt(20,40),randomInt(20,35),randomInt(10,25),randomInt(5,15)],backgroundColor:['#f7931a','#627eea','#26a17b','#9945ff'],borderColor:'rgba(0,0,0,0)',borderWidth:0}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{labels:{color:var(--muted)}}}});
const lineChart=new Chart(lineCtx,{type:'line',data:{labels:['Mon','Tue','Wed','Thu','Fri','Sat','Sun'],datasets:[{label:'Portfolio Value',data:[randomInt(70,100),randomInt(80,110),randomInt(85,120),randomInt(90,130),randomInt(95,140),randomInt(100,150),randomInt(110,160)],borderColor:var(--gold),backgroundColor:'rgba(212,168,67,0.15)',fill:true,tension:0.3}]},options:{responsive:true,maintainAspectRatio:false,scales:{y:{ticks:{color:var(--muted)},grid:{color:'rgba(255,255,255,0.06)'}},x:{ticks:{color:var(--muted)},grid:{display:false}}},plugins:{tooltip:{backgroundColor:'rgba(0,0,0,0.75)',titleColor:'#fff',bodyColor:'#fff'},legend:{display:false}}});
// GSAP reveal
gsap.registerPlugin(ScrollTrigger);
gsap.from('.dashboard-section',{scrollTrigger:{trigger:'.dashboard',start:'top 80%'},y:40,opacity:0,duration:.8,stagger:.2,ease:'power3.out'});
</script>
</body>
</html>
