<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/admin-session.php';
requireAdminLogin();
$admin = getCurrentAdmin();
$nav_type = 'admin'; $active_nav = 'settings';
$page_title = 'Platform Settings'; $page_subtitle = 'Configure wallet addresses and system parameters';
require_once __DIR__ . '/../includes/argon-header.php';
?>
<div id="settingsList" class="card tsec"><div class="tscroll"><table><thead><tr><th>Key</th><th>Value</th><th>Actions</th></tr></thead><tbody><tr><td colspan="3" style="text-align:center;color:var(--argon-muted)">Loading...</td></tr></tbody></table></div></div>
<div id="editModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:200;align-items:center;justify-content:center"><div class="card" style="max-width:460px;width:90%"><div class="card-header"><h6>Edit Setting</h6></div><div class="card-body"><form id="editForm" style="display:flex;flex-direction:column;gap:.75rem"><input type="hidden" name="key" id="editKey"><div><label style="font-size:.78rem;font-weight:600;color:var(--argon-dark);display:block;margin-bottom:.25rem">Key</label><input type="text" id="editKeyDisplay" disabled style="width:100%;padding:.45rem .6rem;border:1px solid var(--argon-border);border-radius:.25rem;font-size:.82rem;background:var(--argon-light)"></div><div><label style="font-size:.78rem;font-weight:600;color:var(--argon-dark);display:block;margin-bottom:.25rem">Value</label><input type="text" name="value" id="editValue" required style="width:100%;padding:.45rem .6rem;border:1px solid var(--argon-border);border-radius:.25rem;font-size:.82rem"></div><div style="display:flex;gap:.5rem;justify-content:flex-end"><button type="button" onclick="hideEditModal()" style="background:var(--argon-light);border:1px solid var(--argon-border);padding:.45rem 1.2rem;border-radius:.25rem;cursor:pointer;font-size:.82rem">Cancel</button><button type="submit" style="background:var(--argon-primary);color:#fff;border:none;padding:.45rem 1.2rem;border-radius:.25rem;cursor:pointer;font-weight:600;font-size:.82rem">Save</button></div></form></div></div></div>
<script>
async function loadSettings(){const r=await fetch('/api/admin/settings.php');const d=await r.json();const c=document.getElementById('settingsList');if(!d.success||!d.data.settings.length){c.innerHTML='<div class="tscroll"><table><tbody><tr><td colspan="3" style="text-align:center;padding:2rem;color:var(--argon-muted)">No settings found.</td></tr></tbody></table></div>';return}c.innerHTML=`<div class="tscroll"><table><thead><tr><th>Key</th><th>Value</th><th>Actions</th></tr></thead><tbody>${d.data.settings.map(s=>`<tr><td style="font-family:monospace;font-size:.78rem">${escHtml(s.setting_key)}</td><td>${escHtml(s.setting_value)||'<span style="color:var(--argon-muted);font-style:italic">empty</span>'}</td><td><a href="#" onclick="editSetting('${escHtml(s.setting_key)}','${escHtml(s.setting_value).replace(/'/g,"\\'")}')" class="act-link">Edit</a></td></tr>`).join('')}</tbody></table></div>`}
function editSetting(key,value){document.getElementById('editKey').value=key;document.getElementById('editKeyDisplay').value=key;document.getElementById('editValue').value=value;document.getElementById('editModal').style.display='flex'}
function hideEditModal(){document.getElementById('editModal').style.display='none'}
document.getElementById('editForm').addEventListener('submit',async(e)=>{e.preventDefault();const f=new FormData(e.target);const r=await fetch('/api/admin/settings.php',{method:'POST',body:f});const d=await r.json();alert(d.message);if(d.success){hideEditModal();loadSettings()}});
function escHtml(s){const d=document.createElement('div');d.textContent=String(s);return d.innerHTML}
loadSettings();
</script>
<?php require_once __DIR__ . '/../includes/argon-footer.php'; ?>
