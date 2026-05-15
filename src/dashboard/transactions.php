<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * User Dashboard — Transaction History
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
    <title>Transactions - <?php echo SITE_NAME; ?></title>
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
        <h2 class="text-3xl font-bold mb-8">Transaction History</h2>

        <div id="transactionsList" class="bg-white rounded shadow overflow-hidden">
            <div class="p-6 text-center text-gray-500">Loading...</div>
        </div>

        <div id="pagination" class="flex justify-center gap-2 mt-6"></div>
    </div>

    <script>
        let currentPage = 1;

        async function loadTransactions(page = 1) {
            currentPage = page;
            const res = await fetch(`/api/user/transactions.php?page=${page}&limit=20`);
            const data = await res.json();
            const container = document.getElementById('transactionsList');

            if (!data.success || !data.data.transactions.length) {
                container.innerHTML = '<div class="p-6 text-center text-gray-500">No transactions yet.</div>';
                document.getElementById('pagination').innerHTML = '';
                return;
            }

            container.innerHTML = `
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Type</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Amount</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Balance Before</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Balance After</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Description</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${data.data.transactions.map(t => `
                            <tr class="border-t">
                                <td class="px-6 py-4"><span class="px-2 py-1 rounded text-sm font-semibold ${typeClass(t.type)}">${t.type}</span></td>
                                <td class="px-6 py-4">$${parseFloat(t.amount).toFixed(2)}</td>
                                <td class="px-6 py-4">$${parseFloat(t.old_balance || 0).toFixed(2)}</td>
                                <td class="px-6 py-4">$${parseFloat(t.new_balance || 0).toFixed(2)}</td>
                                <td class="px-6 py-4">${escHtml(t.description || '')}</td>
                                <td class="px-6 py-4 text-sm">${t.created_at}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>`;

            const totalPages = Math.ceil(data.data.total / data.data.limit);
            let pagHtml = '';
            for (let i = 1; i <= totalPages; i++) {
                pagHtml += `<button onclick="loadTransactions(${i})" class="px-4 py-2 rounded ${i === currentPage ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800'}">${i}</button>`;
            }
            document.getElementById('pagination').innerHTML = pagHtml;
        }

        function typeClass(type) {
            const map = { deposit: 'bg-green-100 text-green-800', withdrawal: 'bg-red-100 text-red-800', investment: 'bg-purple-100 text-purple-800', profit: 'bg-blue-100 text-blue-800', referral: 'bg-yellow-100 text-yellow-800', adjustment: 'bg-gray-100 text-gray-800' };
            return map[type] || 'bg-gray-100 text-gray-800';
        }

        function escHtml(s) { const d = document.createElement('div'); d.textContent = s; return d.innerHTML; }

        loadTransactions();
    </script>
</body>
</html>
