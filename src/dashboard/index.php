<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * User Dashboard
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/auth.php';

requireLogin();

$user = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?php echo SITE_NAME; ?></title>
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
                    <span class="text-gray-700"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></span>
                    <a href="/api/auth/logout.php" class="bg-red-600 text-white px-4 py-2 rounded">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h2 class="text-3xl font-bold mb-8">Welcome, <?php echo htmlspecialchars($user['first_name']); ?>!</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8" id="statsGrid">
            <div class="bg-white p-6 rounded shadow">
                <p class="text-gray-600">Total Balance</p>
                <p class="text-3xl font-bold text-blue-600" id="statBalance">Loading...</p>
            </div>
            <div class="bg-white p-6 rounded shadow">
                <p class="text-gray-600">Interest Balance</p>
                <p class="text-3xl font-bold text-green-600" id="statInterest">Loading...</p>
            </div>
            <div class="bg-white p-6 rounded shadow">
                <p class="text-gray-600">Active Investments</p>
                <p class="text-3xl font-bold text-purple-600" id="statInvestments">Loading...</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-xl font-bold mb-4">Quick Actions</h3>
                <div class="space-y-2">
                    <a href="/dashboard/investments.php" class="block bg-blue-600 text-white px-4 py-2 rounded text-center">Invest Now</a>
                    <a href="/dashboard/deposits.php" class="block bg-green-600 text-white px-4 py-2 rounded text-center">Make Deposit</a>
                    <a href="/dashboard/withdrawals.php" class="block bg-yellow-600 text-white px-4 py-2 rounded text-center">Withdraw</a>
                </div>
            </div>

            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-xl font-bold mb-4">Navigation</h3>
                <div class="space-y-2">
                    <a href="/dashboard/referrals.php" class="block text-blue-600 hover:underline">My Referrals</a>
                    <a href="/dashboard/transactions.php" class="block text-blue-600 hover:underline">Transaction History</a>
                    <a href="/dashboard/profile.php" class="block text-blue-600 hover:underline">Profile</a>
                    <a href="/dashboard/settings.php" class="block text-blue-600 hover:underline">Settings</a>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded shadow mt-6">
            <h3 class="text-xl font-bold mb-4">Recent Transactions</h3>
            <div id="recentTransactions" class="text-gray-500">Loading...</div>
        </div>
    </div>

    <script>
        async function loadDashboard() {
            const res = await fetch('/api/user/dashboard.php');
            const data = await res.json();

            if (!data.success) return;

            const d = data.data;
            document.getElementById('statBalance').textContent = '$' + parseFloat(d.balance).toFixed(2);
            document.getElementById('statInterest').textContent = '$' + parseFloat(d.interest_balance).toFixed(2);
            document.getElementById('statInvestments').textContent = d.active_investments;

            const txContainer = document.getElementById('recentTransactions');
            if (!d.recent_transactions.length) {
                txContainer.innerHTML = '<p class="text-gray-500">No transactions yet.</p>';
            } else {
                txContainer.innerHTML = d.recent_transactions.map(t =>
                    `<div class="flex justify-between py-2 border-b">
                        <span>${escHtml(t.type)} — ${escHtml(t.description || '')}</span>
                        <span class="font-semibold">$${parseFloat(t.amount).toFixed(2)}</span>
                    </div>`
                ).join('');
            }
        }

        function escHtml(s) { const d = document.createElement('div'); d.textContent = s; return d.innerHTML; }

        loadDashboard();
    </script>
</body>
</html>
