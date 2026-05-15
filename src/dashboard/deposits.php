<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * User Dashboard — Deposits
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
    <title>My Deposits - <?php echo SITE_NAME; ?></title>
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
                    <form action="/api/auth/logout.php" method="POST" style="display:inline"><button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">Logout</button></form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold">My Deposits</h2>
            <button onclick="showCreateModal()" class="bg-green-600 text-white px-6 py-2 rounded font-semibold">New Deposit</button>
        </div>

        <div id="depositsList" class="bg-white rounded shadow overflow-hidden">
            <div class="p-6 text-center text-gray-500">Loading...</div>
        </div>
    </div>

    <!-- Create Deposit Modal -->
    <div id="createModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded shadow-lg p-8 max-w-md w-full">
            <h3 class="text-2xl font-bold mb-4">New Deposit</h3>
            <form id="depositForm" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Amount ($)</label>
                    <input type="number" name="amount" step="0.01" min="0.01" required class="mt-1 w-full px-4 py-2 border rounded">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Payment Method</label>
                    <select name="payment_method" required class="mt-1 w-full px-4 py-2 border rounded">
                        <option value="">Select...</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="crypto">Cryptocurrency</option>
                        <option value="paystack">Paystack</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Transaction Reference</label>
                    <input type="text" name="transaction_ref" required class="mt-1 w-full px-4 py-2 border rounded" placeholder="Enter payment reference">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-green-600 text-white py-2 rounded font-semibold">Submit</button>
                    <button type="button" onclick="hideCreateModal()" class="flex-1 bg-gray-300 text-gray-800 py-2 rounded font-semibold">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        async function loadDeposits() {
            const res = await fetch('/api/deposits/list.php');
            const data = await res.json();
            const container = document.getElementById('depositsList');

            if (!data.success || !data.data.deposits.length) {
                container.innerHTML = '<div class="p-6 text-center text-gray-500">No deposits yet.</div>';
                return;
            }

            container.innerHTML = `
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Amount</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Method</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Reference</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${data.data.deposits.map(d => `
                            <tr class="border-t">
                                <td class="px-6 py-4">$${parseFloat(d.amount).toFixed(2)}</td>
                                <td class="px-6 py-4">${escHtml(d.payment_method)}</td>
                                <td class="px-6 py-4">${escHtml(d.transaction_ref)}</td>
                                <td class="px-6 py-4"><span class="px-2 py-1 rounded text-sm ${d.status === 'approved' ? 'bg-green-100 text-green-800' : d.status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'}">${d.status}</span></td>
                                <td class="px-6 py-4 text-sm">${d.created_at}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>`;
        }

        function showCreateModal() { document.getElementById('createModal').classList.remove('hidden'); }
        function hideCreateModal() { document.getElementById('createModal').classList.add('hidden'); }

        document.getElementById('depositForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            const res = await fetch('/api/deposits/create.php', { method: 'POST', body: formData });
            const data = await res.json();
            if (data.success) {
                hideCreateModal();
                loadDeposits();
            } else {
                alert(data.message);
            }
        });

        function escHtml(s) { const d = document.createElement('div'); d.textContent = s; return d.innerHTML; }

        loadDeposits();
    </script>
</body>
</html>
