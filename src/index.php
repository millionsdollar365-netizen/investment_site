<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * Landing / Home Page
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - Investment Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold text-blue-600"><?php echo SITE_NAME; ?></h1>
                </div>
                <div class="flex items-center space-x-4">
                    <?php if (isLoggedIn()): ?>
                        <a href="/dashboard/" class="text-gray-700 hover:text-gray-900">Dashboard</a>
                        <form action="/api/auth/logout.php" method="POST" style="display:inline"><button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">Logout</button></form>
                    <?php else: ?>
                        <a href="/login.php" class="text-gray-700 hover:text-gray-900">Login</a>
                        <a href="/register.php" class="bg-blue-600 text-white px-4 py-2 rounded">Register</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Welcome to <?php echo SITE_NAME; ?></h2>
            <p class="text-xl text-gray-600 mb-8">Secure investment platform with daily returns</p>
            
            <?php if (!isLoggedIn()): ?>
                <div class="flex justify-center gap-4">
                    <a href="/register.php" class="bg-blue-600 text-white px-8 py-3 rounded text-lg font-semibold">Get Started</a>
                    <a href="/login.php" class="bg-gray-200 text-gray-800 px-8 py-3 rounded text-lg font-semibold">Sign In</a>
                </div>
            <?php else: ?>
                <a href="/dashboard/" class="bg-blue-600 text-white px-8 py-3 rounded text-lg font-semibold">Go to Dashboard</a>
            <?php endif; ?>
        </div>

        <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-xl font-bold mb-2">Secure</h3>
                <p class="text-gray-600">Bank-level security for your investments</p>
            </div>
            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-xl font-bold mb-2">Daily Returns</h3>
                <p class="text-gray-600">Earn daily ROI on your investments</p>
            </div>
            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-xl font-bold mb-2">Withdrawals</h3>
                <p class="text-gray-600">Quick and easy withdrawal process</p>
            </div>
        </div>
    </div>
</body>
</html>
