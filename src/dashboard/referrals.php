<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * User Dashboard — Referrals
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
    <title>My Referrals - <?php echo SITE_NAME; ?></title>
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
                    <a href="/dashboard/" class="text-gray-700 hover:text-gray-900">Dashboard</a>
                    <span class="text-gray-700"><?php echo htmlspecialchars($user['first_name']); ?></span>
                    <a href="/api/auth/logout.php" class="bg-red-600 text-white px-4 py-2 rounded">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h2 class="text-3xl font-bold mb-8">My Referrals</h2>

        <div class="bg-white p-6 rounded shadow mb-8">
            <h3 class="text-xl font-bold mb-2">Your Referral Link</h3>
            <div class="flex gap-2">
                <input type="text" id="referralLink" readonly class="flex-1 px-4 py-2 border rounded bg-gray-50" value="<?php echo rtrim(SITE_URL, '/'); ?>/register.php?ref=<?php echo htmlspecialchars($user['referral_code']); ?>">
                <button onclick="copyReferralLink()" class="bg-blue-600 text-white px-4 py-2 rounded font-semibold">Copy</button>
            </div>
        </div>

        <div id="referralsList" class="bg-white rounded shadow overflow-hidden">
            <div class="p-6 text-center text-gray-500">Loading...</div>
        </div>
    </div>

    <script>
        async function loadReferrals() {
            const res = await fetch('/api/user/referrals.php');
            const data = await res.json();
            const container = document.getElementById('referralsList');

            if (!data.success || !data.data.referrals.length) {
                container.innerHTML = '<div class="p-6 text-center text-gray-500">No referrals yet. Share your referral link to earn commissions!</div>';
                return;
            }

            container.innerHTML = `
                <div class="p-4 bg-gray-50 border-b">
                    <p class="text-lg font-semibold">Total Commission: $${parseFloat(data.data.total_commission).toFixed(2)} | Referrals: ${data.data.count}</p>
                </div>
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">User</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Email</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Commission</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${data.data.referrals.map(r => `
                            <tr class="border-t">
                                <td class="px-6 py-4">${escHtml(r.first_name + ' ' + r.last_name)}</td>
                                <td class="px-6 py-4">${escHtml(r.email)}</td>
                                <td class="px-6 py-4">$${parseFloat(r.commission_amount).toFixed(2)}</td>
                                <td class="px-6 py-4"><span class="px-2 py-1 rounded text-sm ${r.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}">${r.status}</span></td>
                                <td class="px-6 py-4 text-sm">${r.created_at}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>`;
        }

        function copyReferralLink() {
            const input = document.getElementById('referralLink');
            input.select();
            document.execCommand('copy');
            alert('Referral link copied!');
        }

        function escHtml(s) { const d = document.createElement('div'); d.textContent = s; return d.innerHTML; }

        loadReferrals();
    </script>
</body>
</html>
