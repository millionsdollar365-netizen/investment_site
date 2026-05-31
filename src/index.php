<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/session.php';
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> — Investment Platform</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/argon.css">
</head>
<body style="display:block">
    <header id="topbar" style="position:static">
        <div class="topbar-left">
            <div class="page-title"><?php echo SITE_NAME; ?></div>
        </div>
        <div class="topbar-right">
            <?php if (isLoggedIn()): ?>
                <a href="/dashboard/" style="color:var(--argon-primary);text-decoration:none;font-weight:600;font-size:.82rem">Dashboard</a>
                <form action="/api/auth/logout.php" method="POST" style="display:inline"><button type="submit" style="background:var(--argon-danger);color:#fff;border:none;padding:.4rem 1rem;border-radius:.25rem;cursor:pointer;font-weight:600;font-size:.75rem">Logout</button></form>
            <?php else: ?>
                <a href="/login.php" style="color:var(--argon-text);text-decoration:none;font-weight:600;font-size:.82rem">Login</a>
                <a href="/register.php" style="background:var(--argon-primary);color:#fff;padding:.4rem 1rem;border-radius:.25rem;text-decoration:none;font-weight:600;font-size:.75rem">Register</a>
            <?php endif; ?>
        </div>
    </header>

    <div id="page-header" style="text-align:center;padding:4rem 1.5rem 6rem">
        <h1 style="font-size:2rem">Welcome to <?php echo SITE_NAME; ?></h1>
        <p style="font-size:1.1rem">Secure investment platform with daily returns</p>
    </div>

    <div id="content" style="max-width:900px;margin:0 auto">
        <div style="text-align:center;margin-bottom:2rem">
            <?php if (!isLoggedIn()): ?>
                <a href="/register.php" style="background:var(--argon-primary);color:#fff;padding:.7rem 2rem;border-radius:.25rem;text-decoration:none;font-weight:600;font-size:.9rem;margin-right:.5rem">Get Started</a>
                <a href="/login.php" style="background:var(--argon-light);color:var(--argon-dark);padding:.7rem 2rem;border-radius:.25rem;text-decoration:none;font-weight:600;font-size:.9rem;border:1px solid var(--argon-border)">Sign In</a>
            <?php else: ?>
                <a href="/dashboard/" style="background:var(--argon-primary);color:#fff;padding:.7rem 2rem;border-radius:.25rem;text-decoration:none;font-weight:600;font-size:.9rem">Go to Dashboard</a>
            <?php endif; ?>
        </div>

        <div class="stats-grid" style="margin-top:2rem">
            <div class="stat-card" style="flex-direction:column;text-align:center;gap:.5rem">
                <div class="stat-icon bg-primary" style="width:48px;height:48px;font-size:1.1rem"><i class="fas fa-shield-alt"></i></div>
                <div class="stat-label">Secure</div>
                <div style="font-size:.78rem;color:var(--argon-muted)">Bank-level security for your investments</div>
            </div>
            <div class="stat-card" style="flex-direction:column;text-align:center;gap:.5rem">
                <div class="stat-icon bg-success" style="width:48px;height:48px;font-size:1.1rem"><i class="fas fa-calendar-check"></i></div>
                <div class="stat-label">Daily Returns</div>
                <div style="font-size:.78rem;color:var(--argon-muted)">Earn daily ROI on your investments</div>
            </div>
            <div class="stat-card" style="flex-direction:column;text-align:center;gap:.5rem">
                <div class="stat-icon bg-warning" style="width:48px;height:48px;font-size:1.1rem"><i class="fas fa-wallet"></i></div>
                <div class="stat-label">Withdrawals</div>
                <div style="font-size:.78rem;color:var(--argon-muted)">Quick and easy withdrawal process</div>
            </div>
        </div>
    </div>

    <footer style="text-align:center"><div class="foot-inner"><div>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?></div></div></footer>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const urlParams=new URLSearchParams(window.location.search);
if(urlParams.get('logout')==='1'){
    Swal.fire({icon:'success',title:'Logged Out',text:'You have been logged out successfully.',timer:2500,showConfirmButton:false});
    window.history.replaceState({},document.title,window.location.pathname);
}
</script>
</body>
</html>
