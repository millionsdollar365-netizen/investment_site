/**
 * PRIMEAXIS INVESTMENT — Argon Dashboard Behavior
 * Sidebar toggle, overlay, mobile responsiveness
 */

/* ── SIDEBAR ── */
function toggleSB(){
  const s=document.getElementById('sidebar');
  const o=document.getElementById('overlay');
  const open=s.classList.toggle('open');
  if(open){o.classList.add('show');document.body.style.overflow='hidden'}
  else{o.classList.remove('show');document.body.style.overflow=''}
}
function closeSB(){
  document.getElementById('sidebar').classList.remove('open');
  document.getElementById('overlay').classList.remove('show');
  document.body.style.overflow='';
}
document.querySelectorAll('.nav-link').forEach(l=>{
  l.addEventListener('click',()=>{
    document.querySelectorAll('.nav-link').forEach(x=>x.classList.remove('active'));
    l.classList.add('active');
    if(window.innerWidth<768)closeSB();
  });
});
window.addEventListener('resize',()=>{if(window.innerWidth>=768)closeSB()});
