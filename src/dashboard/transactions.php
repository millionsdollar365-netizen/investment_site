<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
$user = getCurrentUser();
$nav_type = 'user'; $active_nav = 'transactions';
$page_title = 'Transaction History'; $page_subtitle = 'All your account activity';
require_once __DIR__ . '/../includes/argon-header.php';
?>
<div id="transactionsList" class="card tsec"><div class="tscroll"><table><thead><tr><th>Type</th><th>Amount</th><th>Balance Before</th><th>Balance After</th><th>Description</th><th>Date</th></tr></thead><tbody><tr><td colspan="6" style="text-align:center;color:var(--argon-muted)">Loading...</td></tr></tbody></table></div></div>
<div id="pagination" style="display:flex;justify-content:center;gap:.5rem;margin-top:1rem"></div>

<script>
let currentPage=1;
async function loadTransactions(page=1){currentPage=page;const r=await fetch(`/api/user/transactions.php?page=${page}&limit=20`);const d=await r.json();const c=document.getElementById('transactionsList');if(!d.success||!d.data.transactions.length){c.innerHTML='<div class="tscroll"><table><tbody><tr><td colspan="6" style="text-align:center;padding:2rem;color:var(--argon-muted)">No transactions yet.</td></tr></tbody></table></div>';document.getElementById('pagination').innerHTML='';return}c.innerHTML=`<div class="tscroll"><table><thead><tr><th>Type</th><th>Amount</th><th>Balance Before</th><th>Balance After</th><th>Description</th><th>Date</th></tr></thead><tbody>${d.data.transactions.map(t=>`<tr><td><span class="badge ${typeClass(t.type)}">${t.type}</span></td><td style="font-weight:600">$${parseFloat(t.amount).toFixed(2)}</td><td>$${parseFloat(t.old_balance||0).toFixed(2)}</td><td>$${parseFloat(t.new_balance||0).toFixed(2)}</td><td>${escHtml(t.description||'')}</td><td style="font-size:.75rem;color:var(--argon-muted)">${t.created_at}</td></tr>`).join('')}</tbody></table></div>`;const tp=Math.ceil(d.data.total/d.data.limit);let h='';for(let i=1;i<=tp;i++)h+=`<button onclick="loadTransactions(${i})" style="padding:.35rem .85rem;border-radius:.25rem;font-size:.82rem;border:1px solid var(--argon-border);cursor:pointer;${i===currentPage?'background:var(--argon-primary);color:#fff;border-color:var(--argon-primary)':'background:var(--argon-white);color:var(--argon-text)'}">${i}</button>`;document.getElementById('pagination').innerHTML=h}
function typeClass(t){const m={deposit:'b-success',withdrawal:'b-danger',investment:'b-primary',profit:'b-info',referral:'b-warning',adjustment:'b-default'};return m[t]||'b-default'}
function escHtml(s){const d=document.createElement('div');d.textContent=s;return d.innerHTML}
loadTransactions();
</script>
<?php require_once __DIR__ . '/../includes/argon-footer.php'; ?>
