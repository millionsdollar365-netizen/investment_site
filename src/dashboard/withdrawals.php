<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * User Dashboard — Withdrawals
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
    <title>Withdrawals - <?php echo SITE_NAME; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/assets/js/app.js"></script>
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
            <h2 class="text-3xl font-bold">Withdrawals</h2>
            <button onclick="showCreateModal()" class="bg-yellow-600 text-white px-6 py-2 rounded font-semibold">Request Withdrawal</button>
        </div>

        <div id="withdrawalsList" class="bg-white rounded shadow overflow-hidden">
            <div class="p-6 text-center text-gray-500">Loading...</div>
        </div>
    </div>

    <!-- Create Withdrawal Modal -->
    <div id="createModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded shadow-lg p-8 max-w-md w-full">
            <h3 class="text-2xl font-bold mb-4">Request Withdrawal</h3>
            <form id="withdrawalForm" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Amount ($)</label>
                    <input type="number" name="amount" step="0.01" min="0.01" required class="mt-1 w-full px-4 py-2 border rounded">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Bank Name</label>
                    <input type="text" name="bank_name" required class="mt-1 w-full px-4 py-2 border rounded">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Account Number</label>
                    <input type="text" name="account_number" required class="mt-1 w-full px-4 py-2 border rounded">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Account Holder Name</label>
                    <input type="text" name="account_holder_name" required class="mt-1 w-full px-4 py-2 border rounded">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-yellow-600 text-white py-2 rounded font-semibold">Submit</button>
                    <button type="button" onclick="hideCreateModal()" class="flex-1 bg-gray-300 text-gray-800 py-2 rounded font-semibold">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        async function loadWithdrawals() {
            const res = await fetch('/api/withdrawals/list.php');
            const data = await res.json();
            const container = document.getElementById('withdrawalsList');

            if (!data.success || !data.data.withdrawals.length) {
                container.innerHTML = '<div class="p-6 text-center text-gray-500">No withdrawals yet.</div>';
                return;
            }

            container.innerHTML = `
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Amount</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Bank</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Account</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${data.data.withdrawals.map(w => `
                            <tr class="border-t">
                                <td class="px-6 py-4">$${parseFloat(w.amount).toFixed(2)}</td>
                                <td class="px-6 py-4">${escHtml(w.bank_name)}</td>
                                <td class="px-6 py-4">${escHtml(w.account_number)}</td>
                                <td class="px-6 py-4"><span class="px-2 py-1 rounded text-sm ${w.status === 'approved' ? 'bg-green-100 text-green-800' : w.status === 'completed' ? 'bg-blue-100 text-blue-800' : w.status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'}">${w.status}</span></td>
                                <td class="px-6 py-4 text-sm">${w.created_at}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>`;
        }

        function showCreateModal() { document.getElementById('createModal').classList.remove('hidden'); }
        function hideCreateModal() { document.getElementById('createModal').classList.add('hidden'); }

        document.getElementById('withdrawalForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            const res = await fetch('/api/withdrawals/create.php', { method: 'POST', body: formData });
            const data = await res.json();
            if (data.success) {
                hideCreateModal();
                loadWithdrawals();
            } else {
                alert(data.message);
            }
        });

        function escHtml(s) { const d = document.createElement('div'); d.textContent = s; return d.innerHTML; }

        loadWithdrawals();
    </script>
</body>
</html>
