<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * Admin Dashboard
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/admin-session.php';

requireAdminLogin();

$admin = getCurrentAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - <?php echo SITE_NAME; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <nav class="bg-red-700 shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold text-white"><?php echo SITE_NAME; ?> Admin</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-white"><?php echo htmlspecialchars($admin['username']); ?></span>
                    <a href="/api/admin/logout.php" class="bg-white text-red-600 px-4 py-2 rounded font-semibold">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h2 class="text-3xl font-bold mb-8">Admin Dashboard</h2>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded shadow">
                <p class="text-gray-600">Total Users</p>
                <p class="text-3xl font-bold">0</p>
            </div>
            <div class="bg-white p-6 rounded shadow">
                <p class="text-gray-600">Pending Deposits</p>
                <p class="text-3xl font-bold">0</p>
            </div>
            <div class="bg-white p-6 rounded shadow">
                <p class="text-gray-600">Pending Withdrawals</p>
                <p class="text-3xl font-bold">0</p>
            </div>
            <div class="bg-white p-6 rounded shadow">
                <p class="text-gray-600">Total Balance</p>
                <p class="text-3xl font-bold">$0.00</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded shadow">
            <h3 class="text-xl font-bold mb-4">Management</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="/admin/users.php" class="block bg-blue-600 text-white px-4 py-3 rounded text-center font-semibold">Manage Users</a>
                <a href="/admin/deposits.php" class="block bg-green-600 text-white px-4 py-3 rounded text-center font-semibold">Deposits</a>
                <a href="/admin/withdrawals.php" class="block bg-yellow-600 text-white px-4 py-3 rounded text-center font-semibold">Withdrawals</a>
                <a href="/admin/investments.php" class="block bg-purple-600 text-white px-4 py-3 rounded text-center font-semibold">Investments</a>
                <a href="/admin/plans.php" class="block bg-indigo-600 text-white px-4 py-3 rounded text-center font-semibold">Plans</a>
                <a href="/admin/settings.php" class="block bg-gray-600 text-white px-4 py-3 rounded text-center font-semibold">Settings</a>
            </div>
        </div>
    </div>
</body>
</html>
