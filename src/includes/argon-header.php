<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * Argon Dashboard Header — Sidebar + Topbar + Page Header
 *
 * Set these BEFORE including:
 *   $page_title    — page heading (e.g. "Dashboard")
 *   $page_subtitle — subtitle text
 *   $active_nav    — slug of active sidebar link
 *   $nav_type      — 'user' or 'admin'
 *
 * $user or $admin must already be populated by requireLogin() / requireAdminLogin().
 */

$nav_type = $nav_type ?? 'user';
$page_title = $page_title ?? 'Dashboard';
$page_subtitle = $page_subtitle ?? '';

// ── Avatar initials ──
if ($nav_type === 'admin' && isset($admin)) {
    $display_name = htmlspecialchars($admin['username']);
    $initials = strtoupper(substr($admin['username'], 0, 2));
} elseif (isset($user)) {
    $display_name = htmlspecialchars($user['first_name'] . ' ' . $user['last_name']);
    $initials = strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1));
} else {
    $display_name = 'User';
    $initials = 'U';
}

// ── Sidebar navigation ──
$user_nav = [
    ['slug' => 'dashboard',    'icon' => 'fa-tv',         'label' => 'Dashboard',    'url' => '/dashboard/'],
    ['slug' => 'investments',  'icon' => 'fa-chart-line', 'label' => 'Investments',  'url' => '/dashboard/investments.php'],
    ['slug' => 'plans',        'icon' => 'fa-layer-group','label' => 'Plans',        'url' => '/dashboard/plans.php'],
    ['slug' => 'deposits',     'icon' => 'fa-coins',      'label' => 'Deposits',     'url' => '/dashboard/deposits.php'],
    ['slug' => 'withdrawals',  'icon' => 'fa-wallet',     'label' => 'Withdrawals',  'url' => '/dashboard/withdrawals.php'],
    ['slug' => 'earnings',     'icon' => 'fa-coins',      'label' => 'Earnings',     'url' => '/dashboard/earnings.php'],
    ['slug' => 'transactions', 'icon' => 'fa-list',       'label' => 'Transactions', 'url' => '/dashboard/transactions.php'],
    ['slug' => 'referrals',    'icon' => 'fa-users',      'label' => 'Referrals',    'url' => '/dashboard/referrals.php'],
    ['slug' => 'profile',      'icon' => 'fa-user-circle','label' => 'Profile',      'url' => '/dashboard/profile.php'],
    ['slug' => 'settings',     'icon' => 'fa-cog',        'label' => 'Settings',     'url' => '/dashboard/settings.php'],
];

$admin_nav = [
    ['slug' => 'dashboard',    'icon' => 'fa-tv',         'label' => 'Dashboard',    'url' => '/admin/'],
    ['slug' => 'users',        'icon' => 'fa-users',      'label' => 'Users',        'url' => '/admin/users.php'],
    ['slug' => 'deposits',     'icon' => 'fa-coins',      'label' => 'Deposits',     'url' => '/admin/deposits.php'],
    ['slug' => 'withdrawals',  'icon' => 'fa-wallet',     'label' => 'Withdrawals',  'url' => '/admin/withdrawals.php'],
    ['slug' => 'investments',  'icon' => 'fa-chart-line', 'label' => 'Investments',  'url' => '/admin/investments.php'],
    ['slug' => 'plans',        'icon' => 'fa-layer-group','label' => 'Plans',        'url' => '/admin/plans.php'],
    ['slug' => 'settings',     'icon' => 'fa-cog',        'label' => 'Settings',     'url' => '/admin/settings.php'],
];

$nav_links = $nav_type === 'admin' ? $admin_nav : $user_nav;
$logout_url = $nav_type === 'admin' ? '/api/admin/logout.php' : '/api/auth/logout.php';
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> — <?php echo SITE_NAME; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="/assets/img/favicon.svg">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/argon.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@23/build/css/intlTelInput.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<div id="overlay" onclick="closeSB()"></div>

<!-- SIDEBAR -->
<nav id="sidebar">
    <div class="sb-brand">
        <img src="/assets/img/logo-v2.svg" alt="<?php echo SITE_NAME; ?>" style="height:42px;margin:0 auto">
    </div>
    <ul class="nav-list">
        <?php foreach ($nav_links as $link): ?>
            <li><a href="<?php echo $link['url']; ?>"
                   class="nav-link<?php echo ($active_nav === $link['slug']) ? ' active' : ''; ?>">
                <i class="fas <?php echo $link['icon']; ?>"></i>
                <span><?php echo $link['label']; ?></span>
            </a></li>
        <?php endforeach; ?>
        <hr class="sb-divider">
        <li>
            <form action="<?php echo $logout_url; ?>" method="POST" style="display:contents">
                <button type="submit" class="nav-link" style="color:var(--argon-danger);background:none;border:none;cursor:pointer;width:100%;text-align:left;font-family:inherit">
                    <i class="fas fa-sign-out-alt"></i><span>Logout</span>
                </button>
            </form>
        </li>
    </ul>
</nav>

<!-- MAIN -->
<div id="main">

    <!-- TOPBAR -->
    <header id="topbar">
        <div class="topbar-left">
            <button class="hamburger" aria-label="Toggle menu" onclick="toggleSB()">
                <i class="fas fa-bars"></i>
            </button>
            <div>
                <div class="breadcrumb-nav"><a href="/">Home</a> / <?php echo $page_title; ?></div>
                <div class="page-title"><?php echo $page_title; ?></div>
            </div>
        </div>
        <div class="topbar-right">
            <span style="font-size:.82rem;color:var(--argon-text)"><?php echo $display_name; ?></span>
            <?php $avatar = $nav_type === 'user' && isset($user) ? ($user['avatar'] ?? '') : ''; ?>
            <div class="tb-avatar" title="<?php echo $display_name; ?>" style="overflow:hidden">
                <?php if ($avatar): ?>
                    <img src="<?php echo htmlspecialchars($avatar); ?>" style="width:100%;height:100%;object-fit:cover" onerror="this.style.display='none';this.parentElement.textContent='<?php echo $initials; ?>'">
                <?php else: ?>
                    <?php echo $initials; ?>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- PAGE HEADER -->
    <div id="page-header">
        <h1><?php echo $page_title; ?></h1>
        <?php if ($page_subtitle): ?>
            <p><?php echo $page_subtitle; ?></p>
        <?php endif; ?>
    </div>

    <!-- CONTENT -->
    <main id="content">
